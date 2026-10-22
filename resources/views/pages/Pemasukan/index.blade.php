
@extends('Layouts.app')

@section('title', 'Pemasukan')

@section('link_css')
    @vite(['resources/css/MenuList/pemasukan.css'])
@endsection

@section('content')
<div class="income-page">

    {{-- Notifikasi berhasil --}}
    @if (session()->has('success'))
        <div class="income-alert income-alert--success mb-4"
             role="alert">
            <span class="income-alert__icon">
                <i class="fa-solid fa-circle-check"></i>
            </span>

            <div class="income-alert__content">
                <strong>Berhasil!</strong>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Notifikasi gagal --}}
    @if (session()->has('error'))
        <div class="income-alert income-alert--error mb-4"
             role="alert">
            <span class="income-alert__icon">
                <i class="fa-solid fa-circle-exclamation"></i>
            </span>

            <div class="income-alert__content">
                <strong>Terjadi kesalahan</strong>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Hero / Header halaman --}}
    <section class="income-hero mb-4">

        <div class="income-hero__content">

            <div class="income-eyebrow">
                <span class="income-eyebrow__dot"></span>
                MANAJEMEN KEUANGAN
            </div>

            <h1 class="income-hero__title">
                Kelola <span>Pemasukan</span>
            </h1>

            <p class="income-hero__description">
                Catat setiap pemasukan yang kamu terima dan
                kelola riwayat keuanganmu dengan lebih teratur.
            </p>

            <div class="income-benefits">
                <span>
                    <i class="fa-solid fa-circle-check"></i>
                    Pencatatan teratur
                </span>

                <span>
                    <i class="fa-solid fa-circle-check"></i>
                    Mudah dipantau
                </span>
            </div>

            <div class="income-hero__footer">
                <a href="{{ route('pemasukan.create') }}"
                   wire:navigate
                   class="income-add-button">

                    <span class="income-add-button__icon">
                        <i class="fa-solid fa-plus"></i>
                    </span>

                    <span class="income-add-button__text">
                        <strong>Tambah Pemasukan</strong>
                        <small>Catat transaksi baru</small>
                    </span>

                    <i class="fa-solid fa-arrow-right income-add-button__arrow"></i>
                </a>
            </div>

        </div>

        {{-- Dekorasi visual --}}
        <div class="income-hero__art" aria-hidden="true">

            <div class="income-art-orbit"></div>

            <div class="income-art-card">
                <div class="income-art-card__icon">
                    <i class="fa-solid fa-wallet"></i>
                </div>

                <div class="income-art-card__text">
                    <span>FINANCIAL TRACKER</span>
                    <strong>Pemasukan Anda</strong>
                    <small>Setiap transaksi punya catatan.</small>
                </div>

                <div class="income-art-card__chart">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>

            <div class="income-art-chip">
                <i class="fa-solid fa-check"></i>
                Terorganisir dengan baik
            </div>

        </div>

    </section>

</div>  {{-- Judul daftar transaksi --}}
    <section class="income-list-section">

        {{-- Komponen Livewire yang sudah ada --}}
        <div class="income-list-content">
            @livewire('pemasukan.komponenindexis')
        </div>

    </section>
@endsection
