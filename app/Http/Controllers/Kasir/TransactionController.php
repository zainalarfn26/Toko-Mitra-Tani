<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Import Session facade

class TransactionController extends Controller
{
    /**
     * Menampilkan halaman transaksi dengan daftar produk.
     */
    public function index()
    {
        $products = Product::all();
        // Mengambil keranjang dari session jika ada, jika tidak, array kosong
        $cart = Session::get('cart', []); 
        return view('kasir.transaksi.index', compact('products', 'cart'));
    }

    /**
     * Menambahkan produk ke keranjang (menggunakan Session).
     * Ini akan dipanggil via AJAX dari frontend.
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $quantity = $request->quantity;

        $cart = Session::get('cart', []);

        // Cek stok
        if ($product->stock < $quantity) {
            return response()->json(['error' => 'Stok tidak cukup untuk ' . $product->name . '. Stok tersedia: ' . $product->stock], 400);
        }

        // Jika produk sudah ada di keranjang, update jumlahnya
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $quantity;
            if ($product->stock < $newQuantity) {
                 return response()->json(['error' => 'Penambahan melebihi stok tersedia untuk ' . $product->name . '. Stok tersedia: ' . $product->stock], 400);
            }
            $cart[$product->id]['quantity'] = $newQuantity;
            $cart[$product->id]['subtotal'] = $product->price * $newQuantity;
        } else {
            // Jika produk belum ada, tambahkan ke keranjang
            $cart[$product->id] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => $quantity,
                "subtotal" => $product->price * $quantity,
                "kode_product" => $product->kode_product, // Tambahkan kode_product
                "stock_available" => $product->stock // Tambahkan informasi stok tersedia
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart' => $cart,
            'total_amount' => array_sum(array_column($cart, 'subtotal'))
        ]);
    }

    /**
     * Mengupdate jumlah produk di keranjang (via AJAX).
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0', // Bisa 0 untuk menghapus
        ]);

        $cart = Session::get('cart', []);
        $productId = $request->product_id;
        $newQuantity = $request->quantity;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($newQuantity > $product->stock) {
                return response()->json(['error' => 'Jumlah melebihi stok tersedia untuk ' . $product->name . '. Stok tersedia: ' . $product->stock], 400);
            }

            if ($newQuantity == 0) {
                unset($cart[$productId]); // Hapus jika jumlah 0
            } else {
                $cart[$productId]['quantity'] = $newQuantity;
                $cart[$productId]['subtotal'] = $product->price * $newQuantity;
            }
            Session::put('cart', $cart);
            return response()->json([
                'success' => 'Keranjang berhasil diupdate!',
                'cart' => $cart,
                'total_amount' => array_sum(array_column($cart, 'subtotal'))
            ]);
        }
        return response()->json(['error' => 'Produk tidak ditemukan di keranjang!'], 404);
    }

    /**
     * Menghapus produk dari keranjang (via AJAX).
     */
    public function removeFromCart(Request $request)
    {
        $cart = Session::get('cart', []);
        $productId = $request->product_id;

        // Jika product_id adalah 'all', kosongkan seluruh keranjang
        if ($productId === 'all') {
            Session::forget('cart');
            return response()->json([
                'success' => 'Keranjang berhasil direset!',
                'cart' => []
            ]);
        }
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return response()->json([
                'success' => 'Produk berhasil dihapus dari keranjang!',
                'cart' => $cart,
                'total_amount' => array_sum(array_column($cart, 'subtotal'))
            ]);
        }
        return response()->json(['error' => 'Produk tidak ditemukan di keranjang!'], 404);
    }


    /**
     * Memproses transaksi final dan menyimpan ke database.
     */
    public function processTransaction(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Keranjang belanja kosong!'], 400);
        }

        $totalAmount = array_sum(array_column($cart, 'subtotal'));
        
        // Validasi jumlah pembayaran
        $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $paidAmount = $request->paid_amount;
        
        // Cek apakah pembayaran cukup
        if ($paidAmount < $totalAmount) {
            $kurang = $totalAmount - $paidAmount;
            return response()->json([
                'error' => 'Pembayaran kurang Rp' . number_format($kurang, 0, ',', '.') . '. Total: Rp' . number_format($totalAmount, 0, ',', '.') . ', Dibayar: Rp' . number_format($paidAmount, 0, ',', '.')
            ], 400);
        }

        DB::beginTransaction();
        try {
            $changeAmount = $paidAmount - $totalAmount;

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'change_amount' => $changeAmount,
            ]);

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product || $product->stock < $item['quantity']) {
                    DB::rollback();
                    return response()->json(['error' => 'Stok tidak cukup untuk ' . $item['name'] . '. Transaksi dibatalkan.'], 400);
                }

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            Session::forget('cart'); // Kosongkan keranjang setelah transaksi berhasil

            // Mengembalikan JSON dengan URL redirect
            return response()->json([
                'success' => 'Transaksi berhasil diproses!',
                'redirect_url' => route('kasir.transaksi.struk', $transaction->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan struk transaksi.
     */
    public function receipt($id)
    {
        // Memuat relasi user dan detailTransactions.product
        $transaction = Transaction::with('detailTransactions.product', 'user')->findOrFail($id);
        return view('kasir.transaksi.struk', compact('transaction'));
    }
}