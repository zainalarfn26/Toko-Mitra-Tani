<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <title>
        Login | Mitra Tani - Sistem Kasir
    </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
</head>

<body class="">
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg blur border-radius-lg top-0 z-index-3 shadow position-absolute mt-4 py-2 w-100 start-0 end-0 mx-4">
                    <div class="container-fluid ps-2 pe-0">
                        <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="/">
                            Kasir Mitra Tani
                        </a>
                        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon mt-2">
                                <span class="navbar-toggler-bar-icon"></span>
                                <span class="navbar-toggler-bar-icon"></span>
                                <span class="navbar-toggler-bar-icon"></span>
                            </span>
                        </button>
                        <div class="collapse navbar-collapse" id="navigation">
                            <ul class="navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="/">
                                        <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                                        Home
                                    </a>
                                </li>
                                {{-- Jika ada halaman register atau lainnya --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link me-2" href="{{ route('register') }}">
                                        <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                                        Register
                                    </a>
                                </li> --}}
                            </ul>
                        </div>
                    </div>
                </nav>
                </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <div class="mb-3">
                                        <i class="fas fa-store fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="font-weight-bolder">Selamat Datang!</h4>
                                    <p class="mb-0">Masukkan email dan password Anda untuk login</p>
                                </div>
                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="fas fa-check-circle"></i> {{ session('status') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <form role="form" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope me-1"></i>Email
                                            </label>
                                            <input type="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                                            @error('email')
                                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                <i class="fas fa-lock me-1"></i>Password
                                            </label>
                                            <input type="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" id="password" name="password" required autocomplete="current-password">
                                            @error('password')
                                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                                            <label class="form-check-label" for="remember_me">Ingat saya</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    @if (Route::has('password.request'))
                                        <p class="mb-4 text-sm mx-auto">
                                            <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Lupa password?</a>
                                        </p>
                                    @endif
                                    {{-- Jika ada halaman register --}}
                                    {{-- <p class="mb-4 text-sm mx-auto">
                                        Tidak punya akun?
                                        <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Daftar</a>
                                    </p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <span class="mask bg-gradient-success opacity-3"></span>
                                <div class="text-white position-relative">
                                    <i class="fas fa-store fa-5x mb-4"></i>
                                    <h4 class="text-white font-weight-bolder">"Sistem Kasir Mitra Tani"</h4>
                                    <p class="text-white">Kelola produk, transaksi, dan laporan penjualan dengan mudah dan efisien.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
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
    <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.0.4') }}"></script>
</body>

</html>