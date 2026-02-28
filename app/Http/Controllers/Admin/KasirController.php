<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Import model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use Illuminate\Validation\Rule; // Diperlukan untuk validasi unique saat update

class KasirController extends Controller
{
    /**
     * Menampilkan daftar semua user dengan role 'kasir'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua user yang memiliki role 'kasir'
        $kasirs = User::where('role', 'kasir')->get();

        // Mengirim data kasir ke view 'admin.kasir.index'
        return view('admin.kasir.index', compact('kasirs'));
    }

    /**
     * Menyimpan user kasir baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi data input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email', // Email harus unik di tabel users
            'password' => 'required|string|min:6', // Password minimal 6 karakter
        ]);

        // Membuat user baru dengan role 'kasir'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'kasir', // Set role menjadi 'kasir'
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        // Redirect kembali ke halaman daftar kasir dengan pesan sukses
        return redirect()->route('admin.kasir.index')->with('success', 'Kasir berhasil ditambahkan!');
    }

    /**
     * Memperbarui data user kasir yang sudah ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $kasir  (menggunakan Route Model Binding untuk model User)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $kasir)
    {
        // Validasi data input dari form untuk update
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                // Pastikan email unik, kecuali untuk user yang sedang diedit
                Rule::unique('users')->ignore($kasir->id),
            ],
            // Password tidak wajib saat update, hanya jika diisi
            'password' => 'nullable|string|min:6', 
        ]);

        // Update data nama dan email
        $kasir->name = $request->name;
        $kasir->email = $request->email;

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $kasir->password = Hash::make($request->password);
        }
        
        $kasir->save(); // Simpan perubahan

        // Redirect kembali ke halaman daftar kasir dengan pesan sukses
        return redirect()->route('admin.kasir.index')->with('success', 'Kasir berhasil diupdate!');
    }

    /**
     * Menghapus user kasir dari database.
     *
     * @param  \App\Models\User  $kasir  (menggunakan Route Model Binding untuk model User)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $kasir)
    {
        try {
            // Menghapus user kasir
            $kasir->delete();

            // Redirect kembali ke halaman daftar kasir dengan pesan sukses
            return redirect()->route('admin.kasir.index')->with('success', 'Kasir berhasil dihapus!');
        } catch (\Exception $e) {
            // Jika terjadi error (misalnya karena user masih terkait dengan transaksi),
            // berikan pesan error
            return redirect()->route('admin.kasir.index')->with('error', 'Kasir tidak dapat dihapus karena terkait dengan data transaksi lain.');
        }
    }
}