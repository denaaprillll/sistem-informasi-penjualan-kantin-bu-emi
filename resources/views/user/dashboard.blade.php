@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
<div class="container py-5 mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <p class="text-muted">Cek status dan riwayat pesanan Anda</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('user.dashboard') }}" method="GET" class="d-flex shadow-sm rounded">
                <input type="text" name="no_hp" class="form-control form-control-lg border-0" placeholder="Masukkan Nomor HP Anda" value="{{ $no_hp ?? '' }}" required>
                <button class="btn btn-primary px-4" type="submit">Cari</button>
            </form>
        </div>
    </div>

    @if($no_hp)
        <div class="row g-4">
            @forelse($pesanans as $pesanan)
                @php $detail = $pesanan->detailPenjualans->first(); @endphp
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <span class="text-muted small">Nomor Pesanan</span>
                                    <h5 class="fw-bold mb-0">{{ $pesanan->no_pesanan }}</h5>
                                </div>
                                <div class="text-end">
                                    @php
                                        $statusClass = 'bg-secondary';
                                        if($pesanan->status_pesanan == 'Menunggu Verifikasi' || $pesanan->status_pesanan == 'Menunggu Pembayaran') $statusClass = 'bg-warning text-dark';
                                        if($pesanan->status_pesanan == 'Diproses' || $pesanan->status_pesanan == 'Siap Diambil') $statusClass = 'bg-primary';
                                        if($pesanan->status_pesanan == 'Selesai') $statusClass = 'bg-success';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill">{{ $pesanan->status_pesanan }}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-8">
                                    <h6 class="fw-bold mb-1">{{ $detail->menu->nama_menu ?? 'Ayam Geprek' }}</h6>
                                    <p class="text-muted small mb-0">{{ $detail->jumlah ?? 0 }} Porsi | Level: {{ $detail->level_sambal ?? '-' }}</p>
                                </div>
                                <div class="col-4 text-end">
                                    <h6 class="fw-bold text-danger mb-0">Rp{{ number_format($pesanan->total_penjualan, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                            
                            @if($pesanan->status_pembayaran == 'Belum Dibayar' && $pesanan->metode_pembayaran != 'Tunai (COD)')
                                <a href="{{ route('payment', $pesanan->id) }}" class="btn btn-outline-danger w-100 mt-2">Selesaikan Pembayaran</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" alt="No Orders" width="120" class="mb-3 opacity-50">
                    <h5 class="text-muted">Tidak ada pesanan ditemukan untuk nomor HP tersebut.</h5>
                </div>
            @endforelse
        </div>
    @else
        <div class="text-center py-5 bg-light rounded-3 mt-4">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">Silakan masukkan nomor HP Anda untuk melihat riwayat pesanan.</h5>
        </div>
    @endif
</div>
@endsection
