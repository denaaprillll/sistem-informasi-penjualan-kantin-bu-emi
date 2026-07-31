@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Pengaturan Sistem</h4>
</div>

<div class="row g-4">
    <!-- Informasi Kantin -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-shop me-2 text-primary"></i>Informasi Kantin</h6>
            </div>
            <div class="card-body">
                <form id="formKantin" action="{{ route('admin.pengaturan.kantin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Kantin</label>
                        <input type="text" class="form-control" name="nama_kantin" value="{{ $pengaturan->nama_kantin }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" name="nama_pemilik" value="{{ $pengaturan->nama_pemilik }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor HP / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" value="{{ $pengaturan->no_hp }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jam Operasional</label>
                        <input type="text" class="form-control" name="jam_operasional" placeholder="Contoh: Senin - Jumat (08:00 - 20:00)" value="{{ $pengaturan->jam_operasional }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Link Google Maps</label>
                        <input type="url" class="form-control" name="link_gmaps" value="{{ $pengaturan->link_gmaps }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="alamat" rows="2">{{ $pengaturan->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea class="form-control" name="deskripsi_singkat" rows="2">{{ $pengaturan->deskripsi_singkat }}</textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pengaturan Pembayaran -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-success"></i>Pengaturan Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-info-circle me-1"></i> Pembayaran saat ini mendukung metode Tunai dan Transfer DANA.
                </div>
                <form id="formPembayaran" action="{{ route('admin.pengaturan.pembayaran') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nomor DANA</label>
                        <input type="text" class="form-control" name="dana_nomor" value="{{ $pengaturan->dana_nomor }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Akun DANA</label>
                        <input type="text" class="form-control" name="dana_nama" value="{{ $pengaturan->dana_nama }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">QR Code DANA (Gambar)</label>
                        @if($pengaturan->dana_qr)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $pengaturan->dana_qr) }}" alt="QR DANA" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" name="dana_qr" accept="image/*">
                        <small class="text-muted">Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</small>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pengaturan Menu Utama -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-egg-fried me-2 text-warning"></i>Pengaturan Menu</h6>
            </div>
            <div class="card-body">
                <form id="formMenu" action="{{ route('admin.pengaturan.menu') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Menu Utama</label>
                        <input type="text" class="form-control" name="nama_menu" value="{{ $menu->nama_menu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" name="harga" value="{{ $menu->harga }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Ketersediaan</label>
                        <select class="form-select" name="status">
                            <option value="Tersedia" {{ $menu->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Habis" {{ $menu->status == 'Habis' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Menu</label>
                        @if($menu->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $menu->foto) }}" alt="Foto Menu" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control" name="foto" accept="image/*">
                        <small class="text-muted">Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</small>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-save me-1"></i> Simpan Menu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pengaturan Akun Admin -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2 text-danger"></i>Pengaturan Akun Admin</h6>
            </div>
            <div class="card-body">
                <form id="formAkun" action="{{ route('admin.pengaturan.akun') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Admin</label>
                        <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Admin</label>
                        <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
                    </div>
                    
                    <hr class="my-4">
                    <p class="text-muted small mb-3">Kosongkan kolom password di bawah ini jika tidak ingin mengubah password.</p>

                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" class="form-control" name="password_lama">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" name="password_baru">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="konfirmasi_password">
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-danger"><i class="bi bi-key me-1"></i> Ubah Profil & Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function submitForm(formId) {
        let form = document.getElementById(formId);
        let url = form.getAttribute('action');
        let formData = new FormData(form);

        // Reset any previous invalid feedback
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    confirmButtonColor: '#198754'
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.close();
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        let input = $(`#${formId} [name="${key}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server.'
                    });
                }
            }
        });
    }

    $(document).ready(function() {
        $('#formKantin').submit(function(e) {
            e.preventDefault();
            submitForm('formKantin');
        });

        $('#formPembayaran').submit(function(e) {
            e.preventDefault();
            submitForm('formPembayaran');
        });

        $('#formMenu').submit(function(e) {
            e.preventDefault();
            submitForm('formMenu');
        });

        $('#formAkun').submit(function(e) {
            e.preventDefault();
            submitForm('formAkun');
        });
    });
</script>
@endpush
