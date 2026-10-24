
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KIRAAI</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/auth/login.css'
    ])

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="auth-login-body">

<main class="auth-login-page">

    <div class="auth-login-card">

        {{-- Panel Kiri --}}
        <section class="auth-login-intro">

            <a href="/" class="auth-brand">
                <span class="auth-brand-icon">
                    <i class="fa-solid fa-wallet"></i>
                </span>

                <span>
                    <strong>kira<span>AI</span></strong>
                    <small>SMART FINANCE</small>
                </span>
            </a>

            <div class="auth-intro-content">
                <span class="auth-eyebrow">
                    KEUANGAN LEBIH TERATUR
                </span>

                <h1>
                    Kelola uangmu,
                    <span>rencanakan masa depanmu.</span>
                </h1>

                <p>
                    Catat pemasukan, pantau pengeluaran dalam satu tempat.
                </p>

                <div class="auth-feature">
                    <span class="auth-feature-icon">
                        <i class="fa-solid fa-chart-pie"></i>
                    </span>

                    <div>
                        <strong>Pantau keuangan dengan mudah</strong>
                        <small>Semua catatan keuanganmu lebih terorganisir.</small>
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


        {{-- Panel Kanan: Form Login --}}
        <section class="auth-login-form-panel">

            <div class="auth-form-container">

                <div class="auth-form-heading">
                    

                    <span class="auth-form-label">SELAMAT DATANG</span>

                    <h2>Masuk ke akunmu</h2>

                    <p>
                        Masukkan email dan password untuk melanjutkan.
                    </p>
                </div>

                {{-- Notifikasi --}}
                @if (session('success'))
                    <div class="alert alert-success small rounded-3">
                        <i class="fa-solid fa-circle-check me-1"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger small rounded-3">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-danger fw-semibold small rounded-3 pb-1 text-danger" style="color: rgb(240, 168, 0); font-size: 12px;">
                        <span class="d-flex gap-2 align-items-center px-2 py-1 rounded-4 bg-warning">
                        <i class="fa-solid fa-triangle-exclamation me-1 mb-4 bg-warning"></i>
                        Email atau password tidak valid.
                        </span>
                    </div>
                @endif

                <form action="{{route('login.auth')}}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="auth-input-label">
                            Alamat Email
                        </label>

                        <div class="input-group auth-input-group">
                            <span class="input-group-text">
                                <i class="fa-regular fa-envelope"></i>
                            </span>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="nama@email.com"
                                autocomplete="username"
                                required
                                style="outline: none; width: 100%:"
                                autofocus
                            >
                        </div>

                        @error('email')
                            <small class="text-danger d-block mt-2">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="auth-input-label">
                            Password
                        </label>

                        <div class="input-group auth-input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                  style="outline: none;"
                                required
                            >
                        </div>

                        @error('password')
                            <small class="text-danger d-block mt-2">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Ingat saya --}}
                    <div class="form-check auth-remember mb-4">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            class="form-check-input"
                            id="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Tombol Login --}}
                    <button type="submit" class="auth-login-button">
                        Masuk Sekarang
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

                <div class="auth-register-link">
                    Belum mempunyai akun?
                    <a href="{{ route('register.index') }}">Daftar sekarang</a>
                </div>

                <div class="auth-security-note">
                    <i class="fa-solid fa-shield-halved"></i>
                    Kelola keuanganmu dengan lebih teratur.
                </div>

            </div>

        </section>

    </div>

</main>
 @include('sweetalert::alert')
</body>
</html>
