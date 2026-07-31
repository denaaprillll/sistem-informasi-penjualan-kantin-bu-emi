@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
    <h4 class="mb-0">Laporan Penjualan & Keuangan</h4>
    <div>
        <button class="btn btn-outline-danger me-2" onclick="window.print()"><i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF/Print</button>
        <button class="btn btn-outline-success" onclick="exportToExcel()"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</button>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-pills mb-4 d-print-none" id="reportTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab">Laporan Harian</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly" type="button" role="tab">Laporan Mingguan</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">Laporan Bulanan</button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="reportTabsContent">
    <!-- Laporan Harian -->
    <div class="tab-pane fade show active" id="daily" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Total Penjualan Harian</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPenjualanHarian, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Total Pembelian Harian</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPembelianHarian, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Keuntungan Bersih</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($keuntunganHarian, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm exportable-section">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Statistik Penjualan Harian (Menu)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Nama Menu</th>
                                <th>Terjual</th>
                                <th>Total Pemasukan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menuTerlarisHarian as $index => $item)
                            <tr>
                                <td><span class="badge {{ $index == 0 ? 'bg-warning text-dark' : 'bg-secondary' }} fs-6">#{{ $index + 1 }}</span></td>
                                <td class="fw-bold">{{ $item->menu->nama_menu }}</td>
                                <td>{{ $item->total_terjual }} Porsi</td>
                                <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Belum ada data penjualan hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Laporan Mingguan -->
    <div class="tab-pane fade" id="weekly" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white h-100">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Total Pesanan</p>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalPesananMingguan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white h-100">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Total Penjualan</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPenjualanMingguan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Total Pembelian</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($totalPembelianMingguan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white h-100">
                    <div class="card-body">
                        <p class="mb-1 opacity-75">Pendapatan Bersih</p>
                        <h3 class="mb-0 fw-bold">Rp {{ number_format($keuntunganMingguan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm exportable-section">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Grafik Penjualan Mingguan</h5>
            </div>
            <div class="card-body">
                <canvas id="weeklySalesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Laporan Bulanan -->
    <div class="tab-pane fade" id="monthly" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Pendapatan Bulanan</p>
                        <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($totalPenjualanBulanan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-danger">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Pengeluaran (Pembelian)</p>
                        <h3 class="mb-0 fw-bold text-danger">Rp {{ number_format($totalPembelianBulanan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 border-start border-4 border-primary">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Laba Bersih</p>
                        <h3 class="mb-0 fw-bold text-primary">Rp {{ number_format($keuntunganBulanan, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Grafik Penjualan Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlySalesChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Grafik Pembelian Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyPurchasesChart" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mt-4">
                <div class="card border-0 shadow-sm exportable-section">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Statistik Penjualan Bulan Ini (Menu)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Peringkat</th>
                                        <th>Nama Menu</th>
                                        <th>Total Terjual</th>
                                        <th>Total Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menuTerlarisBulanan as $index => $item)
                                    <tr>
                                        <td><span class="badge {{ $index == 0 ? 'bg-warning text-dark' : 'bg-secondary' }} fs-6">#{{ $index + 1 }}</span></td>
                                        <td class="fw-bold">{{ $item->menu->nama_menu }}</td>
                                        <td>{{ $item->total_terjual }} Porsi</td>
                                        <td>Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">Belum ada data penjualan bulan ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tab styling customization
        $('.nav-pills .nav-link').css('color', '#6b7280');
        $('.nav-pills .nav-link.active').css({
            'background-color': '#dc2626',
            'color': '#fff'
        });

        $('.nav-pills .nav-link').on('click', function() {
            $('.nav-pills .nav-link').css({
                'background-color': 'transparent',
                'color': '#6b7280'
            });
            $(this).css({
                'background-color': '#dc2626',
                'color': '#fff'
            });
        });

        // Initialize Charts when Weekly or Monthly tab is shown
        $('button[data-bs-target="#weekly"]').on('shown.bs.tab', function (e) {
            initWeeklyCharts();
        });
        
        $('button[data-bs-target="#monthly"]').on('shown.bs.tab', function (e) {
            initMonthlyCharts();
        });
    });

    let weeklyChartsRendered = false;
    
    function initWeeklyCharts() {
        if (weeklyChartsRendered) return;
        
        const ctxWeekly = document.getElementById('weeklySalesChart').getContext('2d');
        const grafikMingguan = @json($grafikMingguan);

        new Chart(ctxWeekly, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: grafikMingguan,
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + (value/1000000) + 'jt'; }
                        }
                    }
                }
            }
        });
        
        weeklyChartsRendered = true;
    }

    let monthlyChartsRendered = false;
    
    function initMonthlyCharts() {
        if (monthlyChartsRendered) return;
        
        const grafikPenjualanBulanan = @json($grafikPenjualanBulanan);
        const grafikPembelianBulanan = @json($grafikPembelianBulanan);

        // Sales Chart
        const ctxSales = document.getElementById('monthlySalesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Pendapatan',
                    data: grafikPenjualanBulanan,
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + (value/1000000) + 'jt'; }
                        }
                    }
                }
            }
        });

        // Purchases Chart
        const ctxPurchases = document.getElementById('monthlyPurchasesChart').getContext('2d');
        new Chart(ctxPurchases, {
            type: 'line',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Pengeluaran',
                    data: grafikPembelianBulanan,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + (value/1000000) + 'jt'; }
                        }
                    }
                }
            }
        });
        
        monthlyChartsRendered = true;
    }

    function exportToExcel() {
        // Cari tab pane yang sedang aktif
        let activeTabId = $('.tab-pane.active').attr('id');
        let section = document.getElementById(activeTabId);
        
        // Cari tabel di dalam tab aktif
        let table = section.querySelector('table');
        if (!table) {
            Swal.fire('Info', 'Tidak ada data tabel untuk diekspor pada laporan ini.', 'info');
            return;
        }

        // Konversi HTML Table ke CSV
        let csv = [];
        let rows = table.querySelectorAll('tr');
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length; j++) {
                // Bersihkan teks dari newline dan ekstra spasi
                let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                row.push('"' + text + '"');
            }
            csv.push(row.join(','));
        }

        // Buat file dan trigger download
        let csvContent = csv.join('\n');
        let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        let url = URL.createObjectURL(blob);
        let link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", `laporan-${activeTabId}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<style>
    @media print {
        body { background: #fff; }
        .sidebar, .navbar, .d-print-none, .btn { display: none !important; }
        .main-content { margin-left: 0 !important; width: 100% !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; margin-bottom: 20px; page-break-inside: avoid; }
        .card-body h3 { font-size: 1.5rem !important; }
        canvas { max-width: 100% !important; }
    }
</style>
@endpush
