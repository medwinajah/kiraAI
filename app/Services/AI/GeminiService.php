<?php

namespace App\Services\AI;

use Prism\Prism\Facades\Prism;
use Prism\Prism\Enums\Provider;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use RuntimeException;

class GeminiService
{
    /**
     * Service aturan AI diberikan otomatis oleh Laravel.
     */
    public function __construct(
        protected ChatbotPromptService $promptService
    ) {}

    /**
     * Mengirim pertanyaan dan riwayat percakapan ke Gemini.
     */
    public function reply(
        string $question,
        array $history = [],
        array $financeContext = []
    ): string {
        // Ambil nama model dari config/services.php.
        // MODEL GEMINI
        // $model = config(
        //     'services.ai.gemini_model',
        //     'gemini-3.8-flash'
        // );
        // ======================
        // MODEL GROK
        $model = config('services.ai.groq_model', 'llama-3.3-70b-versatile');
        // ======================

        // Siapkan instruksi AI beserta konteks keuangan.
        $systemPrompt = $this->promptService
            ->systemInstruction($financeContext);

        // Siapkan riwayat percakapan dengan peran yang sesuai.
        $messages = [];

        foreach (array_slice($history, -10) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $role = $item['role'] ?? null;
            $content = $item['content'] ?? null;

            if (
                !is_string($content) ||
                trim($content) === ''
            ) {
                continue;
            }

            if ($role === 'user') {
                $messages[] = new UserMessage($content);
            } elseif ($role === 'assistant') {
                $messages[] = new AssistantMessage($content);
            }
        }

        // Tambahkan pertanyaan pengguna yang terbaru.
        $messages[] = new UserMessage($question);

        // Panggil Gemini menggunakan Prism PHP.
        $response = Prism::text()
            ->using(Provider::Groq, $model)
            ->withSystemPrompt($systemPrompt)
            ->withMessages($messages)

            // ->withProviderOptions([
            //     'thinking' => false,
            // ])
            ->withClientOptions([
                 'connect_timeout' => 10,
                'timeout' => 120,
            ])
            ->asText();

        // Ambil dan bersihkan teks jawaban dari Gemini.
        $answer = trim($response->text);

        if ($answer === '') {
            throw new RuntimeException(
                'ChatBot-AI tidak menghasilkan jawaban teks.'
            );
        }

        return $answer;
    }
}
