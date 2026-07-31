                <thead class="table-light">
                    <tr>
                        <th>ID Pembelian</th>
                        <th>Tanggal & Waktu</th>
                        <th>Supplier</th>
                        <th>Pegawai (PIC)</th>
                        <th>Total Pembelian</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $p)
                    <tr>
                        <td class="fw-bold text-primary">#PUR-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ date('d M Y, H:i', strtotime($p->tanggal_pembelian)) }}</td>
                        <td>{{ $p->supplier->nama_supplier ?? 'Tanpa Supplier' }}</td>
                        <td>{{ $p->pegawai->nama_pegawai ?? 'Admin' }}</td>
                        <td class="fw-bold">Rp {{ number_format($p->total_pembelian, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white" onclick="showDetailModal({{ $p->id }})">
                                <i class="bi bi-list-check me-1"></i> Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data pembelian</td>
                    </tr>
                    @endforelse
                </tbody>
