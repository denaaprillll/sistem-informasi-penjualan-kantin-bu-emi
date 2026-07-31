<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class CheckoutController extends Controller
{
    public function process(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $request->validate([
            'payment_method' => 'required|in:tunai,dana',
        ]);

        if ($request->payment_method == 'dana') {
            $request->validate([
                'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);

            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
            
            $penjualan->update([
                'metode_pembayaran' => 'DANA',
                'bukti_transfer' => $path,
                'status_pembayaran' => 'Menunggu Verifikasi',
                'status_pesanan' => 'Menunggu Diproses'
            ]);
            
            $redirect_url = route('user.dashboard') . '?no_hp=' . urlencode($penjualan->no_hp);
        } else {
            $penjualan->update([
                'metode_pembayaran' => 'Tunai',
                'status_pembayaran' => 'Menunggu Pembayaran',
                'status_pesanan' => 'Menunggu Diproses'
            ]);
            
            $redirect_url = route('pesanan.struk', $penjualan->id);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success', 
                'message' => 'Pembayaran berhasil disimpan.',
                'redirect_url' => $redirect_url
            ]);
        }

        return redirect($redirect_url);
    }
}
