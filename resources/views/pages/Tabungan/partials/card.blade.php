
<div class="row g-3 p-3 p-lg-4 mt-4 mb-3 rounded-4 shadow-sm"
     style="background: linear-gradient(135deg, #ffffff, #f1f9f3); border: 1px solid #dfede4;">

    {{-- Header Ringkasan --}}
    <div class="col-12 mb-2">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3"
                 style="width: 46px; height: 46px; background: #e3f4e8; color: #198754;">
                <i class="fa-solid fa-chart-pie fs-5"></i>
            </div>

            <div>
                <h5 class="fw-bold mb-1" style="color: #25382d;">
                    Ringkasan Tabungan
                </h5>
                <p class="small mb-0" style="color: #718078;">
                    Pantau jumlah target dan progres tabunganmu.
                </p>
            </div>
        </div>
    </div>

    {{-- Card 1: Jumlah Target --}}
    <div id="card_1" class="col-12 col-sm-6 col-lg-4">
        <div class="p-4 rounded-4 h-100 position-relative overflow-hidden shadow-sm"
             style="background: linear-gradient(135deg, #198754, #116342); min-height: 165px;">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="small fw-semibold text-white">
                    Jumlah Target Tabungan
                </span>

                <span class="d-flex align-items-center justify-content-center rounded-3"
                      style="width: 42px; height: 42px; background: rgba(255,255,255,0.16); color: #ffffff;">
                    <i class="fa-solid fa-piggy-bank fs-5"></i>
                </span>
            </div>

            <h2 class="fw-bold display-6 text-white mb-2">{{ $jumlah_semua }}</h2>

            <p class="small mb-0" style="color: #d8f0df;">
                Total rencana tabungan dibuat
            </p>
        </div>
    </div>

    {{-- Card 2: Dalam Proses --}}
    <div id="card_2" class="col-12 col-sm-6 col-lg-4">
        <div class="p-4 rounded-4 h-100 position-relative overflow-hidden shadow-sm"
             style="background: linear-gradient(135deg, #ffffff, #e8f6ec); border: 1px solid #d8ebde; min-height: 165px;">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="small fw-semibold" style="color: #42634d;">
                    Dalam Proses
                </span>

                <span class="d-flex align-items-center justify-content-center rounded-3"
                      style="width: 42px; height: 42px; background: #d6efde; color: #198754;">
                    <i class="fa-solid fa-spinner fs-5"></i>
                </span>
            </div>

            <h2 class="fw-bold display-6 mb-2" style="color: #198754;">
                {{ $jumlah_proses }} <span class="fs-4 fw-normal" style="color: #8ba594;">/ {{ $jumlah_semua }}</span>
            </h2>

            <p class="small mb-0" style="color: #718078;">
                Target tabungan sedang berjalan
            </p>
        </div>
    </div>

    {{-- Card 3: Target Selesai --}}
    <div id="card_3" class="col-12 col-sm-12 col-lg-4">
        <div class="p-4 rounded-4 h-100 position-relative overflow-hidden shadow-sm"
             style="background: linear-gradient(135deg, #176b49, #104b36); min-height: 165px;">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="small fw-semibold text-white">
                    Target Selesai
                </span>

                <span class="d-flex align-items-center justify-content-center rounded-3"
                      style="width: 42px; height: 42px; background: rgba(255,255,255,0.16); color: #ffffff;">
                    <i class="fa-solid fa-circle-check fs-5"></i>
                </span>
            </div>

            <h2 class="fw-bold display-6 text-white mb-2">
               {{ $jumlah_selesai }} <span class="fs-4 fw-normal" style="color: #c5e5d0;">/ {{ $jumlah_semua }}</span>
            </h2>

            <p class="small mb-0" style="color: #d1e9d9;">
                Target tabungan berhasil tercapai
            </p>
        </div>
    </div>

</div>
