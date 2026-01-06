

<?php $__env->startSection('title', 'Kelola Data Ulasan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Kelola Data Ulasan</h1>
        <p class="text-muted">Admin dapat membalas ulasan berdasarkan ID pesanan (Formulir)</p>
    </div>
</header>

<section class="container py-4">

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div></div>
            </div>

            <?php if(isset($ulasanList) && $ulasanList->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th style="width: 90px;">ID Pesanan</th>
                                <th>User</th>
                                <th>Kostum</th>
                                <th style="width: 120px;">Rating</th>
                                <th>Ulasan</th>
                                <th style="width: 160px;">Gambar</th>
                                <th style="width: 360px;">Balasan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $ulasanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <td class="fw-semibold"><?php echo e($u->id); ?></td>
                                    <td class="text-start">
                                        <div class="fw-semibold"><?php echo e($u->nama_user ?? 'User'); ?></div>
                                        <div class="text-muted" style="font-size:0.85rem;"><?php echo e($u->email_user ?? '-'); ?></div>
                                    </td>
                                    <td class="text-start"><?php echo e($u->nama_kostum ?? '-'); ?></td>
                                    <td>
                                        <div class="text-warning" aria-label="Rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="bi <?php echo e(((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        <?php if(!empty($u->review)): ?>
                                            <?php echo e($u->review); ?>

                                        <?php else: ?>
                                            <span class="text-muted">(Tidak ada teks ulasan)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if(!empty($images)): ?>
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ulasanImagesModal<?php echo e($u->id); ?>">
                                                <i class="bi bi-images"></i> Lihat Gambar
                                            </button>

                                            <div class="modal fade" id="ulasanImagesModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header modal-header-surface">
                                                            <h5 class="modal-title">Gambar Ulasan (Pesanan #<?php echo e($u->id); ?>)</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="col-6 col-md-4">
                                                                        <button
                                                                            type="button"
                                                                            class="btn p-0 border-0 bg-transparent"
                                                                            data-preview-src="<?php echo e(asset('storage/' . $img)); ?>"
                                                                            data-preview-title="Gambar <?php echo e($num); ?> (Pesanan #<?php echo e($u->id); ?>)"
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
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-start">
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
                <div class="alert alert-info text-center mb-0"><i class="bi bi-info-circle"></i> Belum ada ulasan.</div>
            <?php endif; ?>
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