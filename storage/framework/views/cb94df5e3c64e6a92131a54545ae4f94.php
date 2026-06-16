

<?php $__env->startSection('title', 'Kelola Data Ulasan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
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

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
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

    .modal-content .modal-header {
        background-color: #0d6efd !important;
        color: #ffffff !important;
    }

    .modal-content .modal-body,
    .modal-content .modal-footer,
    .modal-content form {
        background-color: #0f172af5 !important;
    }

    .modal-content .modal-body .form-control,
    .modal-content .modal-body .form-select,
    .modal-content .modal-body textarea,
    .modal-content .modal-body input,
    .modal-content .modal-body select,
    .modal-content .modal-body .form-check,
    .modal-content .modal-body .form-check-input,
    .modal-content .modal-body .form-floating > .form-control {
        background-color: #0f172af5 !important;
        color: var(--bs-body-color) !important;
    }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Kelola Data Ulasan</h2>
                <p class="text-muted mb-0 small">Admin dapat membalas ulasan berdasarkan ID pesanan (Formulir)</p>
            </div>
            <div class="d-grid d-sm-block">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if(isset($ulasanList) && $ulasanList->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Pesanan</th>
                            <th style="width: 120px;">Rating</th>
                            <th>Ulasan</th>
                            <th class="d-none d-md-table-cell" style="width: 160px;">Gambar</th>
                            <th class="d-none d-md-table-cell" style="width: 360px;">Balasan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ulasanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $images = [];
                                    for ($i = 1; $i <= 5; $i++) {
                                        $field = 'gambar_' . $i;
                                        if (!empty($u->$field)) {
                                            $images[$i] = $u->$field;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="fw-semibold\"><?php echo e($index + 1); ?></td>
                                    <td class=\"text-start\">
                                        <div class=\"fw-semibold\"><?php echo e($u->nama_kostum ?? '-'); ?></div>
                                        <div class=\"text-muted\" style=\"font-size:0.85rem;\"><?php echo e($u->nama_user ?? 'User'); ?></div>
                                    </td>
                                    <td>
                                        <div class=\"text-warning\" aria-label=\"Rating\">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class=\"bi <?php echo e(((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star'); ?>\"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class=\"text-start\">
                                        <?php if(!empty($u->review)): ?>
                                            <?php echo e($u->review); ?>

                                        <?php else: ?>
                                            <span class=\"text-muted\">(Tidak ada teks ulasan)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if(!empty($images)): ?>
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ulasanImagesModal<?php echo e($u->id); ?>">
                                                <i class="bi bi-images"></i> Lihat Gambar
                                            </button>

                                            <div class="modal fade" id="ulasanImagesModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Gambar Ulasan</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-6 col-md-4">
                                                                        <button
                                                                            type="button"
                                                                            class="btn p-0 border-0 bg-transparent"
                                                                            data-preview-src="<?php echo e(asset('storage/' . $img)); ?>"
                                                                            data-preview-title="Gambar <?php echo e($num); ?>"
                                                                            onclick="return openUlasanAdminImagePreview(this.dataset.previewSrc, this.dataset.previewTitle)"
                                                                            aria-label="Lihat Gambar <?php echo e($num); ?>"
                                                                        >
                                                                            <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Gambar <?php echo e($num); ?>" class="img-fluid ulasan-thumb">
                                                                        </button>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                            <div class="text-muted mt-2" style="font-size:0.85rem;">Klik gambar untuk membuka ukuran penuh.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell text-start">
                                        <form method="POST" action="<?php echo e(route('admin.ulasan.balas')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="formulir_id" value="<?php echo e($u->id); ?>">
                                            <textarea name="balasan" class="form-control balasan-textarea" placeholder="Tulis balasan admin..."><?php echo e(old('balasan', $u->balasan)); ?></textarea>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-send"></i> Simpan Balasan
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mb-0">Belum ada ulasan.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Preview Modal Gambar Ulasan (reusable) -->
<div class="modal fade" id="ulasanAdminImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ulasanAdminImagePreviewTitle">Gambar Ulasan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="ulasanAdminImagePreviewImg" src="" alt="Preview" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-ulasan.blade.php ENDPATH**/ ?>