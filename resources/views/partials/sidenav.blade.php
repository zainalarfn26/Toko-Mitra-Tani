@php
$menus = [
    [
        'name' => 'Dashboard',
        'url' => route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'kasir.dashboard'),
        'route' => auth()->user()->role === 'admin' ? 'admin.dashboard' : 'kasir.dashboard',
        'icon' => 'fas fa-tachometer-alt text-primary',
        'roles' => ['admin', 'kasir'],
    ],

        // === Menu khusus Admin ===
    [
        'group' => 'Manajemen',
        'roles' => ['admin'],
    ],
    [
        'name' => 'Kelola Kasir',
        'url' => route('admin.kasir.index'),
        'route' => 'admin.kasir.index',
        'icon' => 'fas fa-users text-success',
        'roles' => ['admin'],
    ],
    [
        'name' => 'Kelola Produk',
        'url' => route('admin.produk.index'),
        'route' => 'admin.produk.index',
        'icon' => 'fas fa-boxes text-warning',
        'roles' => ['admin'],
    ],
    [
        'name' => 'Laporan Penjualan',
        'url' => route('admin.laporan.index'),
        'route' => 'admin.laporan.index',
        'icon' => 'fas fa-chart-line text-danger',
        'roles' => ['admin'],
    ],

    // === Menu khusus Kasir ===
    [
        'group' => 'Transaksi',
        'roles' => ['kasir'],
    ],
    [
        'name' => 'Transaksi Penjualan',
        'url' => route('kasir.transaksi.index'),
        'route' => 'kasir.transaksi.index',
        'icon' => 'fas fa-cash-register text-info',
        'roles' => ['kasir'],
    ],

    [
        'group' => 'Akun',
        'roles' => ['admin', 'kasir']
    ],
    [
        'name' => 'Logout',
        'url' => route('logout'),
        'icon' => 'fas fa-sign-out-alt text-danger',
        'roles' => ['admin', 'kasir'],
        'is_logout' => true,
    ],
];
@endphp

<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'kasir.dashboard') }}">
            <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm rounded-circle bg-gradient-primary shadow text-center me-2">
                    <i class="fas fa-store text-white opacity-10"></i>
                </div>
                <span class="font-weight-bold text-primary">Mitra Tani</span>
            </div>
        </a>
    </div>

    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @foreach ($menus as $menu)
                {{-- Group Title --}}
                @if (isset($menu['group']) && in_array(auth()->user()->role, $menu['roles']))
                    <li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">
                            {{ $menu['group'] }}
                        </h6>
                    </li>
                @elseif (in_array(auth()->user()->role, $menu['roles']))
                    @php
                        $isActive = isset($menu['route']) && request()->routeIs($menu['route']);
                    @endphp
                    <li class="nav-item">
                        @if (!empty($menu['is_logout']))
                            <form id="logout-form" action="{{ $menu['url'] }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <a href="#" class="nav-link text-dark"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="{{ $menu['icon'] }} text-sm"></i>
                                </div>
                                <span class="nav-link-text ms-1">{{ $menu['name'] }}</span>
                            </a>
                        @else
                            <a class="nav-link {{ $isActive ? 'active bg-gradient-primary text-white' : 'text-dark' }}"
                               href="{{ $menu['url'] }}">
                                <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="{{ $menu['icon'] }} text-sm"></i>
                                </div>
                                <span class="nav-link-text ms-1">{{ $menu['name'] }}</span>
                            </a>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>
