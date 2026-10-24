@extends('Layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('link_css')
    @vite([
        'resources/css/MenuList/pengeluaran.css',
        'resources/css/MenuList/pengeluaranTambah.css',
    ])
@endsection

@section('content')

<div class="expense-create-page container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9 col-xxl-8">

            <section class="expense-create-card">

                {{-- Navigasi --}}
                <div class="expense-create-navigation">
                    <a
                        href="{{ route('pengeluaran.index') }}"
                        wire:navigate
                        class="expense-back-link"
                    >
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Kembali ke Pengeluaran
                    </a>

                    <span class="expense-page-label">
                        <i class="fa-solid fa-wallet me-1"></i>
                        Keuangan
                    </span>
                </div>

                {{-- Judul --}}
                <header class="expense-create-heading">
                    <div class="expense-heading-icon">
                        <i class="fa-solid fa-circle-minus"></i>
                    </div>

                    <div>
                        <h1>Tambah Pengeluaran</h1>
                        <p>
                            Catat uang keluar agar pengeluaran usaha
                            dapat dipantau dan dikelola dengan baik.
                        </p>
                    </div>
                </header>

                {{-- Pesan Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger expense-form-alert" role="alert">
                        <div class="fw-semibold mb-1">
                            Ada data yang perlu diperiksa.
                        </div>

                        <p class="small mb-0">
                            Periksa kembali kolom yang ditandai sebelum menyimpan.
                        </p>
                    </div>
                @endif

                {{-- Form Pengeluaran --}}
                <form
                    action="{{ route('pengeluaran.store') }}"
                    method="POST"
                >
                    @csrf

                    <div class="expense-form-section">
                        <h2 class="expense-section-title">
                            Informasi Pengeluaran
                        </h2>

                        <p class="expense-section-description">
                            Isi informasi transaksi sesuai dengan pengeluaran yang dilakukan.
                        </p>

                        <div class="row g-3">

                            {{-- Label Pengeluaran --}}
                            <div class="col-12">
                                <label for="label_pengeluaran" class="expense-form-label">
                                    Label Pengeluaran
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group expense-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-bookmark"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="label"
                                        id="label_pengeluaran"
                                        class="form-control @error('label') is-invalid @enderror"
                                        value="{{ old('label') }}"
                                        placeholder="Contoh: Pembelian bahan baku"
                                        maxlength="255"
                                        required
                                    >
                                </div>

                                @error('label')
                                    <div class="expense-error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Kategori Pengeluaran --}}
                            <div class="col-12 col-md-6">
                                <label for="kategori" class="expense-form-label">
                                    Kategori Pengeluaran
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group expense-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-tags"></i>
                                    </span>

                                    <select
                                        name="kategori"
                                        id="kategori"
                                        class="form-select @error('kategori') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Pilih kategori</option>

                                        @forelse ($kategori_pengeluaran as $kategori)
                                            <option
                                                value="{{ $kategori->id }}"
                                                @selected(old('kategori') == $kategori->id)
                                            >
                                                {{ $kategori->nama_ketegori }}
                                            </option>
                                        @empty
                                            <option value="" disabled>
                                                Belum ada kategori pengeluaran
                                            </option>
                                        @endforelse
                                    </select>
                                </div>

                                @error('kategori')
                                    <div class="expense-error-message">
                                        {{ $message }}
                                    </div>
                                @enderror

                                @if ($kategori_pengeluaran->isEmpty())
                                    <small class="expense-field-help">
                                        Tambahkan kategori pengeluaran terlebih dahulu
                                        sebelum mencatat transaksi.
                                    </small>
                                @endif
                            </div>

                            {{-- Jumlah Pengeluaran --}}
                            @livewire('pengeluaran.partials.formnumber')

                            {{-- Tanggal Pengeluaran --}}
                            <div class="col-12 col-md-6">
                                <label for="tanggal_pengeluaran" class="expense-form-label">
                                    Tanggal Pengeluaran
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group expense-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>

                                    <input
                                        type="date"
                                        name="tanggal_pengeluaran"
                                        id="tanggal_pengeluaran"
                                        class="form-control @error('tanggal_pengeluaran') is-invalid @enderror"
                                        value="{{ old('tanggal_pengeluaran', now()->format('Y-m-d')) }}"
                                        required
                                    >
                                </div>

                                @error('tanggal_pengeluaran')
                                    <div class="expense-error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Deskripsi Pengeluaran --}}
                            <div class="col-12">
                                <label for="deskripsi_pengeluaran" class="expense-form-label">
                                    Deskripsi
                                    <span class="text-secondary fw-normal">(Opsional)</span>
                                </label>

                                <textarea
                                    name="deskripsi_pengeluaran"
                                    id="deskripsi_pengeluaran"
                                    rows="3"
                                    maxlength="2000"
                                    class="form-control expense-textarea @error('deskripsi_pengeluaran') is-invalid @enderror"
                                    placeholder="Tambahkan keterangan jika diperlukan..."
                                >{{ old('deskripsi_pengeluaran') }}</textarea>

                                @error('deskripsi_pengeluaran')
                                    <div class="expense-error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer Form --}}
                    <div class="expense-form-footer">
                        <p class="expense-form-note">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            <a
                                href="{{ route('pengeluaran.index') }}"
                                wire:navigate
                                class="btn expense-btn-cancel"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn expense-btn-submit"
                                @disabled($kategori_pengeluaran->isEmpty())
                            >
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </div>

                </form>

            </section>

        </div>
    </div>
</div>

@endsection