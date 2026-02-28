<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all();

        return view('admin.produk.index', compact('products'));
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'kode_product' => 'required|string|max:255|unique:products,kode_product', 
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Memperbarui data produk yang sudah ada di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $produk  (menggunakan Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $produk)
    {
        // Validasi data input dari form untuk update
        $request->validate([
            'kode_product' => [
                'required',
                'string',
                'max:255',
                // Pastikan kode_product unik, kecuali untuk produk yang sedang diedit
                Rule::unique('products')->ignore($produk->id, 'id'),
            ],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $produk->update($request->all());

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Menghapus produk dari database.
     *
     * @param  \App\Models\Product  $produk  (menggunakan Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $produk)
    {
        try {
            $produk->delete();

            return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {

            return redirect()->route('admin.produk.index')->with('error', 'Produk tidak dapat dihapus karena terkait dengan data transaksi lain.');
        }
    }
}