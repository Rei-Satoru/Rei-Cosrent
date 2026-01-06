@extends('layouts.main')

@section('title', 'Lihat Ulasan - ' . ($kostum->nama_kostum ?? 'Kostum'))

@section('styles')
<style>
    .ulasan-card-header {
        background-color: var(--bs-tertiary-bg);
        color: var(--bs-body-color);
    }

    [data-bs-theme="dark"] .ulasan-card-header {
        background-color: #212529;
        color: #fff;
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
                                    <div class="text-body-secondary" style="font-size:0.85rem;">Klik gambar untuk melihat lebih besar.</div>
                                @endif

                                @if(!empty($u->balasan))
                                    <hr class="my-3">
                                    <div class="fw-bold mb-1"><i class="bi bi-chat-left-text"></i> Balasan Admin:</div>
                                    <div class="mb-0">{{ $u->balasan }}</div>
                                @endif
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
    function showUlasanImage(src) {
        const img = document.getElementById('ulasanImagePreview');
        img.src = src;

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
        });
    });
</script>
@endsection
