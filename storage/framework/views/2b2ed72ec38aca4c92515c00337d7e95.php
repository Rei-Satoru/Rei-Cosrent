

<?php $__env->startSection('title', 'Lihat Ulasan - ' . ($kostum->nama_kostum ?? 'Kostum')); ?>

<?php $__env->startSection('styles'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Lihat Ulasan</h2>
            <a href="<?php echo e(route('katalog.kostum', ['cat' => strtolower($kostum->kategori)])); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

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

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row gap-3 align-items-start align-items-md-center justify-content-between">
                    <div>
                        <div class="text-muted">Kostum</div>
                        <div class="fw-bold fs-5"><?php echo e($kostum->nama_kostum); ?></div>
                        <div class="text-muted" style="font-size:0.9rem;">Kategori: <?php echo e($kostum->kategori); ?></div>
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-chat-square-text"></i>
                        Total ulasan: <span class="fw-bold"><?php echo e($ulasanList->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php if($ulasanList->isEmpty()): ?>
            <div class="alert alert-warning rounded-3">
                <i class="bi bi-exclamation-triangle"></i>
                Ulasan masih kosong untuk kostum ini.
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php $__currentLoopData = $ulasanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
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
                    ?>
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-header ulasan-card-header d-flex flex-column flex-md-row gap-2 justify-content-between align-items-start align-items-md-center">
                                <div>
                                    <div class="fw-bold"><?php echo e($u->nama_user ?? 'User'); ?></div>
                                    <div class="opacity-75" style="font-size:0.85rem;"><?php echo e($createdAtLabel); ?></div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-warning" aria-label="Rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?php echo e(((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#ulasanDetailModal<?php echo e($u->id); ?>">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if(!empty($u->review)): ?>
                                    <p class="mb-3"><?php echo e($u->review); ?></p>
                                <?php else: ?>
                                    <p class="text-muted mb-3">(Tidak ada teks ulasan)</p>
                                <?php endif; ?>

                                <?php if(!empty($images)): ?>
                                    <div class="row g-2 mb-3">
                                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-6 col-md-3 col-lg-2">
                                                <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Gambar ulasan" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($u->balasan)): ?>
                                    <div class="alert alert-success mb-0">
                                        <div class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan Admin</div>
                                        <div><?php echo e($u->balasan); ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="ulasanDetailModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header ulasan-modal-header">
                                    <h5 class="modal-title">Detail Ulasan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <div class="fw-bold"><?php echo e($u->nama_user ?? 'User'); ?></div>
                                        <div class="text-body-secondary" style="font-size:0.9rem;"><?php echo e($createdAtLabel); ?></div>
                                    </div>

                                    <div class="mb-3 text-warning" aria-label="Rating">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <i class="bi <?php echo e(((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star'); ?>"></i>
                                        <?php endfor; ?>
                                        <span class="text-body ms-2">(<?php echo e((int)$u->rating); ?>/5)</span>
                                    </div>

                                    <div class="mb-3">
                                        <div class="fw-bold mb-1">Ulasan</div>
                                        <?php if(!empty($u->review)): ?>
                                            <div><?php echo e($u->review); ?></div>
                                        <?php else: ?>
                                            <div class="text-muted">(Tidak ada teks ulasan)</div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(!empty($images)): ?>
                                        <div class="mb-3">
                                            <div class="fw-bold mb-2">Foto</div>
                                            <div class="row g-2">
                                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-6 col-md-4">
                                                        <img
                                                            src="<?php echo e(asset('storage/' . $img)); ?>"
                                                            alt="Gambar ulasan"
                                                            class="img-fluid rounded ulasan-detail-image"
                                                            style="aspect-ratio:1/1;object-fit:cover;"
                                                            onclick="showUlasanImage('<?php echo e(asset('storage/' . $img)); ?>')"
                                                        >
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <div class="text-body-secondary mt-2" style="font-size:0.85rem;">Klik gambar untuk melihat lebih besar.</div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if(!empty($u->balasan)): ?>
                                        <div class="alert alert-success mb-0">
                                            <div class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan Admin</div>
                                            <div><?php echo e($u->balasan); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

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

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/lihat-ulasan.blade.php ENDPATH**/ ?>