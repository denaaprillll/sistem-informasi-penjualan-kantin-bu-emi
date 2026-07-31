<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Makan Bu Emi - Spesialis Ayam Geprek</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #dc2626; /* Red */
            --primary-hover: #b91c1c;
            --light: #f9fafb;
            --dark: #111827;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #374151;
            scroll-behavior: smooth;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            color: var(--primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            color: #4b5563 !important;
            position: relative;
            margin: 0 10px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.2);
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: #d1d5db;
            padding: 60px 0 20px;
        }
        
        footer h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        footer a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        footer a:hover {
            color: var(--primary);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                Kantin Bu Emi
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->is('/') ? '#beranda' : url('/#beranda') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->is('/') ? '#tentang' : url('/#tentang') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ request()->is('/') ? '#lokasi' : url('/#lokasi') }}">Lokasi</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary rounded-pill px-4 btn-sm mt-1" href="{{ route('user.dashboard') }}">Cek Pesanan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @php
        $pengaturan = \App\Models\Pengaturan::firstOrCreate(['id' => 1]);
    @endphp

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="lokasi">
        <div class="container">
            <div class="row gy-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <h5 class="fs-4 text-white mb-3">{{ $pengaturan->nama_kantin ?? 'Kantin Makan Bu Emi' }}</h5>
                    <p class="mb-4 text-secondary">{{ $pengaturan->deskripsi_singkat ?? 'Spesialis Ayam Geprek dengan cita rasa khas yang pedas, gurih, dan lezat.' }}</p>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h5><i class="bi bi-geo-alt me-2 text-danger"></i> Lokasi Kami</h5>
                    <p class="mb-2">
                        <a href="{{ $pengaturan->link_gmaps ?? '#' }}" target="_blank" class="text-decoration-none text-secondary">
                            {{ $pengaturan->alamat ?? 'Jl. Pendidikan No. 123, Kota Pelajar, 54321' }}
                        </a>
                    </p>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <h5><i class="bi bi-clock me-2 text-danger"></i> Jam Operasional</h5>
                    <p class="fw-bold text-white mb-4">{{ $pengaturan->jam_operasional ?? 'Senin – Sabtu (08.00 – 20.00 WIB)' }}</p>
                    
                    <h5><i class="bi bi-telephone me-2 text-danger"></i> Hubungi Kami</h5>
                    <p class="mb-0 text-secondary">📞 {{ $pengaturan->no_hp ?? '0812-3456-7890' }}</p>
                    <p class="text-secondary">👤 Atas Nama: {{ $pengaturan->nama_pemilik ?? 'Bu Emi' }}</p>
                </div>
            </div>
            
            <div class="row pt-4 border-top border-secondary">
                <div class="col-12 text-center text-secondary">
                    <small>Copyright &copy; {{ date('Y') }} Kantin Makan Bu Emi. All rights reserved.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @stack('scripts')
</body>
</html>
