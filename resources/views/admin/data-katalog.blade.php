@extends('layouts.main')

@section('title', 'Data Katalog - Rei Cosrent')

@section('styles')
    <style>

        .action-buttons {
            display: flex;
            justify-content: center;
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

        .katalog-thumb {
            cursor: zoom-in;
            transition: transform .12s ease;
        }

        .katalog-thumb:hover {
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
    </style>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Katalog</h2>
                <p class="text-muted mb-0 small">Kelola daftar katalog kostum yang tampil di halaman utama.</p>
            </div>
            <div class="d-grid d-sm-block">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Tambah Katalog
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <!-- Pencarian dan Sortir -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.data-katalog') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau deskripsi..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach(($kategori_options ?? []) as $kat)
                                <option value="{{ $kat }}" {{ ($filter_kategori ?? '') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="id_desc" {{ ($sort ?? '') === 'id_desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="name_asc" {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                            <option value="name_desc" {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>Nama Z - A</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </form>
                @if($search || $filter_kategori || ($sort && $sort !== 'id_desc'))
                    <div class="mt-2">
                        <a href="{{ route('admin.data-katalog') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset Pencarian
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @if($katalog->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Katalog</th>
                            <th>Kategori</th>
                            <th class="d-none d-md-table-cell">Deskripsi</th>
                                <th class="d-none d-md-table-cell">Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($katalog as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td class="d-none d-md-table-cell">{{ Str::limit($item->description, 50) }}</td>
                                <td class="d-none d-md-table-cell">
                                    @if(!empty($item->image))
                                        @php
                                            $imgRaw = $item->image ?? '';
                                            if (str_starts_with($imgRaw, 'http')) {
                                                $katalogImageSrc = $imgRaw;
                                            } elseif (str_starts_with($imgRaw, '/storage/')) {
                                                $katalogImageSrc = asset(ltrim($imgRaw, '/'));
                                            } elseif (str_starts_with($imgRaw, 'storage/')) {
                                                $katalogImageSrc = asset($imgRaw);
                                            } elseif ($imgRaw) {
                                                $katalogImageSrc = asset('storage/' . $imgRaw);
                                            } else {
                                                $katalogImageSrc = null;
                                            }
                                        @endphp
                                        <button type="button" class="btn p-0 border-0 bg-transparent js-katalog-image-preview" data-image-src="{{ $katalogImageSrc }}" data-image-title="Gambar Katalog: {{ $item->name }}" aria-label="Lihat gambar katalog {{ $item->name }}">
                                            <img src="{{ $katalogImageSrc }}" alt="{{ $item->name }}" class="katalog-thumb" style="max-width:80px;">
                                        </button>
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.katalog.delete', $item->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus katalog ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title">Edit Katalog</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.katalog.update') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Katalog</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kategori</label>
                                                    <input type="text" name="kategori" class="form-control" value="{{ $item->kategori }}" placeholder="Contoh: Anime, Game, Movie" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="description" class="form-control" rows="3" required>{{ $item->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar (opsional)</label>
                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                    @if(!empty($item->image))
                                                        <small class="text-muted">Gambar saat ini: {{ basename($item->image) }}</small>
                                                    @endif
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
                @if($search || $filter_kategori || ($sort && $sort !== 'id_desc'))
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-search"></i> Pencarian tidak ditemukan. Coba ubah kata kunci atau reset.
                        <div class="mt-2">
                            <a href="{{ route('admin.data-katalog') }}" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Reset Pencarian
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada data katalog. Silakan tambahkan data baru.
                    </div>
                @endif
            @endif

        </div>
    </div>
</section>

<!-- Modal Preview Gambar Katalog (reusable) -->
<div class="modal fade" id="adminKatalogImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminKatalogImagePreviewTitle">Gambar Katalog</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="adminKatalogImagePreviewImg" src="" alt="Preview Gambar Katalog" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Katalog Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.katalog.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Katalog</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Anime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Anime, Game, Movie" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat katalog" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
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

        function showAdminKatalogImagePreview(src, title) {
            const img = document.getElementById('adminKatalogImagePreviewImg');
            const titleEl = document.getElementById('adminKatalogImagePreviewTitle');
            if (!img) return;

            img.src = src || '';
            if (titleEl) titleEl.textContent = title || 'Gambar Katalog';

            const modalEl = document.getElementById('adminKatalogImagePreviewModal');
            if (!modalEl || !window.bootstrap) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-katalog-image-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-image-src');
                const title = btn.getAttribute('data-image-title');
                showAdminKatalogImagePreview(src, title);
            });
        });

        const modalEl = document.getElementById('adminKatalogImagePreviewModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                const img = document.getElementById('adminKatalogImagePreviewImg');
                if (img) img.src = '';
            });
        }
    });
</script>
@endsection
