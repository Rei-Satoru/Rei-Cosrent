

<?php $__env->startSection('title', 'Bayar Denda - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pembayaran Denda</h2>
            <a href="<?php echo e(route('user.denda-saya')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Denda Saya
            </a>
        </div>
        <div class="alert alert-info">
            Silakan lakukan pembayaran sesuai instruksi yang tertera di bawah ini.
        </div>
        <!-- Struktur sama seperti pada pembayaran.blade.php -->
        <div class="card mb-4">
            <div class="card-body">
                <?php
                    $dendaId = null;
                    $nama_kostum = '-';
                    $total_harga = 0;
                    $metode_pembayaran = '-';

                    if (is_object($denda)) {
                        $dendaId = $denda->id ?? null;
                        $nama_kostum = $denda->nama_kostum ?? '-';
                        $total_harga = $denda->jumlah_denda ?? 0;
                        $metode_pembayaran = $denda->metode_pembayaran ?? '-';
                    } elseif (is_array($denda)) {
                        $dendaId = $denda['id'] ?? null;
                        $nama_kostum = $denda['nama_kostum'] ?? '-';
                        $total_harga = $denda['jumlah_denda'] ?? 0;
                        $metode_pembayaran = $denda['metode_pembayaran'] ?? '-';
                    }
                ?>
                <h5 class="card-title">Detail Pembayaran</h5>
                <p class="mb-2"><strong>No. Urut Denda:</strong> <?php echo e($dendaId ?? '-'); ?></p>
                <p class="mb-2"><strong>ID Denda:</strong> <?php echo e($dendaId ?? '-'); ?></p>
                <p class="mb-2"><strong>Nama Kostum:</strong> <?php echo e($nama_kostum); ?></p>
                <p class="mb-2"><strong>Jumlah Denda:</strong> Rp <?php echo e(number_format((float) $total_harga, 0, ',', '.')); ?></p>
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
                <?php if($dendaId): ?>
                    <form method="POST" action="<?php echo e(route('denda.bayar.upload', $dendaId)); ?>" enctype="multipart/form-data">
                <?php else: ?>
                    <div class="alert alert-warning">Tidak ada ID denda untuk mengunggah bukti pembayaran.</div>
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

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/bayar-denda.blade.php ENDPATH**/ ?>