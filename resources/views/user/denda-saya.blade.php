@extends('layouts.main')

@section('title', 'Denda Saya - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h2 class="fw-bold">Denda Saya</h2>
            </div>
            <div>
                <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if(isset($dendas) && count($dendas) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kostum</th>
                            <th>Jenis Denda</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dendas as $d)
                        <tr>
                            <td>{{ $d->id }}</td>
                            <td>{{ $d->nama_kostum ?? '-' }}</td>
                            <td>{{ $d->jenis_denda ?? '-' }}</td>
                            <td><div style="max-height:120px;overflow:auto">{!! nl2br(e($d->keterangan)) !!}</div></td>
                            <td class="text-end">Rp{{ $d->jumlah_denda ? number_format($d->jumlah_denda,0,',','.') : '-' }}</td>
                            <td class="text-center">{{ $d->created_at ? $d->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'Belum Lunas' => 'bg-warning text-dark',
                                        'Lunas' => 'bg-success text-white'
                                    ][$d->status] ?? 'bg-secondary text-white';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $d->status ? ucfirst($d->status) : '-' }}</span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#dendaDetailModal-{{ $d->id }}">
                                    <i class="bi bi-card-list"></i> Detail
                                </button>

                                @php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;
                                    try {
                                        if (!empty($d->bukti_pembayaran)) {
                                            $hasBukti = true;
                                        } else {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_denda_' . $d->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $hasBukti = false;
                                    }
                                @endphp

                                @if($hasBukti)
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#buktiModal-{{ $d->id }}">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                @else
                                    @if(strtolower($d->status) === strtolower('Belum Lunas'))
                                        <a href="{{ route('denda.bayar', $d->id) }}" class="btn btn-success btn-sm ms-2">
                                            <i class="bi bi-cash-coin"></i> Bayar Denda
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="dendaDetailModal-{{ $d->id }}" tabindex="-1" aria-labelledby="dendaDetailLabel-{{ $d->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="dendaDetailLabel-{{ $d->id }}">
                                            <i class="bi bi-card-list"></i> Detail Denda #{{ $d->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br>{{ $d->nama ?? '-' }}</div>
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br>{{ $d->nama_kostum ?? '-' }}</div>
                                                <div class="mb-2"><strong>Jenis Denda:</strong><br>{{ $d->jenis_denda ?? '-' }}</div>
                                                <div class="mb-2"><strong>Keterangan:</strong><br>{!! nl2br(e($d->keterangan)) !!}</div>
                                                <div class="mb-2"><strong>Jumlah Denda:</strong><br>Rp{{ $d->jumlah_denda ? number_format($d->jumlah_denda,0,',','.') : '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Dibuat:</strong><br>{{ $d->created_at ? $d->created_at->format('d M Y H:i') : '-' }}</div>
                                                <div class="mb-2"><strong>Status:</strong><br>{{ $d->status ?? '-' }}</div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-2"><strong>Foto Bukti:</strong><br>
                                            @if(!empty($d->bukti_foto))
                                                <img src="{{ asset('storage/' . $d->bukti_foto) }}" alt="Foto Bukti" class="img-fluid rounded" style="max-height:300px; object-fit:contain; width:100%;">
                                            @else
                                                <div class="text-muted">Tidak tersedia</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-{{ $d->id }}" tabindex="-1" aria-labelledby="buktiModalLabel-{{ $d->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="buktiModalLabel-{{ $d->id }}">Bukti Pembayaran - Denda #{{ $d->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $displayBuktiPath = null;
                                            $displayExt = null;
                                            if (!empty($d->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $d->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($d->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        @endphp

                                        @if($displayBuktiPath)
                                            @if($displayExt === 'pdf')
                                                <embed src="{{ $displayBuktiPath }}" type="application/pdf" width="100%" height="600px" />
                                            @else
                                                <img src="{{ $displayBuktiPath }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'{{ $displayBuktiPath }}\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                            @endif
                                        @else
                                            <div class="alert alert-secondary">Belum ada bukti pembayaran untuk denda ini.</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info text-center">Belum ada data denda untuk akun Anda.</div>
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

