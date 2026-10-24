@extends('Layouts.app')
@section('title', 'Pengeluaran')
@section('link_css')
    @vite(['resources/css/MenuList/pengeluaran.css','resources/js/pengeluaran/pengeluaranImportExcel.js'])
@endsection

@section('content')
   @if (session()->has('success'))
    <section class="mb-3 p-3 rounded-4 bg-success text-white small fw-medium shadow-sm"
             role="alert">
        <i class="bi bi-check-circle-fill me-1"></i>
        {{ session('success') }}
    </section>
@endif

@if (session()->has('error'))
    <section class="mb-3 p-3 rounded-4 bg-danger text-white small fw-medium shadow-sm"
             role="alert">
        <i class="bi bi-exclamation-triangle-fill me-1"></i>
        {{ session('error') }}
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


{{-- ================================================ --}}
{{-- IMPORT EXCEL KHUSUS PENGELUARAN --}}
{{-- ================================================ --}}

<section class="expense-import-card mt-4 mb-4">

    <div class="expense-import-header">
        <div class="expense-import-icon">
            <i class="fa-solid fa-file-excel"></i>
        </div>

        <div class="flex-grow-1">
            <span class="expense-import-eyebrow">
                IMPORT DATA PENGELUARAN
            </span>

            <h4>Upload Pengeluaran dari Excel</h4>

            <p>
                Impor banyak transaksi sekaligus. Periksa data terlebih
                dahulu sebelum menyimpannya ke database.
            </p>
        </div>
    </div>

    {{-- FORM UPLOAD --}}
    <form
        action="{{ route('pengeluaran.import.preview') }}"
        method="POST"
        enctype="multipart/form-data"
        class="expense-import-form"
    >
        @csrf

        <label
            for="expense-excel-file"
            class="expense-import-dropzone"
            data-expense-dropzone
        >
            <input
                type="file"
                id="expense-excel-file"
                name="excel_file"
                class="expense-import-input"
                accept=".xlsx,.xls,.csv"
                data-expense-file-input
                required
            >

            <span class="expense-import-upload-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </span>

            <strong>Tarik dan lepaskan file Excel di sini</strong>

            <span class="expense-import-description">
                atau klik area ini untuk memilih file dari perangkatmu.
            </span>

            <span class="expense-import-formats">
                XLSX, XLS, atau CSV · Maksimal 5 MB
            </span>

            <span
                class="expense-import-filename"
                data-expense-filename
                aria-live="polite"
            >
                Belum ada file yang dipilih
            </span>
        </label>

        @error('excel_file')
            <div class="expense-import-validation">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ $message }}
            </div>
        @enderror

        <div class="expense-import-actions">

            <a
                href="{{ route('pengeluaran.import.template') }}"
                class="expense-import-template"
            >
                <i class="fa-solid fa-download"></i>
                Unduh Template Pengeluaran
            </a>

            <button
                type="submit"
                class="expense-import-submit"
            >
                <i class="fa-solid fa-eye"></i>
                Baca dan Preview Data
            </button>

        </div>
    </form>

    <div class="expense-import-note">
        <i class="fa-solid fa-shield-halved"></i>

        <span>
            Fitur ini hanya mengimpor pengeluaran. Kategori harus sesuai
            dengan kategori pada akunmu. Data baru disimpan setelah
            dikonfirmasi.
        </span>
    </div>

</section>


{{-- ================================================ --}}
{{-- PREVIEW PENGELUARAN --}}
{{-- ================================================ --}}

@if (!empty($importPreview))

    @php
        $validRows = collect($importPreview)
            ->filter(fn ($row) => empty($row['errors']))
            ->count();

        $invalidRows = count($importPreview) - $validRows;
    @endphp

    <section class="expense-import-preview mt-4 mb-4">

        {{-- HEADER PREVIEW --}}
        <div class="expense-preview-header">

            <div class="expense-preview-heading">
                <span class="expense-import-eyebrow">
                    PEMERIKSAAN DATA
                </span>

                <h4>Preview Pengeluaran</h4>

                <p>
                    Periksa data transaksi dan perbaiki baris yang
                    belum valid sebelum menyimpan.
                </p>
            </div>

            <div class="expense-preview-header-actions">

                <span class="expense-preview-count">
                    {{ count($importPreview) }} baris
                </span>

                <form
                    action="{{ route('pengeluaran.import.cancel') }}"
                    method="POST"
                    onsubmit="return confirm('Batalkan preview pengeluaran ini? Preview sementara akan dihapus dan transaksi belum disimpan.');"
                >
                    @csrf

                    <button
                        type="submit"
                        class="expense-preview-close"
                        title="Batalkan preview"
                        aria-label="Batalkan preview pengeluaran"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </form>

            </div>

        </div>

        {{-- RINGKASAN --}}
        <div class="expense-import-summary">

            <div class="expense-import-summary-item">
                <span>Baris valid</span>
                <strong class="text-success">{{ $validRows }}</strong>
            </div>

            <div class="expense-import-summary-item">
                <span>Perlu diperbaiki</span>

                <strong class="{{ $invalidRows > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $invalidRows }}
                </strong>
            </div>

        </div>

        {{-- TABEL PREVIEW --}}
        <div class="table-responsive expense-preview-table-wrap">

            <table class="table align-middle mb-0 expense-preview-table">

                <thead>
                    <tr>
                        <th>Baris</th>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Kategori</th>
                        <th class="text-end">Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($importPreview as $row)

                        <tr>
                            <td>{{ $row['row_number'] }}</td>

                            <td class="text-nowrap">
                                {{ $row['tanggal'] ?? '—' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $row['label'] ?: '—' }}
                                </strong>

                                @if (!empty($row['deskripsi']))
                                    <small class="expense-preview-description">
                                        {{ $row['deskripsi'] }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $row['kategori'] ?: '—' }}
                            </td>

                            <td class="text-end text-nowrap">
                                {{ $row['jumlah'] !== null
                                    ? 'Rp ' . number_format($row['jumlah'], 0, ',', '.')
                                    : '—' }}
                            </td>

                            <td>
                                @if (empty($row['errors']))
                                    <span class="expense-import-status expense-import-status--valid">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Valid
                                    </span>
                                @else
                                    <span class="expense-import-status expense-import-status--invalid">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        Periksa
                                    </span>

                                    <ul class="expense-preview-errors">
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

        {{-- KONFIRMASI --}}
        <div class="expense-import-confirm-area">

            @if ($importHasErrors)

                <p class="expense-import-confirm-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>

                    Masih ada data yang tidak valid. Tutup preview,
                    perbaiki file Excel, lalu unggah ulang.
                </p>

            @else

                <p class="expense-import-confirm-help">
                    Semua baris valid. Pastikan nominal, tanggal,
                    dan kategori sudah benar sebelum melanjutkan.
                </p>

                <form
                    action="{{ route('pengeluaran.import.confirm') }}"
                    method="POST"
                    onsubmit="return confirm('Simpan seluruh transaksi pengeluaran dari file Excel ini?');"
                >
                    @csrf

                    <button
                        type="submit"
                        class="expense-import-submit"
                    >
                        <i class="fa-solid fa-database"></i>
                        Konfirmasi dan Simpan Pengeluaran
                    </button>
                </form>

            @endif

        </div>

    </section>

@endif


    <div class="px-2">
        @livewire('pengeluaran.komponenindexis')
    </div>

    {{-- Modal Scan Nota --}}
    @include('pages.Pengeluaran.partials.modalKamereScan')
    {{-- =============== --}}

@endsection
