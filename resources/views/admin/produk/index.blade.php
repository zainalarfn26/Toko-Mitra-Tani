@extends('layouts.dashboard', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('partials.topnav', ['title' => 'Kelola Produk']) 

    <div class="container-fluid py-4">
        <button class="btn btn-primary mb-3" onclick="showModal()">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>
        
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

        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary text-white">
                <h6 class="mb-0"><i class="fas fa-boxes"></i> Daftar Produk</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover text-center align-middle"> 
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $produk)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $produk->kode_product }}</span></td>
                                <td class="text-start">{{ $produk->name }}</td>
                                <td>Rp {{ number_format($produk->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $produk->stock < 10 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $produk->stock }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="showModal({{ json_encode($produk) }})">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>Tidak ada data produk yang ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="produkModal" tabindex="-1" aria-labelledby="produkModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="produkForm" method="POST">
                @csrf 
                <input type="hidden" name="_method" id="produkMethod">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="produkModalLabel">Form Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kodeProduct" class="form-label">Kode Produk</label>
                            <input type="text" name="kode_product" class="form-control" id="kodeProduct" required>
                        </div>
                        <div class="mb-3">
                            <label for="produkName" class="form-label">Nama Produk</label>
                            <input type="text" name="name" class="form-control" id="produkName" required>
                        </div>
                        <div class="mb-3">
                            <label for="produkPrice" class="form-label">Harga</label>
                            <input type="number" name="price" class="form-control" id="produkPrice" step="0.01" required> {{-- step="0.01" untuk harga desimal --}}
                        </div>
                        <div class="mb-3">
                            <label for="produkStock" class="form-label">Stok</label>
                            <input type="number" name="stock" class="form-control" id="produkStock" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Pastikan kode dieksekusi setelah DOM (Document Object Model) sepenuhnya dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi instance modal Bootstrap
        // Pastikan 'bootstrap' objek global tersedia (dari bootstrap.min.js yang dimuat di layout)
        const produkModal = new bootstrap.Modal(document.getElementById('produkModal'));

        /**
         * Fungsi global untuk menampilkan modal tambah atau edit produk.
         * Didefinisikan di `window.showModal` agar bisa diakses dari atribut `onclick` di HTML.
         * @param {Object|null} produk Data produk jika dalam mode edit, null jika mode tambah.
         */
        window.showModal = function(produk = null) { 
            const form = document.getElementById('produkForm');
            const methodInput = document.getElementById('produkMethod');
            const modalTitle = document.getElementById('produkModalLabel');

            if (produk) {
                // Mode Edit: Mengisi form dengan data produk yang akan diedit
                modalTitle.textContent = 'Edit Produk';
                form.action = `/admin/produk/${produk.id}`; // URL untuk update produk
                methodInput.value = 'PUT'; // Metode HTTP untuk operasi UPDATE
                document.getElementById('kodeProduct').value = produk.kode_product;
                document.getElementById('produkName').value = produk.name;
                document.getElementById('produkPrice').value = produk.price;
                document.getElementById('produkStock').value = produk.stock;
            } else {
                // Mode Tambah: Mereset form dan menyiapkan untuk input produk baru
                modalTitle.textContent = 'Tambah Produk';
                // Gunakan nama route 'produk.store' yang benar sesuai `php artisan route:list`
                form.action = `{{ route('admin.produk.store') }}`; 
                methodInput.value = ''; // Tidak perlu method PUT/PATCH untuk operasi STORE
                form.reset(); // Mereset semua field form
            }

            produkModal.show(); // Menampilkan modal
        };

        // Event listener yang akan dijalankan ketika modal disembunyikan
        document.getElementById('produkModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('produkForm').reset(); // Mereset semua field form
            document.getElementById('produkMethod').value = ''; // Mengosongkan input method
        });
    });
</script>
@endpush