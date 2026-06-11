@extends('layouts.main')

@section('title', 'Data Pengembalian - Rei Cosrent')

@section('styles')
<style>
    .page-title {
        color: #0056b3;
    }

    [data-bs-theme="dark"] .page-title {
        color: #a855f7;
    }

    .thumb {
        width: 76px;
        height: 76px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.5rem;
        cursor: zoom-in;
    }

    .status-badge {
        font-size: 0.75rem;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
    }

    .table th {
        background-color: var(--bs-primary);
        color: #fff;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Pengembalian</h2>
                <p class="text-muted mb-0 small">Kelola verifikasi data pengembalian yang telah diisi user.</p>
            </div>
            <div class="d-grid d-sm-block">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="small text-muted mb-3">
            Total Pengajuan: <strong>{{ $pengembalianList->count() }}</strong> | Menunggu Verifikasi: <strong>{{ $pendingCount }}</strong>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        @if($pengembalianList->isEmpty())
            <div class="alert alert-info mb-0">Belum ada data pengembalian dari user.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle orders-table text-center">
                    <thead>
                        <tr style="background-color: rgba(37, 99, 235, 0.08); border-bottom: 2px solid rgba(37, 99, 235, 0.15);">
                            <th style="width: 50px; color: var(--bs-body-color);">No</th>
                            <th style="color: var(--bs-body-color);">User</th>
                            <th style="color: var(--bs-body-color);">Kostum</th>
                            <th style="color: var(--bs-body-color);">Bukti</th>
                            <th style="color: var(--bs-body-color);">Status</th>
                            <th style="color: var(--bs-body-color);">Catatan User</th>
                            <th style="color: var(--bs-body-color);">Catatan Admin</th>
                            <th style="color: var(--bs-body-color);">Diajukan</th>
                            <th style="color: var(--bs-body-color);">Aksi</th>
                        </thead>
                        <tbody>
                            @foreach($pengembalianList as $index => $item)
                                @php
                                    $order = $item->formulir;
                                    $userName = data_get($order, 'nama', '-');
                                    $userEmail = data_get($order, 'email');
                                    $kostumName = data_get($order, 'nama_kostum', '-');
                                    $catatanAdmin = $item->catatan_admin ?: '-';
                                    $buktiList = collect([$item->gambar1, $item->gambar2, $item->gambar3])->filter();
                                    $statusMap = [
                                        'proses' => ['Proses', 'bg-warning text-dark'],
                                        'ditolak' => ['Ditolak', 'bg-danger'],
                                        'diterima' => ['Diterima', 'bg-success'],
                                    ];
                                    $statusKey = $item->status ?: 'proses';
                                    $statusData = $statusMap[$statusKey] ?? [ucfirst(str_replace('_', ' ', (string) $statusKey)), 'bg-secondary'];
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $userName }}</div>
                                        @if($userEmail)
                                            <div class="small text-muted">{{ $userEmail }}</div>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $kostumName }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            @foreach($buktiList as $index => $bukti)
                                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#imgModal-{{ $item->id }}-{{ $index }}" aria-label="Lihat bukti pengembalian">
                                                    <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti {{ $index + 1 }}" class="thumb">
                                                </button>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge status-badge {{ $statusData[1] }}">{{ $statusData[0] }}</span>
                                    </td>
                                    <td class="text-start">{{ $item->catatan ?: '-' }}</td>
                                    <td class="text-start">{{ $catatanAdmin }}</td>
                                    <td>{{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal-{{ $item->id }}" data-action="setujui">
                                                <i class="bi bi-check-circle"></i> Diterima
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#verifyModal-{{ $item->id }}" data-action="revisi">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                @foreach($buktiList as $index => $bukti)
                                    <div class="modal fade" id="imgModal-{{ $item->id }}-{{ $index }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-surface">
                                                    <h5 class="modal-title">Bukti Pengembalian #{{ $index + 1 }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Pengembalian" class="img-fluid rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="modal fade" id="verifyModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-surface">
                                                <h5 class="modal-title">Verifikasi Pengembalian {{ $kostumName }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.pengembalian.verifikasi', $item->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="row g-3 mb-3 text-start">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">User</div>
                                                            <div class="fw-semibold">{{ $userName }}</div>
                                                            @if($userEmail)
                                                                <div class="small text-muted">{{ $userEmail }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Kostum</div>
                                                            <div class="fw-semibold">{{ $kostumName }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label">Catatan Admin (Opsional)</label>
                                                        <textarea name="catatan_admin" class="form-control" rows="3" maxlength="255" placeholder="Contoh: Foto kedua kurang jelas, mohon upload ulang."></textarea>
                                                    </div>
                                                    <div class="alert alert-info mb-0 text-start">
                                                        Pilih <strong>Diterima</strong> untuk menyelesaikan pesanan, atau pilih <strong>Ditolak</strong> agar user mengajukan ulang data pengembalian.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="aksi" value="revisi" class="btn btn-danger">Ditolak</button>
                                                    <button type="submit" name="aksi" value="setujui" class="btn btn-success">Diterima</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection