@extends('Layouts.app')
@section('title', 'Pengeluaran')
@section('link_css')
    @vite(['resources/css/MenuList/pengeluaran.css'])
@endsection
@section('content')
    @if (session()->has('success'))
        <section class="mb-3 p-3 rounded-4 bg-success text-white small fw-medium shadow-sm">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </section>
    @endif
    @if (session()->has('error'))
        <section class="mb-3 p-3 rounded-4 bg-danger text-white small fw-medium shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('success') }}
        </section>
    @endif


    <section class="d-flex align-items-center justify-content-between
                flex-wrap gap-3 py-3 border rounded-4 shadow-sm px-4"
    style="background: #fff; border-color: #e5ece6 !important;">

    <div class="py-2">
        <h3 class="fw-bold mb-2">
            Kelola <span class="title_pengeluaran">Pengeluaran Anda</span>
        </h3>

        <p class="small text-secondary mb-0">
            Catat setiap pengeluaran yang keluar pada halaman ini!
        </p>
    </div>

    {{-- Tombol Aksi: tetap sejajar --}}
    <div class="d-flex flex-row flex-wrap gap-2 py-2 align-items-center"
        style="flex-shrink: 0;">

        @livewire('pengeluaran.scannota')

        <a href="{{ route('pengeluaran.create') }}"
            wire:navigate
            class="btn btn-success border fw-semibold shadow-sm text-white
                   rounded-3 d-inline-flex align-items-center justify-content-center gap-2 py-3 rounded-4"
            style="white-space: nowrap;">

            <i class="fa-solid fa-circle-plus"></i>
            <span>Pengeluaran</span>

        </a>

    </div>
</section>

    <div class="px-2">
        @livewire('pengeluaran.komponenindexis')
    </div>

    {{-- Modal Scan Nota --}}
    @include('pages.Pengeluaran.partials.modalKamereScan')
    {{-- =============== --}}

@endsection
