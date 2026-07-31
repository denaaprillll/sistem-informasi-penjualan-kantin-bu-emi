<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        // Fallback
        if ($menus->isEmpty()) {
            $menu = Menu::create([
                'nama_menu' => 'Ayam Geprek Bu Emi',
                'harga' => 15000,
                'status' => 'Tersedia',
                'deskripsi' => 'Ayam crispy dengan sambal khas Bu Emi yang pedas, gurih, dan dibuat menggunakan bahan-bahan segar setiap hari.'
            ]);
            $menus = collect([$menu]);
        }
        $pengaturan = \App\Models\Pengaturan::firstOrCreate(['id' => 1]);
        return view('order', compact('menus', 'pengaturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'jumlah' => 'required|integer|min:1',
            'level' => 'required',
        ]);

        // Get first menu
        $menu = Menu::first();
        if (!$menu) {
            $menu = Menu::create([
                'nama_menu' => 'Ayam Geprek Bu Emi',
                'harga' => 15000,
                'deskripsi' => 'Ayam crispy dengan sambal khas Bu Emi yang pedas, gurih, dan dibuat menggunakan bahan-bahan segar setiap hari.'
            ]);
        }

        $subtotal = $request->jumlah * $menu->harga;

        // Create Penjualan
        $penjualan = Penjualan::create([
            'no_pesanan' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'nama_pelanggan' => $request->nama,
            'no_hp' => $request->no_hp,
            'total_penjualan' => $subtotal,
            'status_pembayaran' => 'Belum Dibayar',
            'status_pesanan' => 'Menunggu Pembayaran',
            'waktu_pemesanan' => now(),
        ]);

        // Create DetailPenjualan
        DetailPenjualan::create([
            'penjualan_id' => $penjualan->id,
            'menu_id' => $menu->id ?? 1,
            'jumlah' => $request->jumlah,
            'level_sambal' => $request->level,
            'catatan' => $request->catatan,
            'harga_satuan' => $menu->harga,
            'subtotal' => $subtotal,
        ]);

        return redirect()->route('payment', $penjualan->id);
    }

    public function payment($id)
    {
        $penjualan = Penjualan::with('detailPenjualans.menu')->findOrFail($id);
        $pengaturan = \App\Models\Pengaturan::firstOrCreate(['id' => 1]);
        return view('payment', compact('penjualan', 'pengaturan'));
    }

    public function success($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        return view('success', compact('penjualan'));
    }

    public function struk($id)
    {
        $penjualan = Penjualan::with('detailPenjualans.menu')->findOrFail($id);
        return view('struk', compact('penjualan'));
    }
}
