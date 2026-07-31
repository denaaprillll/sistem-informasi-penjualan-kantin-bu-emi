@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Daftar Menu Makanan</h4>
    <button class="btn btn-primary" onclick="showAddMenuModal()">
        <i class="bi bi-plus-lg me-1"></i> Tambah Menu Baru
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="menuTable" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>Nama Menu</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td class="fw-bold">{{ $menu->nama_menu }}</td>
                        <td>{{ $menu->deskripsi ?: '-' }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary me-1" title="Edit" onclick="editMenu({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', '{{ $menu->harga }}', '{{ addslashes($menu->deskripsi) }}')"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="deleteMenu({{ $menu->id }})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#menuTable').DataTable({
            columnDefs: [
                { orderable: false, targets: [3] }
            ],
            language: {
                emptyTable: "Belum ada menu yang tersedia."
            }
        });
    });

    function showAddMenuModal() {
        Swal.fire({
            title: 'Tambah Menu Baru',
            html:
                '<input id="swal-menu-nama" class="swal2-input" placeholder="Nama Menu (Contoh: Ayam Geprek Bu Emi)">' +
                '<input id="swal-menu-harga" class="swal2-input" type="number" placeholder="Harga (Contoh: 15000)">' +
                '<textarea id="swal-menu-desk" class="swal2-textarea" placeholder="Deskripsi Menu"></textarea>',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const nama_menu = document.getElementById('swal-menu-nama').value;
                const harga = document.getElementById('swal-menu-harga').value;
                const deskripsi = document.getElementById('swal-menu-desk').value;

                if (!nama_menu || !harga) {
                    Swal.showValidationMessage('Nama dan Harga wajib diisi!');
                    return false;
                }

                return $.ajax({
                    url: '{{ route('admin.menu.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama_menu: nama_menu,
                        harga: harga,
                        deskripsi: deskripsi
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

    function editMenu(id, nama, harga, deskripsi) {
        Swal.fire({
            title: 'Edit Menu',
            html:
                `<input id="swal-edit-nama" class="swal2-input" value="${nama}" placeholder="Nama Menu">` +
                `<input id="swal-edit-harga" class="swal2-input" type="number" value="${harga}" placeholder="Harga">` +
                `<textarea id="swal-edit-desk" class="swal2-textarea" placeholder="Deskripsi">${deskripsi === '-' ? '' : deskripsi}</textarea>`,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0d6efd',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                const nama_menu = document.getElementById('swal-edit-nama').value;
                const harga = document.getElementById('swal-edit-harga').value;
                const desk = document.getElementById('swal-edit-desk').value;

                if (!nama_menu || !harga) {
                    Swal.showValidationMessage('Nama dan Harga wajib diisi!');
                    return false;
                }

                return $.ajax({
                    url: `/menu/${id}`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama_menu: nama_menu,
                        harga: harga,
                        deskripsi: desk
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

    function deleteMenu(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Menu masakan ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: `/menu/${id}`,
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
