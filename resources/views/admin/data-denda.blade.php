@extends('layouts.main')

@section('title', 'Data Denda & Kerusakan - Rei Cosrent')

@section('styles')
<style>
    table th { background-color: var(--bs-primary); color: #fff; text-align: center; }
    .action-buttons { display:flex; gap:8px; justify-content:center; }
    .thumb { max-width:100px; max-height:80px; object-fit:cover; }
    .page-title { color: #0056b3; transition: color 0s ease; }

    .bukti-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0;
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .bukti-thumb:hover { transform: scale(1.02); }

    [data-bs-theme="dark"] .page-title { color: #a855f7; }
    [data-bs-theme="light"] .page-title { color: #0056b3; }
</style>
@endsection

@section('content')
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Denda & Kerusakan</h1>
        <p class="text-muted">Kelola denda dan laporan kerusakan kostum.</p>
    </div>
</header>

<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle"></i> Tambah Denda</button>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            @php
                // Build a map of unique names -> nama_kostum for selects/datalists
                $nameMap = [];
                if (isset($formulir) && is_iterable($formulir)) {
                    foreach ($formulir as $f) {
                        if (!isset($nameMap[$f->nama])) {
                            $nameMap[$f->nama] = $f->nama_kostum ?? '';
                        }
                    }
                }
            @endphp

            @if(count($dendas) > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Nama Kostum</th>
                            <th>Jenis Denda</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Bukti Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dendas as $item)
                        <tr id="denda-row-{{ $item->id }}" data-nama="{{ e($item->nama) }}" data-nama_kostum="{{ e($item->nama_kostum) }}" data-jenis_denda="{{ e($item->jenis_denda) }}" data-keterangan="{{ e($item->keterangan) }}" data-jumlah_denda="{{ $item->jumlah_denda }}">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="field-nama">{{ $item->nama }}</td>
                            <td class="field-nama_kostum">{{ $item->nama_kostum }}</td>
                            <td class="field-jenis_denda">{{ $item->jenis_denda }}</td>
                            <td class="field-keterangan"><div style="max-height:120px;overflow:auto">{!! nl2br(e($item->keterangan)) !!}</div></td>
                            <td class="field-jumlah_denda text-end">Rp{{ $item->jumlah_denda ? number_format($item->jumlah_denda,0,',','.') : '-' }}</td>
                            @php
                                $st = strtolower($item->status ?? '');
                                $statusClassMap = [
                                    'proses' => 'bg-warning text-dark',
                                    'revisi' => 'bg-secondary',
                                    'diterima' => 'bg-info text-dark',
                                    'selesai' => 'bg-success',
                                    'dibatalkan' => 'bg-secondary',
                                    'belum lunas' => 'bg-warning text-dark',
                                    'lunas' => 'bg-success text-white',
                                ];
                                $statusIconMap = [
                                    'proses' => 'bi-clock',
                                    'revisi' => 'bi-pencil-square',
                                    'diterima' => 'bi-person-check',
                                    'selesai' => 'bi-check-circle',
                                    'dibatalkan' => 'bi-x-circle',
                                    'belum lunas' => 'bi-exclamation-circle',
                                    'lunas' => 'bi-check2',
                                ];
                                $badgeClass = $statusClassMap[$st] ?? 'bg-dark text-white';
                                $badgeIcon = $statusIconMap[$st] ?? 'bi-info-circle';
                            @endphp
                            <td class="field-status text-center"><span class="badge {{ $badgeClass }}"><i class="bi {{ $badgeIcon }} me-1"></i> {{ ucfirst($item->status) }}</span></td>
                            <td class="text-center">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                @php
                                    $displayBuktiPath = null;
                                    $displayExt = null;
                                    $foundBuktiPath = null;
                                    try {
                                        $files = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                        foreach ($files as $f) {
                                            if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_denda_' . $item->id . '_')) {
                                                $foundBuktiPath = $f;
                                                break;
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $foundBuktiPath = null;
                                    }

                                    if (!empty($item->bukti_pembayaran)) {
                                        $displayBuktiPath = asset('storage/' . $item->bukti_pembayaran);
                                        $displayExt = strtolower(pathinfo($item->bukti_pembayaran, PATHINFO_EXTENSION));
                                    } elseif (!empty($foundBuktiPath)) {
                                        $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                        $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                    }
                                @endphp

                                @if($displayBuktiPath)
                                    @if($displayExt === 'pdf')
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminDendaBuktiModal-{{ $item->id }}" title="Lihat Bukti (PDF)">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#adminDendaBuktiModal-{{ $item->id }}" aria-label="Lihat bukti pembayaran denda">
                                            <img src="{{ $displayBuktiPath }}" alt="Bukti Pembayaran Denda" class="bukti-thumb">
                                        </button>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons" id="action-buttons-{{ $item->id }}">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#dendaDetailModal-{{ $item->id }}" title="Detail">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>
                                    <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}"><i class="bi bi-pencil"></i> Edit</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </td>
                        </tr>


                        <!-- Detail Modal -->
                        <div class="modal fade" id="dendaDetailModal-{{ $item->id }}" tabindex="-1" aria-labelledby="dendaDetailLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title" id="dendaDetailLabel-{{ $item->id }}">
                                            <i class="bi bi-card-list"></i> Detail Denda
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br>{{ $item->nama ?? '-' }}</div>
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br>{{ $item->nama_kostum ?? '-' }}</div>
                                                <div class="mb-2"><strong>Dibuat:</strong><br>{{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Jenis Denda:</strong><br>{{ $item->jenis_denda ?? '-' }}</div>
                                                <div class="mb-2"><strong>Keterangan:</strong><br>{!! nl2br(e($item->keterangan)) !!}</div>
                                                <div class="mb-2"><strong>Jumlah Denda:</strong><br>Rp{{ $item->jumlah_denda ? number_format($item->jumlah_denda,0,',','.') : '-' }}</div>
                                            </div>
                                        </div>

                                        @php
                                            $buktiFotos = collect([
                                                $item->bukti_foto_1 ?? null,
                                                $item->bukti_foto_2 ?? null,
                                                $item->bukti_foto_3 ?? null,
                                                $item->bukti_foto_4 ?? null,
                                                $item->bukti_foto_5 ?? null,
                                            ])->filter();
                                        @endphp
                                        <hr>
                                        <div>
                                            <strong>Bukti Foto:</strong>
                                            @if($buktiFotos->isNotEmpty())
                                                <div class="row g-2 mt-1">
                                                    @foreach($buktiFotos as $bf)
                                                        <div class="col-6 col-md-4 col-lg-3">
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 d-block" onclick="showDendaBuktiFotoPreview('{{ asset('storage/' . $bf) }}')" aria-label="Lihat bukti foto">
                                                                <img src="{{ asset('storage/' . $bf) }}" alt="Bukti Foto" class="img-fluid rounded" style="max-height:160px; object-fit:cover; width:100%; cursor:pointer;" onerror="this.outerHTML = '<a href=\'{{ asset('storage/' . $bf) }}\' target=\'_blank\' class=\'btn btn-outline-secondary btn-sm\'>Lihat File</a>'">
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-muted mt-1">Tidak tersedia</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Pembayaran Modal -->
                        <div class="modal fade" id="adminDendaBuktiModal-{{ $item->id }}" tabindex="-1" aria-labelledby="adminDendaBuktiLabel-{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title" id="adminDendaBuktiLabel-{{ $item->id }}">Bukti Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $modalBuktiPath = null;
                                            $modalExt = null;
                                            $modalFound = null;
                                            try {
                                                $modalFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                                foreach ($modalFiles as $mf) {
                                                    if (\Illuminate\Support\Str::startsWith(basename($mf), 'bukti_denda_' . $item->id . '_')) {
                                                        $modalFound = $mf;
                                                        break;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $modalFound = null;
                                            }

                                            if (!empty($item->bukti_pembayaran)) {
                                                $modalBuktiPath = asset('storage/' . $item->bukti_pembayaran);
                                                $modalExt = strtolower(pathinfo($item->bukti_pembayaran, PATHINFO_EXTENSION));
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
                                            <div class="alert alert-secondary">Belum ada bukti pembayaran untuk denda ini.</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title">Edit Denda</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.denda.update', $item->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama</label>
                                                    <div class="d-flex gap-2">
                                                        <select id="edit-nama-select-{{ $item->id }}" class="form-select" style="max-width: 45%;" onchange="editSelectChange({{ $item->id }})">
                                                            <option value="">-- Pilih dari daftar --</option>
                                                            @foreach($nameMap as $n => $k)
                                                                <option value="{{ e($n) }}" {{ $item->nama == $n ? 'selected' : '' }}>{{ $n }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div style="flex:1">
                                                            <input id="edit-nama-input-{{ $item->id }}" name="nama" class="form-control" list="formulir-names-{{ $item->id }}" placeholder="Atau ketik untuk mencari nama..." value="{{ e($item->nama) }}" autocomplete="off" oninput="editInputChange({{ $item->id }})">
                                                            <datalist id="formulir-names-{{ $item->id }}">
                                                                @foreach($nameMap as $n => $k)
                                                                    <option value="{{ e($n) }}"></option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Kostum</label>
                                                    <input type="text" id="edit-nama-kostum-{{ $item->id }}" name="nama_kostum" class="form-control" value="{{ e($item->nama_kostum) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jenis Denda</label>
                                                    <input type="text" name="jenis_denda" class="form-control" value="{{ e($item->jenis_denda) }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jumlah Denda (angka)</label>
                                                    <input type="number" step="0.01" name="jumlah_denda" class="form-control" value="{{ $item->jumlah_denda }}">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="4">{{ e($item->keterangan) }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 1 (opsional)</label>
                                                    <input type="file" name="bukti_foto_1" class="form-control" accept="image/*">
                                                    @if(!empty($item->bukti_foto_1))
                                                        <div class="mt-2"><img src="{{ asset('storage/' . $item->bukti_foto_1) }}" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 2 (opsional)</label>
                                                    <input type="file" name="bukti_foto_2" class="form-control" accept="image/*">
                                                    @if(!empty($item->bukti_foto_2))
                                                        <div class="mt-2"><img src="{{ asset('storage/' . $item->bukti_foto_2) }}" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 3 (opsional)</label>
                                                    <input type="file" name="bukti_foto_3" class="form-control" accept="image/*">
                                                    @if(!empty($item->bukti_foto_3))
                                                        <div class="mt-2"><img src="{{ asset('storage/' . $item->bukti_foto_3) }}" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 4 (opsional)</label>
                                                    <input type="file" name="bukti_foto_4" class="form-control" accept="image/*">
                                                    @if(!empty($item->bukti_foto_4))
                                                        <div class="mt-2"><img src="{{ asset('storage/' . $item->bukti_foto_4) }}" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 5 (opsional)</label>
                                                    <input type="file" name="bukti_foto_5" class="form-control" accept="image/*">
                                                    @if(!empty($item->bukti_foto_5))
                                                        <div class="mt-2"><img src="{{ asset('storage/' . $item->bukti_foto_5) }}" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title">Hapus Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.denda.destroy', $item->id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p>Anda yakin ingin menghapus data denda ini?</p>
                                            <div><strong>{{ $item->nama }}</strong> - <span class="text-muted">{{ $item->nama_kostum }}</span></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="alert alert-info text-center"><i class="bi bi-info-circle"></i> Belum ada data denda.</div>
            @endif

        </div>
    </div>
</section>

    <!-- Bukti Foto Preview Modal (must be inside content section) -->
    <div class="modal fade" id="dendaBuktiFotoPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header modal-header-surface">
                    <h5 class="modal-title">Bukti Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="dendaBuktiFotoPreviewImg" src="" alt="Preview" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-surface">
                <h5 class="modal-title">Tambah Data Denda / Kerusakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.denda.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama (pilih dari formulir)</label>
                            @php
                                $nameMap = [];
                                if (isset($formulir) && is_iterable($formulir)) {
                                    foreach ($formulir as $f) {
                                        if (!isset($nameMap[$f->nama])) {
                                            $nameMap[$f->nama] = $f->nama_kostum ?? '';
                                        }
                                    }
                                }
                            @endphp
                            <div class="d-flex gap-2">
                                <select id="add-nama-select" class="form-select" style="max-width: 45%;">
                                    <option value="">-- Pilih dari daftar --</option>
                                    @foreach($nameMap as $n => $k)
                                        <option value="{{ e($n) }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                                <div style="flex:1">
                                    <input id="add-nama-input" name="nama" class="form-control" list="formulir-names" placeholder="Atau ketik untuk mencari nama..." autocomplete="off">
                                    <datalist id="formulir-names">
                                        @foreach($nameMap as $n => $k)
                                            <option value="{{ e($n) }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kostum</label>
                            <input type="text" id="add-nama-kostum" name="nama_kostum" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Denda</label>
                            <input type="text" name="jenis_denda" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Denda (angka)</label>
                            <input type="number" step="0.01" name="jumlah_denda" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 1 (opsional)</label>
                            <input type="file" name="bukti_foto_1" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 2 (opsional)</label>
                            <input type="file" name="bukti_foto_2" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 3 (opsional)</label>
                            <input type="file" name="bukti_foto_3" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 4 (opsional)</label>
                            <input type="file" name="bukti_foto_4" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 5 (opsional)</label>
                            <input type="file" name="bukti_foto_5" class="form-control" accept="image/*">
                        </div>
                        <!-- Note: status kept minimal; bukti foto opsional -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(a => setTimeout(()=> new bootstrap.Alert(a).close(), 3000));
    });
</script>
<script>
    function showDendaBuktiFotoPreview(src) {
        const img = document.getElementById('dendaBuktiFotoPreviewImg');
        if (!img) return;
        img.src = src;

        const modalEl = document.getElementById('dendaBuktiFotoPreviewModal');
        if (!modalEl || !window.bootstrap) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('dendaBuktiFotoPreviewModal');
        if (!modalEl) return;
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('dendaBuktiFotoPreviewImg');
            if (img) img.src = '';
        });
    });
</script>
<script>
    // Edit modal helpers
    const nameMap = {!! json_encode($nameMap ?? []) !!};
    function editSelectChange(id) {
        const sel = document.getElementById('edit-nama-select-' + id);
        const input = document.getElementById('edit-nama-input-' + id);
        const kostum = document.getElementById('edit-nama-kostum-' + id);
        if (!sel || !input) return;
        const val = sel.value || '';
        input.value = val;
        if (val && nameMap[val] !== undefined && kostum) {
            kostum.value = nameMap[val] || '';
        }
    }

    function editInputChange(id) {
        const input = document.getElementById('edit-nama-input-' + id);
        const kostum = document.getElementById('edit-nama-kostum-' + id);
        if (!input) return;
        const val = input.value || '';
        if (val && nameMap[val] !== undefined && kostum) {
            kostum.value = nameMap[val] || '';
        }
    }
</script>
<script>
    // Auto-fill nama_kostum in Add Modal based on selected formulir name
    (function(){
        const nameMap = {!! json_encode($nameMap ?? []) !!};
        const input = document.getElementById('add-nama-input');
        const select = document.getElementById('add-nama-select');
        const kostumInput = document.getElementById('add-nama-kostum');
        if (select && input) {
            select.addEventListener('change', function(){
                const val = this.value || '';
                input.value = val; // mirror into input
                if (val && nameMap[val] !== undefined && kostumInput) {
                    kostumInput.value = nameMap[val] || '';
                }
            });
        }
        if (input && kostumInput) {
            // when user selects from datalist or types exact name
            input.addEventListener('input', function(){
                const val = this.value || '';
                if (val && nameMap[val] !== undefined) {
                    kostumInput.value = nameMap[val] || '';
                }
            });

            // also support blur: if exact match found on blur, fill
            input.addEventListener('blur', function(){
                const val = this.value || '';
                if (val && nameMap[val] !== undefined) {
                    kostumInput.value = nameMap[val] || '';
                }
            });
        }
    })();
</script>
@endsection
