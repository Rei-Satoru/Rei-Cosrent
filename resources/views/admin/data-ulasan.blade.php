@extends('layouts.main')

@section('title', 'Kelola Data Ulasan - Rei Cosrent')

@section('styles')
<style>
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

    table th {
        background-color: var(--bs-primary);
        color: white;
        text-align: center;
        font-size: 1.0rem;
    }

    table td {
        font-size: 0.95rem;
        vertical-align: top;
    }

    .table-responsive { overflow-x: auto; }

    .balasan-textarea {
        min-height: 110px;
    }

    .ulasan-thumb {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 0;
        border: 0;
    }
</style>
@endsection

@section('content')
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Kelola Data Ulasan</h1>
        <p class="text-muted">Admin dapat membalas ulasan berdasarkan ID pesanan (Formulir)</p>
    </div>
</header>

<section class="container py-4">

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

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div></div>
            </div>

            @if(isset($ulasanList) && $ulasanList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th style="width: 90px;">ID</th>
                                <th>User</th>
                                <th>Kostum</th>
                                <th style="width: 120px;">Rating</th>
                                <th>Ulasan</th>
                                <th style="width: 160px;">Gambar</th>
                                <th style="width: 360px;">Balasan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ulasanList as $u)
                                @php
                                    $images = [];
                                    for ($i = 1; $i <= 5; $i++) {
                                        $field = 'gambar_' . $i;
                                        if (!empty($u->$field)) {
                                            $images[$i] = $u->$field;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $u->nama_user ?? 'User' }}</div>
                                        <div class="text-muted" style="font-size:0.85rem;">{{ $u->email_user ?? '-' }}</div>
                                    </td>
                                    <td class="text-start">{{ $u->nama_kostum ?? '-' }}</td>
                                    <td>
                                        <div class="text-warning" aria-label="Rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ ((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        @if(!empty($u->review))
                                            {{ $u->review }}
                                        @else
                                            <span class="text-muted">(Tidak ada teks ulasan)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($images))
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ulasanImagesModal{{ $u->id }}">
                                                <i class="bi bi-images"></i> Lihat Gambar
                                            </button>

                                            <div class="modal fade" id="ulasanImagesModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header modal-header-surface">
                                                            <h5 class="modal-title">Gambar Ulasan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                @foreach($images as $num => $img)
                                                                    <div class="col-6 col-md-4">
                                                                        <button
                                                                            type="button"
                                                                            class="btn p-0 border-0 bg-transparent"
                                                                            data-preview-src="{{ asset('storage/' . $img) }}"
                                                                            data-preview-title="Gambar {{ $num }}"
                                                                            onclick="return openUlasanAdminImagePreview(this.dataset.previewSrc, this.dataset.previewTitle)"
                                                                            aria-label="Lihat Gambar {{ $num }}"
                                                                        >
                                                                            <img src="{{ asset('storage/' . $img) }}" alt="Gambar {{ $num }}" class="img-fluid ulasan-thumb">
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="text-muted mt-2" style="font-size:0.85rem;">Klik gambar untuk membuka ukuran penuh.</div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-start">
                                        <form method="POST" action="{{ route('admin.ulasan.balas') }}">
                                            @csrf
                                            <input type="hidden" name="formulir_id" value="{{ $u->id }}">
                                            <textarea name="balasan" class="form-control balasan-textarea" placeholder="Tulis balasan admin...">{{ old('balasan', $u->balasan) }}</textarea>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-send"></i> Simpan Balasan
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center mb-0"><i class="bi bi-info-circle"></i> Belum ada ulasan.</div>
            @endif
        </div>
    </div>
</section>

<!-- Preview Modal Gambar Ulasan (reusable) -->
<div class="modal fade" id="ulasanAdminImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-surface">
                <h5 class="modal-title" id="ulasanAdminImagePreviewTitle">Gambar Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="ulasanAdminImagePreviewImg" src="" alt="Preview" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openUlasanAdminImagePreview(src, title) {
        const img = document.getElementById('ulasanAdminImagePreviewImg');
        if (img) img.src = src;

        const titleEl = document.getElementById('ulasanAdminImagePreviewTitle');
        if (titleEl) titleEl.textContent = title || 'Gambar Ulasan';

        const modalEl = document.getElementById('ulasanAdminImagePreviewModal');
        if (!modalEl || !window.bootstrap) return false;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        return false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('ulasanAdminImagePreviewModal');
        if (!modalEl) return;
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('ulasanAdminImagePreviewImg');
            if (img) img.src = '';

            const titleEl = document.getElementById('ulasanAdminImagePreviewTitle');
            if (titleEl) titleEl.textContent = 'Gambar Ulasan';
        });
    });
</script>
@endsection
