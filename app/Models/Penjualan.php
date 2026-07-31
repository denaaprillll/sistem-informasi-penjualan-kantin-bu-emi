<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Penjualan extends Model {
    protected $table = 'penjualans';
    protected $fillable = ['no_pesanan', 'nama_pelanggan', 'no_hp', 'metode_pembayaran', 'bukti_transfer', 'total_penjualan', 'status_pembayaran', 'status_pesanan', 'waktu_pemesanan'];
    public function detailPenjualans() { return $this->hasMany(DetailPenjualan::class); }
}
