

<?php $__env->startSection('title', 'Denda Saya - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h2 class="fw-bold">Denda Saya</h2>
            </div>
            <div>
                <a href="<?php echo e(route('user.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Profil
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <?php if(isset($dendas) && count($dendas) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kostum</th>
                            <th>Jenis Denda</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($d->id); ?></td>
                            <td><?php echo e($d->nama_kostum ?? '-'); ?></td>
                            <td><?php echo e($d->jenis_denda ?? '-'); ?></td>
                            <td><div style="max-height:120px;overflow:auto"><?php echo nl2br(e($d->keterangan)); ?></div></td>
                            <td class="text-end">Rp<?php echo e($d->jumlah_denda ? number_format($d->jumlah_denda,0,',','.') : '-'); ?></td>
                            <td class="text-center"><?php echo e($d->created_at ? $d->created_at->format('d M Y') : '-'); ?></td>
                            <td>
                                <?php
                                    $statusClass = [
                                        'Belum Lunas' => 'bg-warning text-dark',
                                        'Lunas' => 'bg-success text-white'
                                    ][$d->status] ?? 'bg-secondary text-white';
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e($d->status ? ucfirst($d->status) : '-'); ?></span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#dendaDetailModal-<?php echo e($d->id); ?>">
                                    <i class="bi bi-card-list"></i> Detail
                                </button>

                                <?php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;
                                    try {
                                        if (!empty($d->bukti_pembayaran)) {
                                            $hasBukti = true;
                                        } else {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_denda_' . $d->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $hasBukti = false;
                                    }
                                ?>

                                <?php if($hasBukti): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#buktiModal-<?php echo e($d->id); ?>">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                <?php else: ?>
                                    <?php if(strtolower($d->status) === strtolower('Belum Lunas')): ?>
                                        <a href="<?php echo e(route('denda.bayar', $d->id)); ?>" class="btn btn-success btn-sm ms-2">
                                            <i class="bi bi-cash-coin"></i> Bayar Denda
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="dendaDetailModal-<?php echo e($d->id); ?>" tabindex="-1" aria-labelledby="dendaDetailLabel-<?php echo e($d->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title" id="dendaDetailLabel-<?php echo e($d->id); ?>">
                                            <i class="bi bi-card-list"></i> Detail Denda #<?php echo e($d->id); ?>

                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br><?php echo e($d->nama ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br><?php echo e($d->nama_kostum ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Dibuat:</strong><br><?php echo e($d->created_at ? $d->created_at->format('d M Y H:i') : '-'); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Jenis Denda:</strong><br><?php echo e($d->jenis_denda ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Keterangan:</strong><br><?php echo nl2br(e($d->keterangan)); ?></div>
                                                <div class="mb-2"><strong>Jumlah Denda:</strong><br>Rp<?php echo e($d->jumlah_denda ? number_format($d->jumlah_denda,0,',','.') : '-'); ?></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-2"><strong>Foto Bukti:</strong><br>
                                            <?php
                                                $buktiFotos = collect([
                                                    $d->bukti_foto_1 ?? null,
                                                    $d->bukti_foto_2 ?? null,
                                                    $d->bukti_foto_3 ?? null,
                                                    $d->bukti_foto_4 ?? null,
                                                    $d->bukti_foto_5 ?? null,
                                                ])->filter();
                                            ?>
                                            <?php if($buktiFotos->isNotEmpty()): ?>
                                                <div class="row g-2 mt-1">
                                                    <?php $__currentLoopData = $buktiFotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="col-6 col-md-4 col-lg-3">
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 d-block" onclick="showUserDendaBuktiFotoPreview('<?php echo e(asset('storage/' . $bf)); ?>')" aria-label="Lihat foto bukti">
                                                                <img src="<?php echo e(asset('storage/' . $bf)); ?>" alt="Foto Bukti" class="img-fluid rounded" style="max-height:160px; object-fit:cover; width:100%; cursor:pointer;" onerror="this.outerHTML = '<a href=\'<?php echo e(asset('storage/' . $bf)); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary btn-sm\'>Lihat File</a>'">
                                                            </button>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-muted">Tidak tersedia</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-<?php echo e($d->id); ?>" tabindex="-1" aria-labelledby="buktiModalLabel-<?php echo e($d->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title" id="buktiModalLabel-<?php echo e($d->id); ?>">Bukti Pembayaran - Denda #<?php echo e($d->id); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                            $displayBuktiPath = null;
                                            $displayExt = null;
                                            if (!empty($d->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $d->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($d->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        ?>

                                        <?php if($displayBuktiPath): ?>
                                            <?php if($displayExt === 'pdf'): ?>
                                                <embed src="<?php echo e($displayBuktiPath); ?>" type="application/pdf" width="100%" height="600px" />
                                            <?php else: ?>
                                                <img src="<?php echo e($displayBuktiPath); ?>" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'<?php echo e($displayBuktiPath); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-secondary">Belum ada bukti pembayaran untuk denda ini.</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center"><i class="bi bi-info-circle"></i> Belum ada data denda untuk akun Anda.</div>
        <?php endif; ?>
    </div>
</section>
<!-- Foto Bukti Preview Modal (inside content) -->
<div class="modal fade" id="userDendaBuktiFotoPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-surface">
                <h5 class="modal-title">Foto Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="userDendaBuktiFotoPreviewImg" src="" alt="Preview" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            try {
                setTimeout(() => {
                    if (window.bootstrap && typeof window.bootstrap.Alert !== 'undefined') {
                        const instance = window.bootstrap.Alert.getOrCreateInstance(successAlert);
                        instance.close();
                    } else {
                        successAlert.remove();
                    }
                }, 5000);
            } catch (e) {}
        }
    });
</script>
<script>
    function showUserDendaBuktiFotoPreview(src) {
        const img = document.getElementById('userDendaBuktiFotoPreviewImg');
        if (!img) return;
        img.src = src;

        const modalEl = document.getElementById('userDendaBuktiFotoPreviewModal');
        if (!modalEl || !window.bootstrap) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('userDendaBuktiFotoPreviewModal');
        if (!modalEl) return;
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('userDendaBuktiFotoPreviewImg');
            if (img) img.src = '';
        });
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/denda-saya.blade.php ENDPATH**/ ?>