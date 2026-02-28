<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaction::count();
        $totalKasir = User::where('role', 'kasir')->count();
        $totalProduk = Product::count();

        // Ambil data transaksi per hari (30 hari terakhir)
        $days = collect();
        $data = collect();

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('d M');

            $count = Transaction::whereDate('created_at', $date)->count();

            $days->push($label); // contoh: "08 Jul"
            $data->push($count);
        }

        return view('admin.dashboard', [
            'totalTransaksi' => $totalTransaksi,
            'totalKasir' => $totalKasir,
            'totalProduk' => $totalProduk,
            'labels' => $days,
            'data' => $data,
        ]);
    }
}
