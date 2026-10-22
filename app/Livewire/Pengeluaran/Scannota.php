<?php

namespace App\Livewire\Pengeluaran;

use App\Models\kategoriPengeluran;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Prism\Prism\Enums\FinishReason;
use Prism\Prism\Exceptions\PrismRateLimitedException;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\ValueObjects\Media\Image;
use RealRashid\SweetAlert\Facades\Alert;
use Throwable;

class Scannota extends Component
{
    use WithFileUploads;

    public $fotoNota = null;

    public array $items = [];

    public ?string $merchant = null;

    public ?string $receiptDate = null;

    public ?string $errorMessage = null;

    public function scanNota(): void
    {
        $this->errorMessage = null;
        $this->items = [];
        $this->merchant = null;
        $this->receiptDate = null;

        $this->validate([
            'fotoNota' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        try {
            // 1. Instruksi untuk membaca nota.
            $prompt = <<<'PROMPT'
Baca nota belanja pada gambar dengan teliti.

Aturan:
1. Setiap baris barang menjadi satu item tersendiri.
2. amount adalah total harga baris barang, bukan total seluruh nota.
3. Jangan masukkan subtotal, total pembayaran, pajak umum,
   uang tunai, atau kembalian sebagai barang.
4. Jangan mengarang nama barang, harga, maupun tanggal.
5. Gunakan null jika harga atau tanggal tidak terbaca.
6. Tanggal harus berformat YYYY-MM-DD.
7. Harga harus berupa angka dalam rupiah tanpa pemisah ribuan.
8. Jika tidak ada barang yang dikenali, gunakan items kosong.
PROMPT;

            // 2. Siapkan gambar sementara dari Livewire.
            $image = Image::fromLocalPath(
                path: $this->fotoNota->getRealPath()
            );

            // 3. Definisikan struktur data yang diharapkan dari AI.
            $schema = new ObjectSchema(
                name: 'receipt_scan_result',
                description: 'Hasil pembacaan nota belanja.',
                properties: [
                    new StringSchema(
                        'merchant',
                        'Nama toko pada nota, atau null jika tidak terbaca.',
                        nullable: true
                    ),

                    new StringSchema(
                        'receipt_date',
                        'Tanggal nota YYYY-MM-DD, atau null jika tidak terbaca.',
                        nullable: true
                    ),

                    new ArraySchema(
                        name: 'items',
                        description: 'Setiap baris barang pada nota.',
                        items: new ObjectSchema(
                            name: 'receipt_item',
                            description: 'Informasi satu barang.',
                            properties: [
                                new StringSchema(
                                    'label',
                                    'Nama barang sesuai tulisan pada nota.'
                                ),

                                new NumberSchema(
                                    'amount',
                                    'Harga total baris barang dalam rupiah, atau null jika tidak terbaca.',
                                    nullable: true
                                ),
                            ],
                            requiredFields: [
                                'label',
                                'amount',
                            ]
                        )
                    ),
                ],
                requiredFields: [
                    'merchant',
                    'receipt_date',
                    'items',
                ]
            );

            // 4. Kirim gambar ke Groq melalui Prism.
            $response = Prism::structured()
                ->using(
                    'groq',
                    config(
                        'services.groq.vision_model',
                        'qwen/qwen3.8-27b'
                    )
                )
                ->withSchema($schema)
                ->withSystemPrompt(
                    'Kamu adalah AI pembaca nota belanja. ' .
                        'Baca setiap baris barang secara akurat dan jangan menebak.'
                )
                ->withPrompt($prompt, [$image])
                ->withMaxTokens(800)
                ->usingTemperature(0.1)
                ->withClientOptions([
                    'timeout' => 120,
                ])
                ->asStructured();

            // 5. Jangan menggunakan keluaran yang terpotong.
            if ($response->finishReason === FinishReason::Length) {
                Log::warning('Hasil scan nota terpotong.', [
                    'completion_tokens' =>
                    $response->usage->completionTokens,
                ]);

                $this->errorMessage =
                    'Hasil pembacaan terlalu panjang. ' .
                    'Coba scan nota dalam beberapa bagian.';

                return;
            }

            // 6. Ambil data terstruktur langsung dari Prism.
            // Tidak perlu json_decode() lagi.
            $data = $response->structured;

            if (
                !is_array($data) ||
                !isset($data['items']) ||
                !is_array($data['items'])
            ) {
                Log::warning('Struktur hasil scan tidak valid.', [
                    'finish_reason' => $response->finishReason->name,
                ]);

                $this->errorMessage =
                    'AI belum menghasilkan data nota yang valid. ' .
                    'Silakan coba scan ulang.';

                return;
            }

            // 7. Normalisasi nama toko dan tanggal.
            $this->merchant = is_string($data['merchant'] ?? null)
                ? trim($data['merchant'])
                : null;

            $this->receiptDate = $this->normalizeDate(
                $data['receipt_date'] ?? null
            );

            // 8. Siapkan setiap barang untuk ditampilkan di form.
            foreach ($data['items'] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $label = is_string($item['label'] ?? null)
                    ? trim($item['label'])
                    : '';

                $rawAmount = $item['amount'] ?? null;
                $amount = null;

                // Harga boleh integer, float bulat, atau string angka.
                if (
                    (is_int($rawAmount) || is_float($rawAmount)) &&
                    is_finite((float) $rawAmount) &&
                    $rawAmount >= 0 &&
                    floor((float) $rawAmount) === (float) $rawAmount
                ) {
                    $amount = (int) $rawAmount;
                } elseif (
                    is_string($rawAmount) &&
                    preg_match('/^\d+$/', $rawAmount)
                ) {
                    $amount = (int) $rawAmount;
                }

                // Lewati item yang sama sekali tidak punya nama.
                if ($label === '') {
                    continue;
                }

                $this->items[] = [
                    'label' => $label,
                    'amount' => $amount,
                    'kategori' => '',
                    'tanggal_pengeluaran' => $this->receiptDate ?? '',
                    'deskripsi_pengeluaran' => '',
                    'certainty' => $amount === null
                        ? 'uncertain'
                        : 'clear',
                ];
            }

            if (count($this->items) === 0) {
                $this->errorMessage =
                    'Tidak ada barang yang berhasil dikenali. ' .
                    'Pastikan gambar nota jelas dan tidak terpotong.';
            }
        } catch (PrismRateLimitedException $e) {
            $limits = [];

            foreach ($e->rateLimits as $limit) {
                $limits[] = [
                    'name' => $limit->name,
                    'limit' => $limit->limit,
                    'remaining' => $limit->remaining,
                    'resets_at' =>
                    $limit->resetsAt?->toIso8601String(),
                ];
            }

            Log::warning('Groq membatasi permintaan scan nota.', [
                'limits' => $limits,
                'message' => $e->getMessage(),
            ]);

            $this->errorMessage =
                'Permintaan AI sedang dibatasi oleh Groq. ' .
                'Periksa batas token dan coba lagi setelah memungkinkan.';
        } catch (Throwable $e) {
            Log::error('Gagal membaca nota menggunakan Prism.', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            $this->errorMessage =
                'Gagal membaca nota. Periksa log Laravel untuk mengetahui penyebabnya.';
        }
    }

    private function normalizeDate(mixed $date): ?string
    {
        if (
            !is_string($date) ||
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
        ) {
            return null;
        }

        [$year, $month, $day] = array_map(
            'intval',
            explode('-', $date)
        );

        return checkdate($month, $day, $year)
            ? $date
            : null;
    }

    public function removeItem(int $index): void
    {
        if (!array_key_exists($index, $this->items)) {
            return;
        }

        unset($this->items[$index]);

        $this->items = array_values($this->items);
    }

    public function resetScan(): void
    {
        $this->reset([
            'fotoNota',
            'items',
            'merchant',
            'receiptDate',
            'errorMessage',
        ]);
    }

    public function render()
    {
        $kategori_pengeluaran = kategoriPengeluran::where('id_users', Auth::user()->id)->get();
        return view('livewire.pengeluaran.scannota', [
            'kategori_pengeluaran' => $kategori_pengeluaran,
        ]);
    }

    public function simpanSemua()
    {
        if (!Auth::check()) {
            $this->errorMessage = 'Silakan login kembali.';
            return;
        }

        // 1. Validasi semua barang dan kategori milik pengguna.
        $tableKategori = (new kategoriPengeluran())->getTable();

        $validated = $this->validate([
            'items' => ['required', 'array', 'min:1'],

            'items.*.label' => [
                'required',
                'string',
                'min:4',
                'max:255',
            ],

            'items.*.amount' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.kategori' => [
                'required',
                Rule::exists($tableKategori, 'id')
                    ->where('id_users', Auth::id()),
            ],

            'items.*.tanggal_pengeluaran' => [
                'required',
                'date',
            ],

            'items.*.deskripsi_pengeluaran' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {
            // 2. Simpan semua barang sebagai transaksi terpisah.
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $item) {
                    Pengeluaran::create([
                        // Gunakan user yang sedang login.
                        'id_users' => Auth::id(),

                        'id_kategori' => (int) $item['kategori'],
                        'label' => trim($item['label']),
                        'jumlah_pengeluaran' => (int) $item['amount'],
                        'tanggal_pengeluaran' => $item['tanggal_pengeluaran'],

                        'deskripsi_pengeluaran' =>
                        trim($item['deskripsi_pengeluaran'] ?? '') !== ''
                            ? trim($item['deskripsi_pengeluaran'])
                            : null,
                    ]);
                }
            });

            // 3. Beri notifikasi lalu kembali ke daftar pengeluaran.
            $jumlah = count($validated['items']);

            session()->flash(
                'success',
                "{$jumlah} transaksi pengeluaran berhasil disimpan."
            );

           

            $this->resetScan();
             Alert::success('Sukses', "{$jumlah} transaksi berhasil disimpan.")->toToast('top-right');
            return $this->redirectRoute(
                'pengeluaran.index',
                navigate: true
            );
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan hasil scan nota.', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
            ]);

            $this->errorMessage =
                'Transaksi gagal disimpan. Data belum berhasil disimpan. ' .
                'Periksa log Laravel untuk mengetahui penyebabnya.';

            return;
        }
    }
}
