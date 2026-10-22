<?php

namespace App\Http\Controllers\LaporanPengeluaran;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPengeluaranController extends Controller
{
    /**
     * Menampilkan laporan pengeluaran milik user yang sedang login.
     * Data ringkasan, grafik, kategori, dan tabel semuanya diambil dari database.
     */
    public function index(Request $request)
    {
        // Tentukan batas tanggal berdasarkan filter yang dipilih user.
        $range = $this->resolveRange($request);

        // Query dasar untuk ringkasan dan grafik. Pencarian tabel tidak mengubah ringkasan.
        $queryRingkasan = $this->filteredQuery($request, $range, false);

        // Hitung nilai ringkasan dari transaksi yang cocok dengan periode dan kategori.
        $totalPengeluaran = (clone $queryRingkasan)->sum('jumlah_pengeluaran');
        $jumlahTransaksi = (clone $queryRingkasan)->count();
        $pengeluaranTerbesar = (clone $queryRingkasan)->max('jumlah_pengeluaran') ?? 0;
        $rataRataPengeluaran = $jumlahTransaksi > 0
            ? $totalPengeluaran / $jumlahTransaksi
            : 0;

        // Ambil transaksi sesuai periode untuk membangun grafik dan informasi kategori.
        // Query sudah dibatasi berdasarkan id_users sehingga data antar-user tidak tercampur.
        $dataAnalisis = (clone $queryRingkasan)
            ->with('Kategori')
            ->orderBy('tanggal_pengeluaran')
            ->get();

        // Bentuk data grafik sesuai mode harian, mingguan, bulanan, tahunan, atau rentang khusus.
        $grafikPengeluaran = $this->buildChart($dataAnalisis, $range);

        // Cari kategori dengan nilai pengeluaran paling besar pada periode yang dipilih.
        $kategoriTeratas = $dataAnalisis
            ->groupBy(fn ($item) => $item->Kategori?->nama_ketegori ?? 'Tanpa Kategori')
            ->map(fn (Collection $items) => $items->sum('jumlah_pengeluaran'))
            ->sortDesc()
            ->keys()
            ->first() ?? 'Belum ada data';

        // Ambil tanggal transaksi terakhir untuk ditampilkan pada panel informasi.
        $transaksiTerakhir = $dataAnalisis->sortByDesc('tanggal_pengeluaran')->first();

        // Ambil kategori pengeluaran milik user yang login dari tabel sesuai skema database.
        // Query langsung dipakai agar tidak bergantung pada relasi kategori milik User.
        $kategoriPengeluaran = DB::table('kategori_pengelurans')
            ->where('id_users', Auth::id())
            ->orderBy('nama_ketegori')
            ->get(['id', 'nama_ketegori']);

        // Query tabel memakai pencarian, pengurutan terbaru, dan paginasi 10 baris.
        $laporan = $this->filteredQuery($request, $range, true)
            ->orderByDesc('tanggal_pengeluaran')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Kirim seluruh data yang dibutuhkan oleh Blade, tanpa angka dummy.
        return view('pages.LaporanPengeluaran.index', compact(
            'laporan',
            'totalPengeluaran',
            'jumlahTransaksi',
            'rataRataPengeluaran',
            'pengeluaranTerbesar',
            'grafikPengeluaran',
            'kategoriPengeluaran',
            'kategoriTeratas',
            'transaksiTerakhir',
            'range'
        ));
    }

