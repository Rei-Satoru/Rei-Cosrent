

<?php $__env->startSection('title', 'Login - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    body, section, .container, .row, .col-lg-5, .col-md-7,
    .card, .card-header, .card-body, 
    form, .form-control, .form-select, .form-label, 
    .btn, .btn-primary, .btn-lg, .d-grid,
    .alert, .alert-success, .alert-danger,
    .mb-3, hr, p, a, small, h3, i,
    select option, input, button {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease, transform 0s ease;
    }
    
    .form-control, .form-select {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }
    
    .form-control:focus, .form-select:focus {
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    
    .btn:hover {
        transition: all 0.3s ease;
    }
    
    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--bs-secondary);
        padding: 0;
        font-size: 1.2rem;
        line-height: 1;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: var(--bs-primary);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-xl">
                    <div class="card-header bg-primary text-white text-center py-4 rounded-top">
                        <h3 class="mb-0 fw-bold">Login</h3>
                        <p class="mb-0 small">Masuk sebagai Admin atau User</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Alert Messages -->
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

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php
                            $selectedLoginType = $defaultLoginType ?? request()->query('login_type', 'user');
                            if (!in_array($selectedLoginType, ['admin', 'user'], true)) {
                                $selectedLoginType = 'user';
                            }
                            $isAdminEntry = $selectedLoginType === 'admin';
                        ?>

                        <form method="POST" action="<?php echo e(route('login.post')); ?>">
                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="context" value="<?php echo e($isAdminEntry ? 'admin' : 'user'); ?>">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold"><?php echo e($isAdminEntry ? 'Username' : 'Email / Username / Nama'); ?></label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="<?php echo e($isAdminEntry ? 'Masukkan username admin' : 'Masukkan email, username, atau nama'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required style="padding-right: 40px;">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mb-3">
                                <a href="<?php echo e(route('password.request')); ?>" class="text-decoration-none small fw-semibold">Lupa password?</a>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </div>

                            <div class="text-center mb-3">
                                <p class="text-muted mb-2">atau</p>
                                <a href="<?php echo e(route('auth.google')); ?>" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-google"></i> Login dengan Google
                                </a>
                            </div>

                            <hr>

                            <p class="text-center mb-0">
                                Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-decoration-none fw-semibold">Daftar Sekarang</a>
                            </p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 3 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });
    });
    
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>