@extends('layouts.dashboard', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('partials.topnav', ['title' => 'Transaksi'])

  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-body">
        <form action="{{ route('kasir.transaksi.store') }}" method="POST">
          @csrf
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="product_id">Pilih Produk</label>
              <select name="product_id" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }} - Rp{{ number_format($product->price, 0, ',', '.') }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="quantity">Jumlah</label>
              <input type="number" name="quantity" class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-primary w-100">Tambah ke Transaksi</button>
            </div>
          </div>
        </form>

        @if (session('cart'))
          <hr>
          <form action="{{ route('kasir.transaksi.store') }}" method="POST">
            @csrf
            <table class="table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>Harga Satuan</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @php $total = 0; @endphp
                @foreach (session('cart') as $item)
                  @php $subtotal = $item['quantity'] * $item['price']; $total += $subtotal; @endphp
                  <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="row mt-3">
              <div class="col-md-6">
                <label>Total Bayar</label>
                <input type="text" class="form-control" value="Rp{{ number_format($total, 0, ',', '.') }}" readonly>
              </div>
              <div class="col-md-3">
                <label>Dibayar</label>
                <input type="number" name="paid_amount" class="form-control" min="{{ $total }}" required>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Simpan & Cetak Struk</button>
              </div>
            </div>
          </form>
        @endif
      </div>
    </div>
  </div>
@endsection
