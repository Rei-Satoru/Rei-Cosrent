@extends('layouts.main')

@section('title', 'Peraturan - Rei Cosrent')

@section('styles')
<style>
    /* Nonaktifkan SEMUA transisi saat halaman loading */
    html.no-transition *,
    html.no-transition *::before,
    html.no-transition *::after,
    body.no-transition *,
    body.no-transition *::before,
    body.no-transition *::after,
    .no-transition *,
    .no-transition *::before,
    .no-transition *::after {
        transition: none !important;
        animation: none !important;
    }

    /* Transisi halus 1 detik hanya setelah class no-transition dihapus */
    html:not(.no-transition) *,
    html:not(.no-transition) *::before,
    html:not(.no-transition) *::after,
    body:not(.no-transition) *,
    body:not(.no-transition) *::before,
    body:not(.no-transition) *::after {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease !important;
    }

    /* Transisi khusus untuk elemen interaktif */
    html:not(.no-transition) .btn:hover,
    html:not(.no-transition) .btn:focus,
    html:not(.no-transition) .btn:active,
    body:not(.no-transition) .btn:hover,
    body:not(.no-transition) .btn:focus,
    body:not(.no-transition) .btn:active {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease !important;
    }

    .page-title {
        color: #0056b3;
    }

    [data-bs-theme="dark"] .page-title {
        color: #a855f7;
    }

    [data-bs-theme="light"] .page-title {
        color: #0056b3;
    }

    .aturan-section {
        margin-bottom: 2rem;
    }

    .aturan-title {
        color: var(--bs-primary);
        font-size: 1.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bs-primary);
    }

    .aturan-content {
        line-height: 1.8;
        white-space: pre-line;
    }
</style>
@endsection

@section('content')
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Peraturan Sewa Kostum</h1>
        <p class="text-muted">Syarat, ketentuan, larangan, dan denda sewa kostum Rei Cosrent</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">
    @if($aturan->count() > 0)
        @foreach($aturan as $item)
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <!-- Syarat & Ketentuan -->
                <div class="aturan-section">
                    <h2 class="aturan-title">
                        <i class="bi bi-clipboard-check"></i> Syarat & Ketentuan
                    </h2>
                    <div class="aturan-content">{{ $item->syarat_ketentuan }}</div>
                </div>

                <!-- Larangan & Denda -->
                <div class="aturan-section">
                    <h2 class="aturan-title">
                        <i class="bi bi-exclamation-triangle"></i> Larangan & Denda
                    </h2>
                    <div class="aturan-content">{{ $item->larangan_dan_denda }}</div>
                </div>

                <!-- Tanggal Update -->
                <div class="text-end mt-4">
                    <small class="text-muted">
                        <i class="bi bi-calendar-check"></i> Peraturan dibuat: {{ $item->created_at->format('d F Y') }}
                        <i class="bi bi-calendar-check ms-4"></i> Terakhir diperbarui: {{ $item->updated_at->format('d F Y') }}
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="card shadow-sm">
            <div class="card-body p-5 text-center">
                <i class="bi bi-info-circle" style="font-size: 3rem; color: var(--bs-primary);"></i>
                <h3 class="mt-3">Belum Ada Peraturan</h3>
                <p class="text-muted">Peraturan sewa kostum belum tersedia saat ini.</p>
            </div>
        </div>
    @endif
</section>
@endsection
