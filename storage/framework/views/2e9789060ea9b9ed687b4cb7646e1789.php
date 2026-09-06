

<?php $__env->startSection('title', 'Peraturan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
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

    :root {
        --rules-card-bg: #f8fbff;
        --rules-card-border: rgba(37, 99, 235, 0.12);
        --rules-card-text: #0f172a;
    }

    [data-bs-theme="dark"] {
        --rules-card-bg: #0f172a;
        --rules-card-border: rgba(96, 165, 250, 0.16);
        --rules-card-text: #ffffff;
    }

    .page-title {
        color: var(--brand-blue) !important;
    }

    .page-subtitle {
        color: #0b0b0b !important;
    }

    [data-bs-theme="dark"] .page-subtitle {
        color: #ffffff !important;
    }

    .aturan-card {
        background: var(--rules-card-bg) !important;
        border: 1px solid var(--rules-card-border) !important;
        color: var(--rules-card-text) !important;
    }

    .aturan-card .card-body {
        background: transparent !important;
        color: var(--rules-card-text) !important;
    }

    .aturan-card .text-muted,
    .aturan-card .text-body-secondary,
    .aturan-card .text-secondary {
        color: var(--rules-card-text) !important;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Peraturan Sewa Kostum</h1>
        <p class="page-subtitle">Syarat, ketentuan, larangan, dan denda sewa kostum Rei Cosrent</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">
    <?php if($aturan->count() > 0): ?>
        <?php $__currentLoopData = $aturan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card shadow-sm mb-4 aturan-card">
            <div class="card-body p-4">
                <!-- Syarat & Ketentuan -->
                <div class="aturan-section">
                    <h2 class="aturan-title">
                        <i class="bi bi-clipboard-check"></i> Syarat & Ketentuan
                    </h2>
                    <div class="aturan-content"><?php echo e($item->syarat_ketentuan); ?></div>
                </div>

                <!-- Larangan & Denda -->
                <div class="aturan-section">
                    <h2 class="aturan-title">
                        <i class="bi bi-exclamation-triangle"></i> Larangan & Denda
                    </h2>
                    <div class="aturan-content"><?php echo e($item->larangan_dan_denda); ?></div>
                </div>

                <!-- Tanggal Update -->
                <div class="text-end mt-4">
                    <small class="text-muted">
                        <i class="bi bi-calendar-check"></i> Peraturan dibuat: <?php echo e($item->created_at->format('d F Y')); ?>

                        <i class="bi bi-calendar-check ms-4"></i> Terakhir diperbarui: <?php echo e($item->updated_at->format('d F Y')); ?>

                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <div class="card shadow-sm aturan-card">
            <div class="card-body p-5 text-center">
                <i class="bi bi-info-circle" style="font-size: 3rem; color: var(--bs-primary);"></i>
                <h3 class="mt-3"><i class="bi bi-info-circle"></i> Belum Ada Peraturan</h3>
                <p class="text-muted">Peraturan sewa kostum belum tersedia saat ini.</p>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views\peraturan.blade.php ENDPATH**/ ?>