@extends('Layouts.app')

@section('title', 'Tambah Pemasukan')

@section('link_css')
    @vite([
        'resources/css/MenuList/pemasukan.css',
        'resources/css/MenuList/pemasukanTambah.css',
    ])
@endsection

@section('content')

<div class="income-create-page container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9 col-xxl-8">

            <section class="income-create-card">

                {{-- Navigasi --}}
                <div class="income-create-navigation">
                    <a
                        href="{{ route('pemasukan.index') }}"
                        wire:navigate
                        class="income-back-link"
                    >
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Kembali ke Pemasukan
                    </a>

                    <span class="income-page-label">
                        <i class="fa-solid fa-wallet me-1"></i>
                        Keuangan
                    </span>
                </div>

                {{-- Judul --}}
                <header class="income-create-heading">
                    <div class="income-heading-icon">
                        <i class="fa-solid fa-circle-plus"></i>
                    </div>

                    <div>
                        <h1>Tambah Pemasukan</h1>
                        <p>
                            Catat uang masuk untuk membantu menjaga
                            pencatatan keuangan tetap teratur.
                        </p>
                    </div>
                </header>

                {{-- Pesan Validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger income-form-alert" role="alert">
                        <div class="fw-semibold mb-1">
                            Ada data yang perlu diperiksa.
                        </div>

                        <p class="small mb-0">
                            Periksa kembali kolom yang ditandai sebelum menyimpan.
                        </p>
                    </div>
                @endif

                {{-- Form --}}
                <form
                    action="{{route('pandapatan.store')}}"
                    method="POST"
                >
                    @csrf

                    <div class="income-form-section">
                        <h2 class="income-section-title">
                            Informasi Pemasukan
                        </h2>

                        <p class="income-section-description">
                            Isi informasi transaksi sesuai dengan pemasukan yang diterima.
                        </p>

                        <div class="row g-3">

                            {{-- Label Pemasukan --}}
                            <div class="col-12">
                                <label for="label_pemasukan" class="income-form-label">
                                    Label Pemasukan
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group income-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-bookmark"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="label"
                                        id="label_pemasukan"
                                        class="form-control @error('label') is-invalid @enderror"
                                        value="{{ old('label') }}"
                                        placeholder="Contoh: Penjualan harian"
                                        maxlength="255"
                                        required
                                    >
                                </div>

                                @error('label')
                                    <div class="income-error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="col-12 col-md-6">
                                <label for="kategori" class="income-form-label">
                                    Kategori Pemasukan
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group income-input-group">
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

                                        @forelse ($kategori_pemasukan as $kategori)
                                            <option
                                                value="{{ $kategori->id }}"
                                                @selected(old('kategori') == $kategori->id)
                                            >
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @empty
                                            <option value="" disabled>
                                                Belum ada kategori pemasukan
                                            </option>
                                        @endforelse
                                    </select>
                                </div>

                                @error('kategori')
                                    <div class="income-error-message">{{ $message }}</div>
                                @enderror

                                @if ($kategori_pemasukan->isEmpty())
                                    <small class="income-field-help">
                                        Tambahkan kategori pemasukan terlebih dahulu
                                        sebelum mencatat transaksi.
                                    </small>
                                @endif
                            </div>

                            {{-- Jumlah Pemasukan --}}
                            {{-- Komponen ini tetap menggunakan logika Livewire yang ada --}}
                            @livewire('pemasukan.partials.formnumberlive')

                            {{-- Sumber Pemasukan --}}
                            <div class="col-12 col-md-6">
                                <label for="sumber_pemasukan" class="income-form-label">
                                    Sumber Pemasukan
                                </label>

                                <div class="input-group income-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-arrow-trend-up"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="sumber_pemasukan"
                                        id="sumber_pemasukan"
                                        class="form-control @error('sumber_pemasukan') is-invalid @enderror"
                                        value="{{ old('sumber_pemasukan') }}"
                                        placeholder="Contoh: Penjualan atau pelanggan"
                                        maxlength="255"
                                    >
                                </div>

                                @error('sumber_pemasukan')
                                    <div class="income-error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal Pemasukan --}}
                            <div class="col-12 col-md-6">
                                <label for="tanggal_pemasukan" class="income-form-label">
                                    Tanggal Pemasukan
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group income-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>

                                    <input
                                        type="date"
                                        name="tanggal_pemasukan"
                                        id="tanggal_pemasukan"
                                        class="form-control @error('tanggal_pemasukan') is-invalid @enderror"
                                        value="{{ old('tanggal_pemasukan') }}"
                                        required
                                    >
                                </div>

                                @error('tanggal_pemasukan')
                                    <div class="income-error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-12">
                                <label for="deskripsi_pemasukan" class="income-form-label">
                                    Deskripsi
                                    <span class="text-secondary fw-normal">(Opsional)</span>
                                </label>

                                <textarea
                                    name="deskripsi_pemasukan"
                                    id="deskripsi_pemasukan"
                                    rows="3"
                                    maxlength="2000"
                                    class="form-control income-textarea @error('deskripsi_pemasukan') is-invalid @enderror"
                                    placeholder="Tambahkan keterangan jika diperlukan..."
                                >{{ old('deskripsi_pemasukan') }}</textarea>

                                @error('deskripsi_pemasukan')
                                    <div class="income-error-message">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer Form --}}
                    <div class="income-form-footer">
                        <p class="income-form-note">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </p>

                        <div class="d-flex flex-wrap gap-2">
                            <a
                                href="{{ route('pemasukan.index') }}"
                                wire:navigate
                                class="btn income-btn-cancel"
                            >
                                Batal
                            </a>

                            <button
                                type="submit"
                                class="btn income-btn-submit"
                                @disabled($kategori_pemasukan->isEmpty())
                            >
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Simpan Pemasukan
                            </button>
                        </div>
                    </div>

                </form>

            </section>

        </div>
    </div>
</div>

@endsection