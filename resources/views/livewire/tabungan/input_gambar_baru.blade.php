<div wire:key="upload-gambar-kotak">
    <label for="gambar_baru" class="form-label fw-semibold small mb-2">Gambar Baru</label>

    {{-- Label dijadikan pembungkus area klik utama --}}
    <label for="gambar_baru" class="d-block cursor-pointer" style="width: 150px; height: 200px;">
        @if ($image)
            {{-- Box Preview --}}
            <div class="overflow-hidden border rounded-3 position-relative shadow-sm w-100 h-100">
                {{-- Perbaikan class w-100 h-100 --}}
                <img src="{{ $image->temporaryUrl() }}" alt="gambar_baru"
                    class="w-100 h-100 object-fit-cover img-thumbnail">

                {{-- Lapisan overlay tipis saat hover agar estetik --}}
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 text-white transition-all element-hover-class"
                    style="transition: 0.2s;">
                    <i class="fa-solid fa-pen-to-square fs-4"></i>
                </div>

                {{-- Lapisan Teks Tengah (Akan berada presisi di tengah-tengah gambar) --}}
                <div class="position-absolute  w-100 h-100 bg-dark bg-opacity-25" style="bottom: 2px;">
                    <span class="badge text-dark shadow-sm py-2 px-3 fw-semibold small text-wrap">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Klik untuk Mengganti
                    </span>
                </div>

                <button type="button" wire:click="resetGambar"
                    class="position-absolute btn btn-sm btn-danger d-flex align-items-center justify-content-center shadow"
                    style="bottom: 8px; right: 8px; z-index: 10; width: 32px; height: 32px; padding: 0; border-radius: 50%;">
                    <i class="fa-solid fa-xmark fs-6"></i>
                </button>
            </div>
        @else
            {{-- Box Kosong (Placeholder) --}}
            {{-- Menggunakan border-dashed milik Bootstrap (atau border-dotted sesuai style Anda) --}}
            <section
                class="border border-2 border-dashed d-flex flex-column align-items-center justify-content-center rounded-3 w-100 h-100 text-success shadow-sm header-upload"
                style="background-color: rgb(255, 255, 255); cursor: pointer; transition: 0.2s;">
                <i class="fa-solid fa-cloud-arrow-up fs-3 mb-2 animate-bounce"></i>
                <span class="small fw-semibold">Pilih Gambar</span>
            </section>
        @endif
    </label>

    {{-- Input file tersembunyi --}}
    <input type="file" name="image_gambar_baru" wire:model.live="image" accept="image/*" id="gambar_baru"
        class="d-none">

    {{-- Indikator Loading khusus untuk variabel $image --}}
    <div wire:loading wire:target="image" class="small text-muted mt-2">
        <i class="fa-solid fa-spinner fa-spin text-success"></i> Memproses berkas...
    </div>
</div>
