

<?php $__env->startSection('title', 'Data Pengembalian - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .page-title {
        color: #0056b3;
    }

    [data-bs-theme="dark"] .page-title {
        color: #a855f7;
    }

    .thumb {
        width: 76px;
        height: 76px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0.5rem;
        cursor: zoom-in;
    }

    .status-badge {
        font-size: 0.75rem;
        border-radius: 999px;
        padding: 0.35rem 0.7rem;
    }

    .table th {
        background-color: var(--bs-primary);
        color: #fff;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        vertical-align: middle;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-2">Data Pengembalian</h1>
        <p class="text-muted mb-0">Kelola verifikasi data pengembalian yang telah diisi user.</p>
    </div>
</header>

<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div class="small text-muted">
                    Total Pengajuan: <strong><?php echo e($pengembalianList->count()); ?></strong> | Menunggu Verifikasi: <strong><?php echo e($pendingCount); ?></strong>
                </div>
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

            <?php if($pengembalianList->isEmpty()): ?>
                <div class="alert alert-info mb-0">Belum ada data pengembalian dari user.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Kostum</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Catatan User</th>
                                <th>Catatan Admin</th>
                                <th>Diajukan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pengembalianList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $order = $item->formulir;
                                    $userName = data_get($order, 'nama', '-');
                                    $userEmail = data_get($order, 'email');
                                    $kostumName = data_get($order, 'nama_kostum', '-');
                                    $catatanAdmin = $item->catatan_admin ?: '-';
                                    $buktiList = collect([$item->gambar1, $item->gambar2, $item->gambar3])->filter();
                                    $statusMap = [
                                        'proses' => ['Proses', 'bg-warning text-dark'],
                                        'ditolak' => ['Ditolak', 'bg-danger'],
                                        'diterima' => ['Diterima', 'bg-success'],
                                    ];
                                    $statusKey = $item->status ?: 'proses';
                                    $statusData = $statusMap[$statusKey] ?? [ucfirst(str_replace('_', ' ', (string) $statusKey)), 'bg-secondary'];
                                ?>
                                <tr>
                                    <td class="text-start">
                                        <div class="fw-semibold"><?php echo e($userName); ?></div>
                                        <?php if($userEmail): ?>
                                            <div class="small text-muted"><?php echo e($userEmail); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-start">
                                        <div class="fw-semibold"><?php echo e($kostumName); ?></div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <?php $__currentLoopData = $buktiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bukti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#imgModal-<?php echo e($item->id); ?>-<?php echo e($index); ?>" aria-label="Lihat bukti pengembalian">
                                                    <img src="<?php echo e(asset('storage/' . $bukti)); ?>" alt="Bukti <?php echo e($index + 1); ?>" class="thumb">
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge status-badge <?php echo e($statusData[1]); ?>"><?php echo e($statusData[0]); ?></span>
                                    </td>
                                    <td class="text-start"><?php echo e($item->catatan ?: '-'); ?></td>
                                    <td class="text-start"><?php echo e($catatanAdmin); ?></td>
                                    <td><?php echo e($item->created_at ? $item->created_at->format('d M Y H:i') : '-'); ?></td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal-<?php echo e($item->id); ?>" data-action="setujui">
                                                <i class="bi bi-check-circle"></i> Diterima
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#verifyModal-<?php echo e($item->id); ?>" data-action="revisi">
                                                <i class="bi bi-x-circle"></i> Ditolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <?php $__currentLoopData = $buktiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bukti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="modal fade" id="imgModal-<?php echo e($item->id); ?>-<?php echo e($index); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header modal-header-surface">
                                                    <h5 class="modal-title">Bukti Pengembalian #<?php echo e($index + 1); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="<?php echo e(asset('storage/' . $bukti)); ?>" alt="Bukti Pengembalian" class="img-fluid rounded">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <div class="modal fade" id="verifyModal-<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header modal-header-surface">
                                                <h5 class="modal-title">Verifikasi Pengembalian <?php echo e($kostumName); ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?php echo e(route('admin.pengembalian.verifikasi', $item->id)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="row g-3 mb-3 text-start">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">User</div>
                                                            <div class="fw-semibold"><?php echo e($userName); ?></div>
                                                            <?php if($userEmail): ?>
                                                                <div class="small text-muted"><?php echo e($userEmail); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted mb-1">Kostum</div>
                                                            <div class="fw-semibold"><?php echo e($kostumName); ?></div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 text-start">
                                                        <label class="form-label">Catatan Admin (Opsional)</label>
                                                        <textarea name="catatan_admin" class="form-control" rows="3" maxlength="255" placeholder="Contoh: Foto kedua kurang jelas, mohon upload ulang."></textarea>
                                                    </div>
                                                    <div class="alert alert-info mb-0 text-start">
                                                        Pilih <strong>Diterima</strong> untuk menyelesaikan pesanan, atau pilih <strong>Ditolak</strong> agar user mengajukan ulang data pengembalian.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="aksi" value="revisi" class="btn btn-danger">Ditolak</button>
                                                    <button type="submit" name="aksi" value="setujui" class="btn btn-success">Diterima</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-pengembalian.blade.php ENDPATH**/ ?>