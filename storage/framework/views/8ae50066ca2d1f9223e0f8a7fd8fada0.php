

<?php $__env->startSection('title', 'Pembayaran - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pembayaran Pesanan</h2>
            <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pesanan Saya
            </a>
        </div>
        <div class="alert alert-info">
            Silakan lakukan pembayaran sesuai instruksi yang tertera di bawah ini.
        </div>
        <!-- Contoh konten pembayaran, silakan sesuaikan dengan kebutuhan -->
        <div class="card mb-4">
            <div class="card-body">
                <?php
                    $orderId = null;
                    $nama_kostum = '-';
                    $total_harga = 0;
                    $metode_pembayaran = '-';

                    if (is_object($order)) {
                        $orderId = $order->id ?? null;
                        $nama_kostum = $order->nama_kostum ?? '-';
                        $total_harga = $order->total_harga ?? 0;
                        $metode_pembayaran = $order->metode_pembayaran ?? '-';
                    } elseif (is_array($order)) {
                        $orderId = $order['id'] ?? null;
                        $nama_kostum = $order['nama_kostum'] ?? '-';
                        $total_harga = $order['total_harga'] ?? 0;
                        $metode_pembayaran = $order['metode_pembayaran'] ?? '-';
                    }
                ?>
                <h5 class="card-title">Detail Pembayaran</h5>
                <p class="mb-2"><strong>No. Urut Pesanan:</strong> <?php echo e($orderId ?? '-'); ?></p>
                <p class="mb-2"><strong>ID Pesanan:</strong> <?php echo e($orderId ?? '-'); ?></p>
                <p class="mb-2"><strong>Nama Kostum:</strong> <?php echo e($nama_kostum); ?></p>
                <p class="mb-2"><strong>Total Harga:</strong> Rp <?php echo e(number_format((float) $total_harga, 0, ',', '.')); ?></p>
                <p class="mb-2"><strong>Metode Pembayaran:</strong> <?php echo e($metode_pembayaran); ?></p>
                <hr>
                <h6>Instruksi Pembayaran:</h6>
                <ul>
                    <li>Untuk transfer ke rekening berikut: <strong><?php echo e($profile->nomor_bank ?? ''); ?></strong></li>
                    <li>Untuk pembayaran e-wallet, gunakan nomor: <strong><?php echo e($profile->nomor_ewallet ?? ''); ?></strong></li>
                    <li>Nomor rekening & e-wallet Atas Nama: <strong><?php echo e($profile->name ?? 'Rei Cosrent'); ?></strong></li>
                    <li>Setelah transfer, upload bukti pembayaran di halaman ini.</li>
                </ul>
                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if($orderId): ?>
                    <?php if(!empty($pembayaran) && !empty($pembayaran->bukti_pembayaran)): ?>
                        <div class="mb-3">
                            <a href="<?php echo e(asset('storage/' . $pembayaran->bukti_pembayaran)); ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-image"></i> Lihat Bukti Pembayaran
                            </a>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('pembayaran.upload', $orderId)); ?>" enctype="multipart/form-data">
                <?php else: ?>
                    <div class="alert alert-warning">Tidak ada ID pesanan untuk mengunggah bukti pembayaran.</div>
                <?php endif; ?>
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" required>
                    </div>
                    <button type="submit" class="btn btn-success">Kirim Bukti Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/pembayaran.blade.php ENDPATH**/ ?>