                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Waktu Pesan</th>
                        <th>Pelanggan</th>
                        <th>Detail Pesanan</th>
                        <th>Total Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $pesanan)
                    @php $detail = $pesanan->detailPenjualans->first(); @endphp
                    <tr>
                        <td class="fw-bold">{{ $pesanan->no_pesanan }}</td>
                        <td>{{ date('H:i:s', strtotime($pesanan->waktu_pemesanan)) }}<br><small class="text-muted">{{ date('d M Y', strtotime($pesanan->waktu_pemesanan)) }}</small></td>
                        <td>{{ $pesanan->nama_pelanggan }}<br><small class="text-muted">{{ $pesanan->no_hp }}</small></td>
                        <td>
                            {{ $detail->menu->nama_menu ?? 'Ayam Geprek' }} (x{{ $detail->jumlah ?? 0 }})<br>
                            @if($detail && $detail->level_sambal)
                                <small class="text-danger">{{ $detail->level_sambal }}</small><br>
                            @endif
                            @if($detail && $detail->catatan)
                                <small class="text-muted fst-italic">Catatan: {{ $detail->catatan }}</small>
                            @endif
                        </td>
                        <td class="fw-bold">Rp {{ number_format($pesanan->total_penjualan, 0, ',', '.') }}</td>
                        <td>
                            @if(in_array($pesanan->metode_pembayaran, ['DANA', 'Transfer DANA']))
                                <span class="badge bg-light text-dark border"><i class="bi bi-wallet2 text-primary me-1"></i> DANA</span>
                            @else
                                <span class="badge bg-light text-dark border"><i class="bi bi-cash-stack text-success me-1"></i> Tunai</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'bg-secondary';
                                if($pesanan->status_pesanan == 'Menunggu Verifikasi' || $pesanan->status_pesanan == 'Menunggu Pembayaran') $statusClass = 'bg-warning text-dark';
                                if($pesanan->status_pesanan == 'Diproses' || $pesanan->status_pesanan == 'Siap Diambil') $statusClass = 'bg-primary';
                                if($pesanan->status_pesanan == 'Selesai') $statusClass = 'bg-success';
                                if($pesanan->status_pesanan == 'Dibatalkan') $statusClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $pesanan->status_pesanan }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown">Aksi</button>
                                <ul class="dropdown-menu shadow-sm border-0">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick='showDetailModal(@json($pesanan), @json($detail))'><i class="bi bi-eye text-info me-2"></i>Detail & Bukti</a></li>
                                    <li>
                                        <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status_pesanan" value="Diproses">
                                            <button type="submit" class="dropdown-item"><i class="bi bi-check-circle text-primary me-2"></i>Verifikasi & Proses</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('pesanan.update', $pesanan->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status_pesanan" value="Selesai">
                                            <button type="submit" class="dropdown-item"><i class="bi bi-check-all text-success me-2"></i>Selesai</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
