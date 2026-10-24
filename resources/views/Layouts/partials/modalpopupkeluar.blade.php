{{-- Modal Konfirmasi Keluar --}}
<div
    class="modal fade"
    id="popupkeluar"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="popupkeluarLabel"
    aria-describedby="popupkeluarDescription"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <form
            action="{{ route('logout_sistem') }}"
            method="POST"
            class="modal-content border-0 rounded-4 shadow overflow-hidden"
        >
            @csrf
            @method('DELETE')

            {{-- Header --}}
            <div class="modal-header bg-success border-0 px-4 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">

                    <div
                        class="d-flex align-items-center justify-content-center
                               rounded-3 bg-danger-subtle text-danger flex-shrink-0"
                        style="width: 46px; height: 46px;"
                    >
                        <i class="fa-solid fa-arrow-right-from-bracket fs-5"></i>
                    </div>

                    <div>
                        <h5
                            class="modal-title fw-bold mb-1 text-white"
                            id="popupkeluarLabel"
                        >
                            Keluar dari KiraAI
                        </h5>

                        <p class="text-white small mb-0">
                            Konfirmasi sesi akun
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-close ms-3"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            {{-- Body --}}
            <div class="modal-body px-4 pt-3 pb-4">

                <p class="text-secondary mb-3" id="popupkeluarDescription">
                    Halo,
                    <span class="fw-semibold text-dark">
                        {{ Auth::user()->name }}
                    </span>.
                </p>

                <p class="mb-0" style="line-height: 1.7;">
                    Apakah kamu yakin ingin keluar dari akun?
                    Kamu perlu masuk kembali untuk mengakses dashboard
                    dan data keuanganmu.
                </p>

            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top bg-light px-4 py-3 gap-2">

                <button
                    type="button"
                    class="btn btn-outline-secondary rounded-3 fw-semibold px-3"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-danger rounded-3 fw-semibold px-3"
                >
                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                    Ya, Keluar
                </button>

            </div>

        </form>
    </div>
</div>