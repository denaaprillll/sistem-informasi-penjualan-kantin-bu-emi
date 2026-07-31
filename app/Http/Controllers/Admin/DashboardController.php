<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Menu;
use App\Models\BahanBaku;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        
        $pesananHariIni = Penjualan::whereDate('created_at', today())->count();
        $penjualanHariIni = Penjualan::whereDate('created_at', today())->sum('total_penjualan');
        $pembelianHariIni = Pembelian::whereDate('tanggal_pembelian', $today)->sum('total_pembelian');
        $jumlahMenu = Menu::count();
        $jumlahBahan = BahanBaku::count();
        $stokHampirHabis = BahanBaku::where('stok', '<', 10)->count();

        // Grafik Penjualan 7 Hari Terakhir
        $grafikPenjualan = Penjualan::selectRaw('DATE(waktu_pemesanan) as tanggal, SUM(total_penjualan) as total')
            ->whereDate('waktu_pemesanan', '>=', now()->subDays(6)->toDateString())
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
            
        // Map untuk chart
        $labels = [];
        $data = [];
        foreach($grafikPenjualan as $g) {
            $labels[] = date('d M', strtotime($g->tanggal));
            $data[] = $g->total;
        }

        return view('dashboard.index', compact(
            'pesananHariIni', 'penjualanHariIni', 'pembelianHariIni', 
            'jumlahMenu', 'jumlahBahan', 'stokHampirHabis',
            'labels', 'data'
        ));
    }
}
