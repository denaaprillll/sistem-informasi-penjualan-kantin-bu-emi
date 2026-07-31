@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-primary text-white text-center py-4">
                    <h4 class="fw-bold mb-0">Struk Pesanan</h4>
                    <p class="mb-0 opacity-75 small">Tunjukkan struk ini kepada kasir</p>
                </div>
                <div class="card-body p-4 bg-light">
                    <div class="text-center mb-4 border-bottom pb-4">
                        <p class="text-muted small mb-1">Nomor Pesanan</p>
                        <h3 class="fw-bold text-dark">{{ $penjualan->no_pesanan }}</h3>
                        <div class="mt-2">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                {{ $penjualan->status_pembayaran }}
                            </span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nama Pemesan</span>
                        <span class="fw-bold">{{ $penjualan->nama_pelanggan }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nomor HP</span>
                        <span class="fw-bold">{{ $penjualan->no_hp }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 pb-3 border-bottom">
                        <span class="text-muted">Waktu Pesan</span>
                        <span class="fw-bold">{{ date('d M Y, H:i', strtotime($penjualan->waktu_pemesanan)) }}</span>
                    </div>

                    @php $detail = $penjualan->detailPenjualans->first(); @endphp
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3">Detail Pesanan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $detail->menu->nama_menu ?? 'Ayam Geprek' }} x{{ $detail->jumlah ?? 0 }}</span>
                            <span>Rp{{ number_format(($detail->harga_satuan ?? 0) * ($detail->jumlah ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="text-muted small mb-1">Level Sambal: {{ $detail->level_sambal ?? '-' }}</div>
                        <div class="text-muted small">Catatan: {{ $detail->catatan ?? '-' }}</div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Total Pembayaran</span>
                            <span class="fw-bold fs-4 text-danger">Rp{{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center py-4 border-0">
                    <p class="text-muted small mb-3">Mohon selesaikan pembayaran di kasir Kantin Bu Emi sebelum pesanan diproses.</p>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4">Kembali ke Beranda</a>
                    <a href="{{ route('user.dashboard') }}?no_hp={{ urlencode($penjualan->no_hp) }}" class="btn btn-primary rounded-pill px-4 ms-2">Cek Status Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
