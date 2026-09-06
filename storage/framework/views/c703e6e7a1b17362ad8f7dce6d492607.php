

<?php $__env->startSection('title', 'Data Aturan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
    /* Admin dropdown colors */

    .form-select, select, .dropdown-menu {
        background-color: #0f172af5 !important;
        color: #dee2e6 !important;
        border-color: rgba(148, 163, 184, 0.12) !important;
    }

    .form-select option, select option {
        background-color: #0f172af5;
        color: #dee2e6;
    }

    /* Admin search input styles */
    .input-group .form-control[type="search"], input[type="search"], .card-body .input-group input.form-control {
        background-color: #0f172af5 !important;
        color: #dee2e6 !important;
        border-color: rgba(148, 163, 184, 0.12) !important;
    }
    table th {
        background-color: var(--bs-primary);
        color: white;
        text-align: center;
        font-size: 1.0rem;
    }

    table td {
        font-size: 1.0rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
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
    .modal-content .modal-body .form-control:focus,
    .modal-content .modal-body textarea:focus,
    .modal-content .modal-body input:focus,
    .modal-content .modal-body .form-select:focus,
    .modal-content .modal-body .form-control[readonly],
    .modal-content .modal-body .form-control[disabled] {
        background-color: #0f172af5 !important;
        color: var(--bs-body-color) !important;
        border-color: rgba(148, 163, 184, 0.12) !important;
        box-shadow: none !important;
    }

    .modal-content .modal-body ::placeholder {
        color: rgba(148, 163, 184, 0.6) !important;
    }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Aturan</h2>
                <p class="text-muted mb-0 small" style="color:#ffffff !important;">Kelola syarat ketentuan dan larangan/denda sewa kostum.</p>
            </div>
            <div class="d-grid d-sm-block">
                <?php if($aturan->count() == 0): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-circle"></i> Tambah Aturan
                    </button>
                <?php endif; ?>
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

        <?php if($aturan->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 25%;\">Syarat & Ketentuan</th>
                            <th style="width: 25%;\">Larangan & Denda</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;\">Dibuat</th>
                            <th class="d-none d-md-table-cell" style="width: 10%;\">Diubah</th>
                            <th style="width: 15%;\">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $aturan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div style="max-height: 120px; overflow-y: auto; font-size: 0.95rem;">
                                    <?php echo nl2br(e($item->syarat_ketentuan)); ?>

                                </div>
                            </td>
                            <td>
                                <div style="max-height: 120px; overflow-y: auto; font-size: 0.95rem;">
                                    <?php echo nl2br(e($item->larangan_dan_denda)); ?>

                                </div>
                            </td>
                                <td class="d-none d-md-table-cell text-center"><?php echo e($item->created_at->format('d/m/Y')); ?></td>
                                <td class="d-none d-md-table-cell text-center"><?php echo e($item->updated_at ? $item->updated_at->format('d/m/Y') : '-'); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($item->id); ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <?php if($aturan->count() == 0): ?>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($item->id); ?>">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title">Edit Aturan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.aturan.update')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="syarat_ketentuan_edit_<?php echo e($item->id); ?>" class="form-label fw-semibold">Syarat & Ketentuan</label>
                                                    <textarea class="form-control" id="syarat_ketentuan_edit_<?php echo e($item->id); ?>" name="syarat_ketentuan" rows="8" required><?php echo e($item->syarat_ketentuan); ?></textarea>
                                                    <small class="text-muted">Gunakan Enter untuk membuat baris baru</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="larangan_dan_denda_edit_<?php echo e($item->id); ?>" class="form-label fw-semibold">Larangan & Denda</label>
                                                    <textarea class="form-control" id="larangan_dan_denda_edit_<?php echo e($item->id); ?>" name="larangan_dan_denda" rows="8" required><?php echo e($item->larangan_dan_denda); ?></textarea>
                                                    <small class="text-muted">Gunakan Enter untuk membuat baris baru</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Hapus Aturan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.aturan.delete', $item->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <p>Anda yakin ingin menghapus peraturan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Syarat & Ketentuan</label>
                                                    <div class="border p-2" style="max-height:120px;overflow:auto;"><?php echo nl2br(e($item->syarat_ketentuan)); ?></div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Larangan & Denda</label>
                                                    <div class="border p-2" style="max-height:120px;overflow:auto;"><?php echo nl2br(e($item->larangan_dan_denda)); ?></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada data aturan.
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Aturan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.aturan.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="syarat_ketentuan" class="form-label fw-semibold">Syarat & Ketentuan</label>
                        <textarea class="form-control" id="syarat_ketentuan" name="syarat_ketentuan" rows="8" placeholder="Masukkan syarat dan ketentuan sewa kostum..." required></textarea>
                        <small class="text-muted">Gunakan Enter untuk membuat baris baru</small>
                    </div>
                    <div class="mb-3">
                        <label for="larangan_dan_denda" class="form-label fw-semibold">Larangan & Denda</label>
                        <textarea class="form-control" id="larangan_dan_denda" name="larangan_dan_denda" rows="8" placeholder="Masukkan larangan dan denda sewa kostum..." required></textarea>
                        <small class="text-muted">Gunakan Enter untuk membuat baris baru</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Auto hide alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views\admin\data-aturan.blade.php ENDPATH**/ ?>