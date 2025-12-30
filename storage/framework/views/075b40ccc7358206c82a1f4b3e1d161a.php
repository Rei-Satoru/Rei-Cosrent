

<?php $__env->startSection('title', 'Edit Pesanan - Rei Cosrent'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Edit Pesanan #<?php echo e($order->id); ?></h2>
            <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pesanan Saya
            </a>
        </div>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 fw-bold">Edit Pesanan Lengkap</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('user.pesanan.update', ['id' => $order->id])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <!-- Data Penyewa -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-person-fill"></i> Data Penyewa</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama', $order->nama)); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" class="form-control" value="<?php echo e(old('nomor_telepon', $order->nomor_telepon)); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon Pihak Kedua</label>
                                <input type="text" name="nomor_telepon_2" class="form-control" value="<?php echo e(old('nomor_telepon_2', $order->nomor_telepon_2)); ?>" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="3" required><?php echo e(old('alamat', $order->alamat)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Penyewaan -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-calendar-check"></i> Detail Penyewaan</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Nama Kostum</label>
                                <input type="text" class="form-control" value="<?php echo e($order->nama_kostum); ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pemakaian</label>
                                <input type="date" name="tanggal_pemakaian" class="form-control" value="<?php echo e(old('tanggal_pemakaian', $order->tanggal_pemakaian->format('Y-m-d'))); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Pengembalian</label>
                                <input type="date" name="tanggal_pengembalian" class="form-control" value="<?php echo e(old('tanggal_pengembalian', $order->tanggal_pengembalian->format('Y-m-d'))); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Harga (Termasuk Ongkir)</label>
                                <input type="number" name="total_harga" class="form-control" value="<?php echo e(old('total_harga', $order->total_harga)); ?>" required min="0" step="0.01">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Metode Pembayaran</label>
                                <div class="row g-3">
                                    <?php $mp = old('metode_pembayaran', $order->metode_pembayaran); ?>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_cod" value="COD" <?php echo e($mp === 'COD' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_cod">COD (Cash On Delivery)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_dana" value="Dana" <?php echo e($mp === 'Dana' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_dana">Dana</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_gopay" value="GoPay" <?php echo e($mp === 'GoPay' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_gopay">GoPay</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_shopeepay" value="ShopeePay" <?php echo e($mp === 'ShopeePay' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_shopeepay">ShopeePay</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_qris" value="QRIS" <?php echo e($mp === 'QRIS' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_qris">QRIS</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_transfer" value="Transfer Bank" <?php echo e($mp === 'Transfer Bank' ? 'checked' : ''); ?> required>
                                            <label class="form-check-label" for="pembayaran_transfer">Transfer Bank</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Identitas -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-card-heading"></i> Kartu Identitas</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Jenis Kartu Identitas</label>
                                <?php $kid = old('kartu_identitas', $order->kartu_identitas); ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_pelajar" value="Kartu Pelajar" <?php echo e($kid === 'Kartu Pelajar' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_pelajar">Kartu Pelajar</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_kia" value="KIA" <?php echo e($kid === 'KIA' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_kia">KIA (Kartu Identitas Anak)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_ktm" value="KTM" <?php echo e($kid === 'KTM' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_ktm">KTM (Kartu Tanda Mahasiswa)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_ktp" value="KTP" <?php echo e($kid === 'KTP' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_ktp">KTP (Kartu Tanda Penduduk)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_sim" value="SIM" <?php echo e($kid === 'SIM' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_sim">SIM (Surat Izin Mengemudi)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_lainnya" value="Lainnya" <?php echo e($kid && !in_array($kid, ['Kartu Pelajar','KIA','KTM','KTP','SIM']) ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                            <label class="form-check-label" for="identitas_lainnya">Lainnya</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="identitas_lainnya_input" style="display: none; margin-top: 1rem;">
                                    <input type="text" name="kartu_identitas_lainnya" class="form-control" placeholder="Sebutkan jenis identitas lainnya..." value="<?php echo e(in_array($kid, ['Kartu Pelajar','KIA','KTM','KTP','SIM']) ? '' : $kid); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Kartu Identitas</label>
                                <input type="file" name="foto_kartu_identitas" class="form-control file-input" data-max-size="5242880" accept="image/*">
                                <small class="text-muted">Opsional. Maks 5MB.</small>
                                <div class="invalid-feedback d-block" id="foto_kartu_identitas_error" style="display: none;"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Selfie dengan Kartu Identitas</label>
                                <input type="file" name="selfie_kartu_identitas" class="form-control file-input" data-max-size="5242880" accept="image/*">
                                <small class="text-muted">Opsional. Maks 5MB.</small>
                                <div class="invalid-feedback d-block" id="selfie_kartu_identitas_error" style="display: none;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Pernyataan -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-file-text"></i> Pernyataan</h6>
                        <input type="hidden" name="pernyataan" id="pernyataan_hidden" value="">
                        <div class="alert alert-info mb-3">
                            <p class="mb-0" style="white-space: pre-line;">Dengan ini Saya menyatakan bahwa:
1. Wajib Membayar Lunas
2. Menggunakan/Menjaga/Merawat Secara Baik
3. Mengembalikan Secara Tepat Waktu

Apabila Saya Melanggar maka:
1. Siap Bertanggung Jawab
2. Siap Ganti Rugi
3. Menerima Konsekuensi</p>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agree" required>
                            <label class="form-check-label" for="agree">Saya menyetujui semua ketentuan dan peraturan penyewaan kostum yang berlaku.</label>
                        </div>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="<?php echo e(route('user.pesanan')); ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pernyataanHidden = document.getElementById('pernyataan_hidden');
        const pernyataan = `Dengan ini Saya menyatakan bahwa:
1. Wajib Membayar Lunas
2. Menggunakan/Menjaga/Merawat Secara Baik
3. Mengembalikan Secara Tepat Waktu

Apabila Saya Melanggar maka:
1. Siap Bertanggung Jawab
2. Siap Ganti Rugi
3. Menerima Konsekuensi`;
        if (pernyataanHidden) pernyataanHidden.value = pernyataan;

        toggleLainnyaInput();

        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function() {
                const maxSize = parseInt(this.dataset.maxSize);
                const file = this.files[0];
                const errorDiv = document.getElementById(this.name + '_error');

                if (file && file.size > maxSize) {
                    if (errorDiv) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = `Ukuran file terlalu besar. Maksimal: 5MB, Ukuran file Anda: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
                    }
                    this.value = '';
                    this.classList.add('is-invalid');
                } else {
                    if (errorDiv) {
                        errorDiv.style.display = 'none';
                    }
                    this.classList.remove('is-invalid');
                }
            });
        });
    });

    function toggleLainnyaInput() {
        const lainnyaRadio = document.getElementById('identitas_lainnya');
        const lainnyaInput = document.getElementById('identitas_lainnya_input');
        if (!lainnyaRadio || !lainnyaInput) return;
        lainnyaInput.style.display = lainnyaRadio.checked ? 'block' : 'none';
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/edit-pesanan.blade.php ENDPATH**/ ?>