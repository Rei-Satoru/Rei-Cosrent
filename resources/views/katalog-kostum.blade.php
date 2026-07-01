@extends('layouts.main')

@section('title', ($catalog ? ($catalog->name . ' - Katalog Kostum') : 'Katalog Tidak Ditemukan'))

@section('styles')

    :root {
        --catalog-title-color: #0f172a;

        --catalog-card-bg: #f8fbff;
        --catalog-card-border: rgba(37, 99, 235, 0.12);
        --catalog-text: #0b0b0b;
        --catalog-muted: rgba(11, 11, 11, 0.6);
        --catalog-modal-bg: #f8fbff;
        --catalog-modal-text: #0b0b0b;
        --catalog-modal-muted: rgba(11, 11, 11, 0.7);
        --catalog-modal-border: rgba(37, 99, 235, 0.12);
        --catalog-modal-header-bg: #f8fbff;
    }

    [data-bs-theme="dark"] {
        --catalog-title-color: #ffffff;
        --catalog-card-bg: #0f172a;
        --catalog-card-border: rgba(96, 165, 250, 0.16);
        --catalog-text: #ffffff;
        --catalog-muted: rgba(255, 255, 255, 0.7);
        --catalog-modal-bg: #0f172a;
        --catalog-modal-text: #ffffff;
        --catalog-modal-muted: rgba(255, 255, 255, 0.7);
        --catalog-modal-border: rgba(96, 165, 250, 0.16);
        --catalog-modal-header-bg: #0b1220;
    }

    /* Light theme: force neutral modal surface + readable text inside the costume detail modal */
    [data-bs-theme="light"] .costume-modal .modal-content {
        background: var(--catalog-modal-bg) !important;
        color: var(--catalog-modal-text) !important;
    }

    [data-bs-theme="light"] .costume-modal .modal-header {
        background: var(--catalog-modal-header-bg) !important;
        color: var(--catalog-modal-text) !important;
        border-bottom: 1px solid var(--catalog-modal-border) !important;
    }

    [data-bs-theme="light"] .costume-modal .modal-title {
        color: var(--brand-blue) !important;
        font-weight: 700;
    }

    [data-bs-theme="light"] .costume-modal .modal-body * {
        color: var(--catalog-modal-text) !important;
    }

    [data-bs-theme="light"] .costume-modal .modal-body .text-muted,
    [data-bs-theme="light"] .costume-modal .modal-body .text-secondary,
    [data-bs-theme="light"] .costume-modal .modal-body .text-body-secondary {
        color: var(--catalog-modal-muted) !important;
    }

    /* Custom colors requested by admin */
    .jk-pria { color: #2563eb !important; }
    .jk-wanita { color: #ec4899 !important; }
    .kostum-price { color: #16a34a !important; font-weight: 700; }
    .kostum-brand { color: inherit !important; }
    .costume-modal .modal-body .label-col { color: var(--catalog-modal-muted) !important; }

    [data-bs-theme="light"] .costume-modal .modal-footer {
        background: var(--catalog-modal-bg) !important;
        color: var(--catalog-modal-text) !important;
        border-top: 1px solid var(--catalog-modal-border) !important;
    }

    .costume-modal .modal-title {
        color: var(--brand-blue) !important;
        -webkit-text-fill-color: var(--brand-blue) !important;
    }

    .costume-modal .modal-footer .btn {
        background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%) !important;
        background-color: transparent !important;
        color: #ffffff !important;
        -webkit-text-fill-color: #ffffff !important;
        border: none !important;
        background-size: 200% auto;
        background-position: 0% center;
        transition: background-position 0.6s ease-in-out, transform 0.3s ease !important;
        will-change: background-position, transform;
    }

    .costume-modal .modal-footer .btn:hover {
        background-image: linear-gradient(97deg, #93c5fd 0%, #2563eb 140.21%) !important;
        background-position: 100% center !important;
        background-color: transparent !important;
        color: #ffffff !important;
    }

    .costume-modal .modal-footer .btn:focus {
        background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%) !important;
        background-color: transparent !important;
        color: #ffffff !important;
        box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25) !important;
    }

    .catalog-title-main {
        color: var(--brand-blue) !important;
    }


    .search-card,
    .search-card .card-body {
        background: var(--catalog-card-bg);
        color: var(--catalog-text) !important;
        border-color: var(--catalog-card-border);
    }

    .search-card .form-label,
    .search-card .form-control,
    .search-card .form-select {
        color: var(--catalog-text) !important;
    }

    .search-card p,
    .search-card small,
    .search-card label,
    .search-card .text-muted,
    .search-card .form-text {
        color: var(--catalog-text) !important;
    }

    .search-card .form-control,
    .search-card .form-select {
        background: var(--catalog-card-bg) !important;
        border-color: var(--catalog-card-border) !important;
    }

    .search-card .form-control::placeholder {
        color: var(--catalog-muted) !important;
    }

    [data-bs-theme="light"] .search-card,
    [data-bs-theme="light"] .search-card .card-body,
    [data-bs-theme="light"] .search-card .form-label,
    [data-bs-theme="light"] .search-card .form-control,
    [data-bs-theme="light"] .search-card .form-select,
    [data-bs-theme="light"] .search-card p,
    [data-bs-theme="light"] .search-card small,
    [data-bs-theme="light"] .search-card label,
    [data-bs-theme="light"] .search-card .text-muted,
    [data-bs-theme="light"] .search-card .form-text {
        color: #0b0b0b !important;
    }

    .costume-card {
        overflow: hidden;
        background-color: var(--catalog-card-bg);
        color: var(--brand-blue) !important;
        transition: all 0s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        position: relative;
        border: 1px solid var(--catalog-card-border);
    }

    .costume-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .costume-thumb {
        aspect-ratio: 1 / 1;
        background: var(--bs-secondary-bg, #f8f9fa);
        overflow: hidden;
        transition: background-color 0s ease;
        border-radius: 1.5rem 1.5rem 0 0;
    }

    .costume-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .costume-card-body {
        background-color: transparent;
        color: var(--brand-blue) !important;
        transition: background-color 0s ease, color 0s ease;
    }

    .costume-card-body .fw-bold,
    .costume-card-body .text-secondary,
    .costume-card-body .kostum-brand {
        color: #ffffff !important;
    }

    .costume-card-body .text-secondary {
        color: #ffffff !important;
        transition: color 0s ease;
    }

    /* Dark-theme costume modal rules (avoid overriding light mode) */
    [data-bs-theme="dark"] .costume-modal .modal-content {
        --bs-body-color: var(--catalog-modal-text);
        --bs-emphasis-color: var(--catalog-modal-text);
        --bs-secondary-color: var(--catalog-modal-muted);
        --bs-body-bg: var(--catalog-modal-bg);
        --bs-border-color: var(--catalog-modal-border);
        background: var(--catalog-modal-bg) !important;
        color: var(--catalog-modal-text) !important;
        border: 1px solid var(--catalog-modal-border);
    }

    [data-bs-theme="dark"] .costume-modal .modal-header {
        background: var(--catalog-modal-header-bg) !important;
        color: var(--catalog-modal-text) !important;
        border-bottom: 1px solid var(--catalog-modal-border);
    }

    [data-bs-theme="dark"] .costume-modal .modal-title {
        color: var(--brand-blue) !important;
    }

    [data-bs-theme="dark"] .costume-modal .modal-body {
        color: var(--catalog-modal-text) !important;
    }

    [data-bs-theme="dark"] .costume-modal .modal-body * {
        color: var(--catalog-modal-text) !important;
    }

    [data-bs-theme="dark"] .costume-modal .modal-body .text-muted,
    [data-bs-theme="dark"] .costume-modal .modal-body .text-secondary,
    [data-bs-theme="dark"] .costume-modal .modal-body .text-body-secondary {
        color: var(--catalog-modal-muted) !important;
    }

    [data-bs-theme="dark"] .costume-modal .modal-footer {
        background: var(--catalog-modal-bg) !important;
        color: var(--catalog-modal-text) !important;
        border-top: 1px solid var(--catalog-modal-border);
    }
    
    /* Extra overrides: force opaque modal surface & correct text in light mode */
    [data-bs-theme="light"] .costume-modal.show .modal-content,
    [data-bs-theme="light"] .costume-modal .modal-content {
        background-color: var(--catalog-modal-bg) !important;
        background-image: none !important;
        background: var(--catalog-modal-bg) !important;
        color: var(--catalog-modal-text) !important;
        opacity: 1 !important;
        background-clip: padding-box !important;
    }

    [data-bs-theme="light"] .costume-modal .modal-content::before,
    [data-bs-theme="light"] .costume-modal .modal-content::after {
        background: none !important;
    }

    /* Force detail modal rows text to white for better contrast in dark-only site */
    .costume-modal .modal-body .row.g-3,
    .costume-modal .modal-body .row.g-3 * {
        color: #ffffff !important;
    }

    [data-bs-theme="light"] .costume-modal .text-secondary,
    [data-bs-theme="light"] .costume-modal .text-muted,
    [data-bs-theme="light"] .costume-modal .text-body-secondary {
        color: var(--catalog-modal-muted) !important;
    }
@endsection

@section('content')
    <section class="py-4">
        <div class="container">
            @php
                $catalogTitle = $catalog ? 'Katalog Kostum ' . $catalog->name : 'Katalog tidak ditemukan';
                $catalogDescription = $catalog && $catalog->description ? trim($catalog->description) : '';
                $showDescription = $catalog && $catalogDescription !== '' && strcasecmp($catalogDescription, $catalogTitle) !== 0;
            @endphp
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
                <div>
                    <h2 class="fw-bold mb-0 catalog-title-main">{{ $catalogTitle }}</h2>
                </div>
                <div class="d-grid d-sm-block w-100" style="max-width: 220px;">
                    <a href="{{ route('home') }}#kategori" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-left"></i> Kembali</a>
                <!-- Modal: Admin blocked from filling form -->
                <div class="modal fade" id="adminBlockModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="background-color: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; border: 1px solid var(--catalog-modal-border) !important;">
                            <div class="modal-header" style="background: var(--catalog-modal-header-bg) !important; color: var(--catalog-modal-text) !important; border-bottom: 1px solid var(--catalog-modal-border) !important;">
                                <h5 class="modal-title" style="color: var(--catalog-modal-text) !important;">Aksi Terbatas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="color: var(--catalog-modal-text) !important;">
                                            <div class="mb-3">Anda masuk sebagai <strong>admin</strong>. Untuk mencegah konflik data, <strong>hanya akun pelanggan</strong> yang dapat mengisi formulir penyewaan. Jika ingin memesan, silakan gunakan akun pelanggan.</div>
                                            <div class="table-responsive">
                                                <table class="table table-borderless table-sm mb-0">
                                                    <tbody>
                                                        <tr><th scope="row" style="width:140px;">Nama Kostum</th><td id="adminBlockName">-</td></tr>
                                                        <tr><th scope="row">Brand</th><td id="adminBlockBrand">-</td></tr>
                                                        <tr><th scope="row">Harga</th><td id="adminBlockPrice">-</td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-3"><small class="text-muted">Jika ini adalah percobaan admin untuk mengisi formulir, pertimbangkan membuat akun pelanggan atau menggunakan akun pelanggan untuk memverifikasi alur pemesanan.</small></div>
                                        </div>
                            <div class="modal-footer" style="background: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; border-top: 1px solid var(--catalog-modal-border) !important;">
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            @if(!$catalog)
                <div class="alert alert-warning rounded-3">Katalog tidak ditemukan. <a href="{{ route('home') }}#kategori" class="alert-link">Kembali ke beranda</a>.</div>
            @else
                @if($showDescription)
                    <p class="text-muted mb-4">{{ $catalog->description }}</p>
                @endif

                <!-- Pencarian & Filter (tanpa pencarian kategori) -->
                <div class="card shadow-sm mb-4 search-card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('katalog.kostum') }}" class="row g-3 align-items-end">
                            <input type="hidden" name="cat" value="{{ request('cat') }}">
                            <div class="col-md-5">
                                <label class="form-label">Pencarian</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari nama atau brand..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="Pria" {{ ($filter_jenis_kelamin ?? '') === 'Pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="Wanita" {{ ($filter_jenis_kelamin ?? '') === 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Ukuran</label>
                                <select name="ukuran" class="form-select">
                                    <option value="">Semua</option>
                                    @foreach($ukuran as $uk)
                                        <option value="{{ $uk }}" {{ ($filter_ukuran ?? '') === $uk ? 'selected' : '' }}>{{ $uk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutkan</label>
                                <select name="sort" class="form-select">
                                    <option value="id_desc" {{ ($sort ?? '') === 'id_desc' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="nama_asc" {{ ($sort ?? '') === 'nama_asc' ? 'selected' : '' }}>Nama A - Z</option>
                                    <option value="nama_desc" {{ ($sort ?? '') === 'nama_desc' ? 'selected' : '' }}>Nama Z - A</option>
                                    <option value="harga_asc" {{ ($sort ?? '') === 'harga_asc' ? 'selected' : '' }}>Harga Termurah</option>
                                    <option value="harga_desc" {{ ($sort ?? '') === 'harga_desc' ? 'selected' : '' }}>Harga Termahal</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-grid">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                            </div>
                        </form>
                        @if($search || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_desc'))
                            <div class="mt-2">
                                <a href="{{ route('katalog.kostum', ['cat' => request('cat')]) }}" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset Pencarian
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                @if($kostum->isEmpty())
                    @if($search || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_desc'))
                        <div class="alert alert-warning rounded-3 text-center">
                            <i class="bi bi-search"></i> Pencarian tidak ditemukan. Coba ubah kata kunci atau reset.
                        </div>
                    @else
                        <div class="alert alert-info rounded-3 text-center">
                            <i class="bi bi-info-circle"></i> Belum ada data kostum untuk katalog ini.
                        </div>
                    @endif
                @else
                    <div class="row g-3 row-cols-2 row-cols-md-4 row-cols-lg-5">
                        @php
                            $isAdmin = auth()->check() && ((isset(auth()->user()->is_admin) && auth()->user()->is_admin) || (isset(auth()->user()->role) && auth()->user()->role === 'admin'));
                        @endphp
                        @foreach($kostum as $k)
                        <div class="col">
                            <a href="#" class="card costume-card rounded-xl h-100 border-0 shadow-sm d-block text-decoration-none text-reset" data-bs-toggle="modal" data-bs-target="#detailModal{{ $k->id_kostum }}">
                                <div class="position-relative overflow-hidden costume-thumb">
                                    @php
                                        $img = $k->gambar ?? '';
                                        $src = '';
                                        if ($img) {
                                            if (str_starts_with($img, 'http')) {
                                                $src = $img; // external URL
                                            } elseif (str_starts_with($img, 'storage/')) {
                                                $src = asset($img); // already in storage path
                                            } elseif (str_starts_with($img, 'public/')) {
                                                $src = asset(str_replace('public/', 'storage/', $img)); // convert public/ to storage/
                                            } elseif ($img) {
                                                $src = asset('storage/' . ltrim($img, '/')); // stored filename without prefix
                                            }
                                        }
                                    @endphp
                                    @if($src)
                                        <img src="{{ $src }}" alt="{{ $k->nama_kostum }}">
                                    @else
                                        <img src="{{ asset('assets/img/no-image.png') }}" alt="Tidak ada gambar">
                                    @endif
                                </div>
                                <div class="card-body py-2 px-3 costume-card-body">
                                    <div class="text-center">
                                        <div class="fw-bold" style="font-size:1.0rem;">{{ $k->nama_kostum }}</div>
                                        @if(!empty($k->judul))
                                            <div class="text-secondary" style="font-size:0.75rem;">{{ $k->judul }}</div>
                                        @endif
                                    </div>
                                    @php
                                        $sizes = array_filter(array_map('trim', preg_split('/[,&]/', (string)$k->ukuran_kostum)));
                                        $order = ['XS'=>1,'S'=>2,'M'=>3,'L'=>4,'XL'=>5,'XXL'=>6,'XXXL'=>7];
                                        usort($sizes, function($a,$b) use ($order){
                                            $aKey = strtoupper($a); $bKey = strtoupper($b);
                                            $aR = $order[$aKey] ?? 999; $bR = $order[$bKey] ?? 999;
                                            return $aR === $bR ? strcasecmp($aKey,$bKey) : ($aR <=> $bR);
                                        });
                                    @endphp
                                    <div class="d-flex align-items-center mt-1 gap-2 flex-wrap" style="color: inherit;">
                                        <div class="d-flex gap-1 flex-wrap">
                                            @foreach($sizes as $size)
                                                @if($size !== '')
                                                    <span class="badge" style="background:#374151;color:#fff;font-size:0.65rem;padding:4px 8px;border-radius:6px;">{{ $size }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        @if(!empty($k->jenis_kelamin))
                                            @php($jk = strtolower($k->jenis_kelamin))
                                            @php($jkIcon = $jk === 'pria' ? 'bi-gender-male' : ($jk === 'wanita' ? 'bi-gender-female' : 'bi-gender-ambiguous'))
                                            <span class="jenis-kelamin jk-{{ $jk }}" style="font-size:0.75rem;white-space:nowrap;"><i class="bi {{ $jkIcon }}"></i> {{ $k->jenis_kelamin }}</span>
                                        @endif
                                    </div>
                                    <p class="mb-2 mt-2 kostum-price" style="font-size:0.8rem;">
                                        <strong class="kostum-price">Rp {{ number_format((float)$k->harga_sewa, 0, ',', '.') }}</strong> / <span class="kostum-price">{{ $k->durasi_penyewaan }}</span>
                                    </p>
                                    <p class="mb-1 kostum-brand" style="font-size:0.75rem; font-weight: 600;"><i class="bi bi-tag"></i> {{ $k->brand ?: '-' }}</p>
                                    
                                </div>
                            </a>
                        </div>

                        <div class="modal fade costume-modal" id="detailModal{{ $k->id_kostum }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content" style="background-color: var(--catalog-modal-bg) !important; background-image: none !important; background: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; opacity: 1 !important; border: 1px solid var(--catalog-modal-border) !important;">
                                    <div class="modal-header" style="background: var(--catalog-modal-header-bg) !important; color: var(--catalog-modal-text) !important; border-bottom: 1px solid var(--catalog-modal-border) !important;">
                                        <h5 class="modal-title" style="color: var(--brand-blue) !important;">Detail Kostum</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="color: var(--catalog-modal-text) !important;">
                                        <div class="row g-3">
                                            <div class="col-md-5 text-center">
                                                @if($src)
                                                    <img src="{{ $src }}" alt="Gambar Kostum" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                                @else
                                                    <img src="{{ asset('assets/img/no-image.png') }}" alt="Tidak ada gambar" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                                @endif
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Nama Kostum</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->nama_kostum }}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Judul</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->judul ?: '-' }}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Kategori</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->kategori }}</div></div>
                                                @if(!empty($k->jenis_kelamin))
                                                    <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Jenis Kelamin</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->jenis_kelamin }}</div></div>
                                                @endif
                                                @if(!empty($k->brand))
                                                    <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Brand</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->brand }}</div></div>
                                                @endif
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Harga Sewa</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: Rp {{ number_format((float)$k->harga_sewa, 0, ',', '.') }}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Durasi Penyewaan</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->durasi_penyewaan }}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Ukuran</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {{ $k->ukuran_kostum }}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Include</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {!! nl2br(e($k->include)) !!}</div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted" style="color:var(--catalog-modal-muted) !important;">Exclude</div><div class="col-7" style="color:var(--catalog-modal-text) !important;">: {!! nl2br(e($k->exclude)) !!}</div></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="background: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; border-top: 1px solid var(--catalog-modal-border) !important;">
                                        <a href="{{ route('lihat-ulasan', ['id_kostum' => $k->id_kostum]) }}" class="btn btn-outline-warning">
                                            <i class="bi bi-star"></i> Lihat Ulasan
                                        </a>
                                        @if(session('user_logged_in') || auth()->check())
                                            @if($isAdmin)
                                                <button type="button" class="btn btn-success btn-admin-block" data-bs-toggle="modal" data-bs-target="#adminBlockModal"
                                                    data-kostum-id="{{ $k->id_kostum }}"
                                                    data-kostum-name="{{ $k->nama_kostum }}"
                                                    data-kostum-brand="{{ $k->brand ?: '-' }}"
                                                    data-kostum-price="Rp {{ number_format((float)$k->harga_sewa, 0, ',', '.') }}"
                                                >
                                                    <i class="bi bi-clipboard-check"></i> Isi Formulir Penyewaan
                                                </button>
                                            @else
                                                <a href="{{ route('formulir.penyewaan', ['id_kostum' => $k->id_kostum]) }}" class="btn btn-success">
                                                    <i class="bi bi-clipboard-check"></i> Isi Formulir Penyewaan
                                                </a>
                                            @endif
                                        @else
                                            <button type="button" class="btn btn-success btn-guest-isi" data-login-url="{{ route('login') }}" data-bs-toggle="modal" data-bs-target="#guestLoginModal">
                                                <i class="bi bi-clipboard-check"></i> Isi Formulir Penyewaan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </section>
    <!-- Modal: Guest must login -->
    <div class="modal fade" id="guestLoginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: var(--catalog-modal-bg) !important; background-image: none !important; background: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; opacity: 1 !important; border: 1px solid var(--catalog-modal-border) !important;">
                <div class="modal-header" style="background: var(--catalog-modal-header-bg) !important; color: var(--catalog-modal-text) !important; border-bottom: 1px solid var(--catalog-modal-border) !important;">
                    <h5 class="modal-title" style="color: var(--catalog-modal-text) !important;">Perlu Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: var(--catalog-modal-text) !important;">
                    Anda harus login untuk mengisi formulir penyewaan. Masuk sekarang atau daftar jika belum punya akun.
                </div>
                <div class="modal-footer" style="background: var(--catalog-modal-bg) !important; color: var(--catalog-modal-text) !important; border-top: 1px solid var(--catalog-modal-border) !important;">
                    <a href="{{ route('login') }}" id="guestLoginModalLoginBtn" class="btn btn-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var guestModal = document.getElementById('guestLoginModal');
            if (guestModal) {
                guestModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var loginUrl = button ? button.getAttribute('data-login-url') || '{{ route('login') }}' : '{{ route('login') }}';
                    var loginBtn = document.getElementById('guestLoginModalLoginBtn');
                    if (loginBtn) loginBtn.setAttribute('href', loginUrl);
                });
            }
            
            // Populate adminBlockModal with costume details from the triggering button's data-* attributes
            var adminBlockModal = document.getElementById('adminBlockModal');
            if (adminBlockModal) {
                adminBlockModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    if (!button) return;
                    var name = button.getAttribute('data-kostum-name') || '-';
                    var brand = button.getAttribute('data-kostum-brand') || '-';
                    var price = button.getAttribute('data-kostum-price') || '-';
                    var nameEl = adminBlockModal.querySelector('#adminBlockName');
                    var brandEl = adminBlockModal.querySelector('#adminBlockBrand');
                    var priceEl = adminBlockModal.querySelector('#adminBlockPrice');
                    if (nameEl) nameEl.textContent = name;
                    if (brandEl) brandEl.textContent = brand;
                    if (priceEl) priceEl.textContent = price;
                });
            }

            // Force white text for costume detail modal rows when shown, and remove on hide
            document.querySelectorAll('.costume-modal').forEach(function(modalEl) {
                modalEl.addEventListener('show.bs.modal', function () {
                    var modal = this;
                    var elems = modal.querySelectorAll('.row.g-3, .row.g-3 * , .modal-body .col-7, .modal-body .col-5');
                    elems.forEach(function(el) {
                        try { el.style.setProperty('color', '#ffffff', 'important'); } catch(e) {}
                    });
                    // also ensure the modal title and any labels are white
                    var extras = modal.querySelectorAll('.modal-title, .label-col, .kostum-price, .kostum-brand, .text-secondary, .text-muted');
                    extras.forEach(function(el) { try { el.style.setProperty('color', '#ffffff', 'important'); } catch(e) {} });
                });

                modalEl.addEventListener('hide.bs.modal', function () {
                    var modal = this;
                    var elems = modal.querySelectorAll('.row.g-3, .row.g-3 * , .modal-body .col-7, .modal-body .col-5');
                    elems.forEach(function(el) { try { el.style.removeProperty('color'); } catch(e) {} });
                    var extras = modal.querySelectorAll('.modal-title, .label-col, .kostum-price, .kostum-brand, .text-secondary, .text-muted');
                    extras.forEach(function(el) { try { el.style.removeProperty('color'); } catch(e) {} });
                });
            });
        });
    </script>
@endsection
