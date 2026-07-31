<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $no_hp = $request->get('no_hp'); // Get from query string if user is not authenticated
        
        if ($no_hp) {
            $pesanans = Penjualan::where('no_hp', $no_hp)
                ->with('detailPenjualans.menu')
                ->orderBy('waktu_pemesanan', 'desc')
                ->get();
        } else {
            $pesanans = collect(); // empty collection
        }
        
        return view('user.dashboard', compact('pesanans', 'no_hp'));
    }
}
