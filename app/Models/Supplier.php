<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Supplier extends Model {
    protected $table = 'suppliers';
    protected $fillable = ['nama_supplier', 'alamat', 'no_hp'];
    public function pembelians() { return $this->hasMany(Pembelian::class); }
}
