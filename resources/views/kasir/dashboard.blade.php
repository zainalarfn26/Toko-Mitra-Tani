@extends('layouts.dashboard')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="text-dark">Halo, {{ Auth::user()->name }}</h4>
            <p class="text-muted">Ini adalah ringkasan aktivitasmu sebagai kasir.</p>
        </div>
    </div>

    <!-- Cards -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Total Transaksi Saya</h6>
                    <h3 class="fw-bold">{{ $totalTransaksiSaya }}</h3>
                    <i class="fas fa-receipt text-success fa-2x float-end"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Transaksi Hari Ini</h6>
                    <h3 class="fw-bold">{{ $transaksiHariIni }}</h3>
                    <i class="fas fa-calendar-day text-info fa-2x float-end"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Transaksi Terakhir</h6>
                    <h5 class="mb-0">
                        {{ $transaksiTerakhir ? 'Rp ' . number_format($transaksiTerakhir->total_amount) : 'Belum Ada' }}
                    </h5>
                    <small class="text-muted">{{ $transaksiTerakhir ? $transaksiTerakhir->created_at->format('d M Y H:i') : '' }}</small>
                    <i class="fas fa-clock text-warning fa-2x float-end"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Grafik Transaksi - 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="grafikKasir" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikKasir').getContext('2d');
    const grafikKasir = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels->toArray()) !!},
            datasets: [{
                label: 'Transaksi Saya',
                data: {!! json_encode($data->toArray()) !!},
                backgroundColor: 'rgba(40, 167, 69, 0.5)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 2
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
