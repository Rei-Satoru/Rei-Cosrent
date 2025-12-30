<div class="card shadow-sm border-0 rounded-xl mb-4">
    <div class="card-body p-3">
        <h6 class="fw-bold mb-2">Menu Admin</h6>
        <div class="list-group list-group-flush">
            <a href="<?php echo e(route('admin.profile')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.profile') ? 'active' : ''); ?>">
                <i class="bi bi-person-circle me-2"></i> Dashboard
            </a>
            <a href="<?php echo e(route('admin.data-pengguna')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.data-pengguna') ? 'active' : ''); ?>">
                <i class="bi bi-people me-2"></i> Kelola Data Pengguna
            </a>
            <a href="<?php echo e(route('admin.data-katalog')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.data-katalog') ? 'active' : ''); ?>">
                <i class="bi bi-collection me-2"></i> Kelola Data Katalog
            </a>
            <a href="<?php echo e(route('admin.data-kostum')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.data-kostum') ? 'active' : ''); ?>">
                <i class="bi bi-box me-2"></i> Kelola Data Kostum
            </a>
            <a href="<?php echo e(route('admin.data-aturan')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.data-aturan') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text me-2"></i> Kelola Data Aturan
            </a>
            <a href="<?php echo e(route('admin.data-pesanan')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.data-pesanan') ? 'active' : ''); ?>">
                <i class="bi bi-bag-check me-2"></i> Kelola Pesanan & Pembayaran
            </a>
            <a href="<?php echo e(route('admin.profile-contact')); ?>" class="list-group-item list-group-item-action d-flex align-items-center <?php echo e(request()->routeIs('admin.profile-contact') ? 'active' : ''); ?>">
                <i class="bi bi-gear me-2"></i> Pengaturan Profil
            </a>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/_sidebar.blade.php ENDPATH**/ ?>