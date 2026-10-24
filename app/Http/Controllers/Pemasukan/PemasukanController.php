<?php

namespace App\Http\Controllers\Pemasukan;

use App\Http\Controllers\Controller;
use App\Models\kategoriPemasukan;
use App\Models\Pemasukan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

// HANDLE EXCEL 
use App\Imports\TransaksiPreviewImport;
use App\Exports\TransaksiTemplateExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use DateTimeInterface;


class PemasukanController extends Controller
{
    // Render Data

    public function index()
    {
        $importPreview = [];
        $importHasErrors = false;

        $token = session('transaksi_import_preview_token');

        if (is_string($token) && Str::isUuid($token)) {
            $path = $this->importPreviewPath((int) Auth::id(), $token);

            if (Storage::disk('local')->exists($path)) {
                $payload = json_decode(
                    Storage::disk('local')->get($path),
                    true
                );

                $importPreview = $payload['rows'] ?? [];

                $importHasErrors = collect($importPreview)->contains(
                    fn($row) => !empty($row['errors'])
                );
            } else {
                session()->forget('transaksi_import_preview_token');
            }
        }

        return view('pages.Pemasukan.index', [
            'importPreview' => $importPreview,
            'importHasErrors' => $importHasErrors,
        ]);
    }


    // Render halaman tambah
    public function create()
    {
        $kategori_pemasukan = $this->dataKategoriPemasukan();

        return view(
            'pages.Pemasukan.tambah',
            [
                'kategori_pemasukan' => $kategori_pemasukan,
            ]
        );
    }

