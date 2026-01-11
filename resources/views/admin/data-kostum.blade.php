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

body[data-bs-theme="light"] footer {
    background-color: #0d6efd !important;
}

body[data-bs-theme="dark"] footer {
    background-color: #8a2be2 !important;
}

.rupiah-format::before {
    content: "Rp";
}

</style>
@endsection

@section('content')
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Kostum</h1>
        <p class="text-muted">Kelola daftar kostum yang tersedia untuk disewa.</p>
    </div>
</header>

<!-- Konten -->
<section class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Tombol di atas tabel -->
            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Tambah Kostum
                </button>
            </div>

            <!-- Pencarian dan Filter -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.data-kostum') }}" class="row g-3">
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
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Gambar</th>
                                <th>Jenis Kelamin</th>
                                <th>Brand</th>
                                <th>Harga</th>
                                <th>Durasi</th>
                                <th>Ukuran</th>
                                <th>Include</th>
                                <th>Exclude</th>
                                <th>Domisili</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kostum as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_kostum }}</td>
                                <td>{{ ucfirst($item->kategori) }}</td>
                                <td>
                                    @if(!empty($item->gambar))
                                        <button type="button" class="btn p-0 border-0 bg-transparent js-kostum-image-preview" data-image-src="/storage/{{ basename($item->gambar) }}" data-image-title="Gambar Kostum: {{ $item->nama_kostum }}" aria-label="Lihat gambar kostum {{ $item->nama_kostum }}">
                                            <img src="/storage/{{ basename($item->gambar) }}" alt="{{ $item->nama_kostum }}" class="kostum-thumb" style="max-width:80px;">
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($item->jenis_kelamin ?? '-') }}</td>
                                <td>{{ $item->brand ?? '-' }}</td>
                                <td>Rp{{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                                <td>{{ $item->durasi_penyewaan }}</td>
                                @php
                                    $sizes = array_filter(array_map('trim', preg_split('/[,&]/', $item->ukuran_kostum ?? '')));
                                    $order = ['XS'=>1,'S'=>2,'M'=>3,'L'=>4,'XL'=>5,'XXL'=>6,'XXXL'=>7];
                                    usort($sizes, function($a,$b) use ($order){
                                        $aKey = strtoupper($a); $bKey = strtoupper($b);
                                        $aR = $order[$aKey] ?? 999; $bR = $order[$bKey] ?? 999;
                                        return $aR === $bR ? strcasecmp($aKey,$bKey) : ($aR <=> $bR);
                                    });
                                @endphp
                                <td>{{ $sizes ? implode(' ', $sizes) : '-' }}</td>
                                <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->include }}">{{ $item->include }}</td>
                                <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $item->exclude }}">{{ $item->exclude ?? '-' }}</td>
                                <td>{{ !empty($item->domisili) ? $item->domisili : '-' }}</td>
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
                                                        <button type="button" class="btn p-0 border-0 bg-transparent js-kostum-image-preview" data-image-src="/storage/{{ basename($item->gambar) }}" data-image-title="Gambar Kostum: {{ $item->nama_kostum }}" aria-label="Lihat gambar kostum {{ $item->nama_kostum }}">
                                                            <img src="/storage/{{ basename($item->gambar) }}" alt="Gambar Kostum" class="img-fluid rounded kostum-thumb" style="aspect-ratio:1/1;object-fit:cover;">
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
                                                    <div class="row mb-2"><div class="col-5 text-muted">Ukuran</div><div class="col-7">: {{ $sizes ? implode(' ', $sizes) : '-' }}</div></div>
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
                                                    @php($jk = strtolower($item->jenis_kelamin ?? ''))
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
                                                    <select name="ukuran_kostum" class="form-select" required>
                                                        <option value="" disabled>Pilih ukuran</option>
                                                        @foreach($ukuran as $uk)
                                                            <option value="{{ $uk }}" @if(trim($uk) === trim($item->ukuran_kostum)) selected @endif>{{ $uk }}</option>
                                                        @endforeach
                                                    </select>
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
                        <select name="ukuran_kostum" class="form-select" required>
                            <option value="" disabled selected>Pilih ukuran</option>
                            @foreach($ukuran as $uk)
                                <option value="{{ $uk }}">{{ $uk }}</option>
                            @endforeach
                        </select>
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
