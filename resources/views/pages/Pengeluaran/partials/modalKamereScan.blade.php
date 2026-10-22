
{{-- Modal Scan Nota --}}
<div
    class="modal fade"
    id="modalScanNota"
    tabindex="-1"
    aria-labelledby="judulModalScan"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 overflow-hidden">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="judulModalScan">
                    <i class="fa-solid fa-camera me-2 text-success"></i>
                    Scan Nota Pengeluaran
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body">
                <p class="small text-secondary">
                    Arahkan kamera belakang HP ke nota dan pastikan
                    tulisan serta harga terlihat jelas.
                </p>

                <div class="bg-dark rounded-4 overflow-hidden">
                    <video
                        id="kameraNota"
                        class="w-100 d-block"
                        autoplay
                        muted
                        playsinline
                        style="max-height: 60vh; object-fit: contain;"
                    ></video>
                </div>

                <canvas id="kanvasNota" class="d-none"></canvas>

                <div id="hasilFotoNota" class="mt-3 d-none">
                    <p class="fw-semibold mb-2">Pratinjau hasil tangkapan</p>
                    <img
                        id="fotoNota"
                        class="img-fluid rounded-3 border"
                        alt="Pratinjau nota"
                    >
                </div>

                <div
                    id="statusKameraNota"
                    class="small mt-3"
                    role="status"
                    aria-live="polite"
                >
                    Menyiapkan kamera...
                </div>
            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    id="btnAmbilNota"
                    class="btn btn-success rounded-3"
                    disabled
                >
                    <i class="fa-solid fa-camera me-1"></i>
                    Ambil Gambar
                </button>

                <button
                    type="button"
                    id="btnUlangNota"
                    class="btn btn-outline-secondary rounded-3 d-none"
                >
                    Ulangi
                </button>

                <button
                    type="button"
                    class="btn btn-light border rounded-3"
                    data-bs-dismiss="modal"
                >
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>