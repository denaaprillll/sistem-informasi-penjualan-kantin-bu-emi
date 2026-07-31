<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::firstOrCreate(['id' => 1]);
        $menu = Menu::first();
        if (!$menu) {
            $menu = Menu::create(['nama_menu' => 'Ayam Geprek Bu Emi', 'harga' => 10000, 'status' => 'Tersedia']);
        }
        $admin = User::first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Utama',
                'email' => 'admin@kantinbuemi.com',
                'password' => Hash::make('password')
            ]);
        }

        return view('pengaturan.index', compact('pengaturan', 'menu', 'admin'));
    }

    public function updateKantin(Request $request)
    {
        $request->validate([
            'nama_kantin' => 'required|string|max:255',
            'no_hp' => 'nullable|numeric',
            'link_gmaps' => 'nullable|url',
        ]);

        $pengaturan = Pengaturan::firstOrCreate(['id' => 1]);
        $pengaturan->update($request->only([
            'nama_kantin', 'nama_pemilik', 'alamat', 'link_gmaps', 'no_hp', 'jam_operasional', 'deskripsi_singkat'
        ]));

        return response()->json(['status' => 'success', 'message' => 'Informasi kantin berhasil diperbarui.']);
    }

    public function updatePembayaran(Request $request)
    {
        $request->validate([
            'dana_nomor' => 'nullable|numeric',
            'dana_nama' => 'nullable|string|max:255',
            'dana_qr' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pengaturan = Pengaturan::firstOrCreate(['id' => 1]);

        $data = $request->only(['dana_nomor', 'dana_nama']);

        if ($request->hasFile('dana_qr')) {
            if ($pengaturan->dana_qr) {
                Storage::disk('public')->delete($pengaturan->dana_qr);
            }
            $path = $request->file('dana_qr')->store('pembayaran', 'public');
            $data['dana_qr'] = $path;
        }

        $pengaturan->update($data);

        return response()->json(['status' => 'success', 'message' => 'Pengaturan pembayaran berhasil diperbarui.']);
    }

    public function updateMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'status' => 'required|in:Tersedia,Habis',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $menu = Menu::first();
        if (!$menu) abort(404);

        $data = $request->only(['nama_menu', 'harga', 'status']);

        if ($request->hasFile('foto')) {
            if ($menu->foto) {
                Storage::disk('public')->delete($menu->foto);
            }
            $path = $request->file('foto')->store('menus', 'public');
            $data['foto'] = $path;
        }

        $menu->update($data);

        return response()->json(['status' => 'success', 'message' => 'Pengaturan menu berhasil diperbarui.']);
    }

    public function updateAkun(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password_lama' => 'nullable|string',
            'password_baru' => 'nullable|string|min:6|same:konfirmasi_password',
        ]);

        $admin = User::first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Utama',
                'email' => 'admin@kantinbuemi.com',
                'password' => Hash::make('password')
            ]);
        }

        $data = ['name' => $request->name, 'email' => $request->email];

        if ($request->filled('password_lama') && $request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $admin->password)) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['password_lama' => ['Password lama tidak sesuai dengan catatan kami.']]
                ], 422);
            }
            $data['password'] = Hash::make($request->password_baru);
        }

        $admin->update($data);

        return response()->json(['status' => 'success', 'message' => 'Pengaturan akun admin berhasil diperbarui.']);
    }
}
