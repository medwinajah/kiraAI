@extends('Layouts.app')

@section('title', 'Pemasukan')

@section('link_css')
    @vite(['resources/css/MenuList/pemasukan.css', 'resources/js/pemasukan/pemasukanImportExcel.js'])
@endsection

@section('content')
    <div class="income-page mb-3">

        {{-- Notifikasi berhasil --}}
        @if (session()->has('success'))
            <div class="income-alert income-alert--success mb-4" role="alert">
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
            <div class="income-alert income-alert--error mb-4" role="alert">
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
                    <a href="{{ route('pemasukan.create') }}" class="income-add-button">

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


        {{-- ================================================== --}}
        {{-- IMPORT EXCEL KHUSUS PEMASUKAN --}}
        {{-- ================================================== --}}

        <section class="income-import-card mt-4">

            <div class="income-import-header">
                <div class="income-import-icon">
                    <i class="fa-solid fa-file-excel"></i>
                </div>

                <div class="income-import-heading">
                    <span class="income-import-eyebrow">
                        IMPORT DATA PEMASUKAN
                    </span>

                    <h2>Upload Pemasukan dari Excel</h2>

                    <p>
                        Impor banyak transaksi pemasukan sekaligus.
                        Data akan diperiksa terlebih dahulu sebelum disimpan
                        ke riwayat pemasukanmu.
                    </p>
                </div>
            </div>

            {{-- FORM UPLOAD --}}
            <form action="{{ route('pemasukan.import.preview') }}" method="POST" enctype="multipart/form-data"
                class="income-import-form">
                @csrf

                <label for="income-excel-file" class="income-import-dropzone" data-income-import-dropzone>
                    <input type="file" id="income-excel-file" name="excel_file" class="income-import-input"
                        accept=".xlsx,.xls,.csv" data-income-import-input required>

                    <span class="income-import-upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </span>

                    <strong>Tarik dan lepaskan file Excel di sini</strong>

                    <span class="income-import-description">
                        atau klik area ini untuk memilih file dari perangkatmu.
                    </span>

                    <span class="income-import-formats">
                        XLSX, XLS, atau CSV · Maksimal 5 MB
                    </span>

                    <span class="income-import-filename" data-income-import-filename aria-live="polite">
                        Belum ada file yang dipilih
                    </span>
                </label>

                @error('excel_file')
                    <div class="income-import-validation">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="income-import-actions">

                    <a href="{{ route('pemasukan.import.template') }}" class="income-import-template">
                        <i class="fa-solid fa-download"></i>
                        Unduh Template Pemasukan
                    </a>

                    <button type="submit" class="income-import-submit">
                        <i class="fa-solid fa-eye"></i>
                        Baca dan Preview Data
                    </button>

                </div>
            </form>

            <div class="income-import-note">
                <i class="fa-solid fa-shield-halved"></i>

                <span>
                    Fitur ini hanya mengimpor pemasukan. Periksa tanggal,
                    kategori, dan nominal sebelum mengonfirmasi penyimpanan.
                </span>
            </div>

        </section>

        {{-- ================================================== --}}
        {{-- PREVIEW DATA PEMASUKAN --}}
        {{-- ================================================== --}}

        @if (!empty($importPreview))

            @php
                $validRows = collect($importPreview)->filter(fn($row) => empty($row['errors']))->count();

                $invalidRows = count($importPreview) - $validRows;
            @endphp

            <section class="income-import-preview mt-4">


                <div class="income-import-preview-header d-flex align-items-start justify-content-between gap-3">

                    {{-- Informasi preview --}}
                    <div class="income-import-preview-heading">
                        <span class="income-import-eyebrow">
                            PEMERIKSAAN DATA
                        </span>

                        <h2>Preview Pemasukan</h2>

                        <p>
                            Periksa seluruh data pemasukan sebelum disimpan
                            ke database.
                        </p>
                    </div>

                    {{-- Jumlah baris dan tombol batal --}}
                    <div class="income-import-preview-header-actions">

                        <span class="income-import-preview-count">
                            {{ count($importPreview) }} baris
                        </span>

                        <form action="{{ route('pemasukan.import.cancel') }}" method="POST"
                            onsubmit="return confirm('Batalkan preview pemasukan ini? Data preview sementara akan dihapus dan belum ada transaksi yang disimpan.');">
                            @csrf

                            <button type="submit" class="income-import-preview-close" title="Batalkan preview"
                                aria-label="Batalkan dan tutup preview pemasukan">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </form>

                    </div>

                </div>


                {{-- RINGKASAN VALIDASI --}}
                <div class="income-import-summary">

                    <div class="income-import-summary-item">
                        <span>Baris valid</span>

                        <strong class="text-success">
                            {{ $validRows }}
                        </strong>
                    </div>

                    <div class="income-import-summary-item">
                        <span>Perlu diperbaiki</span>

                        <strong class="{{ $invalidRows > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $invalidRows }}
                        </strong>
                    </div>

                </div>

                {{-- TABEL PREVIEW --}}
                <div class="table-responsive income-import-table-wrap">

                    <table class="table align-middle mb-0 income-import-table">

                        <thead>
                            <tr>
                                <th>Baris</th>
                                <th>Tanggal</th>
                                <th>Transaksi</th>
                                <th>Kategori</th>
                                <th>Sumber Pemasukan</th>
                                <th class="text-end">Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($importPreview as $row)
                                <tr>
                                    <td>
                                        {{ $row['row_number'] }}
                                    </td>

                                    <td class="text-nowrap">
                                        {{ $row['tanggal'] ?? '—' }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $row['label'] ?: '—' }}
                                        </strong>

                                        @if (!empty($row['deskripsi']))
                                            <small class="income-import-row-description">
                                                {{ $row['deskripsi'] }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $row['kategori'] ?: '—' }}
                                    </td>

                                    <td>
                                        {{ $row['sumber_pemasukan'] ?: '—' }}
                                    </td>

                                    <td class="text-end text-nowrap">
                                        {{ $row['jumlah'] !== null ? 'Rp ' . number_format($row['jumlah'], 0, ',', '.') : '—' }}
                                    </td>

                                    <td>
                                        @if (empty($row['errors']))
                                            <span class="income-import-status income-import-status--valid">
                                                <i class="fa-solid fa-circle-check"></i>
                                                Valid
                                            </span>
                                        @else
                                            <span class="income-import-status income-import-status--invalid">
                                                <i class="fa-solid fa-circle-exclamation"></i>
                                                Periksa
                                            </span>

                                            <ul class="income-import-row-errors">
                                                @foreach ($row['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>

                {{-- KONFIRMASI PENYIMPANAN --}}
                <div class="income-import-confirm-area">

                    @if ($importHasErrors)
                        <p class="income-import-confirm-warning">
                            <i class="fa-solid fa-triangle-exclamation"></i>

                            Masih ada baris tidak valid. Perbaiki file Excel
                            lalu unggah kembali sebelum menyimpan pemasukan.
                        </p>
                    @else
                        <p class="income-import-confirm-help">
                            Semua baris valid. Pastikan jumlah dan kategori
                            pemasukan sudah benar sebelum melanjutkan.
                        </p>

                        <form action="{{ route('pemasukan.import.confirm') }}" method="POST"
                            onsubmit="return confirm('Simpan seluruh pemasukan dari file Excel ini?')">
                            @csrf

                            <button type="submit" class="income-import-submit">
                                <i class="fa-solid fa-database"></i>
                                Konfirmasi dan Simpan Pemasukan
                            </button>
                        </form>
                    @endif

                </div>

            </section>

        @endif


    </div> {{-- Judul daftar transaksi --}}
    <section class="income-list-section">

        {{-- Komponen Livewire yang sudah ada --}}
        <div class="income-list-content">
            @livewire('pemasukan.komponenindexis')
        </div>

    </section>
@endsection
