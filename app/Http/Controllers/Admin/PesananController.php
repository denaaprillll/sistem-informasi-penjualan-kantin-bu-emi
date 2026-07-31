<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with('detailPenjualans.menu')->orderBy('created_at', 'desc');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }

        $filter = $request->query('filter', 'today'); // Default filter is today
        $date = $request->query('date');
        
        if ($date) {
            // Filter by specific date if provided
            $query->whereDate('created_at', $date);
            $viewMode = 'date';
        } else if ($filter == 'history') {
            // Show all history (already ordered desc)
            $viewMode = 'history';
        } else {
            // Default: today
            $query->whereDate('created_at', today());
            $viewMode = 'today';
        }

        $penjualans = $query->get();
        return view('pesanan.index', compact('penjualans', 'viewMode', 'date'));
    }

    public function updateStatus(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);
        
        if ($request->has('status_pesanan')) {
            $penjualan->update(['status_pesanan' => $request->status_pesanan]);
        }
        
        if ($request->has('status_pembayaran')) {
            $penjualan->update(['status_pembayaran' => $request->status_pembayaran]);
        }
        
        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}
