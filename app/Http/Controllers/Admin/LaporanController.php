<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\DetailPenjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. LAPORAN HARIAN
        $totalPenjualanHarian = Penjualan::whereDate('waktu_pemesanan', $today)->whereIn('status_pesanan', ['selesai', 'dibayar'])->sum('total_penjualan');
        $totalPembelianHarian = Pembelian::whereDate('tanggal_pembelian', $today)->sum('total_pembelian');
        $keuntunganHarian = $totalPenjualanHarian - $totalPembelianHarian;

        // Menu terlaris hari ini
        $menuTerlarisHarian = DetailPenjualan::select('menu_id', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->whereHas('penjualan', function($query) use ($today) {
                $query->whereDate('waktu_pemesanan', $today)->whereIn('status_pesanan', ['selesai', 'dibayar']);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->get();

        // 2. LAPORAN MINGGUAN
        $totalPesananMingguan = Penjualan::whereBetween('waktu_pemesanan', [$startOfWeek, $endOfWeek])->whereIn('status_pesanan', ['selesai', 'dibayar'])->count();
        $totalPenjualanMingguan = Penjualan::whereBetween('waktu_pemesanan', [$startOfWeek, $endOfWeek])->whereIn('status_pesanan', ['selesai', 'dibayar'])->sum('total_penjualan');
        $totalPembelianMingguan = Pembelian::whereBetween('tanggal_pembelian', [$startOfWeek, $endOfWeek])->sum('total_pembelian');
        $keuntunganMingguan = $totalPenjualanMingguan - $totalPembelianMingguan;

        // Grafik Mingguan (Senin - Minggu)
        $grafikMingguan = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $grafikMingguan[] = (float) Penjualan::whereDate('waktu_pemesanan', $date)->whereIn('status_pesanan', ['selesai', 'dibayar'])->sum('total_penjualan');
        }

        // 3. LAPORAN BULANAN
        $totalPenjualanBulanan = Penjualan::whereBetween('waktu_pemesanan', [$startOfMonth, $endOfMonth])->whereIn('status_pesanan', ['selesai', 'dibayar'])->sum('total_penjualan');
        $totalPembelianBulanan = Pembelian::whereBetween('tanggal_pembelian', [$startOfMonth, $endOfMonth])->sum('total_pembelian');
        $keuntunganBulanan = $totalPenjualanBulanan - $totalPembelianBulanan;

        // Menu terlaris bulan ini
        $menuTerlarisBulanan = DetailPenjualan::select('menu_id', DB::raw('SUM(jumlah) as total_terjual'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->whereHas('penjualan', function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('waktu_pemesanan', [$startOfMonth, $endOfMonth])->whereIn('status_pesanan', ['selesai', 'dibayar']);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->get();

        // Grafik Bulanan (Minggu 1 - 4)
        $grafikPenjualanBulanan = [];
        $grafikPembelianBulanan = [];
        
        $currentDate = $startOfMonth->copy();
        for ($i = 1; $i <= 4; $i++) {
            $weekStart = $currentDate->copy();
            $weekEnd = $i == 4 ? $endOfMonth->copy() : $currentDate->copy()->addDays(6);
            
            $grafikPenjualanBulanan[] = (float) Penjualan::whereBetween('waktu_pemesanan', [$weekStart, $weekEnd])->whereIn('status_pesanan', ['selesai', 'dibayar'])->sum('total_penjualan');
            $grafikPembelianBulanan[] = (float) Pembelian::whereBetween('tanggal_pembelian', [$weekStart, $weekEnd])->sum('total_pembelian');
            
            $currentDate->addDays(7);
        }

        return view('laporan.index', compact(
            'totalPenjualanHarian', 'totalPembelianHarian', 'keuntunganHarian', 'menuTerlarisHarian',
            'totalPesananMingguan', 'totalPenjualanMingguan', 'totalPembelianMingguan', 'keuntunganMingguan', 'grafikMingguan',
            'totalPenjualanBulanan', 'totalPembelianBulanan', 'keuntunganBulanan', 'menuTerlarisBulanan',
            'grafikPenjualanBulanan', 'grafikPembelianBulanan'
        ));
    }
}
