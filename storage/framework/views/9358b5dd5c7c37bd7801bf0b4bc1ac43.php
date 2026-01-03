

<?php $__env->startSection('title', ($ulasan ? 'Edit' : 'Beri') . ' Ulasan - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><?php echo e($ulasan ? 'Edit' : 'Beri'); ?> Ulasan</h2>
            <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pesanan
            </a>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-star-fill"></i> Ulasan untuk Pesanan #<?php echo e($formulir->id); ?></h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Details -->
                        <div class="alert alert-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nama Kostum:</strong> <?php echo e($formulir->nama_kostum ?? '-'); ?>

                                </div>
                                <div class="col-md-6">
                                    <strong>Total Harga:</strong> Rp <?php echo e(number_format((float) $formulir->total_harga, 0, ',', '.')); ?>

                                </div>
                            </div>
                        </div>

                        <form method="POST" action="<?php echo e($ulasan ? route('user.ulasan.update', $formulir->id) : route('user.ulasan.store', $formulir->id)); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php if($ulasan): ?>
                                <?php echo method_field('PUT'); ?>
                            <?php endif; ?>

                            <!-- Rating -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                                <div class="rating-stars" id="ratingStars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?php echo e(($ulasan && $ulasan->rating >= $i) ? '-fill text-warning' : ''); ?> fs-2" data-rating="<?php echo e($i); ?>" style="cursor: pointer;"></i>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="<?php echo e($ulasan->rating ?? ''); ?>" required>
                                <?php $__errorArgs = ['rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Review Text -->
                            <div class="mb-4">
                                <label for="review" class="form-label fw-bold">Ulasan Anda</label>
                                <textarea class="form-control <?php $__errorArgs = ['review'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="review" 
                                          name="review" 
                                          rows="5" 
                                          placeholder="Ceritakan pengalaman Anda..."><?php echo e(old('review', $ulasan->review ?? '')); ?></textarea>
                                <?php $__errorArgs = ['review'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Images Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Foto (Opsional - Maksimal 5 foto)</label>
                                <div class="row g-3">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <div class="col-md-6">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    <?php if($ulasan && $ulasan->{'gambar_' . $i}): ?>
                                                        <div class="position-relative mb-2">
                                                            <img src="<?php echo e(asset('storage/' . $ulasan->{'gambar_' . $i})); ?>" 
                                                                 alt="Gambar <?php echo e($i); ?>" 
                                                                 class="img-fluid rounded mb-2" 
                                                                 style="max-height: 200px; object-fit: cover;"
                                                                 id="preview_<?php echo e($i); ?>">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                                    onclick="deleteImage(<?php echo e($formulir->id); ?>, <?php echo e($i); ?>)">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="mb-2" id="preview_container_<?php echo e($i); ?>" style="display: none;">
                                                            <img src="" 
                                                                 alt="Preview <?php echo e($i); ?>" 
                                                                 class="img-fluid rounded mb-2" 
                                                                 style="max-height: 200px; object-fit: cover;"
                                                                 id="preview_<?php echo e($i); ?>">
                                                        </div>
                                                    <?php endif; ?>
                                                    <label for="gambar_<?php echo e($i); ?>" class="btn btn-outline-secondary btn-sm w-100">
                                                        <i class="bi bi-image"></i> Pilih Foto <?php echo e($i); ?>

                                                    </label>
                                                    <input type="file" 
                                                           class="d-none" 
                                                           id="gambar_<?php echo e($i); ?>" 
                                                           name="gambar_<?php echo e($i); ?>" 
                                                           accept="image/jpeg,image/png,image/jpg"
                                                           onchange="previewImage(<?php echo e($i); ?>)">
                                                    <?php $__errorArgs = ['gambar_' . $i];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB per foto.</small>
                            </div>

                            <?php if($ulasan && $ulasan->balasan): ?>
                                <div class="alert alert-success">
                                    <h6 class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan dari Admin:</h6>
                                    <p class="mb-0"><?php echo e($ulasan->balasan); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> <?php echo e($ulasan ? 'Update Ulasan' : 'Kirim Ulasan'); ?>

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Rating Stars Functionality
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;
            
            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating <= rating) {
                    s.classList.remove('bi-star');
                    s.classList.add('bi-star-fill', 'text-warning');
                } else {
                    s.classList.remove('bi-star-fill', 'text-warning');
                    s.classList.add('bi-star');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('data-rating');
            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating <= rating) {
                    s.classList.add('text-warning');
                }
            });
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating > currentRating) {
                    s.classList.remove('text-warning');
                }
            });
        });
    });

    // Image Preview
    function previewImage(number) {
        const input = document.getElementById('gambar_' + number);
        const preview = document.getElementById('preview_' + number);
        const container = document.getElementById('preview_container_' + number);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                if (container) {
                    container.style.display = 'block';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Delete Image
    function deleteImage(formulirId, imageNumber) {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            fetch(`/ulasan/${formulirId}/delete-image/${imageNumber}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus gambar');
            });
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/ulasan-form.blade.php ENDPATH**/ ?>