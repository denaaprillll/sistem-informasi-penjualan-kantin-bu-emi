@extends('layouts.app')

@push('styles')
<style>
    .success-section {
        padding: 80px 0;
        min-height: calc(100vh - 80px);
        background-color: var(--light);
        display: flex;
        align-items: center;
    }
    
    .success-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        border: none;
        padding: 50px 30px;
        text-align: center;
    }
    
    .check-icon {
        width: 100px;
        height: 100px;
        background-color: #d1e7dd;
        color: #198754;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 50px;
        margin: 0 auto 30px;
    }

    .order-info-box {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 25px;
        margin-top: 30px;
        margin-bottom: 30px;
        text-align: left;
    }
</style>
@endpush

@section('content')
<section class="success-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="success-card">
                    <div class="check-icon shadow-sm">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    
                    <h2 class="fw-bold mb-3">Pesanan Berhasil Dibuat</h2>
                    <p class="text-muted fs-5 mb-0">Terima kasih telah memesan Ayam Geprek Bu Emi.</p>
                    <p class="text-muted fs-5 mb-4">Silakan menunggu konfirmasi dari admin.</p>
                    
                    <div class="order-info-box border border-light shadow-sm">
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Nomor Pesanan</div>
                            <div class="col-6 fw-bold text-end">{{ $penjualan->no_pesanan }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Nama Pelanggan</div>
                            <div class="col-6 fw-bold text-end">{{ $penjualan->nama_pelanggan }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Metode Pembayaran</div>
                            <div class="col-6 fw-bold text-end">{{ $penjualan->metode_pembayaran ?? '-' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Status Pesanan</div>
                            <div class="col-6 text-end">
                                <span class="badge {{ $penjualan->status_pesanan == 'Menunggu Pembayaran' ? 'bg-secondary' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill">
                                    {{ $penjualan->status_pesanan }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-6 text-muted">Total Pembayaran</div>
                            <div class="col-6 fw-bold text-danger fs-5 text-end">Rp{{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5">
                        <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
