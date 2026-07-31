<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->string('metode_pembayaran')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->decimal('total_penjualan', 12, 2)->default(0);
            $table->string('status_pembayaran')->default('Belum Dibayar');
            $table->string('status_pesanan')->default('Menunggu Pembayaran');
            $table->dateTime('waktu_pemesanan');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('penjualans'); }
};
