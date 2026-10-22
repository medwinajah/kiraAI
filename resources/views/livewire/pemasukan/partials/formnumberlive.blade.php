
     {{-- Jumlah Pemasukan --}}
                    <section  wire:ignore.self class="mb-1 col-lg-6 col-sm-12">
                        <label for="jumlah_pemasukan" class="form-label small fw-semibold">Jumlah Pemasukan <sup class="text-danger fs-6">*</sup></label>
                        <div class=" border rounded-3 p-0 d-flex align-items-center @error('jumlah_pemasukan')
                            border-danger
                        @enderror">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input wire:ignore type="number" wire:model.live.debounce.300ms="jumlah_pemasukan" 
                        name="jumlah_pemasukan" 
                        value="{{ $jumlah_pemasukans }}"
                        class="form-control p-2 border-0 w-100 focus-ring focus-ring-light" placeholder="1000000" id="jumlah_pemasukan">
                        </div>
                        
                        <p class="mt-1 mb-0 fw-semibold">Rp. {{ number_format((float)$jumlah_pemasukan,0,',','.') }} </p>
                        @error('jumlah_pemasukan')
                            <span class="small text-danger d-block">{{$message}}</span>
                        @enderror
                    </section>

