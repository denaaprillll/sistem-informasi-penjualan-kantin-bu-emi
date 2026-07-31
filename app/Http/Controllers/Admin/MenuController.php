<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->get();
        return view('menu.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            Menu::create([
                'nama_menu' => $request->nama_menu,
                'harga'     => $request->harga,
                'deskripsi' => $request->deskripsi
            ]);

            return response()->json(['status' => 'success', 'message' => 'Menu berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            $menu = Menu::findOrFail($id);
            $menu->update([
                'nama_menu' => $request->nama_menu,
                'harga'     => $request->harga,
                'deskripsi' => $request->deskripsi
            ]);

            return response()->json(['status' => 'success', 'message' => 'Menu berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            $menu->delete();
            return response()->json(['status' => 'success', 'message' => 'Menu berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
