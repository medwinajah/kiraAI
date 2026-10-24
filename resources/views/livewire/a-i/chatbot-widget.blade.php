 <div>
     {{-- TOMBOL FLOATING CHATBOT --}}
     <section class="position-fixed bottom-0 end-0 m-4" style="z-index: 1050;">

         <button type="button" wire:click="toggleChat"
             class="chatbot-launcher btn d-flex align-items-center gap-2 rounded-pill px-4 py-3 shadow-lg text-white border-0">

             <i class="fa-solid fa-robot fs-5"></i>
             <span class="fw-semibold">Tanya AI</span>
             <i class="fa-solid fa-sparkles small"></i>

         </button>
     </section>

     {{-- PANEL CHAT --}}
     <section class="chatbot-panel {{ $OpenFalse ? 'is-open' : '' }}" aria-hidden="{{ $OpenFalse ? 'false' : 'true' }}">

         {{-- HEADER --}}
         <header class="chatbot-header d-flex align-items-center justify-content-between p-3">

             <div class="d-flex align-items-center gap-3">
                 <div class="chatbot-avatar">
                     <i class="fa-solid fa-robot fs-4"></i>
                 </div>

                 <div>
                     <h6 class="fw-bold text-white mb-1">Asisten KIRA AI</h6>
                     <small class="text-white-50">
                         <i class="fa-solid fa-circle text-success me-1" style="font-size: 8px;"></i>
                         Asisten Keuangan Pribadi
                     </small>
                 </div>
             </div>

             <button type="button" wire:click="closeChat" class="btn text-white" aria-label="Tutup chat">
                 <i class="fa-solid fa-xmark fs-5"></i>
             </button>

         </header>

         {{-- ISI CHAT --}}
         <div class="chatbot-messages">

             {{-- PESAN PEMBUKA --}}
             <div class="d-flex align-items-start gap-2 mb-3">
                 <div class="chatbot-bot-avatar">
                     <i class="fa-solid fa-robot"></i>
                 </div>

                 <div class="chatbot-bubble chatbot-bot">
                     <p class="fw-bold mb-1">Halo! 👋</p>
                     Selamat datang di UANGKU. Saya siap membantu kamu
                     memahami pemasukan, pengeluaran, dan tabunganmu.
                     Apa yang ingin kamu tanyakan?
                 </div>
             </div>

             {{-- RIWAYAT PESAN --}}
             @foreach ($message as $item)
                 <div wire:key="chat-message-{{ $loop->index }}"
                     class="d-flex mb-3
                    {{ $item['role'] === 'user' ? 'justify-content-end' : 'justify-content-start' }}">

                     @if ($item['role'] === 'assistant')
                         <div class="chatbot-bot-avatar me-1">
                             <i class="fa-solid fa-robot"></i>
                         </div>
                     @endif

                     {{-- <div class="chatbot-bubble
                        {{ $item['role'] === 'user' ? 'chatbot-user' : 'chatbot-bot' }}">
                        {{ $item['content'] }}
                    </div> --}}
                     @if ($item['role'] === 'assistant')
                         @php
                             $converter = new \League\CommonMark\GithubFlavoredMarkdownConverter([
                                 'html_input' => 'allow',
                                 'allow_unsafe_links' => false,
                             ]);

                             // Konversi Markdown dan tabel menjadi HTML.
                             $renderedHtml = $converter->convert($item['content'])->getContent();

                             // Bersihkan HTML sebelum dirender di browser.
                             $safeHtml = \Mews\Purifier\Facades\Purifier::clean($renderedHtml);
                         @endphp

                         <div class="chatbot-bubble chatbot-bot chatbot-rich-content">
                             {!! $safeHtml !!}
                         </div>
                     @else
                         {{-- Pesan pengguna tetap menggunakan escaping Blade --}}
                         <div class="chatbot-bubble chatbot-user">
                             {{ $item['content'] }}
                         </div>
                     @endif

                 </div>
             @endforeach

         </div>

         {{-- INPUT PESAN --}}
         <footer class="chatbot-footer p-3">

             <form wire:submit="sendMessage">
                 <div class="d-flex align-items-center gap-2">

                     <input type="text" wire:model="inputMessage" class="form-control chatbot-input"
                         placeholder="Tulis pesan di sini..." maxlength="1000" autocomplete="off"
                         aria-label="Tulis pesan">

                     <button type="submit" class="chatbot-send" aria-label="Kirim pesan">

                         <i class="fa-solid fa-paper-plane"></i>

                     </button>
                 </div>

                 @error('inputMessage')
                     <small class="text-danger d-block mt-1">
                         {{ $message }}
                     </small>
                 @enderror
             </form>

             <p class="text-center text-muted mb-0 mt-2" style="font-size: 11px;">
                 <i class="fa-solid fa-sparkles me-1"></i>
                 Asisten Keuangan KiraAI
             </p>

         </footer>
     </section>

 </div>
