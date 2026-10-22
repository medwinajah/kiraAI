@extends('Layouts.app')

@section('title', 'Kategori Pemasukan')

@section('link_css')
    @vite(['resources/css/MenuList/kategoripemasukan.css'])
@endsection

@section('content')

    {{-- Header Halaman --}}
    <section class="kategori-income-header mb-4">
        <div>
            <span class="kategori-income-eyebrow">
                <i class="fa-solid fa-layer-group me-1"></i>
                MANAJEMEN KEUANGAN
            </span>

            <h3 class="fw-bold mb-2">
                Kategori <span>Pemasukan</span>
            </h3>

            <p class="mb-0">
                Kelola kategori untuk mengelompokkan sumber pemasukanmu
                agar pencatatan keuangan lebih teratur.
            </p>
        </div>

        <button type="button" class="btn kategori-income-add fw-bold text-white p-3" data-bs-toggle="modal"
            data-bs-target="#modalTambahKategori">
            <i class="fa-solid fa-plus me-2"></i>
            Tambah Kategori
        </button>
    </section>

    {{-- Daftar Kategori --}}
    <section class="kategori-income-card">

        <div class="kategori-income-card-header">
            <div>
                <h5 class="fw-bold mb-1">Daftar Kategori Pemasukan</h5>
                <p class="mb-0">
                    Kategori yang digunakan untuk mencatat pemasukan.
                </p>
            </div>

            <span class="kategori-income-icon">
                <i class="fa-solid fa-list-ul"></i>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="font-size: 16px;">No.</th>
                        <th style="font-size: 16px;">Nama Kategori</th>
                        <th style="font-size: 16px;">Deskripsi Kategori</th>
                        <th style="font-size: 16px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @isset($kategori_pemasukan)
                        @forelse ($kategori_pemasukan as $kategori)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <div class="kategori-income-name">
                                        <span>
                                            <i class="fa-solid fa-tag"></i>
                                        </span>

                                        {{ $kategori->nama_kategori }}
                                    </div>
                                </td>

                                <td class="kategori-income-description">
                                    {{ $kategori->deskripsi_kategori ?: 'Tidak ada deskripsi' }}
                                </td>
                                <td class="kategori-income-description text-nowrap">
                                    <button type="button" class="btn btn-danger btn-sm rounded-3" data-bs-toggle="modal"
                                        data-bs-target="#hapusKategori{{ $kategori->id }}"
                                        aria-label="Hapus kategori {{ $kategori->nama_kategori }}">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editKategori{{ $kategori->id }}">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </button>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <section class="text-center d-flex align-items-center flex-column w-100">
                                    <i class="fa-solid fa-folder-open fs-2 mb-3 d-block text-center" style="color: #198754;"></i>

                                    <strong class="fs-4">Belum Ada Kategori</strong>

                                    <p class="text-secondary small mb-0 mt-2">
                                        Tambahkan kategori Pemasukan pertamamu.
                                    </p>
                                    </section>
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <i class="fa-solid fa-folder-open fs-2 mb-3 d-block" style="color: #198754;"></i>

                                <strong>Data Kategori Belum Tersedia</strong>

                                <p class="text-secondary small mb-0 mt-2">
                                    Tampilan daftar siap digunakan setelah
                                    data kategori dikirim ke halaman.
                                </p>
                            </td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
        @include('pages.KategoriPemasukan.partials.modaltambah')
        @include('pages.KategoriPemasukan.partials.modalhapus')
        @include('pages.KategoriPemasukan.partials.modalupdate')
    </section>



@endsection
