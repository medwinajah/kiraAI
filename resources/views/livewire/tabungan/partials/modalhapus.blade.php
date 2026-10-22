@foreach ($tabungan as $data)
    <div class="modal fade"
         id="deletetabunganid{{ $data->id }}"
         tabindex="-1"
         aria-labelledby="judulHapusTabungan{{ $data->id }}"
         aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <form wire:submit="deleteTabungan({{ $data->id }})" class="modal-content border-0 rounded-4 shadow"
                 style="overflow: hidden;">

                {{-- Header --}}
                <div class="modal-header border-0 text-white"
                     style="background: linear-gradient(135deg, #198754, #116342);">

                    <h5 class="modal-title fw-bold"
                        id="judulHapusTabungan{{ $data->id }}">
                        <i class="fa-solid fa-trash-can me-2"></i>
                        Hapus Data Tabungan
                    </h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4 text-center">

                    <div class="d-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle"
                         style="width: 70px; height: 70px; background: #fff0f0; color: #dc3545; font-size: 28px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <h5 class="fw-bold mb-2" style="color: #25382d;">
                        Yakin ingin menghapus tabungan?
                    </h5>

                    <p class="text-secondary small mb-3">
                        Kamu akan menghapus target tabungan berikut:
                    </p>

                    <div class="p-3 rounded-3 text-start"
                         style="background: #f2faf4; border: 1px solid #dcebe0;">

                        <div class="small text-secondary mb-1">
                            Nama Target Tabungan
                        </div>

                        <div class="fw-bold mb-2" style="color: #198754;">
                            {{ $data->label_tabungan }}
                        </div>

                        <div class="small text-secondary mb-1">
                            Target Menabung
                        </div>

                        <div class="fw-bold" style="color: #25382d;">
                            Rp {{ number_format($data->target_tabungan, 0, ',', '.') }}
                        </div>
                    </div>

                    <p class="text-danger small mt-3 mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Pastikan kembali sebelum melanjutkan penghapusan.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 p-3 pt-0 gap-2 flex-nowrap">

                    <button type="button"
                            class="btn btn-light border rounded-3 fw-semibold w-50 py-2"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-danger rounded-3 fw-semibold w-50 py-2">
                        <i class="fa-solid fa-trash-can me-1"></i>
                        Hapus Tabungan
                    </button>

                </div>

            </form>
        </div>
    </div>
@endforeach