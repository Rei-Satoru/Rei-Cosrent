

<?php $__env->startSection('title', 'Lihat Ulasan - ' . ($kostum->nama_kostum ?? 'Kostum')); ?>

<?php $__env->startSection('styles'); ?>
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
                                    <div class="text-body-secondary" style="font-size:0.85rem;">Klik gambar untuk melihat lebih besar.</div>
                                <?php endif; ?>

                                <?php if(!empty($u->balasan)): ?>
                                    <hr class="my-3">
                                    <div class="fw-bold mb-1"><i class="bi bi-chat-left-text"></i> Balasan Admin:</div>
                                    <div class="mb-0"><?php echo e($u->balasan); ?></div>
                                <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/lihat-ulasan.blade.php ENDPATH**/ ?>