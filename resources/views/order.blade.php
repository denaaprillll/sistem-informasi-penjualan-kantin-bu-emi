@extends('layouts.app')

@push('styles')
<style>
    .order-section {
        padding: 80px 0;
        min-height: calc(100vh - 80px);
        background-color: var(--light);
    }
    
    .order-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        border: none;
    }
    
    .order-header {
        background-color: var(--primary);
        color: white;
        padding: 30px;
        text-align: center;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(220, 38, 38, 0.25);
    }
</style>
@endpush

@section('content')
<section class="order-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-4">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                </div>

                <div class="card order-card">
                    <div class="order-header">
                        <h3 class="fw-bold mb-0">Form Pemesanan</h3>
                        <p class="text-white-50 mb-0 mt-1">Ayam Geprek Bu Emi</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        
                        @php $menu = $menus->first(); @endphp
                        <div class="row mb-4 align-items-center p-3 bg-light rounded-3">
                            <div class="col-auto">
                                @if($menu->foto)
                                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <img src="https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?q=80&w=150&auto=format&fit=crop" alt="Ayam Geprek" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="col">
                                <h5 class="fw-bold mb-1">{{ $menu->nama_menu }}</h5>
                                <div class="d-flex align-items-center">
                                    <p class="text-danger fw-bold mb-0 me-2">Rp{{ number_format($menu->harga, 0, ',', '.') }} / Porsi</p>
                                    @if($menu->status == 'Habis')
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('order.store') }}" method="POST">
                            @csrf
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Pemesan</h5>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-lg" name="nama" placeholder="Masukkan nama Anda" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-medium">Nomor WhatsApp / HP</label>
                                <input type="tel" class="form-control form-control-lg" name="no_hp" placeholder="Contoh: 081234567890" required>
                            </div>
                            
                            <h5 class="fw-bold mb-3 mt-5 border-bottom pb-2">Detail Pesanan</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Jumlah Porsi</label>
                                    <input type="number" class="form-control form-control-lg" name="jumlah" value="1" min="1" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-medium">Pilihan Sambal</label>
                                    <select class="form-select form-control-lg" name="level">
                                        <option value="Sedang">Sedang</option>
                                        <option value="Pedas">Pedas</option>
                                        <option value="Sangat Pedas">Sangat Pedas</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-medium">Catatan (Opsional)</label>
                                <textarea class="form-control" name="catatan" rows="3" placeholder="Contoh: Sambal dipisah, dll."></textarea>
                            </div>
                            
                            <div class="d-grid mt-5">
                                @if($menu->status == 'Habis')
                                <button type="button" class="btn btn-secondary btn-lg" disabled>
                                    Menu Sedang Habis
                                </button>
                                @else
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Konfirmasi Pesanan
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
