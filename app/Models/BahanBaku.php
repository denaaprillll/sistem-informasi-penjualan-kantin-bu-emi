<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BahanBaku extends Model {
    protected $table = 'bahan_bakus';
    protected $fillable = ['nama_bahan', 'stok', 'satuan'];
    public function detailPembelians() { return $this->hasMany(DetailPembelian::class); }
}
