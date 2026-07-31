<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DetailPenjualan extends Model {
    protected $table = 'detail_penjualans';
    protected $fillable = ['penjualan_id', 'menu_id', 'jumlah', 'level_sambal', 'catatan', 'harga_satuan', 'subtotal'];
    public function penjualan() { return $this->belongsTo(Penjualan::class); }
    public function menu() { return $this->belongsTo(Menu::class); }
}
