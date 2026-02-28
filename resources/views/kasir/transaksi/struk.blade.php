<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian #{{ $transaction->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }
        .container-struk {
            max-width: 300px; /* Lebar struk standar untuk thermal printer */
            margin: auto;
            border: 1px dashed #ccc; /* Border opsional untuk tampilan di browser */
            padding: 15px;
        }
        h4, h5, h6 {
            text-align: center;
            margin-bottom: 5px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .header p, .footer p {
            margin: 2px 0;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .item-table th, .item-table td {
            padding: 3px 2px; /* Tambah sedikit padding horizontal */
            text-align: left;
            vertical-align: top; /* Pastikan teks rata atas jika ada baris panjang */
        }

        /* Styling spesifik untuk header kolom */
        .item-table th:nth-child(1) { width: 45%; text-align: left; } /* Produk */
        .item-table th:nth-child(2) { width: 15%; text-align: center; } /* Qty */
        .item-table th:nth-child(3) { width: 20%; text-align: right; } /* Harga */
        .item-table th:nth-child(4) { width: 20%; text-align: right; } /* Subtotal */

        /* Styling spesifik untuk data kolom */
        .item-table td:nth-child(1) { text-align: left; } /* Produk */
        .item-table td:nth-child(2) { text-align: center; } /* Qty */
        .item-table td:nth-child(3) { text-align: right; } /* Harga */
        .item-table td:nth-child(4) { text-align: right; } /* Subtotal */

        .summary-row {
            text-align: right;
            padding-top: 5px;
        }
        .summary-row p {
            margin: 2px 0;
        }
        .summary-row strong {
            font-size: 16px;
        }
        .text-center {
            text-align: center;
        }
        .dashed-line {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }
        @media print {
            body {
                margin: 0;
            }
            .container-struk {
                max-width: 80mm; /* Atur lebar sesuai kertas thermal printer */
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            /* Menyesuaikan font-size untuk cetak jika perlu */
            body, .item-table th, .item-table td, .summary-row p {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container-struk">
        <div class="header">
            <h4>TOKO MITRA TANI</h4>
            <p>Jl. Karasan RT.24 RW.05</p>
            <p>Kartoharjo, Magetan</p>
            <p>Telp: 0812-3456-7890</p>
        </div>
        <div class="dashed-line"></div>
        <p>Tanggal: {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d M Y H:i') }}</p>
        <p>No. Transaksi: #{{ $transaction->id }}</p>
        <p>Kasir: {{ $transaction->user->name }}</p>
        <div class="dashed-line"></div>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->detailTransactions as $detail)
                    <tr>
                        <td>{{ $detail->product->name }}</td>
                        <td>{{ $detail->quantity }}</td>
                        <td>{{ number_format($detail->price_per_unit, 0, ',', '.') }}</td>
                        <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="dashed-line"></div>

        <div class="summary-row">
            <p>Total: Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
            <p>Dibayar: Rp{{ number_format($transaction->paid_amount, 0, ',', '.') }}</p>
            <p>Kembali: Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</p>
        </div>
        <div class="dashed-line"></div>
        <div class="footer">
            <p>Terima kasih telah berbelanja!</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        </div>
    </div>

    <div class="text-center mt-4 no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
        <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-secondary">Kembali ke Transaksi</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>