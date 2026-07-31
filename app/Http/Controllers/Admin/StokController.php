<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;

class StokController extends Controller
{
    public function index()
    {
        $bahanBakus = BahanBaku::latest()->get();
        return view('stok.index', compact('bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan'     => 'required|string|max:255',
            'stok'       => 'required|integer|min:0',
        ]);

        try {
            BahanBaku::create([
                'nama_bahan' => $request->nama_bahan,
                'satuan'     => $request->satuan,
                'stok'       => $request->stok,
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data bahan baku berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan'     => 'required|string|max:255',
        ]);

        try {
            $bahanBaku = BahanBaku::findOrFail($id);
            $bahanBaku->update([
                'nama_bahan' => $request->nama_bahan,
                'satuan'     => $request->satuan,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data bahan baku berhasil diubah.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function adjust(Request $request, $id)
    {
        $request->validate([
            'type'   => 'required|in:add,sub',
            'amount' => 'required|integer|min:1',
        ]);

        try {
            $bahanBaku = BahanBaku::findOrFail($id);
            
            if ($request->type === 'sub') {
                if ($bahanBaku->stok < $request->amount) {
                    return response()->json(['status' => 'error', 'message' => 'Stok tidak mencukupi.'], 400);
                }
                $bahanBaku->decrement('stok', $request->amount);
            } else {
                $bahanBaku->increment('stok', $request->amount);
            }

            return response()->json(['status' => 'success', 'message' => 'Stok berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $bahanBaku = BahanBaku::findOrFail($id);
            $bahanBaku->delete();
            return response()->json(['status' => 'success', 'message' => 'Data bahan baku berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
