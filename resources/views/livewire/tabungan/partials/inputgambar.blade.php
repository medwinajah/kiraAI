 {{-- Gambar Tabungan --}}
 <section class="mb-2 col-lg-12 col-sm-12">
     <label for="gambar" class="form-label pt-2 @error('gambar_target_menabung')
                    border-danger
                @enderror  small mb-1 fw-semibold"><i class="fa-solid fa-caret-up"></i> Gambar Penanda Tabungan 
        @if ($gambar)
            <div class="mt-2">
                <p class="small text-muted mb-1">Pratinjau Gambar:</p>
                <span class="position-relative">
                <img src="{{ $gambar->temporaryUrl() }}" class="img-thumbnail d-block" style="max-height: 200px; width: auto;" alt="Pratinjau"   
                >
                 <button type="button" wire:click="resetGambar" class="position-absolute btn  border shadow-sm" style="top:8px; left: 16px;"><i class="fa-solid fa-xmark text-gray"></i></button>
                </span>

            </div>            
        @else
            <section class="dumy-gambar p-4 rounded-4 text-center mt-2">
                <i class="fa-solid fa-image fs-3"></i>
                <p class="text-center small fw-semibold">Upload Gambar</p>
            </section>
        @endif  
        <input type="file" name="gambar_target_menabung" accept="image/*" class="form-control" style="display: none" id="gambar" wire:model.live="gambar" alt="gambar">
        @error('gambar_target_menabung')
                    <span class="mt-1 fw-light text-danger small">{{ $message }}</span>                    
                @enderror
        {{-- Indikator Loading --}}
        <div wire:loading wire:target="gambar" class="small fw-semibold text-muted">
            <i class="fa-solid fa-spinner fa-spin"></i> Mengunggah pratinjau...
        </div>
    </label>
 </section>
