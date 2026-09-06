

<?php $__env->startSection('title', 'Formulir Terkirim - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Terima Kasih!</h2>
            <p class="text-white">Formulir penyewaan Anda telah berhasil dikirim.</p>
        </div>

        <!-- Modal Triggered on Load -->
        <div class="modal fade" id="formSuccessModal" tabindex="-1" aria-labelledby="formSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #0d6efd; color: #fff;">
                        <h5 class="modal-title" id="formSuccessModalLabel"><i class="bi bi-check-circle"></i> Berhasil Dikirim</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-white" style="background-color: rgba(15, 23, 42, 0.96); color: #fff !important;">
                        <p class="mb-2 text-white" style="color: #fff !important;"><?php echo e($message); ?></p>
                        <ul class="mb-0 small text-white" style="color: #fff !important;">
                            <li>Tim kami akan memproses pesanan Anda.</li>
                            <li>Silakan pantau status pesanan di halaman Pesanan Saya.</li>
                        </ul>
                    </div>
                    <div class="modal-footer" style="background-color: rgba(15, 23, 42, 0.96);">
                        <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-primary">
                            <i class="bi bi-receipt"></i> Pesanan Saya
                        </a>
                        <a href="<?php echo e(route('katalog.kostum')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-shop"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="d-grid d-sm-flex justify-content-center gap-2">
                <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-success">
                    <i class="bi bi-receipt"></i> Lihat Pesanan Saya
                </a>
                <a href="<?php echo e(route('katalog.kostum')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-shop"></i> Kembali ke Katalog Kostum
                </a>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('formSuccessModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views\formulir-berhasil.blade.php ENDPATH**/ ?>