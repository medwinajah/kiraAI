<div class="dashboard-analytics" data-dashboard-grafik>

    {{-- DATA JSON UNTUK CHART.JS --}}
    <script type="application/json" data-dashboard-chart-data>{!! json_encode($chartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>


    {{-- =================================================== --}}
    {{-- FILTER PERIODE DAN RENTANG TANGGAL                  --}}
    {{-- =================================================== --}}

    <section class="dashboard-filter-toolbar mb-3">

        <div class="dashboard-filter-information">

            <span class="dashboard-filter-eyebrow">
                <i class="fa-solid fa-sliders me-1"></i>
                FILTER ANALISIS
            </span>

            <h6 class="dashboard-filter-title">
                Periode Keuangan
            </h6>

            <p class="dashboard-filter-description">
                {{ $keteranganPeriode }}
            </p>

        </div>


        <div class="dashboard-filter-actions">

            {{-- PILIH PERIODE --}}

            <div class="dashboard-filter-control dashboard-filter-control-period">

                <label
                    for="dashboard-periode"
                    class="dashboard-filter-label"
                >
                    Periode
                </label>

                <div class="dashboard-filter-select-wrap">

                    <i class="fa-regular fa-calendar"></i>

                    <select
                        id="dashboard-periode"
                        class="form-select dashboard-period-select"
                        wire:model.live="periode"
                        aria-label="Pilih periode grafik keuangan"
                    >
                        <option value="harian">Harian</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                        <option value="rentang">Rentang tanggal</option>
                    </select>

                    <i class="fa-solid fa-chevron-down dashboard-filter-chevron"></i>

                </div>

            </div>


            {{-- TANGGAL MULAI DAN AKHIR --}}

            @if ($periode === 'rentang')

                <div class="dashboard-filter-control dashboard-filter-control-date">

                    <label
                        for="dashboard-tanggal-mulai"
                        class="dashboard-filter-label"
                    >
                        Dari tanggal
                    </label>

                    <input
                        type="date"
                        id="dashboard-tanggal-mulai"
                        class="form-control dashboard-filter-date-input"
                        wire:model.live="tanggalMulai"
                        max="{{ now()->toDateString() }}"
                        aria-label="Tanggal mulai grafik"
                    >

                </div>


                <div class="dashboard-filter-control dashboard-filter-control-date">

                    <label
                        for="dashboard-tanggal-sampai"
                        class="dashboard-filter-label"
                    >
                        Sampai tanggal
                    </label>

                    <input
                        type="date"
                        id="dashboard-tanggal-sampai"
                        class="form-control dashboard-filter-date-input"
                        wire:model.live="tanggalSampai"
                        min="{{ $tanggalMulai ?: '' }}"
                        max="{{ now()->toDateString() }}"
                        aria-label="Tanggal akhir grafik"
                    >

                </div>

            @endif


            {{-- TOMBOL RESET --}}

            <button
                type="button"
                class="btn dashboard-filter-reset"
                wire:click="resetFilter"
                wire:loading.attr="disabled"
                wire:target="resetFilter"
                title="Reset filter ke periode mingguan"
            >

                <span wire:loading.remove wire:target="resetFilter">
                    <i class="fa-solid fa-rotate-left me-2"></i>
                    Reset
                </span>

                <span wire:loading wire:target="resetFilter">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Memuat...
                </span>

            </button>

        </div>

    </section>


    {{-- =================================================== --}}
    {{-- GRAFIK AREA PEMASUKAN VS PENGELUARAN                --}}
    {{-- =================================================== --}}

    <section class="dashboard-chart-panel dashboard-chart-panel-main mb-3">

        <div class="dashboard-panel-header">

            <div>

                <span class="dashboard-panel-kicker">
                    RINGKASAN {{ strtoupper($labelPeriode) }}
                </span>

                <h6 class="dashboard-panel-title">
                    Pemasukan vs. Pengeluaran
                </h6>

                <p class="dashboard-panel-description">
                    Bandingkan nominal uang masuk dan uang keluar.
                    {{ $keteranganPeriode }}.
                </p>

            </div>

            <div class="dashboard-panel-icon dashboard-panel-icon-income">
                <i class="fa-solid fa-chart-area"></i>
            </div>

        </div>


        <div
            class="dashboard-chart-canvas-wrap dashboard-chart-canvas-main"
            wire:ignore
        >

            <canvas
                data-dashboard-chart="perbandingan"
                role="img"
                aria-label="Grafik area perbandingan pemasukan dan pengeluaran pada periode {{ $labelPeriode }}"
            ></canvas>

        </div>


        <div class="dashboard-chart-footnote">

            <i class="fa-solid fa-circle-info me-2"></i>

            <span>
                Data berdasarkan tanggal transaksi pada periode
                {{ strtolower($labelPeriode) }} yang dipilih.
            </span>

        </div>

    </section>


    {{-- =================================================== --}}
    {{-- GRAFIK KATEGORI PEMASUKAN DAN PENGELUARAN           --}}
    {{-- =================================================== --}}

    <div class="row g-3 dashboard-category-grid">


        {{-- KATEGORI PEMASUKAN --}}

        <div class="col-12 col-xl-6">

            <section class="dashboard-chart-panel h-100">

                <div class="dashboard-panel-header">

                    <div>

                        <span class="dashboard-panel-kicker">
                            SUMBER PEMASUKAN
                        </span>

                        <h6 class="dashboard-panel-title">
                            Kategori Pemasukan Teratas
                        </h6>

                        <p class="dashboard-panel-description">
                            Lima kategori dengan jumlah transaksi terbanyak
                            pada periode terpilih.
                        </p>

                    </div>

                    <div class="dashboard-panel-icon dashboard-panel-icon-income">
                        <i class="fa-solid fa-arrow-trend-up"></i>
                    </div>

                </div>


                @if ($kategoriPemasukan->isNotEmpty())

                    <div
                        class="dashboard-chart-canvas-wrap dashboard-chart-canvas-category"
                        wire:ignore
                    >

                        <canvas
                            data-dashboard-chart="kategori-pemasukan"
                            role="img"
                            aria-label="Grafik kategori pemasukan pada periode terpilih"
                        ></canvas>

                    </div>

                @else

                    <div class="dashboard-chart-empty">

                        <div class="dashboard-empty-icon">
                            <i class="fa-solid fa-chart-bar"></i>
                        </div>

                        <h6>Belum ada data pemasukan</h6>

                        <p>
                            Tidak ditemukan transaksi pemasukan pada periode
                            yang dipilih.
                        </p>

                    </div>

                @endif


                <div class="dashboard-chart-footnote">

                    <i class="fa-solid fa-circle-info me-2"></i>

                    <span>
                        Berdasarkan periode {{ strtolower($labelPeriode) }}.
                    </span>

                </div>

            </section>

        </div>


        {{-- KATEGORI PENGELUARAN --}}

        <div class="col-12 col-xl-6">

            <section class="dashboard-chart-panel h-100">

                <div class="dashboard-panel-header">

                    <div>

                        <span class="dashboard-panel-kicker">
                            ALOKASI PENGELUARAN
                        </span>

                        <h6 class="dashboard-panel-title">
                            Kategori Pengeluaran Teratas
                        </h6>

                        <p class="dashboard-panel-description">
                            Lima kategori dengan jumlah transaksi terbanyak
                            pada periode terpilih.
                        </p>

                    </div>

                    <div class="dashboard-panel-icon dashboard-panel-icon-expense">
                        <i class="fa-solid fa-arrow-trend-down"></i>
                    </div>

                </div>


                @if ($kategoriPengeluaran->isNotEmpty())

                    <div
                        class="dashboard-chart-canvas-wrap dashboard-chart-canvas-category"
                        wire:ignore
                    >

                        <canvas
                            data-dashboard-chart="kategori-pengeluaran"
                            role="img"
                            aria-label="Grafik kategori pengeluaran pada periode terpilih"
                        ></canvas>

                    </div>

                @else

                    <div class="dashboard-chart-empty">

                        <div class="dashboard-empty-icon dashboard-empty-icon-expense">
                            <i class="fa-solid fa-chart-bar"></i>
                        </div>

                        <h6>Belum ada data pengeluaran</h6>

                        <p>
                            Tidak ditemukan transaksi pengeluaran pada periode
                            yang dipilih.
                        </p>

                    </div>

                @endif


                <div class="dashboard-chart-footnote">

                    <i class="fa-solid fa-circle-info me-2"></i>

                    <span>
                        Berdasarkan periode {{ strtolower($labelPeriode) }}.
                    </span>

                </div>

            </section>

        </div>

    </div>


    {{-- =================================================== --}}
    {{-- TABEL TRANSAKSI HARI INI                            --}}
    {{-- =================================================== --}}

   {{-- =================================================== --}}
{{-- TABEL TRANSAKSI TERBARU                             --}}
{{-- =================================================== --}}

<section class="dashboard-transaction-panel mt-3">

    <div class="dashboard-panel-header dashboard-transaction-header">

        <div>
            <span class="dashboard-panel-kicker">
                RIWAYAT AKTIVITAS
            </span>

            <h6 class="dashboard-panel-title">
                Transaksi Terbaru
            </h6>

            <p class="dashboard-panel-description">
                Enam transaksi terakhir dari seluruh riwayat keuanganmu.
            </p>
        </div>

        <div class="dashboard-transactions-count">
            <i class="fa-solid fa-receipt me-2"></i>
            {{ $transaksiTerbaru->count() }} transaksi
        </div>

    </div>

    @if ($transaksiTerbaru->isEmpty())

        <div class="dashboard-transaction-empty">

            <div class="dashboard-empty-icon">
                <i class="fa-regular fa-calendar-check"></i>
            </div>

            <h6>Belum ada transaksi</h6>

            <p>
                Transaksi pemasukan dan pengeluaranmu akan muncul
                di bagian ini setelah berhasil dicatat.
            </p>

        </div>

    @else

        <div class="table-responsive dashboard-table-wrap">

            <table class="table align-middle dashboard-transaction-table mb-0">

                <thead>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Transaksi</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Jenis</th>
                        <th scope="col" class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($transaksiTerbaru as $transaksi)

                        <tr wire:key="transaksi-terbaru-{{ $transaksi['id'] }}">

                            {{-- TANGGAL DAN WAKTU --}}

                            <td>
                                <span class="dashboard-transaction-time">
                                    {{ $transaksi['tanggal']->format('d/m/Y') }}
                                    <br>
                                    {{ $transaksi['tanggal']->format('H:i') }}
                                </span>
                            </td>

                            {{-- NAMA TRANSAKSI --}}

                            <td>
                                <div class="dashboard-transaction-name">
                                    {{ $transaksi['label'] }}
                                </div>

                                @if (!empty($transaksi['deskripsi']))
                                    <div class="dashboard-transaction-description">
                                        {{ $transaksi['deskripsi'] }}
                                    </div>
                                @endif
                            </td>

                            {{-- KATEGORI --}}

                            <td>
                                <span class="dashboard-category-pill">
                                    {{ $transaksi['kategori'] }}
                                </span>
                            </td>

                            {{-- JENIS --}}

                            <td>
                                @if ($transaksi['jenis'] === 'Pemasukan')

                                    <span class="dashboard-type-pill dashboard-type-income">
                                        <i class="fa-solid fa-arrow-down me-1"></i>
                                        Pemasukan
                                    </span>

                                @else

                                    <span class="dashboard-type-pill dashboard-type-expense">
                                        <i class="fa-solid fa-arrow-up me-1"></i>
                                        Pengeluaran
                                    </span>

                                @endif
                            </td>

                            {{-- NOMINAL --}}

                            <td class="text-end">

                                @if ($transaksi['jenis'] === 'Pemasukan')

                                    <span class="dashboard-transaction-amount dashboard-amount-income">
                                        + Rp {{ number_format($transaksi['jumlah'], 0, ',', '.') }}
                                    </span>

                                @else

                                    <span class="dashboard-transaction-amount dashboard-amount-expense">
                                        − Rp {{ number_format($transaksi['jumlah'], 0, ',', '.') }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</section>
</div>