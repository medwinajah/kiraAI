<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Grafik extends Component
{
    /*
    |--------------------------------------------------------------------------
    | FILTER PROPERTIES
    |--------------------------------------------------------------------------
    */

    public string $periode = 'mingguan';

    public ?string $tanggalMulai = null;

    public ?string $tanggalSampai = null;


    /*
    |--------------------------------------------------------------------------
    | 1. FILTER HANDLERS
    |--------------------------------------------------------------------------
    */

    /**
     * Menangani perubahan pilihan periode.
     */
    public function updatedPeriode($value): void
    {
        $periodeValid = [
            'harian',
            'mingguan',
            'bulanan',
            'tahunan',
            'rentang',
        ];

        if (!in_array($value, $periodeValid, true)) {
            $this->periode = 'mingguan';

            return;
        }

        if ($value === 'rentang') {
            $today = Carbon::today()->toDateString();

            $this->tanggalMulai ??= Carbon::today()
                ->startOfMonth()
                ->toDateString();

            $this->tanggalSampai ??= $today;

            $this->tanggalMulai = $this->isValidDate(
                $this->tanggalMulai
            )
                ? min($this->tanggalMulai, $today)
                : Carbon::today()->startOfMonth()->toDateString();

            $this->tanggalSampai = $this->isValidDate(
                $this->tanggalSampai
            )
                ? min($this->tanggalSampai, $today)
                : $today;

            if ($this->tanggalMulai > $this->tanggalSampai) {
                $this->tanggalSampai = $this->tanggalMulai;
            }
        }
    }

    /**
     * Menangani perubahan tanggal mulai.
     */
    public function updatedTanggalMulai($value): void
    {
        $this->periode = 'rentang';

        $today = Carbon::today()->toDateString();

        $this->tanggalMulai = $this->isValidDate($value)
            ? min($value, $today)
            : Carbon::today()->startOfMonth()->toDateString();

        if (!$this->isValidDate($this->tanggalSampai)) {
            $this->tanggalSampai = $today;
        }

        $this->tanggalSampai = min(
            $this->tanggalSampai,
            $today
        );

        if ($this->tanggalMulai > $this->tanggalSampai) {
            $this->tanggalSampai = $this->tanggalMulai;
        }
    }

    /**
     * Menangani perubahan tanggal akhir.
     */
    public function updatedTanggalSampai($value): void
    {
        $this->periode = 'rentang';

        $today = Carbon::today()->toDateString();

        $this->tanggalSampai = $this->isValidDate($value)
            ? min($value, $today)
            : $today;

        if (!$this->isValidDate($this->tanggalMulai)) {
            $this->tanggalMulai = Carbon::today()
                ->startOfMonth()
                ->toDateString();
        }

        if ($this->tanggalSampai < $this->tanggalMulai) {
            $this->tanggalMulai = $this->tanggalSampai;
        }
    }

    /**
     * Mengembalikan filter ke kondisi mingguan.
     */
    public function resetFilter(): void
    {
        $this->periode = 'mingguan';
        $this->tanggalMulai = null;
        $this->tanggalSampai = null;
    }

    /**
     * Memeriksa validitas tanggal berformat Y-m-d.
     */
    private function isValidDate($value): bool
    {
        if (
            !is_string($value) ||
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)
        ) {
            return false;
        }

        try {
            $date = Carbon::createFromFormat('!Y-m-d', $value);

            return $date !== false
                && $date->format('Y-m-d') === $value;
        } catch (Throwable $e) {
            return false;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 2. MENENTUKAN PERIODE GRAFIK
    |--------------------------------------------------------------------------
    */

    /**
     * Menyiapkan tanggal awal, tanggal akhir, dan deskripsi periode.
     */
    private function getPeriodeData(Carbon $now): array
    {
        $today = $now->copy()->startOfDay();
        $todayString = $today->toDateString();

        $endDate = $now->copy()->endOfDay();

        switch ($this->periode) {
            case 'harian':
                $startDate = $today->copy();
                $labelPeriode = 'Harian';
                $keteranganPeriode = 'Hari ini, berdasarkan jam';

                break;

            case 'mingguan':
                $startDate = $today->copy()
                    ->startOfWeek(Carbon::MONDAY);

                $labelPeriode = 'Mingguan';
                $keteranganPeriode = 'Minggu berjalan, berdasarkan hari';

                break;

            case 'bulanan':
                $startDate = $today->copy()->startOfMonth();

                $labelPeriode = 'Bulanan';
                $keteranganPeriode = 'Bulan berjalan, berdasarkan hari';

                break;

            case 'tahunan':
                $startDate = $today->copy()->startOfYear();

                $labelPeriode = 'Tahunan';
                $keteranganPeriode = 'Tahun berjalan, berdasarkan bulan';

                break;

            case 'rentang':
                $rangeStart = $this->isValidDate($this->tanggalMulai)
                    ? min($this->tanggalMulai, $todayString)
                    : $today->copy()->startOfMonth()->toDateString();

                $rangeEnd = $this->isValidDate($this->tanggalSampai)
                    ? min($this->tanggalSampai, $todayString)
                    : $todayString;

                if ($rangeStart > $rangeEnd) {
                    $rangeEnd = $rangeStart;
                }

                $startDate = Carbon::createFromFormat(
                    '!Y-m-d',
                    $rangeStart
                )->startOfDay();

                $endDate = Carbon::createFromFormat(
                    '!Y-m-d',
                    $rangeEnd
                )->endOfDay();

                $labelPeriode = 'Rentang Tanggal';

                $keteranganPeriode =
                    $startDate->translatedFormat('d M Y')
                    . ' – '
                    . $endDate->translatedFormat('d M Y');

                break;

            default:
                $this->periode = 'mingguan';

                $startDate = $today->copy()
                    ->startOfWeek(Carbon::MONDAY);

                $labelPeriode = 'Mingguan';
                $keteranganPeriode = 'Minggu berjalan, berdasarkan hari';

                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'labelPeriode' => $labelPeriode,
            'keteranganPeriode' => $keteranganPeriode,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 3. MEMBUAT BUCKET WAKTU
    |--------------------------------------------------------------------------
    */

    /**
     * Menentukan cara pengelompokan transaksi pada grafik.
     *
     * Harian   : per jam.
     * Mingguan : per hari.
     * Bulanan  : per hari.
     * Tahunan  : per bulan.
     * Rentang  : per hari jika <= 31 hari, selain itu per bulan.
     */
    private function getBucketData(
        Carbon $startDate,
        Carbon $endDate
    ): array {
        $totalHari = (int) $startDate
            ->copy()
            ->startOfDay()
            ->diffInDays($endDate->copy()->startOfDay()) + 1;

        $bucketMode = match ($this->periode) {
            'harian' => 'hourly',
            'tahunan' => 'monthly',
            'rentang' => $totalHari > 31 ? 'monthly' : 'daily',
            default => 'daily',
        };

        $labels = [];
        $keys = [];

        if ($bucketMode === 'hourly') {
            $today = Carbon::today();

            for ($jam = 0; $jam <= Carbon::now()->hour; $jam++) {
                $waktu = $today->copy()->addHours($jam);

                $labels[] = $waktu->format('H:i');
                $keys[] = $waktu->format('Y-m-d H');
            }
        } elseif ($bucketMode === 'monthly') {
            $cursor = $startDate->copy()->startOfMonth();
            $lastMonth = $endDate->copy()->startOfMonth();

            while ($cursor->lessThanOrEqualTo($lastMonth)) {
                $labels[] = $this->periode === 'tahunan'
                    ? $cursor->translatedFormat('M')
                    : $cursor->translatedFormat('M Y');

                $keys[] = $cursor->format('Y-m');

                $cursor->addMonth();
            }
        } else {
            $cursor = $startDate->copy()->startOfDay();
            $lastDay = $endDate->copy()->startOfDay();

            while ($cursor->lessThanOrEqualTo($lastDay)) {
                if ($this->periode === 'mingguan') {
                    $labels[] = $cursor->translatedFormat('D, d M');
                } elseif ($this->periode === 'bulanan') {
                    $labels[] = $cursor->format('d');
                } else {
                    $labels[] = $cursor->translatedFormat('d M');
                }

                $keys[] = $cursor->toDateString();

                $cursor->addDay();
            }
        }

        return [
            'mode' => $bucketMode,
            'labels' => $labels,
            'keys' => $keys,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 4. AGREGASI NOMINAL BERDASARKAN WAKTU
    |--------------------------------------------------------------------------
    */

    /**
     * Mengambil total nominal yang sudah dikelompokkan oleh database.
     */
    private function getTotalsByBucket(
        int $userId,
        string $table,
        string $amountColumn,
        string $dateColumn,
        Carbon $startDate,
        Carbon $endDate,
        string $bucketMode
    ): array {
        $query = DB::table($table)
            ->where('id_users', $userId)
            ->whereBetween($dateColumn, [$startDate, $endDate]);

        if ($bucketMode === 'hourly') {
            $expression = "DATE_FORMAT({$dateColumn}, '%Y-%m-%d %H')";
        } elseif ($bucketMode === 'monthly') {
            $expression = "DATE_FORMAT({$dateColumn}, '%Y-%m')";
        } else {
            $expression = "DATE({$dateColumn})";
        }

        return $query
            ->selectRaw(
                "{$expression} as bucket, SUM({$amountColumn}) as total"
            )
            ->groupByRaw($expression)
            ->pluck('total', 'bucket')
            ->map(fn($total) => (int) $total)
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | 5. MENYIAPKAN DATA CHART.JS
    |--------------------------------------------------------------------------
    */

    /**
     * Menyiapkan dataset pemasukan dan pengeluaran.
     */
    private function getChartData(
        int $userId,
        Carbon $startDate,
        Carbon $endDate
    ): array {
        $buckets = $this->getBucketData($startDate, $endDate);

        $keys = $buckets['keys'];
        $indexBucket = array_flip($keys);

        $totalPemasukan = $this->getTotalsByBucket(
            $userId,
            'pemasukans',
            'jumlah_pemasukan',
            'tanggal_pemasukan',
            $startDate,
            $endDate,
            $buckets['mode']
        );

        $totalPengeluaran = $this->getTotalsByBucket(
            $userId,
            'pengeluarans',
            'jumlah_pengeluaran',
            'tanggal_pengeluaran',
            $startDate,
            $endDate,
            $buckets['mode']
        );

        $dataPemasukan = [];
        $dataPengeluaran = [];

        foreach ($keys as $key) {
            $dataPemasukan[] = (int) ($totalPemasukan[$key] ?? 0);
            $dataPengeluaran[] = (int) ($totalPengeluaran[$key] ?? 0);
        }

        return [
            'periode' => $this->periode,

            'perbandingan' => [
                'labels' => $buckets['labels'],
                'pemasukan' => $dataPemasukan,
                'pengeluaran' => $dataPengeluaran,
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 6. KATEGORI PEMASUKAN
    |--------------------------------------------------------------------------
    */

    /**
 * Mengambil 5 kategori pemasukan teratas
 * berdasarkan seluruh riwayat transaksi pengguna.
 */
private function getKategoriPemasukan(int $userId): Collection
{
    return DB::table('kategori_pemasukans as k')
        ->leftJoin('pemasukans as p', function ($join) use ($userId) {
            $join->on('p.id_kategori', '=', 'k.id')
                ->where('p.id_users', '=', $userId);
        })
        ->where('k.id_users', $userId)
        ->select([
            'k.id',
            'k.nama_kategori as nama_kategori',
        ])
        ->selectRaw('
            COUNT(p.id) as total_transaksi,
            COALESCE(SUM(p.jumlah_pemasukan), 0) as total_nominal
        ')
        ->groupBy('k.id', 'k.nama_kategori')
        ->orderByDesc('total_transaksi')
        ->orderByDesc('total_nominal')
        ->orderBy('k.nama_kategori')
        ->limit(5)
        ->get();
}

    /*
    |--------------------------------------------------------------------------
    | 7. KATEGORI PENGELUARAN
    |--------------------------------------------------------------------------
    */

    /**
 * Mengambil 5 kategori pengeluaran teratas
 * berdasarkan seluruh riwayat transaksi pengguna.
 */
private function getKategoriPengeluaran(int $userId): Collection
{
    return DB::table('kategori_pengelurans as k')
        ->leftJoin('pengeluarans as p', function ($join) use ($userId) {
            $join->on('p.id_kategori', '=', 'k.id')
                ->where('p.id_users', '=', $userId);
        })
        ->where('k.id_users', $userId)
        ->select([
            'k.id',
            'k.nama_ketegori as nama_kategori',
        ])
        ->selectRaw('
            COUNT(p.id) as total_transaksi,
            COALESCE(SUM(p.jumlah_pengeluaran), 0) as total_nominal
        ')
        ->groupBy('k.id', 'k.nama_ketegori')
        ->orderByDesc('total_transaksi')
        ->orderByDesc('total_nominal')
        ->orderBy('k.nama_ketegori')
        ->limit(5)
        ->get();
}

    /*
    |--------------------------------------------------------------------------
    | 8. TRANSAKSI TERBARU
    |--------------------------------------------------------------------------
    */

    /**
     * Menggabungkan pemasukan dan pengeluaran.
     *
     * Mengambil beberapa kandidat per jenis, lalu memilih enam transaksi
     * terbaru agar tabel dashboard tidak terlalu panjang.
     */
    private function getTransaksiTerbaru(int $userId): Collection
    {
        $pemasukan = $this->getTransaksiByType(
            $userId,
            'pemasukan',
            10
        );

        $pengeluaran = $this->getTransaksiByType(
            $userId,
            'pengeluaran',
            10
        );

        return $pemasukan
            ->merge($pengeluaran)
            ->sortByDesc(
                fn(array $transaksi) =>
                $transaksi['tanggal']->timestamp
            )
            ->take(6)
            ->values();
    }


    /**
     * Mengambil transaksi untuk satu jenis dengan kategori opsional.
     */
    private function getTransaksiByType(
        int $userId,
        string $jenis,
        int $limit = 10
    ): Collection {
        if ($jenis === 'pemasukan') {
            $table = 'pemasukans';
            $categoryTable = 'kategori_pemasukans';
            $dateColumn = 'tanggal_pemasukan';
            $amountColumn = 'jumlah_pemasukan';
            $descriptionColumn = 'deskripsi_pemasukan';
            $categoryNameColumn = 'nama_kategori';
            $labelJenis = 'Pemasukan';
        } elseif ($jenis === 'pengeluaran') {
            $table = 'pengeluarans';
            $categoryTable = 'kategori_pengelurans';
            $dateColumn = 'tanggal_pengeluaran';
            $amountColumn = 'jumlah_pengeluaran';
            $descriptionColumn = 'deskripsi_pengeluaran';
            $categoryNameColumn = 'nama_ketegori';
            $labelJenis = 'Pengeluaran';
        } else {
            return collect();
        }

        return DB::table($table . ' as p')
            ->leftJoin($categoryTable . ' as k', function ($join) use ($userId) {
                $join->on('k.id', '=', 'p.id_kategori')
                    ->where('k.id_users', '=', $userId);
            })
            ->where('p.id_users', $userId)
            ->orderByDesc('p.' . $dateColumn)
            ->orderByDesc('p.id')
            ->limit($limit)
            ->get([
                'p.id as id',
                'p.label as label',
                'p.' . $amountColumn . ' as jumlah',
                'p.' . $dateColumn . ' as tanggal',
                'p.' . $descriptionColumn . ' as deskripsi',
                'k.' . $categoryNameColumn . ' as kategori',
            ])
            ->map(function ($row) use ($jenis, $labelJenis) {
                return [
                    'id' => $jenis . '-' . $row->id,
                    'label' => $row->label,
                    'kategori' => $row->kategori
                        ?? 'Kategori tidak tersedia',
                    'jenis' => $labelJenis,
                    'jumlah' => (int) $row->jumlah,
                    'tanggal' => Carbon::parse($row->tanggal),
                    'deskripsi' => $row->deskripsi,
                ];
            });
    }

    /*
|--------------------------------------------------------------------------
| MENYIAPKAN DATA GRAFIK KATEGORI
|--------------------------------------------------------------------------
*/

    /**
 * Mengubah hasil query kategori menjadi format Chart.js.
 */
private function getCategoryChartData(Collection $categories): array
{
    return [
        'labels' => $categories
            ->pluck('nama_kategori')
            ->map(fn ($name) => (string) $name)
            ->values()
            ->all(),

        'values' => $categories
            ->pluck('total_transaksi')
            ->map(fn ($total) => (int) $total)
            ->values()
            ->all(),
    ];
}


    /*
    |--------------------------------------------------------------------------
    | 9. RENDER
    |--------------------------------------------------------------------------
    |
    | Render hanya mengatur alur pemanggilan function dan mengirim
    | seluruh data yang diperlukan oleh Blade.
    */

  /*
|--------------------------------------------------------------------------
| 9. RENDER
|--------------------------------------------------------------------------
|
| Mengatur pemanggilan function dan mengirim data ke Blade.
*/

public function render()
{
    abort_unless(Auth::check(), 401);

    $userId = (int) Auth::id();
    $now = Carbon::now();

    /*
    |--------------------------------------------------------------------------
    | 1. Menyiapkan periode
    |--------------------------------------------------------------------------
    */

    $periodeData = $this->getPeriodeData($now);

    $startDate = $periodeData['startDate'];
    $endDate = $periodeData['endDate'];

    /*
    |--------------------------------------------------------------------------
    | 2. Menyiapkan grafik pemasukan vs pengeluaran
    |--------------------------------------------------------------------------
    */

    $chartData = $this->getChartData(
        $userId,
        $startDate,
        $endDate
    );

    /*
    |--------------------------------------------------------------------------
    | 3. Mengambil kategori pemasukan dan pengeluaran
    |--------------------------------------------------------------------------
    */

   // Kategori dihitung dari seluruh riwayat transaksi.
$kategoriPemasukan = $this->getKategoriPemasukan($userId);

$kategoriPengeluaran = $this->getKategoriPengeluaran($userId);

// Data kategori dikirim ke JavaScript untuk Chart.js.
$chartData['kategoriPemasukan'] =
    $this->getCategoryChartData($kategoriPemasukan);

$chartData['kategoriPengeluaran'] =
    $this->getCategoryChartData($kategoriPengeluaran);

    /*
    |--------------------------------------------------------------------------
    | 4. Menyiapkan data kategori untuk Chart.js
    |--------------------------------------------------------------------------
    |
    | Kedua key wajib tersedia karena JavaScript membaca:
    | chartData.kategoriPemasukan
    | chartData.kategoriPengeluaran
    */

    $chartData['kategoriPemasukan'] = $this->getCategoryChartData(
        $kategoriPemasukan
    );

    $chartData['kategoriPengeluaran'] = $this->getCategoryChartData(
        $kategoriPengeluaran
    );

    /*
    |--------------------------------------------------------------------------
    | 5. Mengambil enam transaksi terbaru
    |--------------------------------------------------------------------------
    */

    $transaksiTerbaru = $this->getTransaksiTerbaru($userId);

    /*
    |--------------------------------------------------------------------------
    | 6. Mengirim seluruh data ke Blade
    |--------------------------------------------------------------------------
    */

    return view('livewire.dashboard.grafik', [
        'chartData' => $chartData,

        'kategoriPemasukan' => $kategoriPemasukan,
        'kategoriPengeluaran' => $kategoriPengeluaran,

        'transaksiTerbaru' => $transaksiTerbaru,

        'tanggalHariIni' => $now->translatedFormat('l, d F Y'),

        'labelPeriode' => $periodeData['labelPeriode'],
        'keteranganPeriode' => $periodeData['keteranganPeriode'],
    ]);
}
}
