@extends('layouts.main')

@section('title', 'Pesanan Saya - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Profil
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($pesanan->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                Anda belum memiliki pesanan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kostum</th>
                            <th>Tgl Pakai</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Dibuat</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->nama_kostum ?? '-' }}</td>
                            <td>{{ $order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-' }}</td>
                            <td>{{ $order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-' }}</td>
                            <td>Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'proses' => 'bg-warning text-dark',
                                        'revisi' => 'bg-secondary',
                                        'selesai' => 'bg-success',
                                        'diterima' => 'bg-info text-dark',
                                        'dibatalkan' => 'bg-secondary'
                                    ][$order->status] ?? 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->keterangan ?? '-' }}</td>
                            <td class="text-end">
                                <div class="d-grid gap-2" style="min-width: 190px;">
                                    <button type="button" class="btn btn-sm btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#orderDetailModal-{{ $order->id }}">
                                        <i class="bi bi-card-list"></i> Detail
                                    </button>

                                @php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;

                                    if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                        $hasBukti = true;
                                    } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                        $hasBukti = true;
                                    } else {
                                        try {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $order->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $hasBukti = false;
                                        }
                                    }
                                @endphp

                                @if($hasBukti)
                                    @php
                                        $directBuktiUrl = null;
                                        $directExt = null;

                                        if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                            $directBuktiUrl = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                            $directExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                        } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                            $directBuktiUrl = asset('storage/' . session('uploaded_bukti_path'));
                                            $directExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                        } elseif (!empty($foundBuktiPath)) {
                                            $directBuktiUrl = asset('storage/' . $foundBuktiPath);
                                            $directExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                        }
                                    @endphp

                                    {{-- Always open the bukti modal; modal will display image/embed --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#buktiModal-{{ $order->id }}">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                @else
                                    @if($order->status === 'diterima')
                                        <a href="{{ route('pembayaran', ['id' => $order->id]) }}" class="btn btn-success btn-sm w-100">
                                            <i class="bi bi-cash-coin"></i> Lanjutkan ke Pembayaran
                                        </a>
                                    @endif
                                @endif

                                @if(in_array($order->status, ['proses', 'revisi']))
                                    <a href="{{ route('user.pesanan.edit', ['id' => $order->id]) }}" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#orderActionModal-{{ $order->id }}">
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                @endif

                                @if($order->status === 'selesai')
                                    @php
                                        $hasUlasan = \App\Models\Ulasan::where('id', $order->id)->exists();
                                    @endphp
                                    <a href="{{ route('user.ulasan.form', $order->id) }}" class="btn btn-sm btn-outline-warning w-100">
                                        <i class="bi bi-star"></i> {{ $hasUlasan ? 'Edit Ulasan' : 'Beri Ulasan' }}
                                    </a>
                                @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="orderDetailModal-{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="orderDetailLabel-{{ $order->id }}">
                                            <i class="bi bi-card-list"></i> Detail Pesanan #{{ $order->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br>{{ $order->nama_kostum ?? '-' }}</div>
                                                <div class="mb-2"><strong>Tgl Pakai:</strong><br>{{ $order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-' }}</div>
                                                <div class="mb-2"><strong>Tgl Kembali:</strong><br>{{ $order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-' }}</div>
                                                <div class="mb-2"><strong>Total Harga:</strong><br>Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}</div>
                                                <div class="mb-2"><strong>Metode Pembayaran:</strong><br>{{ $order->metode_pembayaran ?? '-' }}</div>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br>{{ $order->nama }}</div>
                                                <div class="mb-2"><strong>Nomor Telepon:</strong><br>{{ $order->nomor_telepon }}</div>
                                                <div class="mb-2"><strong>Nomor Telepon 2:</strong><br>{{ $order->nomor_telepon_2 }}</div>
                                                <div class="mb-2"><strong>Alamat:</strong><br>{{ $order->alamat }}</div>
                                                <div class="mb-2"><strong>Kartu Identitas:</strong><br>{{ $order->kartu_identitas }}</div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Foto Kartu Identitas:</strong>
                                                @if($order->foto_kartu_identitas)
                                                    <img src="{{ asset('storage/' . $order->foto_kartu_identitas) }}" alt="Foto Kartu Identitas" class="img-fluid rounded mt-2">
                                                @else
                                                    <div class="text-muted">Tidak tersedia</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Selfie Kartu Identitas:</strong>
                                                @if($order->selfie_kartu_identitas)
                                                    <img src="{{ asset('storage/' . $order->selfie_kartu_identitas) }}" alt="Selfie Kartu Identitas" class="img-fluid rounded mt-2">
                                                @else
                                                    <div class="text-muted">Tidak tersedia</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-{{ $order->id }}" tabindex="-1" aria-labelledby="buktiModalLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="buktiModalLabel-{{ $order->id }}">Bukti Pembayaran - Pesanan #{{ $order->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $displayBuktiPath = null;
                                            $displayExt = null;

                                            if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                                $displayBuktiPath = asset('storage/' . session('uploaded_bukti_path'));
                                                $displayExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        @endphp

                                        @if($displayBuktiPath)
                                                @php
                                                    // If it's a PDF, embed it. Otherwise try to display as image.
                                                @endphp
                                                @if($displayExt === 'pdf')
                                                    <embed src="{{ $displayBuktiPath }}" type="application/pdf" width="100%" height="600px" />
                                                @else
                                                    <img src="{{ $displayBuktiPath }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'{{ $displayBuktiPath }}\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                                @endif
                                        @else
                                            <div class="alert alert-secondary"><i class="bi bi-info-circle"></i> Belum ada bukti pembayaran untuk pesanan ini.</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Modal (Hapus permanen) -->
                        @if(in_array($order->status, ['proses', 'revisi']))
                        <div class="modal fade" id="orderActionModal-{{ $order->id }}" tabindex="-1" aria-labelledby="orderActionLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="orderActionLabel-{{ $order->id }}"><i class="bi bi-x-octagon"></i> Hapus Pesanan Permanen</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pesanan #{{ $order->id }} akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="orderActionForm-{{ $order->id }}" method="POST" action="{{ url('/pesanan-saya') }}/{{ $order->id }}/delete">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check-circle"></i> Hapus Sekarang
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            try {
                setTimeout(() => {
                    if (window.bootstrap && typeof window.bootstrap.Alert !== 'undefined') {
                        const instance = window.bootstrap.Alert.getOrCreateInstance(successAlert);
                        instance.close();
                    } else {
                        successAlert.remove();
                    }
                }, 5000);
            } catch (e) {}
        }
    });
</script>
@endsection
