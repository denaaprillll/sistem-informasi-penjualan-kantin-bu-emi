<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Kantin Makan Bu Emi</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #dc2626; /* Red */
            --primary-hover: #b91c1c;
            --bg-light: #f3f4f6; /* Light gray */
            --text-dark: #1f2937;
            --text-muted: #6b7280;
            --white: #ffffff;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--white);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #f3f4f6;
        }

        .sidebar-header h4 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            font-size: 1.2rem;
        }

        .nav-menu {
            padding: 15px 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-item {
            padding: 0 15px;
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 15px;
            width: 24px;
            text-align: center;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background-color: #fef2f2;
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: var(--white);
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        }

        .nav-link.active i {
            color: var(--white);
        }

        /* Main Content */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Navbar */
        .top-navbar {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-dark);
            cursor: pointer;
            display: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Content Area */
        .content-area {
            padding: 25px;
            flex-grow: 1;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card.hover-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.05);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f3f4f6;
            padding: 15px 20px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* DataTables */
        table.dataTable {
            border-collapse: collapse !important;
        }
        
        table.dataTable thead th {
            border-bottom: 2px solid #f3f4f6;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        table.dataTable tbody td {
            vertical-align: middle;
            border-bottom: 1px solid #f3f4f6;
            color: var(--text-dark);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
            .toggle-btn {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <h4><i class="bi bi-shop me-2"></i>Kantin Bu Emi</h4>
        </div>
        <div class="nav-menu">
            <div class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/pesanan') }}" class="nav-link {{ request()->is('pesanan*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i> Daftar Pesanan
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/stok') }}" class="nav-link {{ request()->is('stok*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Stok Bahan Baku
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/menu') }}" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}">
                    <i class="bi bi-card-list"></i> Menu Makanan
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/pembelian') }}" class="nav-link {{ request()->is('pembelian*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Pembelian Bahan Baku
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/laporan') }}" class="nav-link {{ request()->is('laporan*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                </a>
            </div>
            <div class="nav-item mt-4">
                <a href="{{ url('/pengaturan') }}" class="nav-link {{ request()->is('pengaturan*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/') }}" class="nav-link w-100 text-danger hover-bg-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <button class="toggle-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="ms-auto user-profile">
                <div class="text-end me-2 d-none d-md-block">
                    <h6 class="mb-0 font-weight-bold">Admin Utama</h6>
                    <small class="text-muted">admin@kantinbuemi.com</small>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin+Utama&background=dc2626&color=fff" alt="Profile">
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Sidebar Toggle
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
            });

            // Initialize DataTables with default options
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                responsive: true
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
