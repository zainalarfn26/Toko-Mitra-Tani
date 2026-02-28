<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\KasirController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Kasir\TransactionController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
    }
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }
    }
    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// ✅ Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () { // <-- PERBAIKAN DI SINI: tambahkan ->name('admin.')
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('kasir', KasirController::class); // Ini akan menjadi admin.kasir.index, admin.kasir.store, dll.
    Route::resource('produk', ProductController::class); // Ini akan menjadi admin.produk.index, admin.produk.store, dll.
    Route::get('laporan', [ReportController::class, 'index'])->name('laporan.index'); // <-- Ubah nama ini dari 'admin.laporan' menjadi 'laporan.index'
});

// ✅ Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Kasir\DashboardController::class, 'index'])->name('dashboard');
    Route::get('transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi/add-to-cart', [TransactionController::class, 'addToCart'])->name('transaksi.addToCart');
    Route::post('transaksi/update-cart', [TransactionController::class, 'updateCart'])->name('transaksi.updateCart');
    Route::post('transaksi/remove-from-cart', [TransactionController::class, 'removeFromCart'])->name('transaksi.removeFromCart');
    Route::post('transaksi/process', [TransactionController::class, 'processTransaction'])->name('transaksi.process');
    Route::get('struk/{id}', [TransactionController::class, 'receipt'])->name('transaksi.struk');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';