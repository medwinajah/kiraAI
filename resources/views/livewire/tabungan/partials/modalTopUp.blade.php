@foreach ($tabungan as $data)
    <div class="modal fade" wire:ignore.self
         id="top-up-tabungan{{ $data->id }}"
         tabindex="-1"
         aria-labelledby="top-up-label{{ $data->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit="topup({{ $data->id }})" class="modal-content border-0 rounded-4 shadow"
                  style="overflow: hidden;">

                {{-- Header --}}
                <div class="modal-header text-white"
                     style="background: linear-gradient(135deg, #198754, #116342);">

                    <h5 class="modal-title fw-bold"
                        id="top-up-label{{ $data->id }}">
                        <i class="fa-solid fa-wallet me-2"></i>
                        Tambah Saldo Tabungan
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup">
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4">

                    <p class="text-secondary small mb-1">
                        Target tabungan
                    </p>

                    <h6 class="fw-bold mb-4" style="color: #198754;">
                        {{ $data->label_tabungan }}
                    </h6>

                    <label for="tambahan-tabungan{{ $data->id }}"
                           class="form-label small fw-semibold">
                        Jumlah Top-up
                    </label>

                    <div class="input-group rounded-3 overflow-hidden"
                         style="border: 1px solid #dcebe0;">

                        <span class="input-group-text border-0"
                              style="background: #edf7f0; color: #198754;">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="tambahan_tabungan"
                            id="tambahan-tabungan{{ $data->id }}"
                            class="form-control border-0 shadow-none"
                            min="1"
                            step="1"
                            wire:model.live.debounce.300ms="nominal_top_up"
                            placeholder="Contoh: 50000"
                            required
                        >
                        
                    </div>

                    <p class="small fw-light">Rp. {{ $nominal_top_up > 0 ? number_format($nominal_top_up,0,',','.') : '0' }}</p>

                    <small class="text-secondary d-block mt-2">
                        Masukkan nominal uang yang ingin ditambahkan.
                    </small>


                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 pt-0">
                    <button type="button"
                            class="btn btn-light border rounded-3 fw-semibold"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn text-white rounded-3 fw-semibold"
                            style="background: #198754;">
                        <i class="fa-solid fa-plus me-1"></i>
                        Top-up
                    </button>
                </div>

            </form>
        </div>
    </div>
@endforeach