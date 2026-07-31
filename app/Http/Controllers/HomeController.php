<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        // Fallback dummy menu if table is empty (for UI purpose)
        if ($menus->isEmpty()) {
            $menu = Menu::create([
                'nama_menu' => 'Ayam Geprek Bu Emi',
                'harga' => 15000,
                'deskripsi' => 'Ayam crispy dengan sambal khas Bu Emi yang pedas, gurih, dan dibuat menggunakan bahan-bahan segar setiap hari.'
            ]);
            $menus = collect([$menu]);
        }
        $pengaturan = \App\Models\Pengaturan::firstOrCreate(['id' => 1]);
        return view('home', compact('menus', 'pengaturan'));
    }
}