    // Handle simpan data
    public function store(Request $request)
    {

        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pemasukan' => 'required|numeric|min:1000',
                'sumber_pemasukan' => 'nullable|string',
                'tanggal_pemasukan' => 'required|date',
                'deskripsi_pemasukan' => 'nullable|string'
            ],
            [
                'kategori.required' => 'Kategori wajib diisi.',

                'label.required' => 'Label wajib diisi.',
                'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                'label.string' => 'Label harus berupa teks.',

                'jumlah_pemasukan.required' => 'Jumlah pemasukan wajib diisi.',
                'jumlah_pemasukan.numeric' => 'Jumlah pemasukan harus berupa angka.',
                'jumlah_pemasukan.min' => 'Jumlah pemasukan minimal adalah 1.000.',

                'sumber_pemasukan.string' => 'Sumber pemasukan harus berupa teks.',

                'tanggal_pemasukan.required' => 'Tanggal pemasukan wajib diisi.',
                'tanggal_pemasukan.date' => 'Format tanggal pemasukan tidak valid.',

                'deskripsi_pemasukan.string' => 'Deskripsi pemasukan harus berupa teks.',
            ]

        );

        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        try {
            Pemasukan::create(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pemasukan' => $validasi['jumlah_pemasukan'],
                    'sumber_pemasukan' => $validasi['sumber_pemasukan'],
                    'tanggal_pemasukan' => $validasi['tanggal_pemasukan'] . ' ' . $jam_terkini,
                    'deskripsi_pemasukan' => $validasi['deskripsi_pemasukan'],
                    'id_kategori' => $validasi['kategori'],
                ],
                [
                    'kategori.required' => 'Kategori wajib diisi.',

                    'label.required' => 'Label wajib diisi.',
                    'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                    'label.string' => 'Label harus berupa teks.',

                    'jumlah_pemasukan.required' => 'Jumlah pemasukan wajib diisi.',
                    'jumlah_pemasukan.numeric' => 'Jumlah pemasukan harus berupa angka.',
                    'jumlah_pemasukan.min' => 'Jumlah pemasukan minimal adalah 1.000.',

                    'sumber_pemasukan.string' => 'Sumber pemasukan harus berupa teks.',

                    'tanggal_pemasukan.required' => 'Tanggal pemasukan wajib diisi.',
                    'tanggal_pemasukan.date' => 'Format tanggal pemasukan tidak valid.',

                    'deskripsi_pemasukan.string' => 'Deskripsi pemasukan harus berupa teks.',
                ]
            );

            //    Sukses
            Alert::success('Success', 'Data pemasukan berhasil disimpan.');
            return back();
        } catch (\Exception $er) {
            Alert::error('Error', "Mohon maaf data gagal ditambahkan!");
            Logger("Error Tambah Data Pemasukan: " + $er->getMessage());
        }
    }

    // Handle Load Jenis Kategori
    public function dataKategoriPemasukan()
    {
        return kategoriPemasukan::all();
    }

    // Handle Edit
    public function edit($id)
    {
        $data = Pemasukan::findOrFail($id);
        $kategori = kategoriPemasukan::all();
        return view('pages.Pemasukan.edit', [
            'pemasukan' => $data,
            'kategori_pemasukan' => $kategori,
        ]);
    }

    // Handle Update
    public function update(Request $request, $id)
    {
        $data = Pemasukan::findOrFail($id);
        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pemasukan' => 'required|numeric|min:1000',
                'sumber_pemasukan' => 'nullable|string',
                'tanggal_pemasukan' => 'required|date',
                'deskripsi_pemasukan' => 'nullable|string'
            ]
        );
        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        $tanggal_lama = $data->tanggal_pemasukan->format('Y-m-d');
        if ($request->tanggal_pemasukan == $tanggal_lama) {
            $validasi['tanggal_pemasukan'] = $data->tanggal_pemasukan;
        } else {
            $validasi['tanggal_pemasukan'] =  $request->tanggal_pemasukan . ' ' . $jam_terkini;
        }

        try {
            $data->update(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pemasukan' => $validasi['jumlah_pemasukan'],
                    'sumber_pemasukan' => $validasi['sumber_pemasukan'],
                    'tanggal_pemasukan' => $validasi['tanggal_pemasukan'],
                    'deskripsi_pemasukan' => $validasi['deskripsi_pemasukan'],
                    'id_kategori' => $validasi['kategori'],
                ]
            );

            //    Sukses
            Alert::success('Success', 'Data pemasukan berhasil disimpan.')->toToast('top-right');
            return redirect()->route('pemasukan.index');
        } catch (\Exception $er) {

            logger("Error Tambah Data Pemasukan: " . $er->getMessage());
            // PERBAIKAN: Menambahkan return back agar halaman tidak putih kosong saat eror
            Alert::error('Error', "Mohon maaf data gagal diperbarui!");
            return back()->withInput();
        }
    }



    public function previewImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'excel_file.required' => 'Pilih file Excel terlebih dahulu.',
            'excel_file.file' => 'File yang dikirim tidak valid.',
            'excel_file.mimes' => 'Gunakan file XLSX, XLS, atau CSV.',
            'excel_file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $userId = (int) Auth::id();

        try {
            /*
        |--------------------------------------------------------------------------
        | 1. BACA FILE EXCEL
        |--------------------------------------------------------------------------
        */

            $import = new TransaksiPreviewImport();

            Excel::import($import, $request->file('excel_file'));

            $allRows = $import->rows;

            if ($allRows->isEmpty()) {
                return redirect()
                    ->route('pemasukan.index')
                    ->with('error', 'File Excel kosong atau tidak dapat dibaca.');
            }

            /*
        |--------------------------------------------------------------------------
        | 2. VALIDASI HEADER
        |--------------------------------------------------------------------------
        | Template pemasukan harus mempunyai enam kolom dalam urutan:
        | tanggal, label, jumlah, kategori, sumber_pemasukan, deskripsi.
        */

            $normalizeHeader = function ($value): string {
                $value = trim((string) $value);

                // Hilangkan BOM yang mungkin terdapat di awal header CSV.
                $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;

                // Samakan huruf besar dan kecil.
                $value = mb_strtolower($value);

                // Spasi atau tanda hubung diubah menjadi underscore.
                $value = preg_replace('/[\s\-]+/u', '_', $value) ?? $value;

                return $value;
            };

            $headerRow = $allRows->first();

            $headerValues = $headerRow instanceof \Illuminate\Support\Collection
                ? $headerRow->values()->all()
                : array_values((array) $headerRow);

            $expectedHeaders = [
                'tanggal',
                'label',
                'jumlah',
                'kategori',
                'sumber_pemasukan',
                'deskripsi',
            ];

            $actualHeaders = array_map(
                $normalizeHeader,
                array_slice($headerValues, 0, count($expectedHeaders))
            );

            if ($actualHeaders !== $expectedHeaders) {
                Log::warning('Header template Excel pemasukan tidak sesuai.', [
                    'user_id' => $userId,
                    'expected_headers' => $expectedHeaders,
                    'actual_headers' => $actualHeaders,
                ]);

                return redirect()
                    ->route('pemasukan.index')
                    ->with(
                        'error',
                        'Format header Excel tidak sesuai. Unduh template pemasukan terbaru dari aplikasi dan gunakan header pada template tersebut.'
                    );
            }

            /*
        |--------------------------------------------------------------------------
        | 3. AMBIL KATEGORI DARI DATABASE
        |--------------------------------------------------------------------------
        */

            $incomeCategories = DB::table('kategori_pemasukans')
                ->where('id_users', $userId)
                ->get(['id', 'nama_kategori'])
                ->mapWithKeys(fn($category) => [
                    $this->normalizeImportText($category->nama_kategori) => [
                        'id' => (int) $category->id,
                        'name' => $category->nama_kategori,
                    ],
                ])
                ->all();

            /*
        |--------------------------------------------------------------------------
        | 4. BACA DAN VALIDASI DATA MULAI BARIS KEDUA
        |--------------------------------------------------------------------------
        | A = tanggal
        | B = label
        | C = jumlah
        | D = kategori
        | E = sumber_pemasukan
        | F = deskripsi
        */

            $rows = [];

            foreach ($allRows->slice(1)->values() as $index => $item) {
                $data = $item instanceof \Illuminate\Support\Collection
                    ? $item->values()->all()
                    : array_values((array) $item);

                // Pastikan keenam posisi kolom tersedia.
                $data = array_pad($data, 6, null);

                // Abaikan baris yang seluruh kolomnya kosong.
                $hasData = collect($data)->contains(
                    function ($value) {
                        if ($value === null) {
                            return false;
                        }

                        if ($value instanceof \DateTimeInterface) {
                            return true;
                        }

                        return is_string($value)
                            ? trim($value) !== ''
                            : true;
                    }
                );

                if (!$hasData) {
                    continue;
                }

                /*
             * Jangan mengambil nilai berdasarkan nama key.
             * Ambil langsung berdasarkan posisi kolom Excel.
             */
                $tanggalRaw = $data[0];
                $label = trim((string) ($data[1] ?? ''));
                $jumlahRaw = $data[2];
                $kategoriNama = trim((string) ($data[3] ?? ''));
                $sumber = trim((string) ($data[4] ?? ''));
                $deskripsi = trim((string) ($data[5] ?? ''));

                $tanggal = $this->normalizeImportDate($tanggalRaw);
                $jumlah = $this->normalizeImportAmount($jumlahRaw);

                $errors = [];

                // Validasi label.
                if (mb_strlen($label) < 4) {
                    $errors[] = 'Label wajib diisi dan minimal 4 karakter.';
                }

                // Validasi tanggal.
                if ($tanggal === null) {
                    $errors[] = 'Tanggal tidak valid. Gunakan format YYYY-MM-DD.';
                }

                // Validasi jumlah.
                if ($jumlah === null) {
                    $errors[] = 'Jumlah pemasukan harus berupa angka minimal Rp1.000.';
                }

                // Validasi kategori.
                if ($kategoriNama === '') {
                    $errors[] = 'Kategori pemasukan wajib diisi.';
                }

                $matchedCategory = $kategoriNama !== ''
                    ? (
                        $incomeCategories[$this->normalizeImportText($kategoriNama)] ?? null
                    )
                    : null;

                if ($kategoriNama !== '' && $matchedCategory === null) {
                    $errors[] = 'Kategori pemasukan tidak ditemukan pada akun ini.';
                }

                // Tolak data tambahan di luar enam kolom template.
                $extraColumns = array_slice($data, 6);

                foreach ($extraColumns as $extraValue) {
                    if (
                        $extraValue !== null &&
                        trim((string) $extraValue) !== ''
                    ) {
                        $errors[] = 'Terdapat data tambahan di luar enam kolom template.';
                        break;
                    }
                }

                /*
            |--------------------------------------------------------------------------
            | 5. SIMPAN HASIL PREVIEW
            |--------------------------------------------------------------------------
            */

                $rows[] = [
                    'row_number' => $index + 2,

                    'jenis_transaksi' => 'pemasukan',
                    'jenis_label' => 'Pemasukan',

                    'tanggal' => $tanggal,
                    'label' => $label,
                    'jumlah' => $jumlah,

                    'kategori' => $matchedCategory['name'] ?? $kategoriNama,
                    'id_kategori' => $matchedCategory['id'] ?? null,

                    'sumber_pemasukan' => $sumber,
                    'deskripsi' => $deskripsi,

                    'errors' => $errors,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | 6. PASTIKAN ADA TRANSAKSI
        |--------------------------------------------------------------------------
        */

            if (count($rows) === 0) {
                return redirect()
                    ->route('pemasukan.index')
                    ->with(
                        'error',
                        'Tidak ada baris transaksi yang terisi. Isi data mulai dari baris kedua template Excel.'
                    );
            }

            /*
        |--------------------------------------------------------------------------
        | 7. SIMPAN PREVIEW SEMENTARA DI SERVER
        |--------------------------------------------------------------------------
        */

            $token = (string) Str::uuid();
            $path = $this->importPreviewPath($userId, $token);

            Storage::disk('local')->put(
                $path,
                json_encode(
                    ['rows' => $rows],
                    JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
                )
            );

            // Hapus preview lama setelah preview baru berhasil disiapkan.
            $this->clearImportPreview();

            session([
                'transaksi_import_preview_token' => $token,
            ]);

            $validCount = collect($rows)
                ->filter(fn($row) => empty($row['errors']))
                ->count();

            $errorCount = count($rows) - $validCount;

            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'success',
                    "File berhasil dibaca. {$validCount} baris valid dan {$errorCount} baris perlu diperiksa."
                );
        } catch (\Throwable $e) {
            Log::error('Gagal membaca Excel pemasukan KiraAI.', [
                'user_id' => $userId,
                'exception' => $e->getMessage(),
            ]);

            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'error',
                    'File Excel gagal dibaca. Periksa file dan pastikan kamu menggunakan template pemasukan yang diunduh dari aplikasi.'
                );
        }
    }



    // HANDLE KONFIRM IMPORT


    public function confirmImport()
    {
        $userId = (int) Auth::id();

        $token = session('transaksi_import_preview_token');

        // 1. Memastikan token preview valid.
        if (!is_string($token) || !Str::isUuid($token)) {
            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'error',
                    'Preview tidak ditemukan. Silakan unggah ulang file.'
                );
        }

        $path = $this->importPreviewPath($userId, $token);

        if (!Storage::disk('local')->exists($path)) {
            $this->clearImportPreview();

            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'error',
                    'Preview telah kedaluwarsa. Silakan unggah ulang file.'
                );
        }

        try {
            // 2. Membaca preview yang tersimpan di server.
            $payload = json_decode(
                Storage::disk('local')->get($path),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            $rows = $payload['rows'] ?? [];

            if (!is_array($rows) || count($rows) === 0) {
                return redirect()
                    ->route('pemasukan.index')
                    ->with(
                        'error',
                        'Data preview tidak valid. Silakan unggah ulang file.'
                    );
            }

            // 3. Menolak semua data jika terdapat baris tidak valid.
            foreach ($rows as $row) {
                if (
                    !is_array($row) ||
                    !empty($row['errors']) ||
                    ($row['jenis_transaksi'] ?? null) !== 'pemasukan'
                ) {
                    return redirect()
                        ->route('pemasukan.index')
                        ->with(
                            'error',
                            'Preview tidak valid atau bukan impor pemasukan. Silakan unggah ulang file.'
                        );
                }
            }

            // 4. Menyimpan seluruh pemasukan dalam satu transaksi database.
            DB::transaction(function () use ($rows, $userId) {
                foreach ($rows as $row) {
                    $categoryId = (int) ($row['id_kategori'] ?? 0);

                    // Pastikan kategori masih milik pengguna yang login.
                    $categoryExists = DB::table('kategori_pemasukans')
                        ->where('id', $categoryId)
                        ->where('id_users', $userId)
                        ->exists();

                    if ($categoryId <= 0 || !$categoryExists) {
                        throw new \RuntimeException(
                            'Kategori pemasukan tidak tersedia atau bukan milik pengguna ini.'
                        );
                    }

                    // Validasi ulang data penting saat konfirmasi.
                    $tanggal = $this->normalizeImportDate(
                        $row['tanggal'] ?? null
                    );

                    $jumlah = $this->normalizeImportAmount(
                        $row['jumlah'] ?? null
                    );

                    $label = trim((string) ($row['label'] ?? ''));

                    if (
                        $tanggal === null ||
                        $jumlah === null ||
                        mb_strlen($label) < 4
                    ) {
                        throw new \RuntimeException(
                            'Terdapat data pemasukan yang tidak valid.'
                        );
                    }

                    $timestamp = now()->toDateTimeString();

                    // Seluruh data masuk ke tabel pemasukan.
                    DB::table('pemasukans')->insert([
                        'id_users' => $userId,
                        'id_kategori' => $categoryId,

                        'label' => $label,
                        'jumlah_pemasukan' => $jumlah,

                        'sumber_pemasukan' =>
                        trim((string) ($row['sumber_pemasukan'] ?? '')) ?: null,

                        'tanggal_pemasukan' => Carbon::createFromFormat(
                            '!Y-m-d',
                            $tanggal
                        )->startOfDay()->toDateTimeString(),

                        'deskripsi_pemasukan' =>
                        trim((string) ($row['deskripsi'] ?? '')) ?: null,

                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]);
                }
            });

            // 5. Hapus preview setelah semua data berhasil disimpan.
            $incomeCount = count($rows);

            $this->clearImportPreview();

            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'success',
                    "{$incomeCount} transaksi pemasukan berhasil diimpor."
                );
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan impor Excel pemasukan KiraAI.', [
                'user_id' => $userId,
                'exception' => $e->getMessage(),
            ]);

            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'error',
                    'Impor pemasukan gagal disimpan. Tidak ada transaksi yang disimpan sebagian. Periksa data dan kategori, lalu coba kembali.'
                );
        }
    }


    // HANLDE UNDUH TEMPLATE


    public function downloadTemplate()
    {
        $userId = (int) Auth::id();

        $categories = DB::table('kategori_pemasukans')
            ->where('id_users', $userId)
            ->orderBy('nama_kategori')
            ->pluck('nama_kategori')
            ->map(fn($nama) => trim((string) $nama))
            ->filter(fn($nama) => $nama !== '')
            ->unique()
            ->values()
            ->all();

        if (empty($categories)) {
            return redirect()
                ->route('pemasukan.index')
                ->with(
                    'error',
                    'Kamu belum memiliki kategori pemasukan. Buat kategori pemasukan terlebih dahulu sebelum mengunduh template.'
                );
        }

        return Excel::download(
            new TransaksiTemplateExport($categories),
            'template_import_pemasukan.xlsx'
        );
    }


    private function normalizeImportText($value): string
    {
        return Str::lower(trim((string) $value));
    }

    private function normalizeImportDate($value): ?string
    {
        try {
            if ($value instanceof DateTimeInterface) {
                return Carbon::parse($value)->format('Y-m-d');
            }

            // Excel dapat menyimpan tanggal sebagai angka serial.
            if (is_numeric($value) && (float) $value >= 20000 && (float) $value <= 80000) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject((float) $value)
                )->format('Y-m-d');
            }

            $value = trim((string) $value);

            if ($value === '') {
                return null;
            }

            if (preg_match('/^\d{8}$/', $value)) {
                $date = Carbon::createFromFormat('!Ymd', $value);

                if ($date && $date->format('Ymd') === $value) {
                    return $date->format('Y-m-d');
                }
            }

            foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y'] as $format) {
                $date = Carbon::createFromFormat('!' . $format, $value);

                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeImportAmount($value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        if (is_numeric($value)) {
            $amount = (float) $value;
        } else {
            $text = trim((string) $value);

            $text = str_ireplace(['Rp', 'IDR'], '', $text);
            $text = preg_replace('/[\s\x{00A0}]+/u', '', $text);

            if (str_contains($text, ',') && str_contains($text, '.')) {
                // Contoh format Indonesia: 1.234,50
                if (strrpos($text, ',') > strrpos($text, '.')) {
                    $text = str_replace('.', '', $text);
                    $text = str_replace(',', '.', $text);
                } else {
                    // Contoh format internasional: 1,234.50
                    $text = str_replace(',', '', $text);
                }
            } elseif (str_contains($text, ',')) {
                if (preg_match('/,\d{3}$/', $text)) {
                    $text = str_replace(',', '', $text);
                } else {
                    $text = str_replace(',', '.', $text);
                }
            } elseif (
                substr_count($text, '.') > 1 ||
                preg_match('/\.\d{3}$/', $text)
            ) {
                $text = str_replace('.', '', $text);
            }

            if (!is_numeric($text)) {
                return null;
            }

            $amount = (float) $text;
        }

        if (!is_finite($amount) || $amount < 1000 || $amount > PHP_INT_MAX) {
            return null;
        }

        return (int) round($amount);
    }

    private function importPreviewPath(int $userId, string $token): string
    {
        return "import-previews/transaksi/{$userId}/{$token}.json";
    }

    /**
     * Membatalkan preview import pemasukan.
     *
     * Data preview sementara dihapus dan tidak disimpan
     * ke tabel pemasukans.
     */
    public function cancelImportPreview()
    {
        // Menghapus file preview sementara dan token session.
        $this->clearImportPreview();

        return redirect()
            ->route('pemasukan.index')
            ->with(
                'success',
                'Preview import berhasil dibatalkan. Tidak ada data baru yang disimpan.'
            );
    }

    private function clearImportPreview(): void
    {
        $token = session('transaksi_import_preview_token');

        if (is_string($token) && Str::isUuid($token)) {
            Storage::disk('local')->delete(
                $this->importPreviewPath((int) Auth::id(), $token)
            );
        }

        session()->forget('transaksi_import_preview_token');
    }
}
