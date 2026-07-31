@extends('layouts.app')

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        min-height: calc(100vh - 80px);
        background: linear-gradient(135deg, rgba(253, 242, 242, 1) 0%, rgba(255, 255, 255, 1) 100%);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    
    .hero-bg-accent {
        position: absolute;
        top: -10%;
        right: -5%;
        width: 50vw;
        height: 50vw;
        background: radial-gradient(circle, rgba(220,38,38,0.08) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--dark);
        margin-bottom: 1.5rem;
    }

    .hero-title span {
        color: var(--primary);
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .hero-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 2rem;
    }

    .hero-image-wrapper {
        position: relative;
        z-index: 1;
    }

    .hero-image {
        width: 100%;
        max-width: 10000px;
        height: auto;
        border-radius: 24px;
        object-fit: cover;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        transform: rotate(2deg);
        transition: transform 0.5s ease;
    }

    .hero-image:hover {
        transform: rotate(0deg) scale(1.02);
    }
    
    /* Tentang Section */
    .tentang-section {
        padding: 100px 0;
        background-color: var(--light);
    }
    
    .tentang-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--dark);
    }
    
    .section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 4px;
        background-color: var(--primary);
        margin: 15px auto 0;
        border-radius: 2px;
    }

    @media (max-width: 991px) {
        .hero-section {
            padding: 60px 0;
            text-align: center;
        }
        .hero-title {
            font-size: 2.5rem;
        }
        .hero-image {
            margin-top: 3rem;
            max-width: 400px;
        }
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section id="beranda" class="hero-section">
    <div class="hero-bg-accent"></div>
    <div class="container hero-content">
        @php $menu = $menus->first(); @endphp
        <div class="row align-items-center">
            <!-- Kolom Kiri -->
            <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                <h1 class="hero-title">{{ explode(' ', $menu->nama_menu)[0] }} <span>{{ implode(' ', array_slice(explode(' ', $menu->nama_menu), 1)) }}</span></h1>
                <p class="hero-subtitle">
                    {{ $menu->deskripsi ?? 'Ayam crispy dengan sambal khas Bu Emi yang pedas, gurih, dan dibuat menggunakan bahan-bahan segar setiap hari.' }}
                </p>
                <div class="hero-price">
                    Rp{{ number_format($menu->harga, 0, ',', '.') }} <span class="fs-5 text-muted fw-normal">/ Porsi</span>
                </div>
                <div class="d-flex gap-3 justify-content-lg-start justify-content-center">
                    @if($menu->status == 'Habis')
                    <button class="btn btn-secondary btn-lg shadow" disabled>
                        Habis Terjual
                    </button>
                    @else
                    <a href="{{ route('order') }}" class="btn btn-primary btn-lg shadow">
                        Pesan Sekarang
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Kolom Kanan -->
            <div class="col-lg-6 text-center">
                <div class="hero-image-wrapper">
                    <!-- Ganti src ini dengan foto asli ayam geprek -->
                    @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="hero-image">
                    @else
                        <img src="{{ asset('storage/assets/kantin bu emi.jpeg') }}" alt="Ayam Geprek Bu Emi" class="hero-image">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Section -->
<section id="tentang" class="tentang-section">
    <div class="container">
        <div class="tentang-content">
            <h2 class="section-title">Tentang Kantin</h2>
            <p class="lead text-muted lh-lg">
                {{ $pengaturan->deskripsi_singkat ?? 'Kantin Makan Bu Emi menyediakan Ayam Geprek dengan ayam crispy, sambal khas, nasi hangat, dan lalapan segar. Seluruh menu dibuat setiap hari menggunakan bahan berkualitas sehingga menghasilkan cita rasa yang lezat dengan harga yang terjangkau.' }}
            </p>
        </div>
    </div>
</section>

@endsection
