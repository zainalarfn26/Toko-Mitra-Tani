@extends('layouts.dashboard', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('partials.topnav', ['title' => 'Laporan Penjualan'])

  <div class="container-fluid py-4">
    <div class="card shadow-sm">
      <div class="card-header bg-gradient-success text-white">
        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Data Laporan Penjualan</h6>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-3">
          <table class="table table-hover table-striped align-middle">
            <thead class="table-light">
              <tr>
                <th class="text-center">No</th>
                <th>Tanggal & Waktu</th>
                <th>Kasir</th>
                <th class="text-end">Total Transaksi</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($transactions as $trx)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>
                    <i class="far fa-calendar-alt me-1"></i>
                    {{ $trx->created_at->format('d/m/Y') }}
                    <br>
                    <small class="text-muted">
                      <i class="far fa-clock me-1"></i>{{ $trx->created_at->format('H:i') }}
                    </small>
                  </td>
                  <td>
                    <i class="fas fa-user-circle me-1"></i>
                    {{ $trx->user->name }}
                  </td>
                  <td class="text-end">
                    <strong class="text-success">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</strong>
                  </td>
                  <td class="text-center">
                    <span class="badge bg-success">
                      <i class="fas fa-check"></i> Selesai
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada transaksi.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
            @if($transactions->isNotEmpty())
            <tfoot class="table-light">
              <tr>
                <th colspan="3" class="text-end">Total Keseluruhan:</th>
                <th class="text-end">
                  <strong class="text-primary">
                    Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}
                  </strong>
                </th>
                <th></th>
              </tr>
            </tfoot>
            @endif
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
