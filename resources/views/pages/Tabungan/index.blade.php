@extends('Layouts.app')
@section('title', 'Tabungan')
@section('link_css')
    @vite(['resources/css/MenuList/tabungan.css'])
@endsection

@section('content')

   <section class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-3 border-0 bg-body-tertiary rounded-4 shadow-sm px-4">
    <!-- Bagian Teks Judul -->
    <div class="py-1">
        <h3 class="fw-bold mb-1 text-dark">
            Tambahkan <span class="text-success fw-extrabold">Sebuah Tabungan</span>
        </h3>
        <p class="small text-muted mb-0">
            Buat target menabung Anda dan pantau setiap progresnya secara berkala!
        </p>
    </div>
    
    <!-- Bagian Tombol Aksi -->
    <div>
        <!-- Menghapus tag <a> dan membersihkan struktur button -->
        <button type="button" class="btn btn-primary  btn_tambah fw-semibold px-4 py-3 shadow-sm rounded-4 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="fa-solid fa-circle-plus fs-5"></i>
            <span>Tambah Target Tabungan</span>
        </button>
    </div>
</section>

        {{-- Flash Session --}}
    @if (session('message'))
        <section class="p-2 border bg-success text-white rounded-4 shadow-sm mb-2 mt-2 fw-semibold fs-5">
            {{ session('message') }}
        </section>
    @endif
    @if (session('error'))
        <section class="p-2 border bg-warning-subtle fw-semibold text-warning rounded-4 shadow-sm mt-2 mb-2">
            {{ session('error') }}
        </section>
    @endif
    {{-- ================================= --}}

    {{-- Card Informations --}}
    <div class="mt-3 px-3">
        @include('pages.Tabungan.partials.card')
    </div>
    {{-- ================================== --}}

    {{-- Modal Tambah --}}
    @include('pages.Tabungan.partials.modaltambah')
    {{-- ================================== --}}

    {{-- Menu Index Tampil Data --}}
        @livewire('tabungan.componentindex')
    {{-- ================================== --}}
@endsection
