@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Catat Pembelian Baru</h4>
    <a href="{{ route('admin.pembelian') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form id="formPembelian">
            @csrf
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="supplier_id" class="form-label fw-bold">Pilih Supplier</label>
                    <select class="form-select" id="supplier_id" name="supplier_id" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle" id="tableBahanBaku">
                    <thead class="table-light">
                        <tr>
                            <th width="25%">Bahan Baku</th>
                            <th width="15%">Satuan</th>
                            <th width="15%">Jumlah</th>
                            <th width="20%">Harga Satuan (Rp)</th>
                            <th width="15%">Subtotal (Rp)</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-select bahan-baku-select" name="bahan_baku[]" required onchange="handleSelectChange(this)">
                                    <option value="">-- Ketik / Pilih --</option>
                                    @foreach($bahanBakus as $bb)
                                        <option value="{{ $bb->id }}" data-satuan="{{ $bb->satuan }}">{{ $bb->nama_bahan }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control satuan-input" name="satuan[]" required readonly placeholder="Cth: Kg, Ltr">
                            </td>
                            <td>
                                <input type="number" class="form-control qty-input" name="jumlah[]" min="1" value="1" required oninput="calculateRow(this)">
                            </td>
                            <td>
                                <input type="number" class="form-control price-input" name="harga_satuan[]" min="0" value="0" required oninput="calculateRow(this)">
                            </td>
                            <td>
                                <input type="text" class="form-control subtotal-input" readonly value="0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)" disabled><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Pembelian:</td>
                            <td colspan="2" class="fw-bold text-success fs-5">
                                Rp <span id="grandTotal">0</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button type="button" class="btn btn-sm btn-success" onclick="addRow()">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                </button>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-lg" id="btnSimpan">
                    <i class="bi bi-save me-1"></i> Simpan Pembelian
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        initSelect2($('.bahan-baku-select'));
    });

    function initSelect2(element) {
        element.select2({
            tags: true,
            placeholder: "-- Ketik / Pilih Bahan Baku --",
            width: '100%',
            createTag: function (params) {
                var term = $.trim(params.term);
                if (term === '') {
                    return null;
                }
                return {
                    id: term,
                    text: term + ' (Baru)',
                    newTag: true 
                }
            }
        });
    }

    function handleSelectChange(element) {
        let select = $(element);
        let row = select.closest('tr');
        let satuanInput = row.find('.satuan-input');
        
        let selectedOption = select.find(':selected');
        
        // If it's a numeric ID, it's an existing item
        let val = select.val();
        if(val && !isNaN(val)) {
            let satuan = selectedOption.data('satuan');
            satuanInput.val(satuan);
            satuanInput.prop('readonly', true);
        } else if (val) {
            // It's a new tag string
            satuanInput.val('');
            satuanInput.prop('readonly', false);
            satuanInput.focus();
        } else {
            satuanInput.val('');
            satuanInput.prop('readonly', true);
        }
    }

    function calculateRow(element) {
        let row = $(element).closest('tr');
        let qty = parseFloat(row.find('.qty-input').val()) || 0;
        let price = parseFloat(row.find('.price-input').val()) || 0;
        let subtotal = qty * price;
        
        row.find('.subtotal-input').val(new Intl.NumberFormat('id-ID').format(subtotal));
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        $('#tableBahanBaku tbody tr').each(function() {
            let qty = parseFloat($(this).find('.qty-input').val()) || 0;
            let price = parseFloat($(this).find('.price-input').val()) || 0;
            total += (qty * price);
        });
        $('#grandTotal').text(new Intl.NumberFormat('id-ID').format(total));
    }

    function addRow() {
        let tbody = $('#tableBahanBaku tbody');
        
        // Destroy select2 on the original select before cloning to prevent duplicate ID issues
        let originalSelect = tbody.find('tr:first').find('.bahan-baku-select');
        originalSelect.select2('destroy');
        
        let newRow = tbody.find('tr:first').clone();
        
        // Re-initialize select2 on the original element
        initSelect2(originalSelect);
        
        // Reset values on the new row
        let newSelect = newRow.find('.bahan-baku-select');
        newSelect.val('');
        newRow.find('.satuan-input').val('').prop('readonly', true);
        newRow.find('.qty-input').val('1');
        newRow.find('.price-input').val('0');
        newRow.find('.subtotal-input').val('0');
        newRow.find('.btn-danger').prop('disabled', false);
        
        // Append row and init select2 on the new element
        tbody.append(newRow);
        initSelect2(newSelect);
        
        updateDeleteButtons();
    }

    function removeRow(btn) {
        if ($('#tableBahanBaku tbody tr').length > 1) {
            $(btn).closest('tr').remove();
            calculateGrandTotal();
            updateDeleteButtons();
        }
    }

    function updateDeleteButtons() {
        let rowCount = $('#tableBahanBaku tbody tr').length;
        if (rowCount === 1) {
            $('#tableBahanBaku tbody tr .btn-danger').prop('disabled', true);
        } else {
            $('#tableBahanBaku tbody tr .btn-danger').prop('disabled', false);
        }
    }

    $('#formPembelian').on('submit', function(e) {
        e.preventDefault();
        
        if ($('#tableBahanBaku tbody tr').length === 0) {
            Swal.fire('Error', 'Minimal harus ada 1 barang yang dibeli!', 'error');
            return;
        }

        let btn = $('#btnSimpan');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            url: '{{ route('admin.pembelian.store') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#198754'
                    }).then(() => {
                        window.location.href = '{{ route('admin.pembelian') }}';
                    });
                }
            },
            error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan sistem';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Gagal!', errorMsg, 'error');
                btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i> Simpan Pembelian');
            }
        });
    });
</script>
@endpush
