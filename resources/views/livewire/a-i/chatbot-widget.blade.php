<div class="kira-chatbot">

    {{-- TOMBOL FLOATING CHATBOT --}}
    <section class="chatbot-launcher-wrap position-fixed bottom-0 end-0 m-3 m-md-4">
        <button
            type="button"
            wire:click="toggleChat"
            class="chatbot-launcher btn d-flex align-items-center gap-3 rounded-pill border-0"
            aria-label="Buka asisten KiraAI"
            aria-controls="kira-chatbot-panel"
            aria-expanded="{{ $OpenFalse ? 'true' : 'false' }}"
        >
            <span class="chatbot-launcher-icon">
                <i class="fa-solid fa-robot"></i>
            </span>

            <span class="d-flex flex-column text-start">
                <span class="chatbot-launcher-title">Tanya KiraAI</span>
                <span class="chatbot-launcher-subtitle">Asisten keuanganmu</span>
            </span>

            <span class="chatbot-launcher-sparkle">
                <i class="fa-solid fa-sparkles"></i>
            </span>
        </button>
    </section>

    {{-- PANEL CHAT --}}
    <section
        id="kira-chatbot-panel"
        class="chatbot-panel {{ $OpenFalse ? 'is-open' : '' }}"
        aria-hidden="{{ $OpenFalse ? 'false' : 'true' }}"
        aria-label="Chat dengan asisten KiraAI"
        role="dialog"
    >

        {{-- HEADER --}}
        <header class="chatbot-header d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-3">
                <div class="chatbot-avatar">
                    <i class="fa-solid fa-robot"></i>
                    <span class="chatbot-avatar-status"></span>
                </div>

                <div class="chatbot-header-info">
                    <h6 class="mb-1">KiraAI Assistant</h6>

                    <div class="chatbot-status">
                        <span class="chatbot-status-dot"></span>
                        <span>Asisten Keuangan</span>
                    </div>
                </div>
            </div>

            <button
                type="button"
                wire:click="closeChat"
                class="chatbot-close-btn"
                aria-label="Tutup chat"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </header>

        {{-- AREA PESAN --}}
        <div
            class="chatbot-messages"
            aria-live="polite"
            aria-relevant="additions text"
        >

            {{-- PESAN PEMBUKA --}}
            @if (empty($message))
                <div class="chatbot-welcome">
                    <div class="chatbot-welcome-icon">
                        <i class="fa-solid fa-hand-sparkles"></i>
                    </div>

                    <h6>Halo, selamat datang! 👋</h6>

                    <p>
                        Saya <strong>KiraAI</strong>, asisten keuangan yang siap
                        membantu kamu memahami pemasukan, pengeluaran, dan kondisi
                        keuangan berdasarkan data yang tersedia.
                    </p>

                    <div class="chatbot-welcome-hint">
                        <i class="fa-solid fa-lightbulb me-2"></i>
                        Tanyakan sesuatu tentang keuanganmu.
                    </div>
                </div>
            @endif

            {{-- RIWAYAT PESAN --}}
            @foreach ($message as $item)
                <div
                    wire:key="chat-message-{{ $loop->index }}"
                    class="chatbot-message-row {{ $item['role'] === 'user' ? 'is-user' : 'is-assistant' }}"
                >

                    {{-- AVATAR AI --}}
                    @if ($item['role'] === 'assistant')
                        <div class="chatbot-bot-avatar">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                    @endif

                    {{-- PESAN AI --}}
                    @if ($item['role'] === 'assistant')
                        @php
                            $converter = new \League\CommonMark\GithubFlavoredMarkdownConverter([
                                'html_input' => 'strip',
                                'allow_unsafe_links' => false,
                            ]);

                            $renderedHtml = $converter
                                ->convert($item['content'])
                                ->getContent();

                            $safeHtml = \Mews\Purifier\Facades\Purifier::clean($renderedHtml);
                        @endphp

                        <div class="chatbot-bubble chatbot-bot chatbot-rich-content">
                            {!! $safeHtml !!}
                        </div>

                    {{-- PESAN PENGGUNA --}}
                    @else
                        <div class="chatbot-bubble chatbot-user">
                            {{ $item['content'] }}
                        </div>
                    @endif

                </div>
            @endforeach

            {{-- INDIKATOR SAAT AI MEMPROSES JAWABAN --}}
            <div
                wire:loading
                wire:target="sendMessage"
                class="chatbot-message-row is-assistant chatbot-loading-row"
            >
                <div class="chatbot-bot-avatar">
                    <i class="fa-solid fa-robot"></i>
                </div>

                <div class="chatbot-bubble chatbot-bot chatbot-typing">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

        </div>

        {{-- AREA INPUT --}}
        <footer class="chatbot-footer">

            <form wire:submit="sendMessage" class="chatbot-form">
                <div class="chatbot-input-wrapper">

                    <input
                        type="text"
                        wire:model="inputMessage"
                        class="chatbot-input"
                        placeholder="Tanyakan sesuatu..."
                        maxlength="1000"
                        autocomplete="off"
                        aria-label="Tulis pesan untuk KiraAI"
                        required
                    >

                    <button
                        type="submit"
                        class="chatbot-send"
                        aria-label="Kirim pesan"
                        wire:loading.attr="disabled"
                        wire:target="sendMessage"
                    >
                        <i
                            class="fa-solid fa-paper-plane"
                            wire:loading.remove
                            wire:target="sendMessage"
                        ></i>

                        <span
                            class="spinner-border spinner-border-sm"
                            wire:loading
                            wire:target="sendMessage"
                            aria-hidden="true"
                        ></span>
                    </button>

                </div>

                @error('inputMessage')
                    <small class="chatbot-error">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        {{ $message }}
                    </small>
                @enderror
            </form>

            <p class="chatbot-footer-note">
                <i class="fa-solid fa-shield-halved me-1"></i>
                Asisten keuangan KiraAI
            </p>

        </footer>

    </section>

</div>