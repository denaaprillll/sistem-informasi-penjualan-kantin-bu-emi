<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kantin', 'nama_pemilik', 'alamat', 'link_gmaps', 'no_hp', 'jam_operasional', 'deskripsi_singkat', 'dana_nomor', 'dana_nama', 'dana_qr'
    ];
}
