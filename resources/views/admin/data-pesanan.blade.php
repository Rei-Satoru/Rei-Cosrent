@extends('layouts.main')

@section('title', 'Data Pesanan - Rei Cosrent')

@section('styles')
<style>
    table th {
        background-color: var(--bs-primary);
        color: white;
        text-align: center;
        font-size: 1.0rem;
    }

    table td {
        font-size: 0.95rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .page-title {
        color: #0056b3;
        transition: color 0s ease;
    }

    [data-bs-theme="dark"] .page-title {
        color: #a855f7;
    }

    [data-bs-theme="light"] .page-title {
        color: #0056b3;
    }

    footer {
        transition: background-color 1000ms;
    }

    body[data-bs-theme="light"] footer {
        background-color: #0d6efd !important;
    }

    body[data-bs-theme="dark"] footer {
        background-color: #8a2be2 !important;
    }

    .bukti-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0;
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .bukti-thumb:hover {
        transform: scale(1.02);
    }

    .identitas-thumb {
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .identitas-thumb:hover {
        transform: scale(1.01);
    }
</style>
@endsection

@section('content')
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Pesanan</h1>
        <p class="text-muted">Kelola pesanan pengguna dan ubah statusnya.</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Tombol di atas tabel -->
            <div class="d-flex justify-content-start mb-3 flex-wrap gap-2">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Alert -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($pesanan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kostum</th>
                                    <th>Pesanan Dibuat</th>
                                    <th>Pesanan Diupdate</th>
                                    <th>Tgl Pakai</th>
                                    <th>Tgl Kembali</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama_kostum }}</td>
                                    <td>
                                        @if($item->created_at)
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}<br>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->updated_at)
                                            {{ \Carbon\Carbon::parse($item->updated_at)->format('d-m-Y') }}<br>
                                            {{ \Carbon\Carbon::parse($item->updated_at)->format('H:i:s') }}
                                        @endif
                                    </td>
                                    <td>{{ $item->tanggal_pemakaian ? \Carbon\Carbon::parse($item->tanggal_pemakaian)->format('d M Y') : '-' }}</td>
                                    <td>{{ $item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') : '-' }}</td>
                                    <td>Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'proses' => 'bg-warning text-dark',
                                                'revisi' => 'bg-secondary',
                                                'diterima' => 'bg-info text-dark',
                                                'selesai' => 'bg-success',
                                                'dibatalkan' => 'bg-secondary'
                                            ][$item->status] ?? 'bg-dark';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                    <td>
                                        <input type="text"
                                               id="keterangan-{{ $item->id }}"
                                               class="form-control form-control-sm keterangan-input"
                                               placeholder="Tambahkan keterangan"
                                               value="{{ $item->keterangan }}"
                                               data-hidden="hidden-keterangan-{{ $item->id }}"
                                               maxlength="255">
                                    </td>
                                    <td>
                                        @php
                                            $displayBuktiPath = null;
                                            $displayExt = null;
                                            $foundBuktiPath = null;
                                            try {
                                                $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                                foreach ($files as $f) {
                                                    if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $item->id . '_')) {
                                                        $foundBuktiPath = $f;
                                                        break;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $foundBuktiPath = null;
                                            }

                                            if (isset($item->pembayaran_safe) && !empty($item->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $item->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($item->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        @endphp

                                        @if($displayBuktiPath)
                                            @if($displayExt === 'pdf')
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminBuktiModal-{{ $item->id }}" title="Lihat Bukti (PDF)">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#adminBuktiModal-{{ $item->id }}" aria-label="Lihat bukti pembayaran">
                                                    <img src="{{ $displayBuktiPath }}" alt="Bukti Pembayaran" class="bukti-thumb">
                                                </button>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#pesananDetail{{ $item->id }}" title="Detail">
                                                <i class="bi bi-info-circle"></i> Detail
                                            </button>
                                            <form id="updateForm-{{ $item->id }}" action="{{ route('admin.pesanan.update-status', $item->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                <input type="hidden" name="keterangan" id="hidden-keterangan-{{ $item->id }}" value="{{ $item->keterangan }}">
                                                <select name="status" class="form-select form-select-sm" style="width: 120px;">
                                                    @foreach($statusOptions as $status)
                                                        <option value="{{ $status }}" {{ $item->status === $status ? 'selected' : '' }}>
                                                            {{ ucfirst($status) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.pesanan.delete', $item->id) }}" method="POST" style="display:inline; margin-left:6px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="pesananDetail{{ $item->id }}" tabindex="-1" aria-labelledby="pesananDetailLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                                <div class="modal-header modal-header-surface">
                                            <h5 class="modal-title" id="pesananDetailLabel{{ $item->id }}">
                                                <i class="bi bi-card-list"></i> Detail Pesanan
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Nama Kostum:</strong><br>{{ $item->nama_kostum ?? '-' }}</div>
                                                    <div class="mb-2"><strong>Tgl Pakai:</strong><br>{{ $item->tanggal_pemakaian ? \Carbon\Carbon::parse($item->tanggal_pemakaian)->format('d M Y') : '-' }}</div>
                                                    <div class="mb-2"><strong>Tgl Kembali:</strong><br>{{ $item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') : '-' }}</div>
                                                    <div class="mb-2"><strong>Total Harga:</strong><br>Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}</div>
                                                    <div class="mb-2"><strong>Metode Pembayaran:</strong><br>{{ $item->metode_pembayaran ?? '-' }}</div>
                                                    
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Nama:</strong><br>{{ $item->nama }}</div>
                                                    <div class="mb-2"><strong>Nomor Telepon:</strong><br>{{ $item->nomor_telepon ?? '-' }}</div>
                                                    <div class="mb-2"><strong>Nomor Telepon 2:</strong><br>{{ $item->nomor_telepon_2 ?? '-' }}</div>
                                                    <div class="mb-2"><strong>Alamat:</strong><br>{{ $item->alamat ?? '-' }}</div>
                                                    <div class="mb-2"><strong>Kartu Identitas:</strong><br>{{ $item->kartu_identitas ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Foto Kartu Identitas:</strong><br>
                                                        @if($item->foto_kartu_identitas)
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 text-start js-admin-identitas-preview" data-src="{{ asset('storage/' . $item->foto_kartu_identitas) }}" data-title="Foto Kartu Identitas" aria-label="Lihat foto kartu identitas">
                                                                <img src="{{ asset('storage/' . $item->foto_kartu_identitas) }}" alt="Foto Kartu Identitas" class="img-fluid rounded mb-2 identitas-thumb" style="max-width: 100%; height: auto;">
                                                            </button>
                                                        @else
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Selfie Kartu Identitas:</strong><br>
                                                        @if($item->selfie_kartu_identitas)
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 text-start js-admin-identitas-preview" data-src="{{ asset('storage/' . $item->selfie_kartu_identitas) }}" data-title="Selfie Kartu Identitas" aria-label="Lihat selfie kartu identitas">
                                                                <img src="{{ asset('storage/' . $item->selfie_kartu_identitas) }}" alt="Selfie Kartu Identitas" class="img-fluid rounded mb-2 identitas-thumb" style="max-width: 100%; height: auto;">
                                                            </button>
                                                        @else
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- Bukti Modal (per item) -->
                                <div class="modal fade" id="adminBuktiModal-{{ $item->id }}" tabindex="-1" aria-labelledby="adminBuktiLabel-{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-surface">
                                                <h5 class="modal-title" id="adminBuktiLabel-{{ $item->id }}">Bukti Pembayaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @php
                                                    $modalBuktiPath = null;
                                                    $modalExt = null;
                                                    $modalFound = null;
                                                    try {
                                                        $modalFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                                        foreach ($modalFiles as $mf) {
                                                            if (\Illuminate\Support\Str::startsWith(basename($mf), 'bukti_' . $item->id . '_')) {
                                                                $modalFound = $mf;
                                                                break;
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        $modalFound = null;
                                                    }

                                                    if (isset($item->pembayaran_safe) && !empty($item->pembayaran_safe->bukti_pembayaran)) {
                                                        $modalBuktiPath = asset('storage/' . $item->pembayaran_safe->bukti_pembayaran);
                                                        $modalExt = strtolower(pathinfo($item->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                                    } elseif (!empty($modalFound)) {
                                                        $modalBuktiPath = asset('storage/' . $modalFound);
                                                        $modalExt = strtolower(pathinfo($modalFound, PATHINFO_EXTENSION));
                                                    }
                                                @endphp

                                                @if($modalBuktiPath)
                                                    @if($modalExt === 'pdf')
                                                        <embed src="{{ $modalBuktiPath }}" type="application/pdf" width="100%" height="600px" />
                                                    @else
                                                        <img src="{{ $modalBuktiPath }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'{{ $modalBuktiPath }}\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                                    @endif
                                                @else
                                                    <div class="alert alert-secondary">Belum ada bukti pembayaran untuk pesanan ini.</div>
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
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada data pesanan.
                </div>
            @endif

        </div>
    </div>
</section>

<!-- Modal Preview Identitas (reusable) -->
<div class="modal fade" id="adminIdentitasPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-surface">
                <h5 class="modal-title" id="adminIdentitasPreviewTitle">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <embed id="adminIdentitasPreviewEmbed" src="" type="application/pdf" width="100%" height="600px" class="d-none" />
                <img id="adminIdentitasPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });

        // Close modal after 'Simpan Perubahan' is clicked
        document.querySelectorAll('form[id^="updateForm-"]').forEach(form => {
            form.addEventListener('submit', function() {
                // Find the closest modal and hide it
                const modal = form.closest('.modal');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        });

        // Sink visible keterangan inputs into their hidden form fields before submit
        document.querySelectorAll('.keterangan-input').forEach(input => {
            const hiddenId = input.getAttribute('data-hidden');
            const hiddenField = hiddenId ? document.getElementById(hiddenId) : null;

            const syncValue = () => {
                if (hiddenField) {
                    hiddenField.value = input.value;
                }
            };

            syncValue();
            input.addEventListener('input', syncValue);
        });

        function showAdminIdentitasPreview(src, title) {
            const titleEl = document.getElementById('adminIdentitasPreviewTitle');
            const imgEl = document.getElementById('adminIdentitasPreviewImg');
            const embedEl = document.getElementById('adminIdentitasPreviewEmbed');
            const modalEl = document.getElementById('adminIdentitasPreviewModal');
            if (!modalEl || !window.bootstrap) return;

            if (titleEl) titleEl.textContent = title || 'Preview';

            const lower = (src || '').toLowerCase();
            const isPdf = lower.includes('.pdf');

            if (isPdf) {
                if (embedEl) {
                    embedEl.src = src || '';
                    embedEl.classList.remove('d-none');
                }
                if (imgEl) {
                    imgEl.src = '';
                    imgEl.classList.add('d-none');
                }
            } else {
                if (imgEl) {
                    imgEl.src = src || '';
                    imgEl.classList.remove('d-none');
                }
                if (embedEl) {
                    embedEl.src = '';
                    embedEl.classList.add('d-none');
                }
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-admin-identitas-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-src');
                const title = btn.getAttribute('data-title');
                showAdminIdentitasPreview(src, title);
            });
        });

        const identitasModalEl = document.getElementById('adminIdentitasPreviewModal');
        if (identitasModalEl) {
            identitasModalEl.addEventListener('hidden.bs.modal', function () {
                const imgEl = document.getElementById('adminIdentitasPreviewImg');
                const embedEl = document.getElementById('adminIdentitasPreviewEmbed');
                if (imgEl) imgEl.src = '';
                if (embedEl) embedEl.src = '';
            });
        }
    });
</script>
@endsection
