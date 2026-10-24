<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">

    @vite(['resources/js/app.js', 'resources/js/dashboard/responsive.js', 'resources/css/dashboard/style.css', 'resources/css/AI/AI-Style.css', 'resources/js/ScanNota/scan.js', 'resources/js/dashboard/grafik1.js', 'resources/js/ScanNota/scan.js', 'resources/css/dashboard/grafik.css'])

    @yield('link_css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    @livewireStyles
</head>

<body>
    <div class="bg-dashboard d-flex p-2 min-vh-100 overflow-x-hidden gap-2" id="container-dashboard">
        {{-- Navigasi --}}
        <aside class="navigasi px-2 rounded-4 text-nowrap" id="navigasis">
            <section class="d-flex flex-column gap-2">

                <section class="border-bottom pb-3 d-flex flex-column gap-2 mt-3"
                    style="border-color: rgba(255,255,255,0.15) !important;">


                    <div class="d-flex align-items-center gap-2">

                        {{-- Logo KiraAI berbentuk bulat --}}
                        <div class="d-flex align-items-center justify-content-center rounded-circle shadow-sm overflow-hidden"
                            style="
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            padding: 2px;
            background: #ffffff;
            border: 1px solid rgba(69, 200, 120, 0.4);
        ">
                            <img src="{{ asset('img/LogoKiraAI.jpeg') }}" alt="Logo KiraAI"
                                class="w-100 h-100 rounded-circle" style="object-fit: cover; object-position: center;">
                        </div>

                        {{-- Nama aplikasi --}}
                        <div>
                            <h3 class="mb-0 fw-bold text-white" style="font-size: 21px; letter-spacing: 1.5px;">
                                KIRA<span style="color: #45c878;">AI</span>
                            </h3>

                            <span class="small"
                                style="
                font-size: 9px;
                letter-spacing: 1.5px;
                color: #8fbea0;
            ">
                                PERSONAL FINANCE
                            </span>
                        </div>

                    </div>


                    <p class="text-white-50 small mb-0" style="font-size: 11px; line-height: 1.7;">
                        Sistem Manajemen Keuangan
                        <span class="fw-semibold" style="color: #45c878;">Pribadi</span>
                    </p>

                </section>

                <nav>
                    <ul class="w-full list-unstyled text-nowrap">
                        <section class="mb-3 d-flex flex-column text-nowrap gap-2">
                            <li class="fw-semibold text-white-50 small"> Home</li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('dashboard.*') ? 'text-white fw-bold bg-active' : '' }} rounded-2 small list">
                                <a href="{{ route('dashboard.home') }}"
                                    class="text-decoration-none text-white d-flex gap-3 align-items-center fw-semibold">
                                    <span><i class="fa-solid fa-house fs-5"></i></span>Dashboard</a>
                            </li>
                        </section>
                        <section class="mb-3 d-flex flex-column gap-2">
                            <li class="fw-semibold text-white-50 small"> Aksi</li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('pemasukan.*') ? 'text-white fw-bold bg-active' : '' }} rounded-2 small list">
                                <a href="{{ route('pemasukan.index') }}"
                                    class="text-decoration-none text-white  d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-down fs-5"></i></span>Pemasukan</a>
                            </li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('pengeluaran.*') ? 'text-white fw-bold bg-active' : '' }}  rounded-2 small list">
                                <a href="{{ route('pengeluaran.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-up fs-5"></i></span>Pengeluaran</a>
                            </li>
                            {{-- <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('tabungan.*') ? 'text-white fw-bold bg-active' : '' }} rounded-2 small list">
                                <a href="{{ route('tabungan.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center"> <span><i
                                            class="fa-solid fa-money-bill-wheat fs-5"></i></span>Tabungan</a>
                            </li> --}}
                        </section>
                        <section class="mb-3 d-flex flex-column gap-2">
                            <li class="fw-semibold text-white-50 small"> Kategori</li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('kategoripemasukan.*') ? 'text-white fw-bold bg-active' : '' }} rounded-2 small list">
                                <a href="{{ route('kategoripemasukan.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-down fs-5"></i></span>Kategori Pemasukan</a>
                            </li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('kategoripengeluaran.*') ? 'text-white fw-bold bg-active' : '' }}  rounded-2 small list">
                                <a href="{{ route('kategoripengeluaran.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-up fs-5"></i></span>Kategori Pengeluaran</a>
                            </li>
                        </section>
                        <section class="mb-3 d-flex flex-column gap-2">
                            <li class="fw-semibold text-white-50 small"> Laporan</li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('laporanpemasukan.*') ? 'text-white fw-bold bg-active' : '' }}   rounded-2 small list">
                                <a href="{{ route('laporanpemasukan.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-down fs-5"></i></span>Laporan Pemasukan</a>
                            </li>
                            <li
                                class="w-100 px-2 py-2 {{ request()->routeIs('laporanpengeluaran.*') ? 'text-white fw-bold bg-active' : '' }}  rounded-2 small list">
                                <a href="{{ route('laporanpengeluaran.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-up fs-5"></i></span>Laporan Pengeluaran</a>
                            </li>
                        </section>
                        <section class="mb-3 d-flex flex-column gap-2">
                            <li class="fw-semibold text-white-50 small"> Profile</li>
                            <li
                                class="w-100 px-2 py-2  rounded-2 {{ request()->routeIs('profile.*') ? 'text-white fw-bold bg-active' : '' }} small list">
                                <a href="{{ route('profile.index') }}"
                                    class="text-decoration-none text-white d-flex gap-3 fw-semibold align-items-center">
                                    <span><i class="fa-solid fa-circle-user fs-5"></i></span>Profile Akun</a>
                            </li>
                        </section>

                    </ul>
                </nav>
            </section>
        </aside>
        <div class="bg-white w-100 rounded-4 container-main">



            <header
                class="w-100 shadow-sm py-2 px-3 d-flex justify-content-between align-items-center position-sticky top-0 bg-white"
                style="z-index: 99; min-height: 65px;">

                {{-- Tombol Toggle Sidebar --}}
                <section>
                    <button class="btn btn-light border rounded-3 d-flex align-items-center justify-content-center p-2"
                        id="btn-colapses" type="button" style="width: 38px; height: 38px;"
                        aria-label="Buka atau tutup sidebar">
                        <i class="fa-solid fa-bars fs-5 text-secondary"></i>
                    </button>
                </section>

                {{-- Profil dan Logout --}}
                @auth
                    <section class="d-flex align-items-center gap-3">

                        {{-- Informasi Akun Pengguna (Disusun Menumpuk ke Bawah) --}}
                        <a href="{{ route('profile.index') }}" wire:navigate style="text-decoration: none;">
                            <div class="d-flex flex-column align-items-center text-center border-end pe-3 pt-2"
                                style="border-color: #dee2e6 !important;">

                                {{-- Foto Profil / Dummy di bagian atas --}}
                                @if (auth()->user()->profile)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url(auth()->user()->profile) }}"
                                        alt="Foto profil {{ auth()->user()->nama }}" class="rounded-circle border mb-1"
                                        style="width: 38px; height: 38px; object-fit: cover; border-color: #dee2e6 !important;">
                                @else
                                    {{-- Gambar Profile Dummy Netral (Warna Abu-abu Biasa) --}}
                                    <div class="rounded-circle d-flex align-items-center justify-content-center border bg-light mb-1 text-secondary"
                                        style="width: 38px; height: 38px; flex-shrink: 0; border-color: #dee2e6 !important;">
                                        <i class="fa-solid fa-user" style="font-size: 14px;"></i>
                                    </div>
                                @endif

                                {{-- Keterangan Nama dan Email di bagian bawah --}}
                                <div class="leading-tight">
                                    <p class="mb-0 fw-bold text-dark" style="font-size: 14px; line-height: 1.1;">
                                        {{ auth()->user()->name }}
                                    </p>

                                    <small class="text-muted d-block" style="font-size: 10px; line-height: 1.1;">
                                        {{ auth()->user()->email }}
                                    </small>
                                </div>
                            </div>
                        </a>

                        {{-- Tombol Logout Netral (Normal: Abu-abu/Gelap Biasa, Hover: Merah Danger) --}}

                        <button data-bs-toggle="modal" data-bs-target="#popupkeluar" type="button"
                            class="btn btn-danger  border border-secondary-subtle rounded-3 fw-semibold d-flex align-items-center gap-2 px-3 custom-btn-logout"
                            style="font-size: 12px; height: 38px; transition: all 0.2s ease-in-out;">

                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span class="d-none d-md-inline">Logout</span>
                        </button>


                    </section>
                @endauth

            </header>

            <main class="bg-white min-vh-100">
                @yield('content')
                {{-- BUTTON CHAT-AI --}}
                @livewire('a-i.chatbot-widget')
                {{-- ============== --}}
            </main>
        </div>
        {{-- Header --}}
        {{-- Main --}}
    </div>
    @livewireScripts
    @include('sweetalert::alert')
    {{-- Modal Pop-Up Keluar --}}
    @include('Layouts.partials.modalpopupkeluar')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@8.3.1/dist/apexcharts.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
