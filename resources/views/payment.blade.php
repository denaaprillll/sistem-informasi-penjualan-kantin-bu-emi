@extends('layouts.app')

@push('styles')
<style>
    .payment-section {
        padding: 60px 0;
        min-height: calc(100vh - 80px);
        background-color: var(--light);
    }
    
    .payment-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: none;
        margin-bottom: 24px;
    }

    .payment-method-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
    }

    .payment-method-card:hover {
        border-color: #fca5a5;
        background-color: #fef2f2;
    }

    .payment-method-input:checked + .payment-method-card {
        border-color: var(--primary);
        background-color: #fef2f2;
    }

    .payment-method-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .transfer-details {
        display: none;
        background-color: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
        border: 1px solid #e5e7eb;
    }
    
    .file-upload-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        display: none;
        margin-top: 15px;
    }
</style>
@endpush

@section('content')
<section class="payment-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <a href="{{ route('order') }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <h4 class="mb-0 fw-bold">Pembayaran</h4>
                </div>

                <div class="row">
                    <!-- Kolom Kiri: Info Pesanan & Metode -->
                    <div class="col-lg-7">
                        <!-- Ringkasan Pesanan Card -->
                        <div class="payment-card card p-4">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Ringkasan Pesanan #{{ $penjualan->no_pesanan }}</h5>
                            
                            @php $detail = $penjualan->detailPenjualans->first(); @endphp
                            
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Nama Pelanggan</div>
                                <div class="col-sm-8 fw-medium">{{ $penjualan->nama_pelanggan }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Nomor HP</div>
                                <div class="col-sm-8 fw-medium">{{ $penjualan->no_hp }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Menu</div>
                                <div class="col-sm-8 fw-medium">{{ $detail->menu->nama_menu ?? 'Ayam Geprek' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Harga</div>
                                <div class="col-sm-8 fw-medium">Rp{{ number_format($detail->harga_satuan ?? 0, 0, ',', '.') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Jumlah Pesanan</div>
                                <div class="col-sm-8 fw-medium">{{ $detail->jumlah ?? 0 }} Porsi</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4 text-muted">Level Sambal</div>
                                <div class="col-sm-8 fw-medium">{{ $detail->level_sambal ?? '-' }}</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-sm-4 text-muted">Catatan</div>
                                <div class="col-sm-8 fw-medium">{{ $detail->catatan ?? '-' }}</div>
                            </div>
                        </div>

                        <!-- Pilih Metode Pembayaran -->
                        <div class="payment-card card p-4">
                            <h5 class="fw-bold mb-4">Pilih Metode Pembayaran</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="w-100">
                                        <input type="radio" name="payment_method_radio" value="tunai" class="payment-method-input" checked>
                                        <div class="payment-method-card text-center">
                                            <i class="bi bi-cash-stack text-success fs-1 mb-2"></i>
                                            <h6 class="fw-bold">Bayar Tunai</h6>
                                            <p class="small text-muted mb-2">Bayar langsung saat mengambil pesanan</p>
                                            <span class="badge bg-success bg-opacity-10 text-success">Bayar di Tempat (COD)</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="w-100">
                                        <input type="radio" name="payment_method_radio" value="dana" class="payment-method-input">
                                        <div class="payment-method-card text-center">
                                            <i class="bi bi-wallet2 text-primary fs-1 mb-2"></i>
                                            <h6 class="fw-bold">Transfer DANA</h6>
                                            <p class="small text-muted mb-0">Transfer ke akun DANA Kantin Bu Emi</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Detail Transfer DANA -->
                            <div id="danaDetails" class="transfer-details" style="display: none;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <p class="text-muted small mb-1">Atas Nama</p>
                                        <h6 class="fw-bold mb-0">{{ $pengaturan->dana_nama ?? 'Bu Emi' }}</h6>
                                    </div>
                                    <div>
                                        <p class="text-muted small mb-1">Nomor DANA</p>
                                        <div class="d-flex align-items-center">
                                            <h6 class="fw-bold mb-0 me-2" id="danaNumber">{{ $pengaturan->dana_nomor ?? '-' }}</h6>
                                            <button class="btn btn-sm btn-outline-primary py-0 px-2" onclick="copyDana()">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    @if($pengaturan->dana_qr)
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $pengaturan->dana_qr) }}" alt="QR DANA" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                        <p class="text-muted small mt-2">Scan QR untuk membayar</p>
                                    </div>
                                    @endif
                                    <label class="form-label fw-medium small">Upload Bukti Transfer</label>
                                    <input class="form-control form-control-sm" type="file" id="buktiTransfer" name="bukti_transfer" accept=".jpg,.jpeg,.png" form="paymentForm">
                                    <div class="form-text small">Format: JPG, JPEG, PNG. Maksimal 2 MB.</div>
                                    <img id="imagePreview" class="file-upload-preview shadow-sm" alt="Preview Bukti Transfer">
                                </div>
                            </div>

                            <!-- Detail COD -->
                            <div id="codDetails" class="transfer-details" style="display: block;">
                                <h6 class="fw-bold mb-2">Bayar di Tempat (COD)</h6>
                                <p class="text-muted small mb-2">Silakan datang ke Kantin Bu Emi dan lakukan pembayaran secara langsung kepada kasir saat mengambil pesanan.</p>
                                <p class="text-muted small mb-0">Status pembayaran akan menjadi: <span class="badge bg-warning text-dark">Menunggu Pembayaran</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Total & Submit -->
                    <div class="col-lg-5">
                        <div class="payment-card card p-4 sticky-top" style="top: 100px;">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Total Pembayaran</h5>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Harga per porsi</span>
                                <span>Rp{{ number_format($detail->harga_satuan ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                                <span class="text-muted">Jumlah Pesanan</span>
                                <span>x {{ $detail->jumlah ?? 0 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4 align-items-center">
                                <span class="fw-bold fs-5">Total Bayar</span>
                                <span class="fw-bold fs-3 text-danger">Rp{{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</span>
                            </div>

                            <form id="paymentForm" action="{{ route('checkout.process', $penjualan->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="payment_method" id="formPaymentMethod" value="tunai">
                                <button type="button" id="btnSubmit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                                    Lihat Struk Pesanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Toggle DANA details
        $('input[name="payment_method_radio"]').change(function() {
            let method = $(this).val();
            $('#formPaymentMethod').val(method);
            if (method === 'dana') {
                $('#danaDetails').slideDown();
                $('#codDetails').slideUp();
                $('#btnSubmit').text('Kirim Pembayaran');
            } else {
                $('#danaDetails').slideUp();
                $('#codDetails').slideDown();
                $('#btnSubmit').text('Lihat Struk Pesanan');
            }
        });

        // Image Preview
        $('#buktiTransfer').change(function() {
            const file = this.files[0];
            if (file) {
                // Check file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Ukuran file maksimal 2 MB!',
                        confirmButtonColor: '#dc2626'
                    });
                    this.value = '';
                    $('#imagePreview').hide();
                    return;
                }
                
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#imagePreview').attr('src', event.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').hide();
            }
        });

        // Submit Button SweetAlert & AJAX
        $('#btnSubmit').click(function(e) {
            e.preventDefault();
            let method = $('input[name="payment_method_radio"]:checked').val();
            
            // Set explicit hidden input to exactly match what controller needs
            $('#formPaymentMethod').val(method);
            
            if (method === 'dana') {
                let file = $('#buktiTransfer').val();
                if (!file) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Bukti Transfer Kosong',
                        text: 'Silakan upload bukti transfer DANA Anda terlebih dahulu.',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }
            }
            
            let formData = new FormData();
            formData.append('_token', $('input[name="_token"]').val());
            formData.append('payment_method', method);
            
            if (method === 'dana') {
                let fileInput = document.getElementById('buktiTransfer');
                if (fileInput.files.length > 0) {
                    formData.append('bukti_transfer', fileInput.files[0]);
                }
            }
            
            // Disable button while submitting
            $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

            $.ajax({
                url: $('#paymentForm').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (method === 'tunai') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesanan Berhasil Dibuat',
                            text: 'Silakan datang ke Kantin Bu Emi dan tunjukkan struk pesanan kepada kasir.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = response.redirect_url;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pembayaran Berhasil Dikirim',
                            text: 'Bukti pembayaran berhasil dikirim. Admin akan segera melakukan verifikasi pembayaran Anda.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = response.redirect_url;
                            }
                        });
                    }
                },
                error: function(xhr) {
                    $('#btnSubmit').prop('disabled', false).html(method === 'dana' ? 'Kirim Pembayaran' : 'Lihat Struk Pesanan');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim Pembayaran',
                        text: 'Terjadi kesalahan saat menyimpan data. Silakan coba kembali.',
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        });
    });

    function copyDana() {
        let text = document.getElementById("danaNumber").innerText;
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Nomor DANA disalin!',
                showConfirmButton: false,
                timer: 1500
            });
        });
    }
</script>
@endpush
