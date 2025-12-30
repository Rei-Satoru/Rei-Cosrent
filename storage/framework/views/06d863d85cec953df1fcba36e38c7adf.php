

<?php $__env->startSection('title', 'Pesanan Saya - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <a href="<?php echo e(route('user.profile')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Profil
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

        <?php if($pesanan->isEmpty()): ?>
            <div class="alert alert-info text-center" role="alert">
                Anda belum memiliki pesanan.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kostum</th>
                            <th>Tgl Pakai</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Dibuat</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($order->nama_kostum ?? '-'); ?></td>
                            <td><?php echo e($order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-'); ?></td>
                            <td><?php echo e($order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-'); ?></td>
                            <td>Rp <?php echo e(number_format((float) $order->total_harga, 0, ',', '.')); ?></td>
                            <td class="text-center"><?php echo e($order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-'); ?></td>
                            <td>
                                <?php
                                    $statusClass = [
                                        'proses' => 'bg-warning text-dark',
                                        'revisi' => 'bg-secondary',
                                        'selesai' => 'bg-success',
                                        'diterima' => 'bg-info text-dark',
                                        'dibatalkan' => 'bg-secondary'
                                    ][$order->status] ?? 'bg-dark';
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                            </td>
                            <td><?php echo e($order->keterangan ?? '-'); ?></td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#orderDetailModal-<?php echo e($order->id); ?>">
                                    <i class="bi bi-card-list"></i> Detail
                                </button>

                                <?php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;

                                    if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                        $hasBukti = true;
                                    } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                        $hasBukti = true;
                                    } else {
                                        try {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $order->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $hasBukti = false;
                                        }
                                    }
                                ?>

                                <?php if($hasBukti): ?>
                                    <?php
                                        $directBuktiUrl = null;
                                        $directExt = null;

                                        if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                            $directBuktiUrl = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                            $directExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                        } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                            $directBuktiUrl = asset('storage/' . session('uploaded_bukti_path'));
                                            $directExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                        } elseif (!empty($foundBuktiPath)) {
                                            $directBuktiUrl = asset('storage/' . $foundBuktiPath);
                                            $directExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                        }
                                    ?>

                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#buktiModal-<?php echo e($order->id); ?>">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                <?php else: ?>
                                    <?php if($order->status === 'diterima'): ?>
                                        <a href="<?php echo e(route('pembayaran', ['id' => $order->id])); ?>" class="btn btn-success btn-sm ms-2">
                                            <i class="bi bi-cash-coin"></i> Lanjutkan ke Pembayaran
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(in_array($order->status, ['proses', 'revisi'])): ?>
                                    <a href="<?php echo e(route('user.pesanan.edit', ['id' => $order->id])); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#orderActionModal-<?php echo e($order->id); ?>">
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="orderDetailModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="orderDetailLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="orderDetailLabel-<?php echo e($order->id); ?>">
                                            <i class="bi bi-card-list"></i> Detail Pesanan #<?php echo e($order->id); ?>

                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br><?php echo e($order->nama_kostum ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Tgl Pakai:</strong><br><?php echo e($order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-'); ?></div>
                                                <div class="mb-2"><strong>Tgl Kembali:</strong><br><?php echo e($order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-'); ?></div>
                                                <div class="mb-2"><strong>Total Harga:</strong><br>Rp <?php echo e(number_format((float) $order->total_harga, 0, ',', '.')); ?></div>
                                                <div class="mb-2"><strong>Metode Pembayaran:</strong><br><?php echo e($order->metode_pembayaran ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Status:</strong><br><?php echo e(ucfirst($order->status)); ?></div>
                                                <div class="mb-2"><strong>Keterangan:</strong><br><?php echo e($order->keterangan ?? '-'); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br><?php echo e($order->nama); ?></div>
                                                <div class="mb-2"><strong>Nomor Telepon:</strong><br><?php echo e($order->nomor_telepon); ?></div>
                                                <div class="mb-2"><strong>Nomor Telepon 2:</strong><br><?php echo e($order->nomor_telepon_2); ?></div>
                                                <div class="mb-2"><strong>Alamat:</strong><br><?php echo e($order->alamat); ?></div>
                                                <div class="mb-2"><strong>Kartu Identitas:</strong><br><?php echo e($order->kartu_identitas); ?></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Foto Kartu Identitas:</strong>
                                                <?php if($order->foto_kartu_identitas): ?>
                                                    <img src="<?php echo e(asset('storage/' . $order->foto_kartu_identitas)); ?>" alt="Foto Kartu Identitas" class="img-fluid rounded mt-2">
                                                <?php else: ?>
                                                    <div class="text-muted">Tidak tersedia</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Selfie Kartu Identitas:</strong>
                                                <?php if($order->selfie_kartu_identitas): ?>
                                                    <img src="<?php echo e(asset('storage/' . $order->selfie_kartu_identitas)); ?>" alt="Selfie Kartu Identitas" class="img-fluid rounded mt-2">
                                                <?php else: ?>
                                                    <div class="text-muted">Tidak tersedia</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="buktiModalLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="buktiModalLabel-<?php echo e($order->id); ?>">Bukti Pembayaran - Pesanan #<?php echo e($order->id); ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                            $displayBuktiPath = null;
                                            $displayExt = null;

                                            if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                                $displayBuktiPath = asset('storage/' . session('uploaded_bukti_path'));
                                                $displayExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        ?>

                                        <?php if($displayBuktiPath): ?>
                                                <?php
                                                    // If it's a PDF, embed it. Otherwise try to display as image.
                                                ?>
                                                <?php if($displayExt === 'pdf'): ?>
                                                    <embed src="<?php echo e($displayBuktiPath); ?>" type="application/pdf" width="100%" height="600px" />
                                                <?php else: ?>
                                                    <img src="<?php echo e($displayBuktiPath); ?>" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'<?php echo e($displayBuktiPath); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                                <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-secondary">Belum ada bukti pembayaran untuk pesanan ini.</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Modal (Hapus permanen) -->
                        <?php if(in_array($order->status, ['proses', 'revisi'])): ?>
                        <div class="modal fade" id="orderActionModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="orderActionLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="orderActionLabel-<?php echo e($order->id); ?>"><i class="bi bi-x-octagon"></i> Hapus Pesanan Permanen</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pesanan #<?php echo e($order->id); ?> akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="orderActionForm-<?php echo e($order->id); ?>" method="POST" action="<?php echo e(url('/pesanan-saya')); ?>/<?php echo e($order->id); ?>/delete">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check-circle"></i> Hapus Sekarang
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // No dynamic actions needed; delete is permanent by default
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            try {
                setTimeout(() => {
                    // Use Bootstrap's Alert instance to close (if available)
                    if (window.bootstrap && typeof window.bootstrap.Alert !== 'undefined') {
                        const instance = window.bootstrap.Alert.getOrCreateInstance(successAlert);
                        instance.close();
                    } else {
                        // Fallback: remove element
                        successAlert.remove();
                    }
                }, 5000);
            } catch (e) {
                // ignore
            }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/pesanan-saya.blade.php ENDPATH**/ ?>