@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Pesanan</h4>
</div>

<div class="card">
    <div class="card-body">
        <!-- Navigation & Filters -->
        <div class="row mb-4 align-items-end">
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="btn-group w-100 shadow-sm" role="group">
                    <a href="{{ route('admin.pesanan', ['filter' => 'today']) }}" class="btn {{ $viewMode == 'today' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-calendar-event me-1"></i> Hari Ini
                    </a>
                    <a href="{{ route('admin.pesanan', ['filter' => 'history']) }}" class="btn {{ $viewMode == 'history' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="bi bi-folder2-open me-1"></i> Riwayat Hari Sebelumnya
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <form action="{{ route('admin.pesanan') }}" method="GET" id="dateFilterForm">
                    <label class="form-label text-muted mb-1 small">Pilih Tanggal</label>
                    <input type="date" class="form-control" name="date" value="{{ $date ?? '' }}" onchange="document.getElementById('dateFilterForm').submit()">
                </form>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted mb-1 small">Filter Status</label>
                <select class="form-select" id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Siap Diambil">Siap Diambil</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        @if($viewMode == 'history')
            @php 
                $groupedPenjualans = $penjualans->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->created_at)->locale('id')->translatedFormat('d F Y');
                });
            @endphp
            
            @forelse($groupedPenjualans as $tanggal => $group)
                <div class="mt-4 mb-3 fw-bold text-primary border-bottom pb-2 fs-5">
                    <i class="bi bi-calendar3 me-2"></i> ===== {{ $tanggal }} =====
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-hover datatable-history" width="100%">
                        @include('pesanan.partials.table', ['penjualans' => $group])
                    </table>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-folder-x fs-1 d-block mb-3"></i>
                    <h5>Belum ada riwayat pesanan</h5>
                </div>
            @endforelse
        @else
            @if($viewMode == 'date')
                <div class="mb-3 fw-bold text-primary border-bottom pb-2 fs-5">
                    <i class="bi bi-calendar-check me-2"></i> Menampilkan pesanan pada: {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover" id="pesananTable" width="100%">
                    @include('pesanan.partials.table', ['penjualans' => $penjualans])
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableOptions = {
            order: [[1, 'desc']], // Urutkan berdasarkan waktu
            columnDefs: [
                { orderable: false, targets: 7 }
            ]
        };
        
        var table;
        if ($('#pesananTable').length > 0) {
            table = $('#pesananTable').DataTable(tableOptions);
        } else {
            // Untuk multiple table di riwayat
            table = $('.datatable-history').DataTable(tableOptions);
        }

        // Custom status filter
        $('#statusFilter').on('change', function() {
            if ($('#pesananTable').length > 0) {
                table.column(6).search(this.value).draw();
            } else {
                table.each(function() {
                    $(this).DataTable().column(6).search($('#statusFilter').val()).draw();
                });
            }
        });
    });

    function showDetailModal(pesanan, detail) {
        let buktiTransferHtml = '';
        let storageUrl = "{{ asset('storage') }}";
        if ((pesanan.metode_pembayaran === 'DANA' || pesanan.metode_pembayaran === 'Transfer DANA') && pesanan.bukti_transfer) {
            buktiTransferHtml = `
                <hr>
                <h6 class="fw-bold mb-2">Bukti Transfer (DANA)</h6>
                <div class="text-center">
                    <img src="${storageUrl}/${pesanan.bukti_transfer}" class="img-fluid rounded shadow-sm border" alt="Bukti Transfer">
                </div>
            `;
        }

        let menuName = detail && detail.menu ? detail.menu.nama_menu : 'Ayam Geprek';
        let qty = detail ? detail.jumlah : 0;
        let level = detail ? detail.level_sambal : '-';
        let catatan = detail ? detail.catatan : '-';
        
        let totalFmt = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(pesanan.total_penjualan);

        Swal.fire({
            title: 'Detail Pesanan',
            html: `
                <div class="text-start fs-6">
                    <p class="mb-1"><strong>ID:</strong> ${pesanan.no_pesanan}</p>
                    <p class="mb-1"><strong>Nama:</strong> ${pesanan.nama_pelanggan}</p>
                    <p class="mb-1"><strong>No HP:</strong> ${pesanan.no_hp}</p>
                    <p class="mb-1"><strong>Waktu:</strong> ${pesanan.waktu_pemesanan}</p>
                    <hr>
                    <p class="mb-1"><strong>Menu:</strong> ${menuName}</p>
                    <p class="mb-1"><strong>Jumlah:</strong> ${qty} Porsi</p>
                    <p class="mb-1"><strong>Level Sambal:</strong> ${level}</p>
                    <p class="mb-1 text-danger"><strong>Catatan:</strong> ${catatan}</p>
                    <p class="mb-1 mt-2"><strong>Metode:</strong> <span class="badge bg-light text-dark border">${pesanan.metode_pembayaran || '-'}</span></p>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Total Pembayaran</span>
                        <span class="text-danger">${totalFmt}</span>
                    </div>
                    ${buktiTransferHtml}
                </div>
            `,
            width: 500,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#6c757d'
        });
    }
</script>
@endpush
