<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $kasirId = Auth::id();

        $totalTransaksiSaya = Transaction::where('user_id', $kasirId)->count();

        $transaksiHariIni = Transaction::where('user_id', $kasirId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $transaksiTerakhir = Transaction::where('user_id', $kasirId)
            ->latest()
            ->first();

        // Grafik 7 hari terakhir
        $labels = collect();
        $data = collect();

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('d M');

            $jumlah = Transaction::where('user_id', $kasirId)
                ->whereDate('created_at', $tanggal)
                ->count();

            $labels->push($label);
            $data->push($jumlah);
        }

        return view('kasir.dashboard', compact(
            'totalTransaksiSaya',
            'transaksiHariIni',
            'transaksiTerakhir',
            'labels',
            'data'
        ));
    }
}
