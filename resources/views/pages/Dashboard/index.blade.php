@extends('Layouts.app')

@section('title', 'Dashboard')

@section('link_css')
    @vite(['resources/css/MenuList/dashboard.css'])
@endsection

@section('content')

    <div class="dashboard-page">

        {{-- WELCOME BANNER --}}
        <section class="dashboard-welcome mb-4">
            <div class="row align-items-center g-3">

                <div class="col-12 col-lg-8">
                    <span class="dashboard-eyebrow">
                        <i class="fa-solid fa-chart-pie me-2"></i>
                        FINANCIAL OVERVIEW
                    </span>

                    <h2 class="dashboard-welcome-title">
                        Halo, {{ Auth::user()->name }}!
                    </h2>

                    <p class="dashboard-welcome-description">
                        Selamat datang kembali di <span class="fw-bold">kiraAI.</span>
                        Pantau pemasukan dan pengeluaranmu untuk membantu
                        menjaga kondisi keuangan tetap teratur.
                    </p>

                    <div class="dashboard-welcome-date">
                        <i class="fa-regular fa-calendar me-2"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <div class="col-lg-4 d-none d-lg-flex justify-content-end">
                    <div class="dashboard-illustration">
                        <div class="dashboard-decoration dashboard-decoration-one"></div>
                        <div class="dashboard-decoration dashboard-decoration-two"></div>

                        <div class="dashboard-wallet-icon">
                            <i class="fa-solid fa-wallet"></i>
                        </div>

                        <div class="dashboard-floating-icon">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>

                        <div class="dashboard-floating-coins">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                </div>

            </div>
        </section>


        {{-- JUDUL RINGKASAN --}}
        <section class="dashboard-section-heading mb-3">
            <div>
                <span class="dashboard-section-label">
                    RINGKASAN KEUANGAN
                </span>

                <h5 class="dashboard-section-title">
                    Kondisi Keuanganmu
                </h5>

                <p class="dashboard-section-description">
                    Berikut ringkasan keuangan yang sudah tercatat di <span class="fw-bold">kiraAI</span>.
                </p>
            </div>

            <span class="dashboard-period-badge">
                <i class="fa-solid fa-calendar-check me-2"></i>
                Keseluruhan
            </span>
        </section>


        {{-- KARTU RINGKASAN --}}
        <div class="row g-3 mb-4">

            {{-- CARD PEMASUKAN --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="dashboard-summary-card h-100">

                    <div class="dashboard-card-top">
                        <div>
                            <p class="dashboard-card-label">
                                Total Pemasukan
                            </p>

                            <h4 class="dashboard-card-value">
                                Rp {{ number_format($jumlah_pemasukan, 0, ',', '.') }}
                            </h4>
                        </div>

                        <div class="dashboard-card-icon icon-income">
                            <span><i class="fa-solid fa-circle-down fs-5"></i></span>
                        </div>
                    </div>

                    <div class="dashboard-card-divider"></div>

                    <div class="dashboard-card-footer">
                        <span class="dashboard-status-icon status-income">
                            <i class="fa-solid fa-circle-arrow-up"></i>
                        </span>

                        <span>Total pemasukan yang diterima</span>
                    </div>

                </div>
            </div>


            {{-- CARD PENGELUARAN --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="dashboard-summary-card h-100">

                    <div class="dashboard-card-top">
                        <div>
                            <p class="dashboard-card-label">
                                Total Pengeluaran
                            </p>

                            <h4 class="dashboard-card-value">
                                Rp {{ number_format($jumlah_pengeluaran, 0, ',', '.') }}
                            </h4>
                        </div>

                        <div class="dashboard-card-icon icon-expense">
                            <span><i class="fa-solid fa-circle-up fs-5"></i></span>
                        </div>
                    </div>

                    <div class="dashboard-card-divider"></div>

                    <div class="dashboard-card-footer">
                        <span class="dashboard-status-icon status-expense">
                            <i class="fa-solid fa-wallet"></i>
                        </span>

                        <span>Total pengeluaran yang tercatat</span>
                    </div>

                </div>
            </div>


            {{-- CARD SALDO AKHIR --}}
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="dashboard-summary-card h-100">

                    <div class="dashboard-card-top">
                        <div>
                            <p class="dashboard-card-label">
                                Saldo Akhir
                            </p>

                            <h4 class="dashboard-card-value">
                                Rp {{ number_format($saldo_akhir, 0, ',', '.') }}
                            </h4>
                        </div>

                        <div class="dashboard-card-icon icon-balance">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>

                    <div class="dashboard-card-divider"></div>

                    <div class="dashboard-card-footer">
                        <span class="dashboard-status-icon status-balance">
                            <i class="fa-solid fa-chart-line"></i>
                        </span>

                        <span>Sisa saldo setelah alokasi tabungan</span>
                    </div>

                </div>
            </div>

        </div>


        {{-- GRAFIK KEUANGAN --}}
        <section class="dashboard-chart-section">

            <div class="dashboard-section-heading mb-3">
                <div>
                    <span class="dashboard-section-label">
                        ANALISIS KEUANGAN
                    </span>

                    <h5 class="dashboard-section-title">
                        Grafik Keuangan
                    </h5>

                    <p class="dashboard-section-description">
                        Pantau dan bandingkan pemasukan dengan pengeluaranmu.
                    </p>
                </div>

                <div class="dashboard-chart-heading-icon">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>

            {{-- Komponen grafik Livewire tetap dipertahankan --}}
            <livewire:dashboard.grafik />

        </section>

    </div>

@endsection
