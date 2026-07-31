@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Stok Bahan Baku</h4>
    <button class="btn btn-primary" onclick="showAddModal()">
        <i class="bi bi-plus-lg me-1"></i> Tambah Bahan Baku
    </button>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="stokTable" width="100%">
                        <thead class="table-light">
                            <tr>
                                <th>ID Bahan</th>
                                <th>Nama Bahan</th>
                                <th>Satuan</th>
                                <th>Sisa Stok</th>
                                <th>Status</th>
                                <th>Tanggal Update</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bahanBakus as $bb)
                            <tr>
                                <td>#BB-{{ str_pad($bb->id, 3, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $bb->nama_bahan }}</td>
                                <td>{{ $bb->satuan }}</td>
                                <td class="fw-bold {{ $bb->stok < 10 ? 'text-danger' : '' }}">{{ $bb->stok }}</td>
                                <td>
                                    @if($bb->stok > 20)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="bi bi-check-circle me-1"></i> Aman</span>
                                    @elseif($bb->stok > 10)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"><i class="bi bi-exclamation-circle me-1"></i> Menipis</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i class="bi bi-x-circle me-1"></i> Habis/Kritis</span>
                                    @endif
                                </td>
                                <td>
                                    @php $date = $bb->updated_at ?? $bb->created_at; @endphp
                                    @if($date)
                                        @if($date->isToday())
                                            Hari ini
                                        @elseif($date->isYesterday())
                                            Kemarin
                                        @else
                                            {{ \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-success me-1" title="Tambah Stok" onclick="updateStok({{ $bb->id }}, 'add')"><i class="bi bi-plus-circle"></i></button>
                                    <button class="btn btn-sm btn-outline-warning me-1" title="Kurangi Stok" onclick="updateStok({{ $bb->id }}, 'sub')"><i class="bi bi-dash-circle"></i></button>
                                    <button class="btn btn-sm btn-outline-primary me-1" title="Edit" onclick="editBahan({{ $bb->id }}, '{{ addslashes($bb->nama_bahan) }}', '{{ addslashes($bb->satuan) }}')"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="confirmDelete({{ $bb->id }})"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stokTable').DataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ]
        });
    });

    function showAddModal() {
        Swal.fire({
            title: 'Tambah Bahan Baku',
            html:
                '<input id="swal-input1" class="swal2-input" placeholder="Nama Bahan">' +
                '<input id="swal-input2" class="swal2-input" placeholder="Satuan (cth: Kg, Liter)">' +
                '<input id="swal-input3" class="swal2-input" type="number" placeholder="Stok Awal">',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc2626',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const nama_bahan = document.getElementById('swal-input1').value;
                const satuan = document.getElementById('swal-input2').value;
                const stok = document.getElementById('swal-input3').value;

                if (!nama_bahan || !satuan || !stok) {
                    Swal.showValidationMessage('Semua data wajib diisi!');
                    return false;
                }

                return $.ajax({
                    url: '{{ route('admin.stok.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama_bahan: nama_bahan,
                        satuan: satuan,
                        stok: stok
                    }
                }).catch(error => {
                    let errorMessage = 'Terjadi kesalahan pada server';
                    if (error.responseJSON && error.responseJSON.message) {
                        errorMessage = error.responseJSON.message;
                    }
                    Swal.showValidationMessage(errorMessage);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: result.value.message,
                    icon: 'success',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function updateStok(id, type) {
        let title = type === 'add' ? 'Tambah Stok' : 'Kurangi Stok';
        let btnColor = type === 'add' ? '#198754' : '#ffc107';
        Swal.fire({
            title: title,
            input: 'number',
            inputLabel: 'Masukkan Jumlah',
            inputPlaceholder: 'Jumlah...',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: btnColor,
            showLoaderOnConfirm: true,
            preConfirm: (amount) => {
                if (!amount || amount <= 0) {
                    Swal.showValidationMessage('Jumlah harus lebih dari 0');
                    return false;
                }
                return $.ajax({
                    url: `{{ url('/stok') }}/${id}/adjust`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: type,
                        amount: amount
                    }
                }).catch(error => {
                    Swal.showValidationMessage(error.responseJSON?.message || 'Terjadi kesalahan pada server');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Berhasil!', text: result.value.message, icon: 'success'}).then(() => window.location.reload());
            }
        });
    }

    function editBahan(id, nama, satuan) {
        Swal.fire({
            title: 'Edit Bahan Baku',
            html:
                `<input id="swal-edit1" class="swal2-input" value="${nama}" placeholder="Nama Bahan">` +
                `<input id="swal-edit2" class="swal2-input" value="${satuan}" placeholder="Satuan (cth: Kg, Liter)">`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const nama_bahan = document.getElementById('swal-edit1').value;
                const sat = document.getElementById('swal-edit2').value;
                if (!nama_bahan || !sat) {
                    Swal.showValidationMessage('Semua data wajib diisi!');
                    return false;
                }
                return $.ajax({
                    url: `{{ url('/stok') }}/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama_bahan: nama_bahan,
                        satuan: sat
                    }
                }).catch(error => {
                    Swal.showValidationMessage(error.responseJSON?.message || 'Terjadi kesalahan pada server');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Berhasil!', text: result.value.message, icon: 'success'}).then(() => window.location.reload());
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data bahan baku ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `{{ url('/stok') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                }).catch(error => {
                    Swal.showValidationMessage(error.responseJSON?.message || 'Terjadi kesalahan pada server');
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({title: 'Terhapus!', text: result.value.message, icon: 'success'}).then(() => window.location.reload());
            }
        })
    }
</script>
@endpush
