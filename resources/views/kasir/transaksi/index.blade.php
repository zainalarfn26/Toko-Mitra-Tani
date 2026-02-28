@extends('layouts.dashboard', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('partials.topnav', ['title' => 'Transaksi Penjualan'])

    <div class="container-fluid py-4">
        {{-- Alert untuk pesan sukses/error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            <div class="col-md-4">
                <div class="card mb-0 shadow-sm">
                    <div class="card-header py-3 bg-gradient-primary text-white text-center">
                        <h6 class="mb-0"><i class="fas fa-search me-2"></i>Cari Barang</h6>
                    </div>
                    <div class="card-body p-3">
                        <div>
                            <div class="mb-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan nama atau kode produk...">
                            </div>
                            <div id="searchResults" class="list-group">
                                {{-- Hasil pencarian akan dimuat di sini via JS --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-0 shadow-sm">
                    <div class="card-header py-3 bg-gradient-info text-white text-center">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Hasil Pencarian</h6>
                    </div>
                    <div class="card-body p-0 text-center">
                        <div class="table-responsive p-3" style="max-height: 280px; overflow-y: auto;">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">ID Barang</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7">Nama Barang</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Harga Jual</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    {{-- Produk akan dirender oleh JavaScript di sini --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 shadow-sm">
            <div class="card-header py-3 bg-gradient-success text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang Kasir</h6>
                <button class="btn btn-danger btn-sm" onclick="resetCart()">
                    <i class="fas fa-redo me-1"></i>RESET KERANJANG
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive p-3">
                    <table class="table align-items-center mb-0 table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3" style="width: 5%;">No</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7" style="width: 30%;">Nama Barang</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center" style="width: 15%;">Jumlah</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-end" style="width: 20%;">Harga Satuan</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-end" style="width: 20%;">Subtotal</th>
                                <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">
                            {{-- Item keranjang akan dimuat di sini via JS --}}
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-light border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-0">
                                <label for="totalAmountDisplay" class="form-label fw-bold mb-2">
                                    <i class="fas fa-calculator me-2"></i>Total Semua
                                </label>
                                <input type="text" class="form-control form-control-lg fw-bold text-success" id="totalAmountDisplay" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paidAmountInput" class="form-label fw-bold mb-2">
                                    <i class="fas fa-money-bill-wave me-2"></i>Dibayar
                                </label>
                                <input type="number" class="form-control form-control-lg" id="paidAmountInput" min="0" placeholder="Masukkan jumlah bayar" required>
                            </div>
                            <div class="mb-3">
                                <label for="changeAmountDisplay" class="form-label fw-bold mb-2" id="changeLabel">
                                    <i class="fas fa-coins me-2"></i>Kembali
                                </label>
                                <input type="text" class="form-control form-control-lg fw-bold" id="changeAmountDisplay" readonly>
                            </div>
                            <button class="btn btn-success btn-lg w-100 py-3" id="processTransactionBtn">
                                <i class="fas fa-print me-2"></i>Bayar & Cetak Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    let currentCart = {}; // Objek untuk menyimpan keranjang di frontend
    // Data produk dari backend, diubah menjadi objek yang bisa diakses berdasarkan ID
    const productsData = @json($products->keyBy('id')); 

    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi keranjang dari session di backend jika ada
        const initialCart = @json($cart);
        if (Object.keys(initialCart).length > 0) {
            currentCart = initialCart;
            renderCart();
        } else {
            // Jika keranjang kosong, disable tombol bayar
            document.getElementById('processTransactionBtn').disabled = true;
        }

        // Event listener untuk input pencarian produk (memanggil handleSearch)
        document.getElementById('searchInput').addEventListener('keyup', handleSearch);

        // Event listener untuk input jumlah bayar
        document.getElementById('paidAmountInput').addEventListener('keyup', calculateChange);
        document.getElementById('paidAmountInput').addEventListener('change', calculateChange);
        document.getElementById('paidAmountInput').addEventListener('input', calculateChange);

        // Event listener untuk tombol "Bayar & Cetak Struk"
        document.getElementById('processTransactionBtn').addEventListener('click', processTransaction);

        // handleSearch(); // <-- Dihapus/dikomentari agar hasil pencarian kosong di awal
    });

    // Fungsi untuk mengelola pencarian produk dan merender hasilnya
    function handleSearch() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const productTableBody = document.getElementById('productTableBody');
        productTableBody.innerHTML = ''; // Kosongkan hasil sebelumnya

        // Filter produk berdasarkan nama atau kode produk
        const filteredProducts = Object.values(productsData).filter(product =>
            product.name.toLowerCase().includes(searchTerm) ||
            (product.kode_product && product.kode_product.toLowerCase().includes(searchTerm))
        );

        // Render produk yang difilter
        if (filteredProducts.length > 0) {
            filteredProducts.forEach(product => {
                const row = `
                    <tr>
                        <td class="ps-3"><p class="text-xs font-weight-bold mb-0">${product.kode_product || '-'}</p></td>
                        <td><p class="text-xs font-weight-bold mb-0">${product.name}</p></td>
                        <td class="text-center"><p class="text-xs font-weight-bold mb-0">Rp${formatRupiah(product.price)}</p></td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <input type="number" class="form-control form-control-sm" style="width: 70px;" value="1" min="1" id="qty_${product.id}">
                                <button class="btn btn-success btn-sm" onclick="addToCart(${product.id})">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                productTableBody.innerHTML += row;
            });
        } else {
            productTableBody.innerHTML = `<tr><td colspan="4" class="text-center py-3"><em class="text-muted">Produk tidak ditemukan</em></td></tr>`;
        }
    }

    // Fungsi untuk menambah produk ke keranjang
    window.addToCart = function(productId) {
        const quantityInput = document.getElementById(`qty_${productId}`);
        let quantity = parseInt(quantityInput ? quantityInput.value : 1);

        if (isNaN(quantity) || quantity < 1) {
            // alert('Jumlah harus minimal 1.'); // Dihapus/dikomentari
            return;
        }

        const product = productsData[productId];

        if (!product) {
            // alert('Produk tidak ditemukan.'); // Dihapus/dikomentari
            return;
        }

        // Cek stok di frontend
        let currentQtyInCart = currentCart[productId] ? currentCart[productId].quantity : 0;
        if (product.stock < (currentQtyInCart + quantity)) {
            // alert(`Stok tidak cukup untuk ${product.name}. Stok tersedia: ${product.stock}. Di keranjang: ${currentQtyInCart}.`); // Dihapus/dikomentari
            return;
        }

        // Kirim permintaan AJAX ke backend untuk menambah ke session cart
        fetch('{{ route('kasir.transaksi.addToCart') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: quantity })
        })
        .then(response => {
            if (!response.ok) {
                // Jika respons bukan 2xx, baca body sebagai JSON untuk error message
                return response.json().then(errorData => { throw new Error(errorData.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentCart = data.cart; // Update keranjang dari respons backend
                renderCart();
                showNotification('Produk berhasil ditambahkan ke keranjang', 'success');

                // --- PERBAIKAN: Kosongkan input pencarian dan hasilnya ---
                document.getElementById('searchInput').value = '';
                handleSearch(); // Ini akan mengosongkan hasil pencarian
                // --- AKHIR PERBAIKAN ---

            } else if (data.error) {
                showNotification(data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang.', 'danger');
        });
    };

    // Fungsi untuk merender ulang keranjang
    function renderCart() {
        const cartTableBody = document.getElementById('cartTableBody');
        cartTableBody.innerHTML = '';
        let totalAmount = 0;
        let itemNo = 1;

        if (Object.keys(currentCart).length === 0) {
            cartTableBody.innerHTML = `<tr><td colspan="6" class="text-center">Keranjang belanja kosong.</td></tr>`;
            document.getElementById('totalAmountDisplay').value = `Rp${formatRupiah(0)}`; 
            document.getElementById('paidAmountInput').value = '';
            document.getElementById('changeAmountDisplay').value = `Rp${formatRupiah(0)}`;
            document.getElementById('changeAmountDisplay').className = 'form-control form-control-lg fw-bold';
            document.getElementById('changeLabel').innerHTML = '<i class="fas fa-coins"></i> Kembali';
            document.getElementById('changeLabel').className = 'form-label fw-bold';
            document.getElementById('processTransactionBtn').disabled = true;
            return;
        }

        for (const productId in currentCart) {
            const item = currentCart[productId];
            totalAmount += item.subtotal;

            const row = `
                <tr>
                    <td class="ps-3"><p class="text-xs font-weight-bold mb-0">${itemNo++}</p></td>
                    <td><p class="text-xs font-weight-bold mb-0">${item.name}</p></td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm d-inline-block" style="width: 80px;"
                               value="${item.quantity}" min="1"
                               onchange="updateCartQuantity(${item.id}, this.value)">
                    </td>
                    <td class="text-end"><p class="text-xs font-weight-bold mb-0">Rp${formatRupiah(item.price)}</p></td>
                    <td class="text-end"><p class="text-xs font-weight-bold mb-0 text-success">Rp${formatRupiah(item.subtotal)}</p></td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            cartTableBody.innerHTML += row;
        }

        document.getElementById('totalAmountDisplay').value = `Rp${formatRupiah(totalAmount)}`;
        document.getElementById('paidAmountInput').min = 0; 
        calculateChange(); 
    }

    // Fungsi untuk mengupdate jumlah produk di keranjang
    window.updateCartQuantity = function(productId, newQuantity) {
        newQuantity = parseInt(newQuantity);
        if (isNaN(newQuantity) || newQuantity < 0) {
            // alert('Jumlah tidak valid.'); // Dihapus/dikomentari
            renderCart(); 
            return;
        }

        const product = productsData[productId];
        if (newQuantity > product.stock) {
            // alert(`Jumlah melebihi stok tersedia untuk ${product.name}. Stok tersedia: ${product.stock}.`); // Dihapus/dikomentari
            renderCart(); 
            return;
        }

        fetch('{{ route('kasir.transaksi.updateCart') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId, quantity: newQuantity })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => { throw new Error(errorData.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentCart = data.cart;
                renderCart();
                showNotification('Keranjang berhasil diupdate', 'success');
            } else if (data.error) {
                showNotification(data.error, 'danger');
                renderCart();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat mengupdate keranjang.', 'danger');
            renderCart();
        });
    };

    // Fungsi untuk menghapus produk dari keranjang
    window.removeFromCart = function(productId) {
        if (!confirm('Yakin ingin menghapus produk ini dari keranjang?')) {
            return;
        }

        fetch('{{ route('kasir.transaksi.removeFromCart') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => { throw new Error(errorData.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentCart = data.cart;
                renderCart();
                showNotification('Produk berhasil dihapus dari keranjang', 'success');
            } else if (data.error) {
                showNotification(data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat menghapus produk dari keranjang.', 'danger');
        });
    };

    // Fungsi untuk mereset keranjang
    window.resetCart = function() {
        if (!confirm('Yakin ingin mereset seluruh keranjang belanja?')) {
            return;
        }
        // Kirim permintaan ke backend untuk mengosongkan session cart
        fetch('{{ route('kasir.transaksi.removeFromCart') }}', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: 'all' }) // Mengirim product_id 'all' untuk menandakan reset
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => { throw new Error(errorData.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentCart = {}; // Kosongkan keranjang di frontend
                renderCart();
                showNotification('Keranjang berhasil direset!', 'success');
            } else if (data.error) {
                showNotification(data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat mereset keranjang.', 'danger');
        });
    };


    // Fungsi untuk menghitung kembalian
    function calculateChange() {
        // Hapus semua karakter non-angka kecuali koma/titik untuk desimal, lalu konversi ke float
        let total = parseFloat(document.getElementById('totalAmountDisplay').value.replace('Rp', '').replace(/\./g, '').replace(',', '.'));
        if (isNaN(total)) total = 0; 

        const paidAmountInput = document.getElementById('paidAmountInput');
        const changeDisplay = document.getElementById('changeAmountDisplay');
        const changeLabel = document.getElementById('changeLabel');
        let paid = parseFloat(paidAmountInput.value);
        if (isNaN(paid)) paid = 0;

        const difference = paid - total;
        
        // Jika pembayaran kurang dari total
        if (difference < 0) {
            const kurang = Math.abs(difference);
            changeDisplay.value = `Rp${formatRupiah(kurang)}`;
            changeDisplay.className = 'form-control form-control-lg fw-bold text-danger';
            changeLabel.innerHTML = '<i class="fas fa-exclamation-circle"></i> Kurang';
            changeLabel.className = 'form-label fw-bold text-danger';
            document.getElementById('processTransactionBtn').disabled = true;
        } else {
            // Jika pembayaran cukup atau lebih
            changeDisplay.value = `Rp${formatRupiah(difference)}`;
            changeDisplay.className = 'form-control form-control-lg fw-bold text-success';
            changeLabel.innerHTML = '<i class="fas fa-coins"></i> Kembali';
            changeLabel.className = 'form-label fw-bold text-success';
            
            // Validasi tombol Bayar
            if (Object.keys(currentCart).length > 0 && total > 0) {
                document.getElementById('processTransactionBtn').disabled = false;
            } else {
                document.getElementById('processTransactionBtn').disabled = true;
            }
        }
    }

    // Fungsi untuk memproses transaksi final
    function processTransaction() {
        if (Object.keys(currentCart).length === 0) {
            showNotification('Keranjang belanja kosong. Tambahkan produk terlebih dahulu.', 'danger');
            return;
        }

        const paidAmount = parseFloat(document.getElementById('paidAmountInput').value);
        let totalAmount = parseFloat(document.getElementById('totalAmountDisplay').value.replace('Rp', '').replace(/\./g, '').replace(',', '.'));
        if (isNaN(totalAmount)) totalAmount = 0;

        if (isNaN(paidAmount) || paidAmount < totalAmount) {
            const kurang = totalAmount - (isNaN(paidAmount) ? 0 : paidAmount);
            showNotification(`Pembayaran kurang Rp${formatRupiah(kurang)}. Total: Rp${formatRupiah(totalAmount)}, Dibayar: Rp${formatRupiah(isNaN(paidAmount) ? 0 : paidAmount)}`, 'danger');
            return;
        }

        // Siapkan data keranjang untuk dikirim ke backend
        const itemsForBackend = Object.values(currentCart).map(item => ({
            id: item.id,
            qty: item.quantity
        }));

        fetch('{{ route('kasir.transaksi.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                items: itemsForBackend,
                paid_amount: paidAmount
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => { throw new Error(errorData.error || 'Terjadi kesalahan pada server.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification(data.success, 'success');
                currentCart = {}; // Kosongkan keranjang di frontend
                renderCart(); // Render ulang keranjang kosong
                if (data.redirect_url) {
                    setTimeout(() => {
                        window.location.href = data.redirect_url; // Redirect ke halaman struk
                    }, 1000);
                }
            } else if (data.error) {
                showNotification(data.error, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification(error.message || 'Terjadi kesalahan saat memproses transaksi.', 'danger');
        });
    }

    // Fungsi untuk menampilkan notifikasi
    function showNotification(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Fungsi helper untuk format Rupiah
    function formatRupiah(angka) {
        // Konversi angka ke string
        let number_string = angka.toString();

        // Pisahkan bagian integer dan desimal
        let parts = number_string.split('.');
        let integerPart = parts[0];

        // Hapus semua karakter non-digit dari bagian integer
        // Ini memastikan input seperti "120.000" atau "120,000" tetap diproses dengan benar
        integerPart = integerPart.replace(/\D/g, ''); 

        // Lakukan pemisahan ribuan
        let sisa = integerPart.length % 3;
        let rupiah = integerPart.substr(0, sisa);
        let ribuan = integerPart.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        // Pastikan tidak ada bagian desimal yang ditambahkan jika angka aslinya tidak punya desimal
        let decimalPart = parts.length > 1 && parts[1].length > 0 ? ',' + parts[1] : '';

        rupiah = rupiah + decimalPart; 

        return rupiah;
    }
</script>
@endpush