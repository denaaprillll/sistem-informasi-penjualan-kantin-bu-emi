<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pembelian extends Model {
    protected $table = 'pembelians';
    protected $fillable = ['pegawai_id', 'supplier_id', 'tanggal_pembelian', 'total_pembelian'];
    public function pegawai() { return $this->belongsTo(Pegawai::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function detailPembelians() { return $this->hasMany(DetailPembelian::class); }
}
