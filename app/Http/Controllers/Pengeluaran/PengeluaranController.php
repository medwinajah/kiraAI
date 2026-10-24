<?php

namespace App\Http\Controllers\Pengeluaran;

use App\Http\Controllers\Controller;
use App\Models\kategoriPengeluran;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

// LIBRARY HANDLE IMPORT FILE EXCEL
use App\Exports\PengeluaranTemplateExport;
use App\Imports\PengeluaranPreviewImport;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PengeluaranController extends Controller
{
    // Render Data
    public function index()
    {
        $importPreview = [];

        $token = session('pengeluaran_import_preview_token');

        if ($token && Auth::check()) {
            $path = $this->pengeluaranImportPreviewPath(
                (int) Auth::id(),
                (string) $token
            );

            if (Storage::disk('local')->exists($path)) {
                $payload = json_decode(
                    Storage::disk('local')->get($path),
                    true
                );

                $importPreview = $payload['rows'] ?? [];
            } else {
                session()->forget('pengeluaran_import_preview_token');
            }
        }

        $importHasErrors = collect($importPreview)->contains(
            fn($row) => !empty($row['errors'])
        );

        return view('pages.Pengeluaran.index', compact(
            'importPreview',
            'importHasErrors'
        ));
    }

    // Render halaman tambah
    public function create()
    {
        $kategori_pengeluaran = $this->dataKategoriPengeluaran();

        return view(
            'pages.Pengeluaran.tambah',
            [
                'kategori_pengeluaran' => $kategori_pengeluaran,
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
                'jumlah_pengeluaran' => 'required|numeric|min:1000',
                'tanggal_pengeluaran' => 'required|date',
                'deskripsi_pengeluaran' => 'nullable|string'
            ],
            [
                'kategori.required' => 'Kategori wajib diisi.',

                'label.required' => 'Label wajib diisi.',
                'label.min' => 'Label minimal harus terdiri dari 4 karakter.',
                'label.string' => 'Label harus berupa teks.',

                'jumlah_pengeluaran.required' => 'Jumlah pengeluaran wajib diisi.',
                'jumlah_pengeluaran.numeric' => 'Jumlah pengeluaran harus berupa angka.',
                'jumlah_pengeluaran.min' => 'Jumlah pengeluaran minimal adalah 1.000.',

                'tanggal_pengeluaran.required' => 'Tanggal pengeluaran wajib diisi.',
                'tanggal_pengeluaran.date' => 'Format tanggal pengeluaran tidak valid.',

                'deskripsi_pengeluaran.string' => 'Deskripsi pengeluaran harus berupa teks.',
            ]

        );

        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        try {
            Pengeluaran::create(
                [
                    'id_users' => Auth::user()->id,
                    'label' => $validasi['label'],
                    'jumlah_pengeluaran' => $validasi['jumlah_pengeluaran'],
                    'tanggal_pengeluaran' => $validasi['tanggal_pengeluaran'] . ' ' . $jam_terkini,
                    'deskripsi_pengeluaran' => $validasi['deskripsi_pengeluaran'],
                    'id_kategori' => $validasi['kategori'],
                ],
            );

            //    Sukses
            Alert::success('Success', 'Data pengeluaran berhasil disimpan.')->toToast('top-right');
            return back();
        } catch (\Exception $er) {
            Alert::error('Error', "Mohon maaf data gagal ditambahkan!");
            Logger("Error Tambah Data pengeluaran: " + $er->getMessage());
        }
    }

    // Handle Load Jenis Kategori
    public function dataKategoriPengeluaran()
    {
        return kategoriPengeluran::all();
    }

    // Handle Edit
    public function edit($id)
    {
        $data = Pengeluaran::findOrFail($id);
        $kategori = kategoriPengeluran::all();
        return view('pages.Pengeluaran.edit', [
            'pengeluaran' => $data,
            'kategori_pengeluaran' => $kategori,
        ]);
    }

    // Handle Update
    public function update(Request $request, $id)
    {
        $data = Pengeluaran::findOrFail($id);
        $users = Auth::user()->id;
        $validasi = $request->validate(
            [
                'kategori' => 'required',
                'label' => "required|min:4|string",
                'jumlah_pengeluaran' => 'required|numeric|min:1000',
                'tanggal_pengeluaran' => 'required|date',
                'deskripsi_pengeluaran' => 'nullable|string'
            ]
        );
        // 
        $jam_terkini = Carbon::now()->format('H:i:s');
        $tanggal_lama = $data->tanggal_pengeluaran->format('Y-m-d');
        if ($request->tanggal_pengeluaran == $tanggal_lama) {
            $validasi['tanggal_pengeluaran'] = $data->tanggal_pengeluaran;
        } else {
            $validasi['tanggal_pengeluaran'] =  $request->tanggal_pengeluaran . ' ' . $jam_terkini;
        }

        try {
            $data->update(
                [
                    'id_users' => $users->id,
                    'label' => $validasi['label'],
                    'jumlah_pengeluaran' => $validasi['jumlah_pengeluaran'],
                    'tanggal_pengeluaran' => $validasi['tanggal_pengeluaran'],
                    'deskripsi_pengeluaran' => $validasi['deskripsi_pengeluaran'],
                    'id_kategori' => $validasi['kategori'],
                ]
            );

            //    Sukses
            Alert::success('Success', 'Data pengeluaran berhasil disimpan.')->toToast('top-right');
            return redirect()->route('pengeluaran.index');
        } catch (\Exception $er) {

            logger("Error Tambah Data pengeluaran: " . $er->getMessage());
            // PERBAIKAN: Menambahkan return back agar halaman tidak putih kosong saat eror
            Alert::error('Error', "Mohon maaf data gagal diperbarui!");
            return back()->withInput();
        }
    }

    // HANLDE UNDUH TEMPLATE
    public function downloadImportTemplate()
    {
        $userId = (int) Auth::id();

        $categories = kategoriPengeluran::query()
            ->where('id_users', $userId)
            ->pluck('nama_ketegori')
            ->map(fn($name) => trim((string) $name))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($categories)) {
            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'error',
                    'Tambahkan kategori pengeluaran terlebih dahulu sebelum mengunduh template.'
                );
        }

        return Excel::download(
            new PengeluaranTemplateExport($categories),
            'template_import_pengeluaran.xlsx'
        );
    }


    // HANLDE PRIVEW IMPORT

   
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
         * 1. BACA WORKSHEET PENGELUARAN
         */
        $import = new PengeluaranPreviewImport();

        Excel::import(
            $import,
            $request->file('excel_file')
        );

        $allRows = $import->rows;

        if ($allRows->isEmpty()) {
            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'error',
                    'Worksheet Pengeluaran kosong atau tidak ditemukan. Unduh template terbaru dari aplikasi.'
                );
        }

        /*
         * 2. NORMALISASI NAMA HEADER
         */
        $normalizeHeader = function ($value): string {
            $value = (string) $value;

            // Hilangkan BOM pada CSV.
            $value = preg_replace('/^\xEF\xBB\xBF/', '', $value) ?? $value;

            // Ganti non-breaking space dengan spasi biasa.
            $value = str_replace("\xC2\xA0", ' ', $value);

            $value = mb_strtolower(trim($value));

            // Spasi, tanda hubung, dan underscore diseragamkan.
            $value = preg_replace('/[\s_-]+/u', '_', $value) ?? $value;

            // Hilangkan karakter yang tidak diperlukan.
            $value = preg_replace('/[^a-z0-9_]/u', '', $value) ?? $value;

            $value = preg_replace('/_+/', '_', $value) ?? $value;

            return trim($value, '_');
        };

        /*
         * 3. NAMA HEADER YANG DIDUKUNG
         *
         * Beberapa variasi nama dapat menunjuk kolom yang sama.
         */
        $headerAliases = [
            'tanggal' => [
                'tanggal',
                'tanggal_pengeluaran',
                'tanggal_transaksi',
                'date',
            ],
            'label' => [
                'label',
                'nama_transaksi',
                'transaksi',
                'nama_pengeluaran',
            ],
            'jumlah' => [
                'jumlah',
                'jumlah_pengeluaran',
                'nominal',
                'nominal_pengeluaran',
                'nilai_pengeluaran',
            ],
            'kategori' => [
                'kategori',
                'kategori_pengeluaran',
                'nama_kategori',
            ],
            'deskripsi' => [
                'deskripsi',
                'deskripsi_pengeluaran',
                'keterangan',
                'catatan',
            ],
        ];

        /*
         * 4. BACA HEADER DAN PETAKAN POSISI KOLOM
         */
        $headerRow = $allRows->first();

        $headerValues = $headerRow instanceof \Illuminate\Support\Collection
            ? $headerRow->values()->all()
            : array_values((array) $headerRow);

        $headerMap = [];
        $duplicateHeaders = [];

        foreach ($headerValues as $columnIndex => $headerValue) {
            $normalized = $normalizeHeader($headerValue);

            foreach ($headerAliases as $canonicalName => $aliases) {
                if (in_array($normalized, $aliases, true)) {
                    if (isset($headerMap[$canonicalName])) {
                        $duplicateHeaders[] = $canonicalName;
                    } else {
                        $headerMap[$canonicalName] = $columnIndex;
                    }

                    break;
                }
            }
        }

        $requiredHeaders = array_keys($headerAliases);

        $missingHeaders = array_values(
            array_diff($requiredHeaders, array_keys($headerMap))
        );

        if (!empty($missingHeaders) || !empty($duplicateHeaders)) {
            Log::warning('Header Excel pengeluaran tidak valid.', [
                'user_id' => $userId,
                'sheet' => 'Pengeluaran',
                'headers_dibaca' => $headerValues,
                'header_terpetakan' => $headerMap,
                'header_hilang' => $missingHeaders,
                'header_duplikat' => $duplicateHeaders,
            ]);

            $message = 'Header Excel tidak sesuai. ';

            if (!empty($missingHeaders)) {
                $message .= 'Kolom yang belum ditemukan: '
                    . implode(', ', $missingHeaders)
                    . '. ';
            }

            if (!empty($duplicateHeaders)) {
                $message .= 'Terdapat header ganda: '
                    . implode(', ', array_unique($duplicateHeaders))
                    . '. ';
            }

            $message .= 'Unduh template pengeluaran terbaru dan pastikan setiap kolom hanya muncul satu kali.';

            return redirect()
                ->route('pengeluaran.index')
                ->with('error', $message);
        }

        /*
         * 5. AMBIL KATEGORI MILIK PENGGUNA LOGIN
         */
        $categories = kategoriPengeluran::query()
            ->where('id_users', $userId)
            ->get(['id', 'nama_ketegori'])
            ->mapWithKeys(fn ($category) => [
                $this->normalizePengeluaranImportText(
                    $category->nama_ketegori
                ) => [
                    'id' => (int) $category->id,
                    'name' => $category->nama_ketegori,
                ],
            ])
            ->all();

        $knownIndexes = array_values($headerMap);
        $rows = [];

        /*
         * 6. BACA DAN VALIDASI TRANSAKSI
         */
        foreach ($allRows->slice(1)->values() as $index => $item) {
            $data = $item instanceof \Illuminate\Support\Collection
                ? $item->values()->all()
                : array_values((array) $item);

            // Abaikan baris yang seluruh nilainya kosong.
            $hasData = collect($data)->contains(function ($value) {
                if ($value === null) {
                    return false;
                }

                if ($value instanceof DateTimeInterface) {
                    return true;
                }

                return trim((string) $value) !== '';
            });

            if (!$hasData) {
                continue;
            }

            // Ambil nilai berdasarkan nama header yang sudah dipetakan.
            $readColumn = fn (string $name) =>
                $data[$headerMap[$name]] ?? null;

            $tanggalRaw = $readColumn('tanggal');
            $label = trim((string) ($readColumn('label') ?? ''));
            $jumlahRaw = $readColumn('jumlah');
            $kategoriNama = trim(
                (string) ($readColumn('kategori') ?? '')
            );
            $deskripsi = trim(
                (string) ($readColumn('deskripsi') ?? '')
            );

            $tanggal = $this->normalizePengeluaranImportDate(
                $tanggalRaw
            );

            $jumlah = $this->normalizePengeluaranImportAmount(
                $jumlahRaw
            );

            $errors = [];

            if (mb_strlen($label) < 4) {
                $errors[] = 'Label wajib diisi dan minimal 4 karakter.';
            }

            if ($tanggal === null) {
                $errors[] = 'Tanggal tidak valid. Gunakan format YYYY-MM-DD.';
            }

            if ($jumlah === null) {
                $errors[] = 'Jumlah pengeluaran harus berupa angka minimal Rp1.000.';
            }

            if ($kategoriNama === '') {
                $errors[] = 'Kategori pengeluaran wajib diisi.';
            }

            $matchedCategory = $kategoriNama !== ''
                ? (
                    $categories[
                        $this->normalizePengeluaranImportText($kategoriNama)
                    ] ?? null
                )
                : null;

            if ($kategoriNama !== '' && $matchedCategory === null) {
                $errors[] = 'Kategori tidak ditemukan pada akun ini.';
            }

            // Tolak data tambahan di luar kolom yang dikenali.
            foreach ($data as $columnIndex => $extraValue) {
                if (
                    !in_array($columnIndex, $knownIndexes, true) &&
                    $extraValue !== null &&
                    trim((string) $extraValue) !== ''
                ) {
                    $errors[] = 'Terdapat data tambahan di luar kolom template.';
                    break;
                }
            }

            $rows[] = [
                'row_number' => $index + 2,
                'tanggal' => $tanggal,
                'label' => $label,
                'jumlah' => $jumlah,
                'kategori' => $matchedCategory['name'] ?? $kategoriNama,
                'id_kategori' => $matchedCategory['id'] ?? null,
                'deskripsi' => $deskripsi,
                'errors' => $errors,
            ];
        }

        /*
         * 7. PASTIKAN ADA TRANSAKSI
         */
        if (empty($rows)) {
            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'error',
                    'Tidak ada transaksi yang terisi. Masukkan data mulai dari baris kedua template.'
                );
        }

        /*
         * 8. SIMPAN PREVIEW SEMENTARA
         */
        $token = (string) \Illuminate\Support\Str::uuid();

        $path = $this->pengeluaranImportPreviewPath(
            $userId,
            $token
        );

        Storage::disk('local')->put(
            $path,
            json_encode(
                ['rows' => $rows],
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            )
        );

        // Bersihkan preview lama setelah preview baru berhasil dibuat.
        $this->clearPengeluaranImportPreview();

        session([
            'pengeluaran_import_preview_token' => $token,
        ]);

        $validCount = collect($rows)
            ->filter(fn ($row) => empty($row['errors']))
            ->count();

        $errorCount = count($rows) - $validCount;

        return redirect()
            ->route('pengeluaran.index')
            ->with(
                'success',
                "File berhasil dibaca. {$validCount} baris valid dan {$errorCount} baris perlu diperiksa."
            );

    } catch (\Throwable $e) {
        Log::error('Gagal membaca Excel pengeluaran.', [
            'user_id' => $userId,
            'exception' => $e->getMessage(),
        ]);

        return redirect()
            ->route('pengeluaran.index')
            ->with(
                'error',
                'File Excel gagal dibaca. Periksa nama worksheet dan gunakan template pengeluaran terbaru.'
            );
    }
}


    // HANLDE CONFIRMASI DAN PEMBATALAN

    public function confirmImport()
    {
        $userId = (int) Auth::id();
        $token = session('pengeluaran_import_preview_token');

        if (!$token) {
            return redirect()
                ->route('pengeluaran.index')
                ->with('error', 'Tidak ada preview pengeluaran yang perlu disimpan.');
        }

        $path = $this->pengeluaranImportPreviewPath($userId, (string) $token);

        if (!Storage::disk('local')->exists($path)) {
            $this->clearPengeluaranImportPreview();

            return redirect()
                ->route('pengeluaran.index')
                ->with('error', 'Preview sudah tidak tersedia. Silakan unggah file kembali.');
        }

        $payload = json_decode(
            Storage::disk('local')->get($path),
            true
        );

        $rows = $payload['rows'] ?? [];

        if (empty($rows)) {
            return redirect()
                ->route('pengeluaran.index')
                ->with('error', 'Tidak ada data pengeluaran untuk disimpan.');
        }

        if (collect($rows)->contains(fn($row) => !empty($row['errors']))) {
            return redirect()
                ->route('pengeluaran.index')
                ->with('error', 'Masih ada baris tidak valid. Perbaiki Excel sebelum menyimpan.');
        }

        try {
            DB::transaction(function () use ($rows, $userId) {
                foreach ($rows as $row) {
                    $categoryId = (int) ($row['id_kategori'] ?? 0);

                    // Pastikan kategori benar-benar milik akun yang login.
                    $categoryExists = kategoriPengeluran::query()
                        ->where('id', $categoryId)
                        ->where('id_users', $userId)
                        ->exists();

                    $tanggal = $this->normalizePengeluaranImportDate(
                        $row['tanggal'] ?? null
                    );

                    $jumlah = $this->normalizePengeluaranImportAmount(
                        $row['jumlah'] ?? null
                    );

                    $label = trim((string) ($row['label'] ?? ''));

                    if (
                        !$categoryExists ||
                        $tanggal === null ||
                        $jumlah === null ||
                        mb_strlen($label) < 4
                    ) {
                        throw new \RuntimeException(
                            'Data preview tidak valid atau kategori sudah tidak tersedia.'
                        );
                    }

                    Pengeluaran::create([
                        'id_users' => $userId,
                        'id_kategori' => $categoryId,
                        'label' => $label,
                        'jumlah_pengeluaran' => $jumlah,
                        'tanggal_pengeluaran' => $tanggal,
                        'deskripsi_pengeluaran' => trim(
                            (string) ($row['deskripsi'] ?? '')
                        ) ?: null,
                    ]);
                }
            });

            $savedCount = count($rows);

            $this->clearPengeluaranImportPreview();

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'success',
                    "{$savedCount} transaksi pengeluaran berhasil diimpor."
                );
        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan import pengeluaran.', [
                'user_id' => $userId,
                'exception' => $e->getMessage(),
            ]);

            return redirect()
                ->route('pengeluaran.index')
                ->with(
                    'error',
                    'Pengeluaran gagal disimpan. Periksa kembali kategori dan data Excel.'
                );
        }
    }

    public function cancelImportPreview()
    {
        $this->clearPengeluaranImportPreview();

        return redirect()
            ->route('pengeluaran.index')
            ->with(
                'success',
                'Preview pengeluaran dibatalkan. Tidak ada transaksi baru yang disimpan.'
            );
    }

    // METHOD HELPER

    private function normalizePengeluaranImportText($value): string
    {
        return mb_strtolower(trim((string) $value));
    }

    private function normalizePengeluaranImportDate($value): ?string
    {
        try {
            if ($value instanceof DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            if (is_numeric($value) && $value >= 20000 && $value <= 80000) {
                return ExcelDate::excelToDateTimeObject((float) $value)
                    ->format('Y-m-d');
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

            foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d'] as $format) {
                try {
                    $date = Carbon::createFromFormat('!' . $format, $value);

                    if ($date && $date->format($format) === $value) {
                        return $date->format('Y-m-d');
                    }
                } catch (\Throwable $e) {
                    // Coba format berikutnya.
                }
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizePengeluaranImportAmount($value): ?float
    {
        // Angka asli Excel lebih aman dibaca secara langsung.
        if (is_int($value) || is_float($value)) {
            return $value >= 1000 ? (float) $value : null;
        }

        $value = trim((string) $value);
        $value = preg_replace('/Rp|\s/u', '', $value) ?? $value;

        if ($value === '') {
            return null;
        }

        // Contoh format ribuan Indonesia: 1.000 atau 1.250.000.
        if (preg_match('/^\d{1,3}(?:\.\d{3})+$/', $value)) {
            $value = str_replace('.', '', $value);
        } elseif (preg_match('/^\d{1,3}(?:,\d{3})+$/', $value)) {
            // Contoh format ribuan: 1,000.
            $value = str_replace(',', '', $value);
        } elseif (
            str_contains($value, '.') &&
            str_contains($value, ',')
        ) {
            // Contoh format Indonesia: 1.250,50.
            if (strrpos($value, ',') > strrpos($value, '.')) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                // Contoh format internasional: 1,250.50.
                $value = str_replace(',', '', $value);
            }
        } elseif (str_contains($value, ',')) {
            // Koma tunggal dengan tiga digit di belakang dianggap pemisah ribuan.
            if (preg_match('/^\d{1,3},\d{3}$/', $value)) {
                $value = str_replace(',', '', $value);
            } else {
                $value = str_replace(',', '.', $value);
            }
        }

        if (!is_numeric($value)) {
            return null;
        }

        $amount = (float) $value;

        return $amount >= 1000 ? $amount : null;
    }

    private function pengeluaranImportPreviewPath(
        int $userId,
        string $token
    ): string {
        return "import-previews/pengeluaran/{$userId}/{$token}.json";
    }

    private function clearPengeluaranImportPreview(): void
    {
        $token = session('pengeluaran_import_preview_token');

        if ($token && Auth::check()) {
            Storage::disk('local')->delete(
                $this->pengeluaranImportPreviewPath(
                    (int) Auth::id(),
                    (string) $token
                )
            );
        }

        session()->forget('pengeluaran_import_preview_token');
    }
}
