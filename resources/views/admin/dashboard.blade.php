@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="text-dark">Selamat Datang, {{ Auth::user()->name }}</h4>
            <p class="text-muted">Berikut ringkasan data hari ini</p>
        </div>
    </div>

    <!-- Cards Ringkasan -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Total Transaksi</h6>
                    <h3 class="fw-bold">{{ $totalTransaksi }}</h3>
                    <i class="fas fa-receipt text-primary fa-2x float-end"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Jumlah Kasir</h6>
                    <h3 class="fw-bold">{{ $totalKasir }}</h3>
                    <i class="fas fa-user-tie text-success fa-2x float-end"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted">Total Produk</h6>
                    <h3 class="fw-bold">{{ $totalProduk }}</h3>
                    <i class="fas fa-boxes text-warning fa-2x float-end"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Penjualan -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Grafik Penjualan - 1 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="penjualanChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('penjualanChart').getContext('2d');
    const penjualanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {!! json_encode($data) !!},
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
@endpush
