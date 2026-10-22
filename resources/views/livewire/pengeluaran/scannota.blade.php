<div>
    {{-- Tombol Scan Nota: aktif pada HP melalui JavaScript --}}
    <button type="button" class="btn btn-success d-flex py-3 rounded-4 gap-2 align-items-center" data-scan-button>
        <i class="fa-solid fa-camera"></i>
        <span class="fw-semibold">Scan Nota</span>
    </button>

    {{-- Modal kamera --}}
    <div class="modal fade" data-scan-modal tabindex="-1" aria-labelledby="judulScanNota" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 overflow-hidden">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="judulScanNota">
                        <i class="fa-solid fa-camera text-success me-2"></i>
                        spanScan Nota Pengeluaran
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <p class="small text-secondary">
                        Gunakan kamera perangkat dan posisikan nota
                        agar nama barang serta harganya terlihat jelas.
                        Pada HP, kamera belakang akan diprioritaskan jika tersedia
                    </p>

                    {{-- JavaScript mengelola elemen kamera ini --}}
                    <div class="bg-dark rounded-4 overflow-hidden" wire:ignore>
                        <video data-scan-video class="w-100 d-block" autoplay muted playsinline
                            style="max-height: 55vh; object-fit: contain;"></video>

                        <canvas data-scan-canvas class="d-none"></canvas>
                    </div>

                    <div class="small mt-3" data-scan-status role="status" aria-live="polite" wire:ignore>
                        Menyiapkan kamera...
                    </div>

                    @error('fotoNota')
                        <div class="alert alert-danger small mt-3 mb-0">
                            {{ $message }}
                        </div>
                    @enderror

                    @if ($errorMessage)
                        <div class="alert alert-danger small mt-3 mb-0">
                            {{ $errorMessage }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small mt-3">
                            Ada data yang belum valid.
                            Periksa kembali nama barang, harga, kategori, dan tanggal.
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div wire:loading wire:target="scanNota" class="alert alert-info small mt-3 mb-0">
                        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                        AI sedang membaca nota...
                    </div>

                    {{-- Hasil pembacaan AI --}}
                    @if (count($items) > 0)
                        <div class="mt-4">
                            <h6 class="fw-bold">Periksa Hasil Scan</h6>

                            <p class="small text-secondary">
                                Toko:
                                {{ $merchant ?: 'Tidak terbaca' }}
                                <br>
                                Periksa semua informasi sebelum menyimpan.
                            </p>
                            
                                @foreach ($items as $index => $item)
                                    <div class="border rounded-4 p-3 mb-3"
                                        wire:key="scan-nota-item-{{ $index }}">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong>Barang {{ $index + 1 }}</strong>

                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3"
                                                wire:click="removeItem({{ $index }})">
                                                Hapus
                                            </button>
                                        </div>

                                        @if ($item['certainty'] === 'uncertain')
                                            <div class="alert alert-warning small py-2">
                                                Informasi ini perlu diperiksa kembali.
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">
                                                Nama barang
                                            </label>

                                            <input type="text" class="form-control"
                                                wire:model="items.{{ $index }}.label" required maxlength="255">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">
                                                Kategori pengeluaran
                                            </label>

                                            <select class="form-select" wire:model="items.{{ $index }}.kategori"
                                                required>
                                                <option value="">
                                                    Pilih kategori
                                                </option>

                                                @forelse ($kategori_pengeluaran as $kategori)
                                                    <option value="{{ $kategori->id }}">
                                                        {{ $kategori->nama_kategori ?? ($kategori->nama_ketegori ?? 'Kategori #' . $kategori->id) }}
                                                    </option>
                                                @empty
                                                    <option value="" disabled selected>
                                                        -- Kategori belum ada, silahkan buat kategori terlebih dahulu --
                                                    </option>
                                                @endforelse
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">
                                                Harga barang (Rp)
                                            </label>

                                            <input type="number" class="form-control"
                                                wire:model="items.{{ $index }}.amount" min="1000"
                                                step="1" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold">
                                                Tanggal pengeluaran
                                            </label>

                                            <input type="date" class="form-control"
                                                wire:model="items.{{ $index }}.tanggal_pengeluaran" required>
                                        </div>

                                        <div>
                                            <label class="form-label small fw-semibold">
                                                Deskripsi
                                            </label>

                                            <textarea class="form-control" wire:model="items.{{ $index }}.deskripsi_pengeluaran" rows="2"
                                                placeholder="Opsional"></textarea>
                                        </div>
                                    </div>
                                @endforeach

                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success rounded-3" data-scan-capture>
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                        Baca Nota dengan AI
                    </button>

                    <button type="button" class="btn btn-outline-secondary rounded-3 d-none" data-scan-again>
                        Scan Ulang
                    </button>

                    <button type="button" class="btn btn-light border rounded-3" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
                {{-- Button Tambah --}}
                @if (count($items) > 0)
                    <section class="mb-2 px-2 py-2 text-right">
                        <button type="button" class="btn btn-success rounded-3 w-100" wire:click="simpanSemua"
                            wire:loading.attr="disabled" wire:target="simpanSemua">
                            <span wire:loading.remove wire:target="simpanSemua">
                                <i class="fa-solid fa-floppy-disk me-1"></i>
                                Simpan Semua Pengeluaran ({{ count($items) }})
                            </span>

                            <span wire:loading wire:target="simpanSemua">
                                <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </section>
                @endif
                {{-- ============= --}}

            </div>
        </div>
    </div>
</div>

@script
    <script>
        const root = $wire.$el;

        const tombolScan = root.querySelector('[data-scan-button]');
        const modalElement = root.querySelector('[data-scan-modal]');
        const video = root.querySelector('[data-scan-video]');
        const canvas = root.querySelector('[data-scan-canvas]');
        const status = root.querySelector('[data-scan-status]');
        const tombolAmbil = root.querySelector('[data-scan-capture]');
        const tombolUlang = root.querySelector('[data-scan-again]');

        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        // const mediaHP = window.matchMedia('(max-width: 767.98px)');

        let stream = null;
        let sedangUpload = false;

        // function aturTombolScan() {
        //     tombolScan.disabled = !mediaHP.matches;
        //     tombolScan.title = mediaHP.matches ?
        //         'Buka kamera untuk scan nota' :
        //         'Scan Nota tersedia melalui HP';

        //     if (!mediaHP.matches && modalElement.classList.contains('show')) {
        //         modal.hide();
        //     }
        // }

        function hentikanKamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            video.pause();
            video.srcObject = null;
        }

        async function mulaiKamera() {
            if (stream) return;

            if (!navigator.mediaDevices?.getUserMedia) {
                status.textContent =
                    'Kamera membutuhkan browser yang mendukung akses kamera melalui HTTPS atau localhost.';
                return;
            }

            status.textContent = 'Meminta izin kamera...';

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    audio: false,
                    video: {
                        facingMode: {
                            ideal: 'environment'
                        },
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                });

                video.srcObject = stream;
                await video.play();

                tombolAmbil.disabled = false;
                status.textContent =
                    'Kamera siap. Arahkan ke nota dan pastikan tulisannya jelas.';
            } catch (error) {
                hentikanKamera();

                status.textContent = error.name === 'NotAllowedError' ?
                    'Akses kamera ditolak. Izinkan kamera pada browser.' :
                    'Kamera gagal dibuka. Periksa izin kamera dan HTTPS.';

                console.error('Kesalahan kamera:', error);
            }
        }

        // tombolScan.addEventListener('click', function() {
        //     if (mediaHP.matches) modal.show();
        // });
        tombolScan.addEventListener('click', function() {
            modal.show();
        });

        modalElement.addEventListener('shown.bs.modal', mulaiKamera);
        modalElement.addEventListener('hidden.bs.modal', hentikanKamera);

        // mediaHP.addEventListener('change', aturTombolScan);
        // aturTombolScan();

        tombolAmbil.addEventListener('click', async function() {
            if (sedangUpload) return;

            if (!stream || !video.videoWidth) {
                status.textContent = 'Tunggu sampai kamera siap.';
                return;
            }

            try {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                canvas.getContext('2d').drawImage(
                    video,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                // Ubah gambar kamera menjadi file JPEG.
                const blob = await new Promise((resolve, reject) => {
                    canvas.toBlob(
                        result => result ?
                        resolve(result) :
                        reject(new Error('Gambar gagal dibuat.')),
                        'image/jpeg',
                        0.85
                    );
                });

                const file = new File(
                    [blob],
                    'nota.jpg', {
                        type: 'image/jpeg'
                    }
                );

                hentikanKamera();

                sedangUpload = true;
                tombolAmbil.disabled = true;
                tombolUlang.disabled = true;
                tombolUlang.classList.remove('d-none');
                status.textContent = 'Mengunggah gambar ke Livewire...';

                // Upload sementara ke komponen Livewire, lalu jalankan Prism.
                $wire.upload(
                    'fotoNota',
                    file,
                    () => {
                        status.textContent = 'Gambar diterima. Prism sedang memproses nota...';

                        $wire.scanNota()
                            .then(() => {
                                status.textContent =
                                    'Pemrosesan selesai. Periksa nama, harga, kategori, dan tanggal.';
                            })
                            .catch(error => {
                                status.textContent =
                                    'Pemrosesan gagal. Periksa pesan kesalahan pada formulir.';
                                console.error(error);
                            })
                            .finally(() => {
                                sedangUpload = false;
                                tombolUlang.disabled = false;
                            });
                    },
                    error => {
                        sedangUpload = false;
                        tombolAmbil.disabled = false;
                        tombolUlang.disabled = false;

                        status.textContent =
                            'Upload gambar gagal. Periksa koneksi lalu coba kembali.';

                        console.error(error);
                    },
                    event => {
                        status.textContent =
                            'Mengunggah gambar: ' +
                            event.detail.progress + '%';
                    }
                );
            } catch (error) {
                sedangUpload = false;
                tombolAmbil.disabled = false;

                status.textContent =
                    error.message || 'Gagal mengambil gambar nota.';

                console.error(error);
            }
        });

        tombolUlang.addEventListener('click', async function() {
            if (sedangUpload) return;

            hentikanKamera();

            tombolAmbil.disabled = true;
            tombolUlang.disabled = true;

            try {
                await $wire.resetScan();

                await mulaiKamera();
            } finally {
                tombolUlang.disabled = false;
            }
        });
    </script>
@endscript
