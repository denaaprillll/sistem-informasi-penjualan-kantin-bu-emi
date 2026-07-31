<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pegawai extends Model {
    protected $table = 'pegawais';
    protected $fillable = ['nama_pegawai', 'posisi', 'no_hp'];
    public function pembelians() { return $this->hasMany(Pembelian::class); }
}