    /**
     * Mengekspor hasil filter menjadi CSV yang dapat dibuka melalui Microsoft Excel.
     * CSV digunakan agar tidak memerlukan package ekspor tambahan.
     */
    public function exportExcel(Request $request)
    {
        // Gunakan filter yang sama dengan tabel laporan, termasuk pencarian.
        $range = $this->resolveRange($request);
        $rows = $this->filteredQuery($request, $range, true)
            ->orderByDesc('tanggal_pengeluaran')
            ->get();

        // Buat nama file dengan tanggal dan waktu saat file diunduh.
        $filename = 'laporan-pengeluaran-' . now()->format('Ymd-His') . '.csv';

        // Stream file agar CSV dapat diunduh tanpa menyimpan file sementara di server.
        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');

            // BOM membantu Excel mengenali karakter UTF-8, termasuk teks berbahasa Indonesia.
            fwrite($output, "\xEF\xBB\xBF");

            // Tulis judul kolom CSV.
            fputcsv($output, [
                'No',
                'Tanggal',
                'Nama Pengeluaran',
                'Kategori',
                'Deskripsi',
                'Jumlah Pengeluaran (Rp)',
            ], ',', '"', '\\');

            // Tulis setiap transaksi dari user yang sedang login dan sesuai filter.
            foreach ($rows as $index => $item) {
                fputcsv($output, [
                    $index + 1,
                    $item->tanggal_pengeluaran?->format('Y-m-d H:i:s'),
                    $item->label,
                    $item->Kategori?->nama_ketegori ?? 'Tanpa Kategori',
                    $item->deskripsi_pengeluaran ?? '-',
                    $item->jumlah_pengeluaran,
                ], ',', '"', '\\');
            }

            // Tutup stream setelah seluruh baris berhasil ditulis.
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Menampilkan versi cetak laporan. User dapat memilih printer atau Save as PDF.
     * Implementasi ini tidak membutuhkan library PDF eksternal.
     */
    public function exportPdf(Request $request)
    {
        // Terapkan periode, kategori, dan pencarian yang sama dengan halaman laporan.
        $range = $this->resolveRange($request);
        $laporan = $this->filteredQuery($request, $range, true)
            ->orderByDesc('tanggal_pengeluaran')
            ->get();

        // Hitung total pada hasil yang akan dicetak.
        $totalPengeluaran = $laporan->sum('jumlah_pengeluaran');

        // Tampilkan halaman cetak yang membuka dialog cetak browser secara otomatis.
        return view('pages.LaporanPengeluaran.cetak', compact(
            'laporan',
            'range',
            'totalPengeluaran'
        ));
    }

    /**
     * Membuat query pengeluaran milik user dengan filter periode, kategori, dan opsional pencarian.
     */
    private function filteredQuery(Request $request, array $range, bool $includeSearch = false): Builder
    {
        // Batasi data berdasarkan user yang login dan muat relasi kategori agar tidak query berulang.
        $query = Pengeluaran::query()
            ->where('id_users', Auth::id())
            ->with('Kategori')
            ->whereBetween('tanggal_pengeluaran', [$range['start'], $range['end']]);

        // Terapkan filter kategori jika ID kategori valid dan benar-benar milik user ini.
        if ($request->filled('kategori') && ctype_digit((string) $request->input('kategori'))) {
            $kategoriId = (int) $request->input('kategori');
            // Pastikan kategori ada dan memang dimiliki oleh user yang sedang login.
            $kategoriMilikUser = DB::table('kategori_pengelurans')
                ->where('id', $kategoriId)
                ->where('id_users', Auth::id())
                ->exists();

            if ($kategoriMilikUser) {
                $query->where('id_kategori', $kategoriId);
            }
        }

        // Pencarian hanya diterapkan pada tabel, ekspor, dan halaman cetak jika diminta.
        if ($includeSearch && $request->filled('search')) {
            $keyword = trim((string) $request->input('search'));

            $query->where(function (Builder $searchQuery) use ($keyword) {
                // Cari berdasarkan nama transaksi atau deskripsi transaksi.
                $searchQuery
                    ->where('label', 'like', '%' . $keyword . '%')
                    ->orWhere('deskripsi_pengeluaran', 'like', '%' . $keyword . '%')
                    // Cari juga nama kategori melalui relasi kategori pengeluaran.
                    ->orWhereHas('Kategori', function (Builder $kategoriQuery) use ($keyword) {
                        $kategoriQuery->where('nama_ketegori', 'like', '%' . $keyword . '%');
                    });
            });
        }

        return $query;
    }

    /**
     * Menentukan tanggal mulai dan akhir untuk semua pilihan filter periode.
     */
    private function resolveRange(Request $request): array
    {
        // Validasi pilihan periode agar hanya nilai yang dikenal yang diproses.
        $periode = (string) $request->input('periode', 'bulanan');
        $pilihanPeriode = ['harian', 'mingguan', 'bulanan', 'tahunan', 'custom'];

        if (! in_array($periode, $pilihanPeriode, true)) {
            $periode = 'bulanan';
        }

        // Rentang tanggal khusus menggunakan tanggal dari input form.
        if ($periode === 'custom') {
            try {
                $start = Carbon::parse(
                    $request->input('tanggal_awal', now()->startOfMonth()->toDateString())
                )->startOfDay();
                $end = Carbon::parse(
                    $request->input('tanggal_akhir', now()->toDateString())
                )->endOfDay();
            } catch (\Throwable $exception) {
                // Jika tanggal tidak valid, kembali ke periode bulan ini agar halaman tetap terbuka.
                $start = now()->startOfMonth();
                $end = now()->endOfDay();
            }

            // Tukar batas tanggal jika user memasukkan tanggal awal setelah tanggal akhir.
            if ($start->gt($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }
        } else {
            // Untuk periode standar, tentukan rentang berdasarkan tanggal saat ini.
            $sekarang = now();

            switch ($periode) {
                case 'harian':
                    $start = $sekarang->copy()->startOfDay();
                    $end = $sekarang->copy()->endOfDay();
                    break;

                case 'mingguan':
                    // Minggu dihitung dari Senin sampai Minggu.
                    $start = $sekarang->copy()->startOfWeek(Carbon::MONDAY);
                    $end = $sekarang->copy()->endOfWeek(Carbon::SUNDAY);
                    break;

                case 'tahunan':
                    $start = $sekarang->copy()->startOfYear();
                    $end = $sekarang->copy()->endOfYear();
                    break;

                default:
                    // Periode bulanan mencakup awal sampai akhir bulan berjalan.
                    $start = $sekarang->copy()->startOfMonth();
                    $end = $sekarang->copy()->endOfMonth();
                    break;
            }
        }

        // Label tanggal digunakan untuk menampilkan periode yang aktif di halaman.
        $labelPeriode = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        return [
            'periode' => $periode,
            'start' => $start,
            'end' => $end,
            'tanggalAwal' => $start->format('Y-m-d'),
            'tanggalAkhir' => $end->format('Y-m-d'),
            'labelPeriode' => $labelPeriode,
        ];
    }

    /**
     * Mengubah transaksi database menjadi data batang grafik tanpa library tambahan.
     */
    private function buildChart(Collection $items, array $range): array
    {
        $periode = $range['periode'];
        $start = $range['start']->copy()->startOfDay();
        $end = $range['end']->copy()->startOfDay();
        $mode = $periode;
        $labels = [];

        // Buat sumbu grafik sesuai periode yang sedang dipilih.
        switch ($periode) {
            case 'harian':
                // Grafik harian mengelompokkan transaksi per blok empat jam.
                $mode = 'harian';
                for ($i = 0; $i < 6; $i++) {
                    $labels[$i] = sprintf('%02d–%02d', $i * 4, ($i * 4) + 3);
                }
                break;

            case 'mingguan':
                // Grafik mingguan mempunyai satu batang untuk setiap hari.
                $labels = [
                    1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis',
                    5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
                ];
                break;

            case 'tahunan':
                // Grafik tahunan mempunyai satu batang untuk setiap bulan.
                $labels = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                    5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
                    9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
                ];
                break;

            case 'custom':
                // Untuk rentang maksimal 14 hari, tampilkan satu batang untuk setiap tanggal.
                $jumlahHari = (int) $start->diffInDays($end) + 1;
                if ($jumlahHari <= 14) {
                    $mode = 'custom-harian';
                    for ($tanggal = $start->copy(); $tanggal->lte($end); $tanggal->addDay()) {
                        $labels[$tanggal->format('Y-m-d')] = $tanggal->format('d/m');
                    }
                } else {
                    // Untuk rentang panjang, kelompokkan data per tujuh hari agar grafik tetap terbaca.
                    $mode = 'custom-mingguan';
                    $jumlahMinggu = (int) ceil($jumlahHari / 7);
                    for ($i = 1; $i <= $jumlahMinggu; $i++) {
                        $labels[$i] = 'Minggu ' . $i;
                    }
                }
                break;

            default:
                // Grafik bulanan membagi transaksi ke dalam kelompok minggu 1 sampai 5.
                $labels = [1 => 'Minggu 1', 2 => 'Minggu 2', 3 => 'Minggu 3', 4 => 'Minggu 4', 5 => 'Minggu 5'];
                break;
        }

        // Isi semua kelompok dengan nol supaya label tetap muncul meskipun tidak ada transaksi.
        $totals = [];
        foreach ($labels as $key => $label) {
            $totals[(string) $key] = 0;
        }

        // Tempatkan nilai setiap transaksi ke kelompok waktu yang sesuai.
        foreach ($items as $item) {
            if (! $item->tanggal_pengeluaran) {
                continue;
            }

            $tanggal = Carbon::parse($item->tanggal_pengeluaran);

            switch ($mode) {
                case 'harian':
                    $key = (string) (int) floor($tanggal->hour / 4);
                    break;

                case 'mingguan':
                    $key = (string) $tanggal->dayOfWeekIso;
                    break;

                case 'tahunan':
                    $key = (string) $tanggal->month;
                    break;

                case 'custom-harian':
                    $key = $tanggal->format('Y-m-d');
                    break;

                case 'custom-mingguan':
                    $selisihHari = (int) $start->diffInDays($tanggal->copy()->startOfDay());
                    $key = (string) ((int) floor($selisihHari / 7) + 1);
                    break;

                default:
                    $key = (string) ((int) floor(($tanggal->day - 1) / 7) + 1);
                    break;
            }

            // Tambahkan nominal hanya jika kelompoknya tersedia pada sumbu grafik.
            if (array_key_exists($key, $totals)) {
                $totals[$key] += (float) $item->jumlah_pengeluaran;
            }
        }

        // Normalisasi tinggi batang dari 0 sampai 100 berdasarkan nilai terbesar.
        $nilaiMaksimal = $totals ? max($totals) : 0;
        $grafik = [];

        foreach ($labels as $key => $label) {
            $jumlah = (float) ($totals[(string) $key] ?? 0);
            $tinggi = $nilaiMaksimal > 0 ? (int) round(($jumlah / $nilaiMaksimal) * 100) : 0;

            $grafik[] = [
                'label' => $label,
                'jumlah' => $jumlah,
                'tinggi' => $tinggi,
            ];
        }

        return $grafik;
    }
}
