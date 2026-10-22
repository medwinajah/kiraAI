<?php

namespace App\Http\Controllers\LaporanPemasukan;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanPemasukanController extends Controller
{
    /**
     * Menampilkan laporan pemasukan milik user yang sedang login.
     * Data ringkasan, grafik, kategori, dan tabel semuanya diambil dari database.
     */

    public function index(Request $request)
    {
        // Tentukan batas tanggal berdasarkan filter yang dipilih user.
        $range = $this->resolveRange($request);

        // Query dasar untuk ringkasan dan grafik. Pencarian tabel tidak mengubah ringkasan.
        $queryRingkasan = $this->filteredQuery($request, $range, false);

        // Hitung nilai ringkasan dari transaksi yang cocok dengan periode dan kategori.
        $totalPemasukan = (clone $queryRingkasan)->sum('jumlah_pemasukan');
        $jumlahTransaksi = (clone $queryRingkasan)->count();
        $pemasukanTerbesar = (clone $queryRingkasan)->max('jumlah_pemasukan') ?? 0;
        $rataRataPemasukan = $jumlahTransaksi > 0
            ? $totalPemasukan / $jumlahTransaksi
            : 0;

        // Ambil transaksi sesuai periode untuk membangun grafik dan informasi kategori.
        // Query sudah dibatasi berdasarkan id_users sehingga data antar-user tidak tercampur.
        $dataAnalisis = (clone $queryRingkasan)
            ->with('Kategori')
            ->orderBy('tanggal_pemasukan')
            ->get();

        // Bentuk data grafik sesuai mode harian, mingguan, bulanan, tahunan, atau rentang khusus.
        $grafikPemasukan = $this->buildChart($dataAnalisis, $range);

        // Cari kategori dengan nilai pemasukan paling besar pada periode yang dipilih.
        $kategoriTeratas = $dataAnalisis
            ->groupBy(fn($item) => $item->Kategori?->nama_kategori ?? 'Tanpa Kategori')
            ->map(fn(Collection $items) => $items->sum('jumlah_pemasukan'))
            ->sortDesc()
            ->keys()
            ->first() ?? 'Belum ada data';

        // Ambil tanggal transaksi terakhir untuk ditampilkan pada panel informasi.
        $transaksiTerakhir = $dataAnalisis->sortByDesc('tanggal_pemasukan')->first();

        // Ambil kategori milik user untuk pilihan filter pada halaman laporan.
        // Relasi User::KategoriPemasukan harus menggunakan foreign key id_users.
        // Ambil daftar kategori pemasukan milik user yang sedang login.
        $kategoriPemasukan = DB::table('kategori_pemasukans')
            ->where('id_users', Auth::id())
            ->orderBy('nama_kategori')
            ->get(['id', 'nama_kategori']);

        // Query tabel memakai pencarian, pengurutan terbaru, dan paginasi 10 baris.
        $laporan = $this->filteredQuery($request, $range, true)
            ->orderByDesc('tanggal_pemasukan')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Kirim seluruh data yang dibutuhkan oleh Blade, tanpa angka dummy.
        return view('pages.LaporanPemasukan.index', compact(
            'laporan',
            'totalPemasukan',
            'jumlahTransaksi',
            'rataRataPemasukan',
            'pemasukanTerbesar',
            'grafikPemasukan',
            'kategoriPemasukan',
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
            ->orderByDesc('tanggal_pemasukan')
            ->get();

        // Buat nama file dengan tanggal dan waktu saat file diunduh.
        $filename = 'laporan-pemasukan-' . now()->format('Ymd-His') . '.csv';

        // Stream file agar CSV dapat diunduh tanpa menyimpan file sementara di server.
        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');

            // BOM membantu Excel mengenali karakter UTF-8, termasuk teks berbahasa Indonesia.
            fwrite($output, "\xEF\xBB\xBF");

            // Tulis judul kolom CSV.
            fputcsv($output, [
                'No',
                'Tanggal',
                'Nama Pemasukan',
                'Kategori',
                'Sumber Pemasukan',
                'Deskripsi',
                'Jumlah Pemasukan (Rp)',
            ], ',', '"', '\\');

            // Tulis setiap transaksi dari user yang sedang login dan sesuai filter.
            foreach ($rows as $index => $item) {
                fputcsv($output, [
                    $index + 1,
                    $item->tanggal_pemasukan?->format('Y-m-d H:i:s'),
                    $item->label,
                    $item->Kategori?->nama_kategori ?? 'Tanpa Kategori',
                    $item->sumber_pemasukan ?? '-',
                    $item->deskripsi_pemasukan ?? '-',
                    $item->jumlah_pemasukan,
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
            ->orderByDesc('tanggal_pemasukan')
            ->get();

        // Hitung total pada hasil yang akan dicetak.
        $totalPemasukan = $laporan->sum('jumlah_pemasukan');

        // Tampilkan halaman cetak yang membuka dialog cetak browser secara otomatis.
        return view('pages.LaporanPemasukan.cetak', compact(
            'laporan',
            'range',
            'totalPemasukan'
        ));
    }

    /**
     * Membuat query pemasukan milik user dengan filter periode, kategori, dan opsional pencarian.
     */
    private function filteredQuery(Request $request, array $range, bool $includeSearch = false): Builder
    {
        // Batasi data berdasarkan user yang login dan muat relasi kategori agar tidak query berulang.
        $query = Pemasukan::query()
            ->where('id_users', Auth::id())
            ->with('Kategori')
            ->whereBetween('tanggal_pemasukan', [$range['start'], $range['end']]);

        // Terapkan filter kategori jika ID kategori valid dan benar-benar milik user ini.
        // Periksa apakah kategori benar-benar milik user yang sedang login.
        if ($request->filled('kategori') && ctype_digit((string) $request->input('kategori'))) {

            // Ambil ID kategori dari filter.
            $kategoriId = (int) $request->input('kategori');

            // Pastikan kategori terdaftar dan dimiliki oleh user yang login.
            $kategoriMilikUser = DB::table('kategori_pemasukans')
                ->where('id', $kategoriId)
                ->where('id_users', Auth::id())
                ->exists();

            // Terapkan filter hanya jika kategori tersebut milik user.
            if ($kategoriMilikUser) {
                $query->where('id_kategori', $kategoriId);
            }
        }

        // Pencarian hanya diterapkan pada tabel, ekspor, dan halaman cetak jika diminta.
        if ($includeSearch && $request->filled('search')) {
            $keyword = trim((string) $request->input('search'));

            $query->where(function (Builder $searchQuery) use ($keyword) {
                // Cari berdasarkan label, sumber, atau deskripsi transaksi.
                $searchQuery
                    ->where('label', 'like', '%' . $keyword . '%')
                    ->orWhere('sumber_pemasukan', 'like', '%' . $keyword . '%')
                    ->orWhere('deskripsi_pemasukan', 'like', '%' . $keyword . '%')
                    // Cari juga nama kategori melalui relasi kategori pemasukan.
                    ->orWhereHas('Kategori', function (Builder $kategoriQuery) use ($keyword) {
                        $kategoriQuery->where('nama_kategori', 'like', '%' . $keyword . '%');
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
     * Mengubah transaksi database menjadi data grafik berdasarkan periode.
     *
     * @param Collection $items Data pemasukan milik user yang telah difilter.
     * @param array $range Informasi periode, tanggal mulai, dan tanggal akhir.
     */
    private function buildChart(Collection $items, array $range): array
    {
        // Ambil jenis periode dan tanggal dari hasil resolveRange().
        $periode = $range['periode'];
        $start = $range['start'];
        $end = $range['end'];

        // Siapkan label sumbu grafik dan mode pengelompokan transaksi.
        $labels = [];
        $mode = $periode;

        // Tentukan kelompok waktu berdasarkan filter yang dipilih.
        switch ($periode) {
            case 'harian':
                // Grafik harian membagi transaksi menjadi enam kelompok waktu.
                $mode = 'harian';

                for ($i = 0; $i < 6; $i++) {
                    $labels[$i] = sprintf(
                        '%02d–%02d',
                        $i * 4,
                        ($i * 4) + 3
                    );
                }
                break;

            case 'mingguan':
                // Grafik mingguan menampilkan total pemasukan setiap hari.
                $labels = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ];
                break;

            case 'tahunan':
                // Grafik tahunan menampilkan total pemasukan setiap bulan.
                $labels = [
                    1 => 'Jan',
                    2 => 'Feb',
                    3 => 'Mar',
                    4 => 'Apr',
                    5 => 'Mei',
                    6 => 'Jun',
                    7 => 'Jul',
                    8 => 'Agu',
                    9 => 'Sep',
                    10 => 'Okt',
                    11 => 'Nov',
                    12 => 'Des',
                ];
                break;

            case 'custom':
                // Hitung jumlah hari dalam rentang tanggal yang dipilih.
                $jumlahHari = (int) $start->copy()
                    ->startOfDay()
                    ->diffInDays($end->copy()->startOfDay()) + 1;

                if ($jumlahHari <= 14) {
                    // Rentang maksimal 14 hari menampilkan satu batang per tanggal.
                    $mode = 'custom-harian';

                    for (
                        $tanggal = $start->copy()->startOfDay();
                        $tanggal->lte($end);
                        $tanggal->addDay()
                    ) {
                        $labels[$tanggal->format('Y-m-d')] =
                            $tanggal->format('d/m');
                    }
                } else {
                    // Rentang lebih panjang dikelompokkan per tujuh hari.
                    $mode = 'custom-mingguan';
                    $jumlahMinggu = (int) ceil($jumlahHari / 7);

                    for ($i = 1; $i <= $jumlahMinggu; $i++) {
                        $labels[$i] = 'Minggu ' . $i;
                    }
                }
                break;

            default:
                // Grafik bulanan mengelompokkan tanggal menjadi lima kelompok.
                $labels = [
                    1 => 'Minggu 1',
                    2 => 'Minggu 2',
                    3 => 'Minggu 3',
                    4 => 'Minggu 4',
                    5 => 'Minggu 5',
                ];
                break;
        }

        // Isi setiap kelompok dengan nol agar label tetap muncul tanpa transaksi.
        $totals = [];

        foreach ($labels as $key => $label) {
            $totals[(string) $key] = 0;
        }

        // Kelompokkan dan jumlahkan transaksi sesuai tanggalnya.
        foreach ($items as $item) {
            // Lewati transaksi jika tanggalnya tidak tersedia.
            if (! $item->tanggal_pemasukan) {
                continue;
            }

            // Ubah tanggal transaksi menjadi objek Carbon.
            $tanggal = Carbon::parse($item->tanggal_pemasukan);

            // Tentukan kelompok waktu untuk setiap transaksi.
            switch ($mode) {
                case 'harian':
                    // Gunakan kelompok waktu empat jam.
                    $key = (string) (int) floor($tanggal->hour / 4);
                    break;

                case 'mingguan':
                    // Gunakan nomor hari, yaitu Senin 1 sampai Minggu 7.
                    $key = (string) $tanggal->dayOfWeekIso;
                    break;

                case 'tahunan':
                    // Gunakan nomor bulan, yaitu Januari 1 sampai Desember 12.
                    $key = (string) $tanggal->month;
                    break;

                case 'custom-harian':
                    // Gunakan tanggal lengkap sebagai kunci kelompok.
                    $key = $tanggal->format('Y-m-d');
                    break;

                case 'custom-mingguan':
                    // Hitung kelompok minggu dari awal rentang tanggal.
                    $selisihHari = (int) $start->copy()
                        ->startOfDay()
                        ->diffInDays($tanggal->copy()->startOfDay());

                    $key = (string) (
                        (int) floor($selisihHari / 7) + 1
                    );
                    break;

                default:
                    // Kelompokkan tanggal bulanan per tujuh hari.
                    $key = (string) (
                        (int) floor(($tanggal->day - 1) / 7) + 1
                    );
                    break;
            }

            // Tambahkan nominal jika kelompok waktunya tersedia.
            if (array_key_exists($key, $totals)) {
                $totals[$key] += (float) $item->jumlah_pemasukan;
            }
        }

        // Cari nilai terbesar untuk menentukan tinggi relatif setiap batang.
        $nilaiMaksimal = $totals ? max($totals) : 0;

        // Susun hasil akhir yang akan digunakan oleh Blade.
        $grafik = [];

        foreach ($labels as $key => $label) {
            // Ambil total pemasukan kelompok tersebut.
            $jumlah = (float) ($totals[(string) $key] ?? 0);

            // Hitung tinggi batang dalam persentase 0 sampai 100.
            $tinggi = $nilaiMaksimal > 0
                ? (int) round(($jumlah / $nilaiMaksimal) * 100)
                : 0;

            // Simpan label, nominal, dan tinggi untuk ditampilkan di grafik.
            $grafik[] = [
                'label' => $label,
                'jumlah' => $jumlah,
                'tinggi' => $tinggi,
            ];
        }

        // Kembalikan data grafik kepada method index().
        return $grafik;
    }
}
