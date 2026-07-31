<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Supplier;
use App\Models\BahanBaku;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with(['supplier', 'pegawai'])->orderBy('tanggal_pembelian', 'desc');
        
        $filter = $request->query('filter', 'today');
        $date = $request->query('date');
        $viewMode = 'today';

        if ($date) {
            $query->whereDate('tanggal_pembelian', $date);
            $viewMode = 'date';
        } else {
            if ($filter == 'history') {
                $viewMode = 'history';
            } elseif ($filter == 'week') {
                $query->whereBetween('tanggal_pembelian', [now()->startOfWeek(), now()->endOfWeek()]);
                $viewMode = 'week';
            } elseif ($filter == 'month') {
                $query->whereMonth('tanggal_pembelian', now()->month)->whereYear('tanggal_pembelian', now()->year);
                $viewMode = 'month';
            } elseif ($filter == 'all') {
                $viewMode = 'all';
            } else {
                $query->whereDate('tanggal_pembelian', today());
                $viewMode = 'today';
            }
        }

        $pembelians = $query->get();
        $totalTransaksi = $pembelians->count();
        $totalPengeluaran = $pembelians->sum('total_pembelian');

        return view('pembelian.index', compact('pembelians', 'viewMode', 'date', 'filter', 'totalTransaksi', 'totalPengeluaran'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $bahanBakus = BahanBaku::all();
        return view('pembelian.create', compact('suppliers', 'bahanBakus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required|exists:suppliers,id',
            'bahan_baku'    => 'required|array|min:1',
            'satuan'        => 'required|array|min:1',
            'jumlah'        => 'required|array|min:1',
            'jumlah.*'      => 'numeric|min:1',
            'harga_satuan'  => 'required|array|min:1',
            'harga_satuan.*'=> 'numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalPembelian = 0;
            $details = [];

            foreach ($request->bahan_baku as $index => $bahan_baku_val) {
                $jumlah = $request->jumlah[$index];
                $harga_satuan = $request->harga_satuan[$index];
                $satuan = $request->satuan[$index];
                $subtotal = $jumlah * $harga_satuan;
                
                $totalPembelian += $subtotal;
                
                // Cek apakah bahan baku adalah ID angka (bahan baku lama) atau string (bahan baku baru)
                if (is_numeric($bahan_baku_val) && BahanBaku::find($bahan_baku_val)) {
                    $bahan_baku_id = $bahan_baku_val;
                } else {
                    // Buat bahan baku baru jika diketik manual
                    $newBahanBaku = BahanBaku::create([
                        'nama_bahan' => $bahan_baku_val,
                        'stok' => 0, // Stok awal 0, nanti di-increment di bawah
                        'satuan' => $satuan
                    ]);
                    $bahan_baku_id = $newBahanBaku->id;
                }

                $details[] = [
                    'bahan_baku_id' => $bahan_baku_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga_satuan,
                    'subtotal' => $subtotal,
                ];
            }

            // Create parent record
            $pembelian = Pembelian::create([
                'pegawai_id' => null, // Set to null instead of 1 because pegawais table might be empty
                'supplier_id' => $request->supplier_id,
                'tanggal_pembelian' => now(),
                'total_pembelian' => $totalPembelian,
            ]);

            // Save details and update stock
            foreach ($details as $detail) {
                $pembelian->detailPembelians()->create($detail);
                
                // Update stok
                BahanBaku::where('id', $detail['bahan_baku_id'])->increment('stok', $detail['jumlah']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success', 
                'message' => 'Data pembelian berhasil dicatat dan stok bahan baku bertambah.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error', 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'pegawai', 'detailPembelians.bahanBaku'])->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $pembelian
        ]);
    }

    public function printReport(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $query = Pembelian::with(['supplier', 'pegawai'])->orderBy('tanggal_pembelian', 'asc');
        
        $title = "Laporan Pembelian";
        $periode = "";

        if ($filter == 'week') {
            $query->whereBetween('tanggal_pembelian', [now()->startOfWeek(), now()->endOfWeek()]);
            $title = "LAPORAN MINGGUAN PEMBELIAN BAHAN BAKU";
            $periode = "Periode : \n" . now()->startOfWeek()->translatedFormat('d F Y') . ' - ' . now()->endOfWeek()->translatedFormat('d F Y');
        } elseif ($filter == 'month') {
            $query->whereMonth('tanggal_pembelian', now()->month)
                  ->whereYear('tanggal_pembelian', now()->year);
            $title = "LAPORAN BULANAN PEMBELIAN BAHAN BAKU";
            $periode = "Periode : \nBulan " . now()->translatedFormat('F Y');
        } else {
            $query->whereDate('tanggal_pembelian', today());
            $title = "LAPORAN HARIAN PEMBELIAN BAHAN BAKU";
            $periode = "Tanggal : \n" . today()->translatedFormat('d F Y');
        }

        $pembelians = $query->get();
        $totalTransaksi = $pembelians->count();
        $totalPengeluaran = $pembelians->sum('total_pembelian');
        
        $frequentSupplier = null;
        if ($filter == 'month') {
            $supplierCount = $pembelians->groupBy('supplier_id')->map->count();
            if ($supplierCount->count() > 0) {
                $topSupplierId = $supplierCount->sortDesc()->keys()->first();
                $frequentSupplier = \App\Models\Supplier::find($topSupplierId)->nama_supplier ?? 'Tanpa Supplier';
            } else {
                $frequentSupplier = '-';
            }
        }

        return view('pembelian.print', compact('pembelians', 'title', 'periode', 'filter', 'totalTransaksi', 'totalPengeluaran', 'frequentSupplier'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->query('filter', 'today');
        $query = Pembelian::with(['supplier', 'pegawai'])->orderBy('tanggal_pembelian', 'asc');
        
        $filename = "Export_Pembelian_";

        if ($filter == 'week') {
            $query->whereBetween('tanggal_pembelian', [now()->startOfWeek(), now()->endOfWeek()]);
            $filename .= "Mingguan_" . now()->format('Y-m-d');
        } elseif ($filter == 'month') {
            $query->whereMonth('tanggal_pembelian', now()->month)
                  ->whereYear('tanggal_pembelian', now()->year);
            $filename .= "Bulanan_" . now()->format('Y-m');
        } else {
            $query->whereDate('tanggal_pembelian', today());
            $filename .= "Harian_" . today()->format('Y-m-d');
        }

        $pembelians = $query->get();
        
        $csvFileName = $filename . '.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID Pembelian', 'Tanggal', 'Supplier', 'Pegawai', 'Total');

        $callback = function() use($pembelians, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($pembelians as $p) {
                $row['ID Pembelian'] = 'PUR-' . str_pad($p->id, 4, '0', STR_PAD_LEFT);
                $row['Tanggal'] = date('d M Y H:i', strtotime($p->tanggal_pembelian));
                $row['Supplier'] = $p->supplier->nama_supplier ?? 'Tanpa Supplier';
                $row['Pegawai'] = $p->pegawai->nama_pegawai ?? 'Admin';
                $row['Total'] = $p->total_pembelian;

                fputcsv($file, array($row['ID Pembelian'], $row['Tanggal'], $row['Supplier'], $row['Pegawai'], $row['Total']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
