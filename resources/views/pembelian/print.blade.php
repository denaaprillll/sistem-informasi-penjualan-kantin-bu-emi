<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .header h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .header p {
            margin: 0;
            white-space: pre-wrap;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 5px 0;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .summary-box {
            margin-top: 20px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        .summary-item strong {
            display: block;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">🖨 Cetak Laporan</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h2>KANTIN BU EMI</h2>
        <h3>{{ $title }}</h3>
        <p>{{ $periode }}</p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">ID</th>
                <th style="width: 30%;">Supplier</th>
                <th style="width: 20%;">Pegawai</th>
                <th style="width: 30%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelians as $p)
                <tr>
                    <td>PUR-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->supplier->nama_supplier ?? 'Tanpa Supplier' }}</td>
                    <td>{{ $p->pegawai->nama_pegawai ?? 'Admin' }}</td>
                    <td class="text-right">Rp{{ number_format($p->total_pembelian, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px 0;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="summary-box">
        <div class="summary-item">
            <strong>TOTAL TRANSAKSI</strong>
            {{ $totalTransaksi }}
        </div>
        
        <div class="summary-item">
            <strong>TOTAL PEMBELIAN{{ $filter == 'week' ? ' MINGGU INI' : ($filter == 'month' ? ' BULAN INI' : '') }}</strong>
            Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
        </div>

        @if($filter == 'week')
            <div class="summary-item">
                <strong>RATA-RATA PEMBELIAN PER HARI</strong>
                Rp{{ number_format($totalTransaksi > 0 ? $totalPengeluaran / 7 : 0, 0, ',', '.') }}
            </div>
        @endif

        @if($filter == 'month')
            <div class="summary-item">
                <strong>SUPPLIER YANG PALING SERING DIGUNAKAN</strong>
                {{ $frequentSupplier ?? '-' }}
            </div>
        @endif
    </div>
</body>
</html>
