<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\PembelianController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/payment/{id}', [OrderController::class, 'payment'])->name('payment');
Route::post('/checkout/{id}', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/pesanan/sukses/{id}', [OrderController::class, 'success'])->name('success');
Route::get('/pesanan/struk/{id}', [OrderController::class, 'struk'])->name('pesanan.struk');

// User Dashboard
Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

// Admin Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/pesanan', [PesananController::class, 'index'])->name('admin.pesanan');
Route::post('/pesanan/{id}', [PesananController::class, 'updateStatus'])->name('pesanan.update');
Route::get('/stok', [StokController::class, 'index'])->name('admin.stok');
Route::post('/stok', [StokController::class, 'store'])->name('admin.stok.store');
Route::put('/stok/{id}', [StokController::class, 'update'])->name('admin.stok.update');
Route::patch('/stok/{id}/adjust', [StokController::class, 'adjust'])->name('admin.stok.adjust');
Route::delete('/stok/{id}', [StokController::class, 'destroy'])->name('admin.stok.destroy');
Route::get('/menu', [App\Http\Controllers\Admin\MenuController::class, 'index'])->name('admin.menu');
Route::post('/menu', [App\Http\Controllers\Admin\MenuController::class, 'store'])->name('admin.menu.store');
Route::put('/menu/{id}', [App\Http\Controllers\Admin\MenuController::class, 'update'])->name('admin.menu.update');
Route::delete('/menu/{id}', [App\Http\Controllers\Admin\MenuController::class, 'destroy'])->name('admin.menu.destroy');
Route::get('/pembelian', [App\Http\Controllers\Admin\PembelianController::class, 'index'])->name('admin.pembelian');
Route::get('/pembelian/create', [App\Http\Controllers\Admin\PembelianController::class, 'create'])->name('admin.pembelian.create');
Route::post('/pembelian', [App\Http\Controllers\Admin\PembelianController::class, 'store'])->name('admin.pembelian.store');
Route::get('/pembelian/export', [App\Http\Controllers\Admin\PembelianController::class, 'exportExcel'])->name('admin.pembelian.export');
Route::get('/pembelian/print', [App\Http\Controllers\Admin\PembelianController::class, 'printReport'])->name('admin.pembelian.print');
Route::get('/pembelian/{id}', [App\Http\Controllers\Admin\PembelianController::class, 'show'])->name('admin.pembelian.show');
Route::get('/laporan', [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('admin.laporan');
Route::get('/pengaturan', [App\Http\Controllers\Admin\PengaturanController::class, 'index'])->name('admin.pengaturan');
Route::post('/pengaturan', function() { return redirect()->route('admin.pengaturan'); });
Route::post('/pengaturan/kantin', [App\Http\Controllers\Admin\PengaturanController::class, 'updateKantin'])->name('admin.pengaturan.kantin');
Route::post('/pengaturan/pembayaran', [App\Http\Controllers\Admin\PengaturanController::class, 'updatePembayaran'])->name('admin.pengaturan.pembayaran');
Route::post('/pengaturan/menu', [App\Http\Controllers\Admin\PengaturanController::class, 'updateMenu'])->name('admin.pengaturan.menu');
Route::post('/pengaturan/akun', [App\Http\Controllers\Admin\PengaturanController::class, 'updateAkun'])->name('admin.pengaturan.akun');
