<?php

namespace App\Services\AI;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceContextService
{
    /**
     * Mengambil ringkasan keuangan pengguna yang sedang login.
     */
    public function getSummaryForCurrentUser(): array
    {
        // 1. Ambil ID pengguna yang sedang login.
        $userId = Auth::id();

        if (!$userId) {
            throw new AuthenticationException(
                'Pengguna harus login untuk mengakses data keuangan.'
            );
        }

        $now = now();
        $tanggalHariIni = $now->toDateString();

        /*
         * 2. TOTAL PEMASUKAN KESELURUHAN
         */
        $totalPemasukan = (int) DB::table('pemasukans')
            ->where('id_users', $userId)
            ->sum('jumlah_pemasukan');

        /*
         * 3. TOTAL PENGELUARAN KESELURUHAN
         */
        $totalPengeluaran = (int) DB::table('pengeluarans')
            ->where('id_users', $userId)
            ->sum('jumlah_pengeluaran');

        /*
         * 4. TOTAL PEMASUKAN HARI INI
         */
        $totalPemasukanHariIni = (int) Pemasukan::query()
            ->where('id_users', $userId)
            ->whereDate('tanggal_pemasukan', $tanggalHariIni)
            ->sum('jumlah_pemasukan');

        /*
         * 5. TOTAL PENGELUARAN HARI INI
         */
        $totalPengeluaranHariIni = (int) Pengeluaran::query()
            ->where('id_users', $userId)
            ->whereDate('tanggal_pengeluaran', $tanggalHariIni)
            ->sum('jumlah_pengeluaran');

        /*
         * 6. TOTAL PEMASUKAN BULAN INI
         */
        $pemasukanBulanIni = (int) DB::table('pemasukans')
            ->where('id_users', $userId)
            ->whereYear('tanggal_pemasukan', $now->year)
            ->whereMonth('tanggal_pemasukan', $now->month)
            ->sum('jumlah_pemasukan');

        /*
         * 7. TOTAL PENGELUARAN BULAN INI
         */
        $pengeluaranBulanIni = (int) DB::table('pengeluarans')
            ->where('id_users', $userId)
            ->whereYear('tanggal_pengeluaran', $now->year)
            ->whereMonth('tanggal_pengeluaran', $now->month)
            ->sum('jumlah_pengeluaran');

        /*
         * 8. SALDO AKHIR
         *
         * Tabungan tidak lagi diperhitungkan di service ini.
         */
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        /*
         * 9. RINGKASAN KATEGORI PEMASUKAN
         */
        $kategoriPemasukan = $this->getCategoryBreakdown(
            Pemasukan::class,
            $userId,
            'jumlah_pemasukan',
            'tanggal_pemasukan',
            $now
        );

        /*
         * 10. RINGKASAN KATEGORI PENGELUARAN
         */
        $kategoriPengeluaran = $this->getCategoryBreakdown(
            Pengeluaran::class,
            $userId,
            'jumlah_pengeluaran',
            'tanggal_pengeluaran',
            $now
        );

        /*
         * 11. KEMBALIKAN DATA UNTUK KONTEKS AI
         */
        return [
            'periode' => $now->format('Y-m'),

            'keseluruhan' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'saldo_akhir' => $saldoAkhir,
            ],

            'hari_ini' => [
                'pemasukan' => $totalPemasukanHariIni,
                'pengeluaran' => $totalPengeluaranHariIni,
            ],

            'bulan_ini' => [
                'pemasukan' => $pemasukanBulanIni,
                'pengeluaran' => $pengeluaranBulanIni,
            ],

            'kategori_pemasukan' => $kategoriPemasukan,

            'kategori_pengeluaran' => $kategoriPengeluaran,
        ];
    }

    /**
     * Menghitung total transaksi berdasarkan kategori.
     *
     * Kategori diurutkan dari total transaksi terbesar.
     */
    private function getCategoryBreakdown(
        string $modelClass,
        $userId,
        string $amountColumn,
        string $dateColumn,
        Carbon $now
    ): array {
        // Ambil transaksi milik pengguna beserta relasi kategorinya.
        $transaksi = $modelClass::query()
            ->where('id_users', $userId)
            ->with('Kategori')
            ->get();

        $keseluruhan = [];
        $bulanIni = [];

        foreach ($transaksi as $item) {
            // Gunakan nama kategori dari relasi model.
            $namaKategori = $item->Kategori?->nama_kategori
                ?? 'Tanpa kategori';

            $jumlah = (int) $item->{$amountColumn};

            // Jika transaksi belum memiliki tanggal, lewati
            // perhitungan kategori bulan berjalan untuk transaksi ini.
            $tanggal = $item->{$dateColumn};

            $isBulanIni = false;

            if ($tanggal) {
                $tanggal = Carbon::parse($tanggal);

                $isBulanIni = $tanggal->year === $now->year
                    && $tanggal->month === $now->month;
            }

            // Akumulasi seluruh transaksi per kategori.
            if (!isset($keseluruhan[$namaKategori])) {
                $keseluruhan[$namaKategori] = [
                    'nama_kategori' => $namaKategori,
                    'jumlah_transaksi' => 0,
                    'total' => 0,
                ];
            }

            $keseluruhan[$namaKategori]['jumlah_transaksi']++;
            $keseluruhan[$namaKategori]['total'] += $jumlah;

            // Akumulasi transaksi kategori pada bulan berjalan.
            if ($isBulanIni) {
                if (!isset($bulanIni[$namaKategori])) {
                    $bulanIni[$namaKategori] = [
                        'nama_kategori' => $namaKategori,
                        'jumlah_transaksi' => 0,
                        'total' => 0,
                    ];
                }

                $bulanIni[$namaKategori]['jumlah_transaksi']++;
                $bulanIni[$namaKategori]['total'] += $jumlah;
            }
        }

        // Kategori dengan pengeluaran/pemasukan terbesar ditampilkan dulu.
        $urutkanKategori = function (array $kategori): array {
            return collect($kategori)
                ->sortByDesc('total')
                ->values()
                ->all();
        };

        return [
            'keseluruhan' => $urutkanKategori($keseluruhan),
            'bulan_ini' => $urutkanKategori($bulanIni),
        ];
    }
}