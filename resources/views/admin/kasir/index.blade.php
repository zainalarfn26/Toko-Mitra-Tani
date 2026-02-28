@extends('layouts.dashboard', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('partials.topnav', ['title' => 'Kelola Kasir'])

<div class="container-fluid py-4">
    <button class="btn btn-secondary mb-3" onclick="showModal()">Tambah Kasir</button>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kasirs as $kasir)
                        <tr>
                            <td>{{ $kasir->name }}</td>
                            <td>{{ $kasir->email }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning"
                                    onclick="showModal({{ json_encode($kasir) }})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.kasir.destroy', $kasir->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus kasir ini? Tindakan ini tidak dapat dibatalkan.')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>Tidak ada data kasir yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Kasir -->
<div class="modal fade" id="kasirModal" tabindex="-1" aria-labelledby="kasirModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="kasirForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="kasirMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kasirModalLabel">Form Kasir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kasirName" class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" id="kasirName" required>
                    </div>
                    <div class="mb-3">
                        <label for="kasirEmail" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="kasirEmail" required>
                    </div>
                    <div class="mb-3" id="kasirPasswordWrapper">
                        <label for="kasirPassword" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="kasirPassword">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
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
        const kasirModal = new bootstrap.Modal(document.getElementById('kasirModal'));

        /**
         * Fungsi global untuk menampilkan modal tambah atau edit kasir.
         * Didefinisikan di `window.showModal` agar bisa diakses dari atribut `onclick` di HTML.
         * @param {Object|null} kasir Data kasir jika dalam mode edit, null jika mode tambah.
         */
        window.showModal = function(kasir = null) {
            const form = document.getElementById('kasirForm');
            const methodInput = document.getElementById('kasirMethod');
            const modalTitle = document.getElementById('kasirModalLabel');
            const passwordWrapper = document.getElementById('kasirPasswordWrapper');
            const kasirPasswordInput = document.getElementById('kasirPassword');

            // Reset validasi sebelumnya
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            if (kasir) {
                // Mode Edit: Mengisi form dengan data kasir yang akan diedit
                modalTitle.textContent = 'Edit Kasir';
                form.action = `/admin/kasir/${kasir.id}`; // URL untuk update kasir
                methodInput.value = 'PUT'; // Metode HTTP untuk operasi UPDATE
                document.getElementById('kasirName').value = kasir.name;
                document.getElementById('kasirEmail').value = kasir.email;
                
                // Sembunyikan field password dan buat tidak required saat edit
                passwordWrapper.style.display = 'none';
                kasirPasswordInput.removeAttribute('required');
                kasirPasswordInput.value = ''; // Kosongkan password field
            } else {
                // Mode Tambah: Mereset form dan menyiapkan untuk input kasir baru
                modalTitle.textContent = 'Tambah Kasir';
                form.action = `{{ route('admin.kasir.store') }}`; // URL untuk menyimpan kasir baru
                methodInput.value = ''; // Tidak perlu method PUT/PATCH untuk operasi STORE
                form.reset(); // Mereset semua field form
                
                // Tampilkan field password dan buat required saat tambah
                passwordWrapper.style.display = 'block';
                kasirPasswordInput.setAttribute('required', 'required');
            }

            kasirModal.show(); // Menampilkan modal
        };

        // Event listener yang akan dijalankan ketika modal disembunyikan
        document.getElementById('kasirModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('kasirForm').reset(); // Mereset semua field form
            document.getElementById('kasirMethod').value = ''; // Mengosongkan input method
            document.getElementById('kasirPasswordWrapper').style.display = 'block'; // Pastikan password field terlihat lagi
            document.getElementById('kasirPassword').setAttribute('required', 'required'); // Pastikan required lagi
        });
    });
</script>
@endpush