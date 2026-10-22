<?php

namespace App\Livewire\AI;

use App\Services\AI\FinanceContextService;
use Livewire\Component;
use App\Services\AI\GeminiService;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ChatbotWidget extends Component
{
    // Handle Open Media Chat
    public $OpenFalse = false;

    // Menyimpan riwayat percakapan
    public $message = [];

    // Menyimpan teks yang sedang diketik
    public $inputMessage = '';

    protected GeminiService $geminiService;

    public function boot(GeminiService $geminiService): void
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Mengambil riwayat percakapan ketika komponen pertama kali dibuat.
     */
    public function mount(): void
    {
        $this->message = session(
            $this->historySessionKey(),
            []
        );
    }

    public function render()
    {
        return view('livewire.a-i.chatbot-widget');
    }

    /**
     * Membuat identitas penyimpanan riwayat per pengguna.
     */
    private function historySessionKey(): string
    {
        return 'uangku_chat_history_'
            . (Auth::user()->id ?? 'guest');
    }

    // Membuka atau menutup panel chatbot.
    public function toggleChat(): void
    {
        $this->OpenFalse = !$this->OpenFalse;
    }

    // Menutup panel chatbot.
    public function closeChat(): void
    {
        $this->OpenFalse = false;
    }

    /**
     * Memproses pesan pengguna dan meminta jawaban Gemini.
     */
    public function sendMessage(
        FinanceContextService $financeContextService
    ): void {
        // 1. Validasi input pengguna.
        $this->validate([
            'inputMessage' => 'required|string|max:1000',
        ]);

        $question = trim($this->inputMessage);

        if ($question === '') {
            return;
        }

        // 2. Ambil riwayat dari session server.
        $sessionKey = $this->historySessionKey();

        $history = session($sessionKey, []);

        // Pastikan riwayat memiliki struktur yang valid.
        if (!is_array($history)) {
            $history = [];
        }

        $history = array_values(array_filter(
            $history,
            fn($item) =>
            is_array($item)
                && in_array(
                    $item['role'] ?? null,
                    ['user', 'assistant'],
                    true
                )
                && is_string($item['content'] ?? null)
        ));

        // Kirim maksimal 10 pesan sebelumnya ke Gemini.
        $history = array_slice($history, -10);

        // 3. Tampilkan pesan pengguna pada antarmuka.
        $this->message = $history;

        $this->message[] = [
            'role' => 'user',
            'content' => $question,
        ];

        // Kosongkan input agar siap digunakan kembali.
        $this->inputMessage = '';

        try {
            // Ambil ringkasan keuangan pengguna yang sedang login.
            $financeContext = $financeContextService
                ->getSummaryForCurrentUser();

            // 5. Minta Gemini menghasilkan jawaban melalui Prism.
            $answer = $this->geminiService->reply(
                $question,
                $history,
                $financeContext
            );

            // 6. Tambahkan balasan AI ke percakapan.
            $this->message[] = [
                'role' => 'assistant',
                'content' => $answer,
            ];
        } catch (Throwable $exception) {
            // Simpan rincian error di log server.
            report($exception);

            // Pesan aman untuk ditampilkan kepada pengguna.
            $this->message[] = [
                'role' => 'assistant',
                'content' => 'Maaf, terjadi kendala saat '
                    . 'menghubungi AI. Silakan coba kembali.'.$exception->getMessage(),
            ];
        }

        // 7. Simpan maksimal 20 pesan terakhir di session.
        session()->put(
            $sessionKey,
            array_slice($this->message, -20)
        );
    }
}
