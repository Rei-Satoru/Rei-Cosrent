@extends('layouts.main')

@section('title', 'Data Kostum - Rei Cosrent')

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
    gap: 6px;
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

    .orders-table {
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 0;
        overflow: hidden;
    }

    .orders-table thead th {
        background: rgba(37, 99, 235, 0.08);
        color: var(--bs-body-color);
        border-bottom: 1px solid rgba(148, 163, 184, 0.22);
        font-weight: 700;
        white-space: nowrap;
    }

    .orders-table tbody td {
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        border-color: rgba(148, 163, 184, 0.14);
        vertical-align: middle;
        font-size: 0.95rem;
    }

    .orders-table thead th,
    .orders-table tbody td {
        border-right: 1px solid rgba(148, 163, 184, 0.14);
    }

    .orders-table thead th:last-child,
    .orders-table tbody td:last-child {
        border-right: 0;
    }

    .orders-table tbody tr:hover td {
        background: rgba(37, 99, 235, 0.04);
    }

    [data-bs-theme="dark"] .orders-table {
        border-color: rgba(148, 163, 184, 0.24);
    }

    [data-bs-theme="dark"] .orders-table thead th {
        background: rgba(59, 130, 246, 0.16);
        border-bottom-color: rgba(148, 163, 184, 0.22);
    }

    [data-bs-theme="dark"] .orders-table tbody td {
        background: rgba(15, 23, 42, 0.96);
        border-color: rgba(148, 163, 184, 0.16);
    }

    [data-bs-theme="dark"] .orders-table thead th,
    [data-bs-theme="dark"] .orders-table tbody td {
        border-right-color: rgba(148, 163, 184, 0.16);
    }

    [data-bs-theme="dark"] .orders-table tbody tr:hover td {
        background: rgba(59, 130, 246, 0.14);
    }

    .table img {
        max-width: 80px;
        height: auto;
    }

    .kostum-thumb {
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .kostum-thumb:hover {
        transform: scale(1.02);
    }

    footer {
        transition: background-color 1000ms;
    }

    body[data-bs-theme=\"light\"] footer {
        background-color: #0d6efd !important;
    }

    body[data-bs-theme=\"dark\"] footer {
        background-color: #8a2be2 !important;
    }

    .rupiah-format::before {
        content: \"Rp\";
    }
</style>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Kostum</h2>
                <p class="text-muted mb-0 small">Kelola daftar kostum yang tersedia untuk disewa.</p>
            </div>
            <div class="d-grid d-sm-block">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kostum
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4" style="background-color: rgba(37, 99, 235, 0.06); border: 1px solid rgba(37, 99, 235, 0.12);">
            <div class="card-body">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, brand, kategori..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat }}" {{ ($filter_kategori ?? '') === $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="jenis_kelamin" class="form-select">
                                <option value="">Semua Jenis Kelamin</option>
                                <option value="Pria" {{ ($filter_jenis_kelamin ?? '') === 'Pria' ? 'selected' : '' }}>Pria</option>
                                <option value="Wanita" {{ ($filter_jenis_kelamin ?? '') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="id_asc" {{ ($sort ?? '') === 'id_asc' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>A - Z</option>
                                <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Z - A</option>
                                <option value="harga_asc" {{ ($sort ?? '') === 'harga_asc' ? 'selected' : '' }}>Harga Termurah</option>
                                <option value="harga_desc" {{ ($sort ?? '') === 'harga_desc' ? 'selected' : '' }}>Harga Termahal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="ukuran" class="form-select">
                                <option value="">Semua Ukuran</option>
                                @foreach($ukuran as $uk)
                                    <option value="{{ $uk }}" {{ ($filter_ukuran ?? '') === $uk ? 'selected' : '' }}>{{ $uk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                    @if($search || $filter_kategori || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_asc'))
                        <div class="mt-2">
                            <a href="{{ route('admin.data-kostum') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
            </div>


            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($kostum->count() > 0)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Menampilkan <strong>{{ $kostum->count() }}</strong> dari data kostum
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle orders-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th class="d-none d-md-table-cell">Gambar</th>
                                <th class="d-none d-md-table-cell">Jenis Kelamin</th>
                                <th class="d-none d-md-table-cell">Brand</th>
                                <th>Harga</th>
                                <th class="d-none d-md-table-cell">Durasi</th>
                                <th class="d-none d-md-table-cell">Ukuran</th>
                                <th class="d-none d-lg-table-cell">Include</th>
                                <th class="d-none d-lg-table-cell">Exclude</th>
                                <th class="d-none d-md-table-cell">Domisili</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kostum as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kostum }}</td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td class="d-none d-md-table-cell">
                                    @if(!empty($item->gambar))
                                            @php
                                                $imgRaw = $item->gambar ?? '';
                                                if (str_starts_with($imgRaw, 'http')) {
                                                    $kostumImgSrc = $imgRaw;
                                                } elseif (str_starts_with($imgRaw, '/storage/')) {
                                                    $kostumImgSrc = asset(ltrim($imgRaw, '/'));
                                                } elseif (str_starts_with($imgRaw, 'storage/')) {
                                                    $kostumImgSrc = asset($imgRaw);
                                                } elseif ($imgRaw) {
                                                    $kostumImgSrc = asset('storage/' . $imgRaw);
                                                } else {
                                                    $kostumImgSrc = null;
                                                }
                                            @endphp
                                            <button type="button" class="btn p-0 border-0 bg-transparent js-kostum-image-preview" data-image-src="{{ $kostumImgSrc }}" data-image-title="Gambar Kostum: {{ $item->nama_kostum }}" aria-label="Lihat gambar kostum {{ $item->nama_kostum }}">
                                                <img src="{{ $kostumImgSrc }}" alt="{{ $item->nama_kostum }}" class="kostum-thumb" style="max-width:80px;">
                                            </button>
                                        @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell">{{ ucfirst($item->jenis_kelamin ?? '-') }}</td>
                                <td class="d-none d-md-table-cell">{{ $item->brand ?? '-' }}</td>
                                <td>Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                                <td class="d-none d-md-table-cell">{{ $item->durasi_penyewaan }}</td>
                                @php
                                    $sizes = array_filter(array_map('trim', preg_split('/[,&]/', $item->ukuran_kostum ?? '')));
                                    $order = ['XS'=>1,'S'=>2,'M'=>3,'L'=>4,'XL'=>5,'XXL'=>6,'XXXL'=>7];
                                    usort($sizes, function($a,$b) use ($order){
                                        $aKey = strtoupper($a); $bKey = strtoupper($b);
                                        $aR = $order[$aKey] ?? 999; $bR = $order[$bKey] ?? 999;
                                        return $aR === $bR ? strcasecmp($aKey,$bKey) : ($aR <=> $bR);
                                    });
                                @endphp
                                <td class="d-none d-md-table-cell">{{ $sizes ? implode(' ', $sizes) : '-' }}</td>
                                <td class="d-none d-lg-table-cell" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->include }}">{{ $item->include }}</td>
                                <td class="d-none d-lg-table-cell" style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->exclude }}">{{ $item->exclude ?? '-' }}</td>
                                <td class="d-none d-md-table-cell">{{ !empty($item->domisili) ? $item->domisili : '-' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $item->id_kostum }}" title="Detail">
                                            <i class="bi bi-info-circle"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id_kostum }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.kostum.delete', $item->id_kostum) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kostum ini?')" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal{{ $item->id_kostum }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title">Detail: {{ $item->nama_kostum }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-5 text-center">
                                                    @if(!empty($item->gambar))
                                                        @php
                                                            $imgRaw = $item->gambar ?? '';
                                                            if (str_starts_with($imgRaw, 'http')) {
                                                                $kostumImgSrc = $imgRaw;
                                                            } elseif (str_starts_with($imgRaw, '/storage/')) {
                                                                $kostumImgSrc = asset(ltrim($imgRaw, '/'));
                                                            } elseif (str_starts_with($imgRaw, 'storage/')) {
                                                                $kostumImgSrc = asset($imgRaw);
                                                            } elseif ($imgRaw) {
                                                                $kostumImgSrc = asset('storage/' . $imgRaw);
                                                            } else {
                                                                $kostumImgSrc = null;
                                                            }
                                                        @endphp
                                                        <button type="button" class="btn p-0 border-0 bg-transparent js-kostum-image-preview" data-image-src="{{ $kostumImgSrc }}" data-image-title="Gambar Kostum: {{ $item->nama_kostum }}" aria-label="Lihat gambar kostum {{ $item->nama_kostum }}">
                                                            <img src="{{ $kostumImgSrc }}" alt="Gambar Kostum" class="img-fluid rounded kostum-thumb" style="aspect-ratio:1/1;object-fit:cover;">
                                                        </button>
                                                    @else
                                                        <img src="{{ asset('assets/img/no-image.png') }}" alt="Tidak ada gambar" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="row mb-2"><div class="col-5 text-muted">Nama Kostum</div><div class="col-7">: {{ $item->nama_kostum }}</div></div>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Judul</div><div class="col-7">: {{ $item->judul ?: '-' }}</div></div>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Kategori</div><div class="col-7">: {{ ucfirst($item->kategori) }}</div></div>
                                                    @if(!empty($item->jenis_kelamin))
                                                        <div class="row mb-2"><div class="col-5 text-muted">Jenis Kelamin</div><div class="col-7">: {{ ucfirst($item->jenis_kelamin) }}</div></div>
                                                    @endif
                                                    @if(!empty($item->brand))
                                                        <div class="row mb-2"><div class="col-5 text-muted">Brand</div><div class="col-7">: {{ $item->brand }}</div></div>
                                                    @endif
                                                    <div class="row mb-2"><div class="col-5 text-muted">Harga Sewa</div><div class="col-7">: Rp {{ number_format((float)$item->harga_sewa, 0, ',', '.') }}</div></div>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Durasi Penyewaan</div><div class="col-7">: {{ $item->durasi_penyewaan }}</div></div>
                                                    @php
                                                        $sizes = array_filter(array_map('trim', preg_split('/[,&]/', $item->ukuran_kostum ?? '')));
                                                        $order = ['XS'=>1,'S'=>2,'M'=>3,'L'=>4,'XL'=>5,'XXL'=>6,'XXXL'=>7];
                                                        usort($sizes, function($a,$b) use ($order){
                                                            $aKey = strtoupper($a); $bKey = strtoupper($b);
                                                            $aR = $order[$aKey] ?? 999; $bR = $order[$bKey] ?? 999;
                                                            return $aR === $bR ? strcasecmp($aKey,$bKey) : ($aR <=> $bR);
                                                        });
                                                    @endphp
                                                    <div class="row mb-2">
                                                        <div class="col-5 text-muted">Ukuran</div>
                                                        <div class="col-7">
                                                            @if($sizes)
                                                                @foreach($sizes as $size)
                                                                    <span class="badge bg-secondary me-1 mb-1">{{ $size }}</span>
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Include</div><div class="col-7">: {!! nl2br(e($item->include)) !!}</div></div>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Exclude</div><div class="col-7">: {!! nl2br(e($item->exclude)) !!}</div></div>
                                                    <div class="row"><div class="col-5 text-muted">Domisili</div><div class="col-7">: {{ $item->domisili ?: '-' }}</div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $item->id_kostum }}" tabindex="-1">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title">Edit Kostum</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.kostum.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="id_kostum" value="{{ $item->id_kostum }}">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Kategori</label>
                                                    <select name="kategori" class="form-select" required>
                                                        @foreach($kategori as $kat)
                                                            <option value="{{ $kat }}" {{ strtolower($kat) === strtolower($item->kategori) ? 'selected' : '' }}>{{ $kat }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Kostum</label>
                                                    <input type="text" name="nama_kostum" class="form-control" value="{{ $item->nama_kostum }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Judul</label>
                                                    <input type="text" name="judul" class="form-control" value="{{ $item->judul ?? '' }}" placeholder="Judul tampilan" required>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Gambar</label>
                                                    <input type="file" name="gambar" class="form-control" accept="image/*,.jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.tiff,.ico">
                                                    <small class="text-muted">Kosongkan jika tidak mengganti gambar. Semua format gambar didukung (JPG, PNG, GIF, WEBP, SVG, BMP, dll)</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label d-block">Jenis Kelamin</label>
                                                    @php
                                                        $jk = strtolower($item->jenis_kelamin ?? '');
                                                    @endphp
                                                    <div class="d-flex gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkPria{{ $item->id_kostum }}" value="Pria" {{ $jk === 'pria' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="jkPria{{ $item->id_kostum }}">Pria</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkWanita{{ $item->id_kostum }}" value="Wanita" {{ $jk === 'wanita' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="jkWanita{{ $item->id_kostum }}">Wanita</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Brand</label>
                                                    <input type="text" name="brand" class="form-control" value="{{ $item->brand ?? '' }}" placeholder="Brand kostum" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga Sewa</label>
                                                    <input type="number" name="harga_sewa" class="form-control" value="{{ $item->harga_sewa }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Durasi Penyewaan</label>
                                                    <input type="text" name="durasi_penyewaan" class="form-control" value="{{ $item->durasi_penyewaan }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ukuran Kostum</label>
                                                    <input type="text" name="ukuran_kostum" class="form-control" placeholder="Contoh: XS, S, M, L atau S,M,L" value="{{ old('ukuran_kostum', $item->ukuran_kostum ?? '') }}">
                                                    <small class="text-muted">Masukkan ukuran dipisah koma (contoh: XS, S, M, L).</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Include</label>
                                                    <textarea name="include" class="form-control" rows="3" required>{{ $item->include }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Exclude (Opsional)</label>
                                                    <textarea name="exclude" class="form-control" rows="3">{{ $item->exclude }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Domisili</label>
                                                    <input type="text" name="domisili" class="form-control" value="{{ $item->domisili ?? '' }}" placeholder="Kota/Kabupaten, Provinsi" required>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                @if($search || $filter_kategori || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_asc'))
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-search"></i> Pencarian tidak ditemukan. Coba ubah kata kunci atau reset filter.
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada data kostum. Silakan tambahkan data baru.
                    </div>
                @endif
            @endif

        </div>
    </div>
</section>

<!-- Modal Preview Gambar Kostum (reusable) -->
<div class="modal fade" id="adminKostumImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminKostumImagePreviewTitle">Gambar Kostum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="adminKostumImagePreviewImg" src="" alt="Preview Gambar Kostum" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Kostum Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.kostum.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            @if($kategori && count($kategori) > 0)
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            @else
                                <option value="" disabled selected><i class="bi bi-info-circle"></i> Belum ada data katalog</option>
                            @endif
                        </select>
                        @if(!$kategori || count($kategori) == 0)
                            <small class="text-danger">Tambahkan data katalog terlebih dahulu.</small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kostum</label>
                        <input type="text" name="nama_kostum" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" class="form-control" placeholder="Judul tampilan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*,.jpg,.jpeg,.png,.gif,.webp,.svg,.bmp,.tiff,.ico" required>
                        <small class="text-muted">Pilih satu gambar untuk kostum. Semua format gambar didukung (JPG, PNG, GIF, WEBP, SVG, BMP, dll)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-block">Jenis Kelamin</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="addJkPria" value="Pria" required>
                                <label class="form-check-label" for="addJkPria">Pria</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="addJkWanita" value="Wanita" required>
                                <label class="form-check-label" for="addJkWanita">Wanita</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <input type="text" name="brand" class="form-control" placeholder="Brand kostum" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Sewa</label>
                        <input type="number" name="harga_sewa" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Durasi Penyewaan</label>
                        <input type="text" name="durasi_penyewaan" class="form-control" value="3 hari" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ukuran Kostum</label>
                        <input type="text" name="ukuran_kostum" class="form-control" placeholder="Contoh: XS, S, M, L atau S,M,L" value="{{ old('ukuran_kostum') }}" required>
                        <small class="text-muted">Masukkan ukuran dipisah koma (contoh: XS, S, M, L).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Include</label>
                        <textarea name="include" class="form-control" rows="3" placeholder="Yang termasuk dalam paket" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Exclude (Opsional)</label>
                        <textarea name="exclude" class="form-control" rows="3" placeholder="Yang tidak termasuk"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Domisili</label>
                        <input type="text" name="domisili" class="form-control" placeholder="Kota/Kabupaten, Provinsi" required>
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
    // Auto-hide alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });

        function showAdminKostumImagePreview(src, title) {
            const img = document.getElementById('adminKostumImagePreviewImg');
            const titleEl = document.getElementById('adminKostumImagePreviewTitle');
            if (!img) return;

            img.src = src || '';
            if (titleEl) titleEl.textContent = title || 'Gambar Kostum';

            const modalEl = document.getElementById('adminKostumImagePreviewModal');
            if (!modalEl || !window.bootstrap) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-kostum-image-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-image-src');
                const title = btn.getAttribute('data-image-title');
                showAdminKostumImagePreview(src, title);
            });
        });

        const modalEl = document.getElementById('adminKostumImagePreviewModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                const img = document.getElementById('adminKostumImagePreviewImg');
                if (img) img.src = '';
            });
        }
    });

    // Single-image mode: no per-image deletion logic
</script>
@endsection
