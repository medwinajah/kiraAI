@extends('Layouts.app')

@section('title', 'Profil Saya')

@section('link_css')
    @vite(['resources/css/MenuList/profile.css'])
@endsection

@section('content')

@php
    $nama = $user->name ?? 'Pengguna';

    $inisial = collect(preg_split('/\s+/', trim($nama)))
        ->filter()
        ->take(2)
        ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
        ->implode('');

    $fotoProfil = $user->profile
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile)
        : null;
@endphp
<div class="profile-page">

    {{-- ================================================= --}}
    {{-- HEADER PROFIL --}}
    {{-- ================================================= --}}

    <section class="profile-header mb-4">

        <div class="profile-header-content">

            <span class="profile-eyebrow">
                <i class="fa-solid fa-shield-halved me-2"></i>
                PENGATURAN AKUN
            </span>

            <h3 class="profile-page-title">
                Profil <span>Saya</span>
            </h3>

            <p class="profile-header-description">
                Kelola informasi pribadi, foto profil, dan identitas akun
                KiraAI dalam satu tempat.
            </p>

            <div class="profile-header-meta">
                <span>
                    <i class="fa-regular fa-calendar-check me-2"></i>
                    Bergabung sejak
                    {{ $user->created_at?->translatedFormat('d F Y') ?? '—' }}
                </span>
            </div>

        </div>

        <div class="profile-header-illustration">
            <div class="profile-header-decoration profile-header-decoration-one"></div>
            <div class="profile-header-decoration profile-header-decoration-two"></div>

            <div class="profile-header-icon">
                <i class="fa-solid fa-user-gear"></i>
            </div>

            <span class="profile-header-floating-icon">
                <i class="fa-solid fa-check"></i>
            </span>
        </div>

    </section>


    {{-- ================================================= --}}
    {{-- NOTIFIKASI --}}
    {{-- ================================================= --}}

    @if (session('success'))
        <div class="alert profile-alert profile-alert-success alert-dismissible fade show"
            role="alert">

            <i class="fa-solid fa-circle-check me-2"></i>
            {{ session('success') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Tutup"></button>

        </div>
    @endif

    @if (session('error'))
        <div class="alert profile-alert profile-alert-danger alert-dismissible fade show"
            role="alert">

            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ session('error') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Tutup"></button>

        </div>
    @endif

    @if ($errors->any())
        <div class="alert profile-alert profile-alert-danger"
            role="alert">

            <div class="d-flex align-items-start gap-2">

                <i class="fa-solid fa-triangle-exclamation mt-1"></i>

                <div>
                    <strong>Periksa kembali data kamu.</strong>

                    <p class="mb-0 mt-1">
                        Terdapat data yang belum sesuai. Periksa pesan
                        di bawah kolom yang ditandai sebelum menyimpan.
                    </p>
                </div>

            </div>

        </div>
    @endif


    {{-- ================================================= --}}
    {{-- KONTEN PROFIL --}}
    {{-- ================================================= --}}

    <div class="row g-4">


        {{-- ============================================= --}}
        {{-- KARTU IDENTITAS --}}
        {{-- ============================================= --}}

        <div class="col-12 col-xl-4">

            <section class="profile-card h-100">

                <div class="profile-card-top">

                    <span class="profile-card-top-label">
                        IDENTITAS AKUN
                    </span>

                    <span class="profile-card-status">
                        <span class="profile-status-dot"></span>
                        Aktif
                    </span>

                </div>


                {{-- FOTO PROFIL --}}

                <div class="profile-avatar-wrapper">

                    @if ($fotoProfil)

                        <img
                            src="{{asset('storage/'.Auth::user()->profile) }}"
                            alt="Foto profil {{ $nama }}"
                            class="profile-avatar"
                        >

                    @else

                        <div class="profile-avatar profile-avatar-initial">
                            {{ $inisial ?: 'U' }}
                        </div>

                    @endif

                    <span class="profile-avatar-badge">
                        <i class="fa-solid fa-user-check"></i>
                    </span>

                </div>


                {{-- INFORMASI UTAMA --}}

                <div class="profile-identity">

                    <h5>{{ $user->name }}</h5>

                    <p>{{ $user->email }}</p>

                    <span class="profile-role">
                        <i class="fa-solid fa-shield-halved me-2"></i>
                        {{ ucfirst($user->hak_akses ?? 'Pengguna') }}
                    </span>

                </div>


                <div class="profile-divider"></div>


                {{-- RINGKASAN IDENTITAS --}}

                <div class="profile-information">

                    <div class="profile-information-item">

                        <span class="profile-information-icon">
                            <i class="fa-regular fa-user"></i>
                        </span>

                        <div class="profile-information-content">

                            <small>Nama Lengkap</small>

                            <strong>{{ $user->name }}</strong>

                        </div>

                    </div>


                    <div class="profile-information-item">

                        <span class="profile-information-icon">
                            <i class="fa-solid fa-venus-mars"></i>
                        </span>

                        <div class="profile-information-content">

                            <small>Jenis Kelamin</small>

                            <strong>
                                @switch($user->jenis_kelamin)
                                    @case('pria')
                                        Laki-laki
                                        @break

                                    @case('wanita')
                                        Perempuan
                                        @break

                                    @default
                                        Belum diisi
                                @endswitch
                            </strong>

                        </div>

                    </div>


                    <div class="profile-information-item">

                        <span class="profile-information-icon">
                            <i class="fa-regular fa-envelope"></i>
                        </span>

                        <div class="profile-information-content">

                            <small>Alamat Email</small>

                            <strong class="profile-email-value">
                                {{ $user->email }}
                            </strong>

                        </div>

                    </div>


                    <div class="profile-information-item">

                        <span class="profile-information-icon">
                            <i class="fa-regular fa-calendar"></i>
                        </span>

                        <div class="profile-information-content">

                            <small>Tanggal Bergabung</small>

                            <strong>
                                {{ $user->created_at?->translatedFormat('d M Y') ?? '—' }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="profile-security-note">

                    <span class="profile-security-icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>

                    <p>
                        Informasi akun kamu ditampilkan berdasarkan
                        data pengguna yang sedang login.
                    </p>

                </div>

            </section>

        </div>


        {{-- ============================================= --}}
        {{-- FORM EDIT PROFIL --}}
        {{-- ============================================= --}}

        <div class="col-12 col-xl-8">

            <section class="profile-form-card">


                {{-- HEADER FORM --}}

                <div class="profile-form-heading">

                    <div>

                        <span class="profile-form-eyebrow">
                            PENGELOLAAN AKUN
                        </span>

                        <h5>Informasi Pribadi</h5>

                        <p>
                            Perbarui informasi profil agar data akun
                            kamu tetap akurat.
                        </p>

                    </div>

                    <span class="profile-form-icon">
                        <i class="fa-solid fa-user-pen"></i>
                    </span>

                </div>


                <div class="profile-form-divider"></div>


                {{-- FORM SUBMIT --}}

                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf
                    @method('PUT')


                    <div class="row g-4">


                        {{-- NAMA LENGKAP --}}

                        <div class="col-12">

                            <label for="nama" class="profile-label">
                                Nama Lengkap
                                <span class="profile-required">*</span>
                            </label>

                            <div class="input-group profile-input
                                @error('nama') profile-input-invalid @enderror">

                                <span class="input-group-text">
                                    <i class="fa-regular fa-user"></i>
                                </span>

                                <input
                                    type="text"
                                    name="nama"
                                    id="nama"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $user->name) }}"
                                    placeholder="Masukkan nama lengkap"
                                    maxlength="255"
                                    autocomplete="name"
                                    required
                                >

                            </div>

                            @error('nama')
                                <small class="profile-error-message">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- EMAIL --}}

                        <div class="col-12">

                            <label for="email" class="profile-label">
                                Alamat Email
                                <span class="profile-required">*</span>
                            </label>

                            <div class="input-group profile-input
                                @error('email') profile-input-invalid @enderror">

                                <span class="input-group-text">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="nama@email.com"
                                    maxlength="255"
                                    autocomplete="email"
                                    required
                                >

                            </div>

                            @error('email')
                                <small class="profile-error-message">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                    {{ $message }}
                                </small>
                            @enderror

                            <small class="profile-field-help">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Email harus unik dan belum digunakan akun lain.
                            </small>

                        </div>


                        {{-- JENIS KELAMIN --}}

                        <div class="col-12 col-md-6">

                            <label for="jenis_kelamin" class="profile-label">
                                Jenis Kelamin
                            </label>

                            <div class="input-group profile-input
                                @error('jenis_kelamin') profile-input-invalid @enderror">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-venus-mars"></i>
                                </span>

                                <select
                                    name="jenis_kelamin"
                                    id="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                >

                                    <option value="">
                                        Pilih jenis kelamin
                                    </option>

                                    <option
                                        value="pria"
                                        @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'pria')
                                    >
                                        Laki-laki
                                    </option>

                                    <option
                                        value="wanita"
                                        @selected(old('jenis_kelamin', $user->jenis_kelamin) === 'wanita')
                                    >
                                        Perempuan
                                    </option>

                                </select>

                            </div>

                            @error('jenis_kelamin')
                                <small class="profile-error-message">
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>


                        {{-- HAK AKSES --}}

                        <div class="col-12 col-md-6">

                            <label for="hak_akses" class="profile-label">
                                Hak Akses
                            </label>

                            <div class="input-group profile-input">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </span>

                                <input
                                    type="text"
                                    id="hak_akses"
                                    class="form-control profile-readonly"
                                    value="{{ ucfirst($user->hak_akses ?? 'Pengguna') }}"
                                    readonly
                                >

                            </div>

                            <small class="profile-field-help">
                                Hak akses hanya dapat diubah melalui pengaturan
                                administrator.
                            </small>

                        </div>


                        {{-- FOTO PROFIL --}}

                        <div class="col-12">

                            <label for="profile" class="profile-label">
                                Foto Profil
                            </label>

                            <div class="profile-upload-wrapper">

                                <div class="profile-upload-icon">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>

                                <div class="profile-upload-content">

                                    <input
                                        type="file"
                                        name="profile"
                                        id="profile"
                                        class="form-control profile-file @error('profile') is-invalid @enderror"
                                        accept="image/jpeg,image/png,image/webp"
                                    >

                                    <small class="profile-field-help">
                                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                                        Kosongkan jika tidak ingin mengganti foto.
                                    </small>

                                </div>

                            </div>

                            @error('profile')
                                <small class="profile-error-message">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i>
                                    {{ $message }}
                                </small>
                            @enderror

                        </div>

                    </div>


                    {{-- FOOTER FORM --}}

                    <div class="profile-form-footer">

                        <p class="profile-form-reminder">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Pastikan informasi yang kamu masukkan sudah benar.
                        </p>

                        <div class="profile-form-actions">

                            <button
                                type="reset"
                                class="btn profile-reset-button"
                            >
                                <i class="fa-solid fa-rotate-left me-2"></i>
                                Reset
                            </button>

                            <button
                                type="submit"
                                class="btn profile-save-button"
                            >
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                Simpan Perubahan
                            </button>

                        </div>

                    </div>

                </form>

            </section>

        </div>

    </div>

</div>

@endsection