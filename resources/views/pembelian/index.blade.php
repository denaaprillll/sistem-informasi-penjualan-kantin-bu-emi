@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Riwayat Pembelian Bahan Baku</h4>
    <a href="{{ route('admin.pembelian.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Catat Pembelian Baru
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white h-100 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title opacity-75">Jumlah Transaksi</h6>
                <h3 class="mb-0">{{ $totalTransaksi }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-success text-white h-100 border-0 shadow-sm">
            <div class="card-body">
                <h6 class="card-title opacity-75">Total Pengeluaran</h6>
                <h3 class="mb-0">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Navigation & Filters -->
        <div class="row mb-4 align-items-end">
            <div class="col-md-5 mb-3 mb-md-0">
                <label class="form-label text-muted small mb-1">Periode Laporan</label>
                <div class="btn-group w-100 shadow-sm" role="group">
                    <a href="{{ route('admin.pembelian', ['filter' => 'today']) }}" class="btn {{ $filter == 'today' && !$date ? 'btn-primary' : 'btn-outline-primary' }}">Hari Ini</a>
                    <a href="{{ route('admin.pembelian', ['filter' => 'week']) }}" class="btn {{ $filter == 'week' ? 'btn-primary' : 'btn-outline-primary' }}">Minggu Ini</a>
                    <a href="{{ route('admin.pembelian', ['filter' => 'month']) }}" class="btn {{ $filter == 'month' ? 'btn-primary' : 'btn-outline-primary' }}">Bulan Ini</a>
                    <a href="{{ route('admin.pembelian', ['filter' => 'all']) }}" class="btn {{ $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua Riwayat</a>
                </div>
            </div>
            
            <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label text-muted small mb-1">Tampilan</label>
                <div class="btn-group w-100 shadow-sm" role="group">
                    <a href="{{ route('admin.pembelian', ['filter' => 'today']) }}" class="btn {{ $viewMode == 'today' && !$date ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        <i class="bi bi-calendar-event me-1"></i> Hari Ini
                    </a>
                    <a href="{{ route('admin.pembelian', ['filter' => 'history']) }}" class="btn {{ $viewMode == 'history' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                        <i class="bi bi-folder2-open me-1"></i> Lihat Hari Sebelumnya
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <form action="{{ route('admin.pembelian') }}" method="GET" id="dateFilterForm">
                    <label class="form-label text-muted mb-1 small">Pilih Tanggal</label>
                    <input type="date" class="form-control" name="date" value="{{ $date ?? '' }}" onchange="document.getElementById('dateFilterForm').submit()">
                </form>
            </div>
        </div>
        
        <div class="mb-4">
            <a href="{{ route('admin.pembelian.print', ['filter' => $filter]) }}" target="_blank" class="btn btn-dark me-2">
                <i class="bi bi-printer me-1"></i> Cetak {{ $filter == 'week' ? 'Mingguan' : ($filter == 'month' ? 'Bulanan' : 'Laporan Hari Ini') }}
            </a>
            <a href="{{ route('admin.pembelian.export', ['filter' => $filter]) }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
            </a>
        </div>

        @if($viewMode == 'history')
            @php 
                $groupedPembelians = $pembelians->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->tanggal_pembelian)->locale('id')->translatedFormat('d F Y');
                });
            @endphp
            
            @forelse($groupedPembelians as $tanggal => $group)
                <div class="mt-4 mb-3 fw-bold text-primary border-bottom pb-2 fs-5">
                    <i class="bi bi-calendar3 me-2"></i> ===== {{ $tanggal }} =====
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-hover datatable-history" width="100%">
                        @include('pembelian.partials.table', ['pembelians' => $group])
                    </table>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-folder-x fs-1 d-block mb-3"></i>
                    <h5>Belum ada riwayat pembelian</h5>
                </div>
            @endforelse
        @else
            @if($viewMode == 'date')
                <div class="mb-3 fw-bold text-primary border-bottom pb-2 fs-5">
                    <i class="bi bi-calendar-check me-2"></i> Menampilkan pembelian pada: {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover" id="pembelianTable" width="100%">
                    @include('pembelian.partials.table', ['pembelians' => $pembelians])
                </table>
            </div>
        @endif
    </div>
</div>
<!-- Modal Detail Pembelian -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title w-100 text-center fw-bold" id="detailModalLabel">Detail Pembelian <span id="modal-id-pembelian"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-4">
        <div class="row mb-4">
            <div class="col-md-4">
                <p class="mb-0 text-muted small">Tanggal :</p>
                <p class="fw-bold mb-0" id="modal-tanggal"></p>
            </div>
            <div class="col-md-4">
                <p class="mb-0 text-muted small">Supplier :</p>
                <p class="fw-bold mb-0" id="modal-supplier"></p>
            </div>
            <div class="col-md-4">
                <p class="mb-0 text-muted small">Pegawai (PIC) :</p>
                <p class="fw-bold mb-0" id="modal-pegawai"></p>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="modal-table-detail">
                <thead class="table-light">
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="modal-detail-body">
                    <!-- Dinamis dari AJAX -->
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end fs-6">TOTAL PEMBELIAN</th>
                        <th class="text-end fs-6 text-primary" id="modal-total-pembelian"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
      </div>
      <div class="modal-footer justify-content-center border-top-0 pt-0">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var tableOptions = {
            order: [[1, 'desc']],
            columnDefs: [
                { orderable: false, targets: 5 }
            ]
        };

        if ($('#pembelianTable').length > 0) {
            $('#pembelianTable').DataTable(tableOptions);
        } else {
            $('.datatable-history').DataTable(tableOptions);
        }
    });

    const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

    function showDetailModal(id) {
        Swal.fire({
            title: 'Memuat Detail...',
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $.ajax({
            url: `{{ url('/pembelian') }}/${id}`,
            type: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    let p = response.data;
                    let strId = String(p.id).padStart(4, '0');
                    
                    // Format Tanggal (Contoh: 30 Juli 2026 19:31 WIB)
                    // Ganti spasi dengan T agar valid di semua browser
                    let dateStr = p.tanggal_pembelian ? p.tanggal_pembelian.replace(' ', 'T') : '';
                    let dateObj = new Date(dateStr);
                    let formattedDate = '';
                    if (!isNaN(dateObj.getTime())) {
                        let options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                        formattedDate = dateObj.toLocaleDateString('id-ID', options).replace(',', '') + ' WIB';
                    }
                    
                    $('#modal-id-pembelian').text(`#PUR-${strId}`);
                    $('#modal-tanggal').text(formattedDate);
                    $('#modal-supplier').text(p.supplier ? p.supplier.nama_supplier : 'Tanpa Supplier');
                    $('#modal-pegawai').text(p.pegawai ? p.pegawai.nama_pegawai : 'Admin');
                    
                    let tbody = $('#modal-detail-body');
                    tbody.empty();

                    if(p.detail_pembelians && p.detail_pembelians.length > 0) {
                        p.detail_pembelians.forEach(function(detail) {
                            let namaBahan = detail.bahan_baku ? detail.bahan_baku.nama_bahan : 'Unknown';
                            let satuan = detail.bahan_baku ? detail.bahan_baku.satuan : '';
                            
                            tbody.append(`
                                <tr>
                                    <td>${namaBahan}</td>
                                    <td>${detail.jumlah}</td>
                                    <td>${satuan}</td>
                                    <td>Rp${new Intl.NumberFormat('id-ID').format(detail.harga_satuan)}</td>
                                    <td class="text-end fw-bold">Rp${new Intl.NumberFormat('id-ID').format(detail.subtotal)}</td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append(`<tr><td colspan="5" class="text-center">Tidak ada detail pembelian.</td></tr>`);
                    }

                    $('#modal-total-pembelian').text(`Rp${new Intl.NumberFormat('id-ID').format(p.total_pembelian)}`);

                    Swal.close();
                    detailModal.show();
                }
            },
            error: function() {
                Swal.fire('Gagal', 'Tidak dapat memuat detail data.', 'error');
            }
        });
    }
</script>
@endpush
