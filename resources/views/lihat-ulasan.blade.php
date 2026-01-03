@extends('layouts.main')

@section('title', 'Lihat Ulasan - ' . ($kostum->nama_kostum ?? 'Kostum'))

@section('styles')
<style>
    .ulasan-card-header,
    .ulasan-modal-header {
        background-color: var(--bs-tertiary-bg);
        color: var(--bs-body-color);
    }

    [data-bs-theme="dark"] .ulasan-card-header,
    [data-bs-theme="dark"] .ulasan-modal-header {
        background-color: #212529;
        color: #fff;
    }

    [data-bs-theme="dark"] .ulasan-modal-header .btn-close {
        filter: invert(1) grayscale(100%);
        opacity: .9;
    }

    .ulasan-detail-image { cursor: pointer; }
</style>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Lihat Ulasan</h2>
            <a href="{{ route('katalog.kostum', ['cat' => strtolower($kostum->kategori)]) }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

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

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center justify-content-between">
                    <div>
                        <div class="text-muted">Kostum</div>
                        <div class="fw-bold fs-5">{{ $kostum->nama_kostum }}</div>
                        <div class="text-muted" style="font-size:0.9rem;">Kategori: {{ $kostum->kategori }}</div>
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-chat-square-text"></i>
                        Total ulasan: <span class="fw-bold">{{ $ulasanList->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($ulasanList->isEmpty())
            <div class="alert alert-warning rounded-3">
                <i class="bi bi-exclamation-triangle"></i>
                Ulasan masih kosong untuk kostum ini.
            </div>
        @else
            <div class="row g-3">
                @foreach($ulasanList as $u)
                    @php
                        $images = [];
                        for ($i = 1; $i <= 5; $i++) {
                            $field = 'gambar_' . $i;
                            if (!empty($u->$field)) {
                                $images[] = $u->$field;
                            }
                        }
                        $createdAtLabel = '-';
                        try {
                            if (!empty($u->created_at)) {
                                $createdAtLabel = \Carbon\Carbon::parse($u->created_at)->format('d M Y');
                            }
                        } catch (\Exception $e) {
                            $createdAtLabel = '-';
                        }
                    @endphp
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header ulasan-card-header d-flex flex-column flex-md-row gap-2 justify-content-between align-items-start align-items-md-center">
                                <div>
                                    <div class="fw-bold">{{ $u->nama_user ?? 'User' }}</div>
                                    <div class="opacity-75" style="font-size:0.85rem;">{{ $createdAtLabel }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-warning" aria-label="Rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ ((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ulasanDetailModal{{ $u->id }}">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(!empty($u->review))
                                    <p class="mb-3">{{ $u->review }}</p>
                                @else
                                    <p class="text-muted mb-3">(Tidak ada teks ulasan)</p>
                                @endif

                                @if(!empty($images))
                                    <div class="row g-2 mb-3">
                                        @foreach($images as $img)
                                            <div class="col-6 col-md-3 col-lg-2">
                                                <img src="{{ asset('storage/' . $img) }}" alt="Gambar ulasan" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(!empty($u->balasan))
                                    <div class="alert alert-success mb-0">
                                        <div class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan Admin</div>
                                        <div>{{ $u->balasan }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ulasanDetailModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header ulasan-modal-header">
                                    <h5 class="modal-title">Detail Ulasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <div class="fw-bold">{{ $u->nama_user ?? 'User' }}</div>
                                        <div class="text-body-secondary" style="font-size:0.9rem;">{{ $createdAtLabel }}</div>
                                    </div>

                                    <div class="mb-3 text-warning" aria-label="Rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ ((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                        @endfor
                                        <span class="text-body ms-2">({{ (int)$u->rating }}/5)</span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="fw-bold mb-1">Ulasan</div>
                                        @if(!empty($u->review))
                                            <div>{{ $u->review }}</div>
                                        @else
                                            <div class="text-muted">(Tidak ada teks ulasan)</div>
                                        @endif
                                    </div>

                                    @if(!empty($images))
                                        <div class="mb-3">
                                            <div class="fw-bold mb-2">Foto</div>
                                            <div class="row g-2">
                                                @foreach($images as $img)
                                                    <div class="col-6 col-md-4">
                                                        <img
                                                            src="{{ asset('storage/' . $img) }}"
                                                            alt="Gambar ulasan"
                                                            class="img-fluid rounded ulasan-detail-image"
                                                            style="aspect-ratio:1/1;object-fit:cover;"
                                                            onclick="showUlasanImage('{{ asset('storage/' . $img) }}')"
                                                        >
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-body-secondary mt-2" style="font-size:0.85rem;">Klik gambar untuk melihat lebih besar.</div>
                                        </div>
                                    @endif

                                    @if(!empty($u->balasan))
                                        <div class="alert alert-success mb-0">
                                            <div class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan Admin</div>
                                            <div>{{ $u->balasan }}</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

<div class="modal fade" id="ulasanImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="ulasanImagePreview" src="" alt="Preview" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let lastDetailModalId = null;

    function showUlasanImage(src) {
        const img = document.getElementById('ulasanImagePreview');
        img.src = src;

        // Hide currently open detail modal so the image preview truly "overlays" it.
        const openModalEl = document.querySelector('.modal.show');
        if (openModalEl && openModalEl.id && openModalEl.id !== 'ulasanImagePreviewModal') {
            lastDetailModalId = openModalEl.id;
            const openModal = bootstrap.Modal.getInstance(openModalEl);
            if (openModal) {
                openModal.hide();
            }
        }

        const modalEl = document.getElementById('ulasanImagePreviewModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    // Clear preview on close
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('ulasanImagePreviewModal');
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('ulasanImagePreview');
            img.src = '';

            // Restore the last detail modal after closing image preview.
            if (lastDetailModalId) {
                const detailEl = document.getElementById(lastDetailModalId);
                if (detailEl) {
                    const detailModal = bootstrap.Modal.getOrCreateInstance(detailEl);
                    detailModal.show();
                }
                lastDetailModalId = null;
            }
        });
    });
</script>
@endsection
