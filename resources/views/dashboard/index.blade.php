@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Dashboard</h4>
    <div class="text-muted"><i class="bi bi-calendar3"></i> {{ date('d F Y') }}</div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Pesanan -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1 fs-6">Total Pesanan Hari Ini</p>
                        <h3 class="mb-0 fw-bold">{{ $pesananHariIni }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger">
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Penjualan -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted fw-bold">Penjualan Hari Ini</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success">
                        <i class="bi bi-cash-coin fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Pembelian -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted fw-bold">Pembelian Stok Bahan Hari Ini</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($pembelianHariIni, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                        <i class="bi bi-cart-dash fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jumlah Menu -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted fw-bold">Jumlah Menu</h6>
                        <h3 class="fw-bold mb-0">{{ $jumlahMenu }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info">
                        <i class="bi bi-card-list fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Hari Ini -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted fw-bold">Pesanan Hari Ini</h6>
                        <h3 class="fw-bold mb-0">{{ $pesananHariIni }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Hampir Habis -->
    <div class="col-xl-4 col-md-6">
        <div class="card hover-animate h-100 border-start border-4 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-muted fw-bold">Stok Hampir Habis</h6>
                        <h3 class="fw-bold mb-0 text-danger">{{ $stokHampirHabis }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Grafik Penjualan (7 Hari Terakhir)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(220, 38, 38, 0.5)');
        gradient.addColorStop(1, 'rgba(220, 38, 38, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($data) !!},
                    borderColor: '#dc2626',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#dc2626',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(31, 41, 55, 0.9)',
                    titleFont: {
                        family: "'Poppins', sans-serif",
                        size: 13
                    },
                    bodyFont: {
                        family: "'Poppins', sans-serif",
                        size: 14
                    },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        borderDash: [5, 5],
                        color: '#f3f4f6'
                    },
                    ticks: {
                        font: {
                            family: "'Poppins', sans-serif"
                        },
                        callback: function(value, index, values) {
                            return 'Rp ' + (value/1000) + 'k';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: "'Poppins', sans-serif"
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
    });
</script>
@endpush
