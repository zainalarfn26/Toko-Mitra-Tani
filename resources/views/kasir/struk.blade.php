<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Struk Pembayaran</title>
  <style>
    body { font-family: monospace; font-size: 14px; }
    .struk { width: 300px; margin: 0 auto; }
    .center { text-align: center; }
    .line { border-top: 1px dashed #000; margin: 10px 0; }
  </style>
</head>
<body onload="window.print()">
  <div class="struk">
    <div class="center">
      <h3>Toko Mitra Tani</h3>
      <p>Jl. Sejahtera No. 123</p>
    </div>

    <div class="line"></div>

    <p>No Transaksi: {{ $transaction->id }}</p>
    <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
    <p>Kasir: {{ $transaction->user->name }}</p>

    <div class="line"></div>

    @foreach ($transaction->detailTransactions as $detail)
      <p>{{ $detail->product->name }} x {{ $detail->quantity }}</p>
      <p style="text-align: right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
    @endforeach

    <div class="line"></div>

    <p>Total: Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
    <p>Dibayar: Rp{{ number_format($transaction->paid_amount, 0, ',', '.') }}</p>
    <p>Kembali: Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</p>

    <div class="center">
      <p>Terima kasih!</p>
    </div>
  </div>
</body>
</html>
