<?php

namespace App\Services\AI;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
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

        // Jangan mengambil data keuangan jika belum login.
        if (!$userId) {
            throw new AuthenticationException(
                'Pengguna harus login untuk mengakses data keuangan.'
            );
        }

        // Menentukan periode bulan berjalan.
        $now = now();

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
         * 4. TOTAL SALDO TABUNGAN SAAT INI
         */
        $totalTabungan = (int) DB::table('tabungans')
            ->where('id_users', $userId)
            ->sum('total_tabungan');
        
        // TOTAL PEMASUKAN HARI INI
        $totalPemasukanHariIni = Pemasukan::where('id_users',$userId)->where('tanggal_pemasukan',now()->format('Y-m-d'))->sum('jumlah_pemasukan');

        /*
         * 5. PEMASUKAN BULAN BERJALAN
         */
        $pemasukanBulanIni = (int) DB::table('pemasukans')
            ->where('id_users', $userId)
            ->whereYear('tanggal_pemasukan', $now->year)
            ->whereMonth('tanggal_pemasukan', $now->month)
            ->sum('jumlah_pemasukan');


            // TOTAL PEMASUKAN HARI INI
        $totalPengeluaranHariIni = (int) Pengeluaran::where('id_users',$userId)->where('tanggal_pengeluaran',now()->format('Y-m-d'))->sum('jumlah_pengeluaran');
        /*
         * 6. PENGELUARAN BULAN BERJALAN
         */
        $pengeluaranBulanIni = (int) DB::table('pengeluarans')
            ->where('id_users', $userId)
            ->whereYear('tanggal_pengeluaran', $now->year)
            ->whereMonth('tanggal_pengeluaran', $now->month)
            ->sum('jumlah_pengeluaran');

        /*
         * 7. PENAMBAHAN TABUNGAN BULAN BERJALAN
         *
         * list_tabungan.id_tabungan terhubung ke tabungan.id.
         */
        $tambahanTabunganBulanIni = (int) DB::table('list_tabungans as lt')
            ->join(
                'tabungans as t',
                't.id',
                '=',
                'lt.id_tabungan'
            )
            ->where('t.id_users', $userId)
            ->whereYear('lt.tanggal_tambahan', $now->year)
            ->whereMonth('lt.tanggal_tambahan', $now->month)
            ->sum('lt.tambahan_tabungan');

        /*
         * 8. HITUNG SALDO AKHIR
         *
         * Mengikuti rumus saldo dashboard UANGKU:
         * pemasukan - (pengeluaran + tabungan).
         */
        $saldoAkhir = $totalPemasukan
            - ($totalPengeluaran + $totalTabungan);

        /*
         * 9. KEMBALIKAN DATA DALAM BENTUK ARRAY
         */
        return [
            'periode' => $now->format('Y-m'),

            'keseluruhan' => [
                'total_pemasukan' => $totalPemasukan,
                'total_pengeluaran' => $totalPengeluaran,
                'total_tabungan' => $totalTabungan,
                'saldo_akhir' => $saldoAkhir,
            ],
            
            'hari_ini'=>[
                    'pemasukan'=>$totalPemasukanHariIni,
                    'pengeluaran'=>$totalPengeluaranHariIni,
            ],
            
            'bulan_ini' => [
                'pemasukan' => $pemasukanBulanIni,
                'pengeluaran' => $pengeluaranBulanIni,
                'tambahan_tabungan' => $tambahanTabunganBulanIni,
            ],

            
        ];
    }
}
