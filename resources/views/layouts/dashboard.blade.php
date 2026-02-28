<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>
        Mitra Tani - Sistem Kasir
    </title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />

    <style>
        /* Warna Primer Hijau */
        :root {
            --bs-primary: #28a744; /* Hijau utama (mirip Bootstrap Success) */
            --bs-primary-rgb: 40, 167, 68;
            --bs-gradient-primary: linear-gradient(310deg, #28a744 0%, #2dc26b 100%); /* Gradasi hijau */
        }

        /* Override kelas Bootstrap dan Argon Dashboard */
        .bg-gradient-primary {
            background-image: var(--bs-gradient-primary) !important;
        }

        .bg-primary {
            background-color: var(--bs-primary) !important;
        }

        .text-primary {
            color: var(--bs-primary) !important;
        }

        .btn-primary {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .btn-primary:hover, .btn-primary:focus { /* Tambah :focus agar konsisten */
            background-color: #2dc26b !important; /* Hijau sedikit lebih terang saat hover/focus */
            border-color: #2dc26b !important;
        }

        /* Sidenav active item (biasanya menggunakan bg-gradient-dark, kita timpa menjadi hijau) */
        .sidenav .navbar-nav .nav-item .nav-link.active {
            background-image: var(--bs-gradient-primary) !important;
            color: #fff !important; /* Pastikan teks putih untuk kontras */
        }

        /* Override elemen spesifik di layout dashboard (background biru di bagian atas) */
        .min-height-300.bg-primary {
            background-color: var(--bs-primary) !important;
            background-image: var(--bs-gradient-primary) !important; /* Gunakan gradasi juga */
        }
        
        /* Warna untuk header card utama yang biasanya pakai bg-primary (seperti di transaksi kasir, produk, kasir) */
        .card .card-header.bg-primary {
            background-color: var(--bs-primary) !important;
        }

        /* Warna untuk header tabel kustom (table-custom-header) */
        .table-custom-header thead th {
            background-color: var(--bs-primary) !important; /* Warna hijau primer untuk header tabel */
            color: #fff !important; /* Teks putih untuk kontras */
            border-bottom: 1px solid rgba(255,255,255,0.3) !important; /* Border bawah */
        }
        .table-custom-header thead th p,
        .table-custom-header thead th span { /* Pastikan teks di dalam p/span juga putih */
            color: #fff !important;
        }
    </style>

</head>

<body class="{{ $class ?? '' }}">
    {{-- Ini adalah blok kondisi untuk tampilan Guest (belum login) --}}
    @guest
        @yield('content')
    @endguest

    {{-- Ini adalah blok kondisi untuk tampilan User yang sudah Login --}}
    @auth
        {{-- Ini untuk halaman non-dashboard seperti login/register/recover password yang masih menggunakan layout ini --}}
        @if (in_array(request()->route()->getName(), ['sign-in-static', 'sign-up-static', 'login', 'register', 'recover-password', 'rtl', 'virtual-reality']))
            @yield('content')
        @else
            {{-- Ini adalah kode untuk layout dashboard yang sebenarnya setelah login --}}
            @if (!in_array(request()->route()->getName(), ['profile', 'profile-static']))
                {{-- Background biru di bagian atas dashboard --}}
                <div class="min-height-300 bg-primary position-absolute w-100"></div>
            @elseif (in_array(request()->route()->getName(), ['profile-static', 'profile']))
                {{-- Background khusus untuk halaman profil --}}
                <div class="position-absolute w-100 min-height-300 top-0"
                    style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/profile-layout-header.jpg'); background-position-y: 50%;">
                    <span class="mask bg-primary opacity-6"></span>
                </div>
            @endif

            {{-- Include Sidebar (sidenav) --}}
            @include('partials.sidenav')

            {{-- Main Content Area --}}
            <main class="main-content position-relative border-radius-lg">
                {{-- Konten dari setiap view Blade akan masuk di sini --}}
                @yield('content')
            </main>

            {{-- Include Fixed Plugin / Settings button --}}
            @include('components.fixed-plugin')
        @endif
    @endauth

    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="{{ asset('assets/js/argon-dashboard.js') }}"></script>
    @stack('scripts')
    @stack('js') {{-- Tempat untuk custom JavaScript dari child views --}}
</body>

</html>