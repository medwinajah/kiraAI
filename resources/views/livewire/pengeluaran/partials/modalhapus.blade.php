{{-- Modal Hapus Pengeluaran --}}
@foreach ($Pengeluaran as $data)

    <div
        class="modal fade"
        id="deleteusers{{ $data->id }}"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="deleteModalLabel{{ $data->id }}"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">

            <form
                wire:submit="delete({{ $data->id }})"
                class="modal-content border-0 rounded-4 shadow-lg overflow-hidden"
            >

                {{-- Header Modal --}}
                <div class="modal-header border-0 px-4 pt-4 pb-2">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="bg-danger-subtle text-danger rounded-circle
                                   d-flex align-items-center justify-content-center
                                   flex-shrink-0"
                            style="width: 48px; height: 48px;"
                        >
                            <i class="fa-solid fa-trash-can fs-5"></i>
                        </div>

                        <div>
                            <h5
                                class="modal-title fw-bold mb-1"
                                id="deleteModalLabel{{ $data->id }}"
                            >
                                Hapus Pengeluaran?
                            </h5>

                            <p class="text-secondary small mb-0">
                                Konfirmasi penghapusan data
                            </p>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close ms-auto"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>

                </div>


                {{-- Body Modal --}}
                <div class="modal-body px-4 pt-3 pb-4">

                    <p class="text-secondary mb-3">
                        Apakah kamu yakin ingin menghapus catatan pengeluaran ini?
                        Pastikan data yang dipilih sudah benar.
                    </p>

                    {{-- Ringkasan Data --}}
                    <div class="border rounded-3 bg-light p-3">

                        <div class="small text-secondary mb-2">
                            Data yang akan dihapus
                        </div>

                        <div
                            class="d-flex justify-content-between
                                   align-items-start gap-3"
                        >

                            {{-- Informasi Pengeluaran --}}
                            <div class="flex-grow-1 min-w-0">

                                <div class="fw-semibold text-dark text-break">
                                    {{ $data->label }}
                                </div>

                                <div class="small text-secondary mt-1">
                                    <i class="fa-solid fa-wallet me-1"></i>
                                    Pengeluaran
                                </div>

                            </div>

                            {{-- Nominal Pengeluaran --}}
                            <div class="text-end text-nowrap">

                                <div class="small text-secondary mb-1">
                                    Nominal
                                </div>

                                <div class="fw-bold text-danger">
                                    Rp{{ number_format(
                                        (float) $data->jumlah_pengeluaran,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Footer Modal --}}
                <div
                    class="modal-footer border-0 bg-light
                           px-4 py-3 d-flex gap-2"
                >

                    <button
                        type="button"
                        class="btn btn-outline-secondary rounded-3
                               fw-semibold flex-fill"
                        data-bs-dismiss="modal"
                    >
                        <i class="fa-solid fa-xmark me-1"></i>
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger rounded-3 fw-semibold flex-fill"
                    >
                        <i class="fa-solid fa-trash-can me-1"></i>
                        Hapus Data
                    </button>

                </div>

            </form>

        </div>
    </div>

@endforeach