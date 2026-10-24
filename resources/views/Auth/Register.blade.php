<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | KIRAAI</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth/login.css', 'resources/css/auth/register.css'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="auth-login-body">

    <main class="auth-login-page auth-register-page">

        <div class="auth-login-card">

            {{-- Panel Kiri --}}
            <section class="auth-login-intro">

                <a href="/" class="auth-brand">
                    <span class="auth-brand-icon">
                        <img src="{{ asset('img/LogoKiraAI.jpeg') }}" alt="Logo KiraAI">
                    </span>

                    <span class="auth-brand-text">
                        <strong>kira<span>AI</span></strong>
                        <small>SMART FINANCE</small>
                    </span>
                </a>

                <div class="auth-intro-content">

                    <span class="auth-eyebrow">
                        MULAI ATUR KEUANGANMU
                    </span>

                    <h1>
                        Langkah kecil hari ini,
                        <span>masa depan lebih baik.</span>
                    </h1>

                    <p>
                        Buat akun kiraAI dan mulai kelola pemasukan,
                        pengeluaran, serta laporan pemasuakan dan pengeluaran.
                    </p>

                    <div class="auth-feature">
                        <span class="auth-feature-icon">
                            <i class="fa-solid fa-chart-pie"></i>
                        </span>

                        <div>
                            <strong>Pencatatan lebih teratur</strong>
                            <small>Pantau pergerakan uangmu dengan mudah.</small>
                        </div>
                    </div>

                    <div class="auth-feature">
                        <span class="auth-feature-icon">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </span>

                        <div>
                            <strong>Bantuan AI</strong>
                            <small>Tanya AI mengenai keuangan mu.</small>
                        </div>

                    </div>

                </div>

                <div class="auth-intro-footer">
                    Catat dengan bijak. Kelola dengan cerdas.
                </div>

            </section>


            {{-- Panel Kanan: Form Register --}}
            <section class="auth-login-form-panel">

                <div class="auth-form-container">

                    <div class="auth-form-heading">
                        <span class="auth-form-label">
                            BERGABUNG DENGAN kiraAI
                        </span>

                        <h2>Buat akun baru</h2>

                        <p>
                            Lengkapi data berikut untuk mulai menggunakan BERGABUNG DENGAN kiraAI
                            .
                        </p>
                    </div>

                    {{-- Notifikasi --}}
                    @if (session('success'))
                        <div class="alert alert-success small rounded-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger small rounded-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger small rounded-3 mb-3 text-warning">
                            Periksa kembali data yang kamu masukkan.
                        </div>
                    @endif

                    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="row g-3">

                            {{-- Nama --}}
                            <div class="col-12 mb-3">
                                <label for="nama" class="auth-input-label">
                                    Nama Lengkap
                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-user"></i>
                                    </span>

                                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}"
                                        class="form-control" placeholder="Masukkan nama lengkap" autocomplete="name"
                                        style="outline: none;" required>
                                </div>

                                @error('nama')
                                    <small class="text-danger d-block mt-1" style="color:red;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-12 mb-3">
                                <label for="email" class="auth-input-label">
                                    Alamat Email
                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>

                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="form-control" placeholder="nama@email.com" autocomplete="email"
                                        style="outline: none;" required>
                                </div>

                                @error('email')
                                    <small class="text-danger d-block mt-1" style="color:red;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-12 mb-3">
                                <label for="jenis_kelamin" class="auth-input-label">
                                    Jenis Kelamin
                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-venus-mars"></i>
                                    </span>

                                    <select name="jenis_kelamin" id="jenis_kelamin"
                                        class="form-select border-0 shadow-none" style="outline: none;" required>
                                        <option value="">
                                            Pilih jenis kelamin
                                        </option>

                                        <option value="pria" @selected(old('jenis_kelamin') === 'pria')>
                                            Laki-laki
                                        </option>

                                        <option value="wanita" @selected(old('jenis_kelamin') === 'wanita')>
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                @error('jenis_kelamin')
                                    <small class="text-danger d-block mt-1" style="color:red;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Foto Profil Opsional --}}
                            <div class="col-12 mb-3">
                                <label for="profile" class="auth-input-label">
                                    Foto Profil
                                    <span class="text-secondary fw-normal">
                                        (Opsional)
                                    </span>
                                </label>

                                <input type="file" name="profile" id="profile"
                                    class="form-control auth-register-file" accept="image/*" style="outline: none;">

                                @error('profile')
                                    <small class="text-danger d-block mt-1" style="color:red;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-12 mb-3">
                                <label for="password" class="auth-input-label">
                                    Password
                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>

                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Buat password" autocomplete="new-password"
                                        style="outline: none;" required>
                                </div>

                                @error('password')
                                    <small class="text-danger d-block mt-1" style="color:red;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-12 mb-3">
                                <label for="password_confirmation" class="auth-input-label">
                                    Konfirmasi Password
                                </label>

                                <div class="input-group auth-input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </span>

                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Ulangi password"
                                        autocomplete="new-password" style="outline: none;" required>
                                </div>
                            </div>

                            {{-- Tombol Daftar --}}
                            <div class="col-12 mb-3 mt-4">
                                <button type="submit" class="auth-login-button">
                                    Buat Akun
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>

                        </div>
                    </form>

                    <div class="auth-register-link">
                        Sudah mempunyai akun?
                        <a href="{{ url('/login') }}">Masuk sekarang</a>
                    </div>

                    <div class="auth-security-note">
                        <i class="fa-solid fa-shield-halved"></i>
                        Data akunmu digunakan untuk pencatatan keuangan.
                    </div>

                </div>
            </section>

        </div>

    </main>
    @include('sweetalert::alert')
</body>

</html>
