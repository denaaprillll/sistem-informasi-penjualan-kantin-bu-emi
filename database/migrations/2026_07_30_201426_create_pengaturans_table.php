<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            // Informasi Kantin
            $table->string('nama_kantin')->default('Kantin Bu Emi');
            $table->string('nama_pemilik')->default('Bu Emi');
            $table->text('alamat')->nullable();
            $table->string('link_gmaps')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            
            // Pengaturan Pembayaran (DANA)
            $table->string('dana_nomor')->nullable();
            $table->string('dana_nama')->nullable();
            $table->string('dana_qr')->nullable(); // path to image
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
