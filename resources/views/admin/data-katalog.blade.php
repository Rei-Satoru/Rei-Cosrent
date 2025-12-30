@extends('layouts.main')

@section('title', 'Data Katalog - Rei Cosrent')

@section('styles')
    <style>
        table th {
            background-color: var(--bs-primary);
            color: white;
            text-align: center;
            font-size: 1.0rem;
        }

        table td {
            font-size: 1.0rem;
        }

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

        .table img {
            max-width: 80px;
            height: auto;
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
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Katalog</h1>
        <p class="text-muted">Kelola daftar katalog kostum yang tampil di halaman utama.</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Tombol di atas tabel -->
            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Tambah Katalog
                </button>
            </div>

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

            @if($katalog->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Katalog</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($katalog as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ Str::limit($item->description, 50) }}</td>
                                <td>
                                    @if(!empty($item->image))
                                        <img src="/storage/{{ basename($item->image) }}" alt="{{ $item->name }}" style="max-width:80px;">
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
    });
</script>
@endsection
