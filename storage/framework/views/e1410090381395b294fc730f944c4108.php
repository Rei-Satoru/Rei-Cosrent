

<?php $__env->startSection('title', 'Formulir Penyewaan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .form-section {
        background-color: var(--bs-body-bg);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-section h4 {
        color: var(--bs-primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bs-primary);
    }

    [data-bs-theme="dark"] .form-section h4 {
        color: #a855f7;
        border-bottom-color: #a855f7;
    }

    .kostum-info {
        background: var(--bs-secondary-bg);
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .required-label::after {
        content: " *";
        color: red;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Formulir Penyewaan Kostum</h2>
            <a href="<?php echo e(route('katalog.kostum', ['cat' => strtolower($kostum->kategori)])); ?>" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <!-- Informasi Kostum -->
        <div class="form-section">
            <h4><i class="bi bi-info-circle"></i> Informasi Kostum</h4>
            <div class="kostum-info">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php
                            $img = $kostum->gambar ?? '';
                            $src = '';
                            if ($img) {
                                if (str_starts_with($img, 'http')) {
                                    $src = $img;
                                } elseif (str_starts_with($img, 'storage/')) {
                                    $src = asset($img);
                                } elseif (str_starts_with($img, 'public/')) {
                                    $src = asset(str_replace('public/', 'storage/', $img));
                                } elseif ($img) {
                                    $src = asset('storage/' . ltrim($img, '/'));
                                }
                            }
                        ?>
                        <?php if($src): ?>
                            <img src="<?php echo e($src); ?>" alt="<?php echo e($kostum->nama_kostum); ?>" class="img-fluid rounded" style="max-width: 200px;">
                        <?php else: ?>
                            <img src="<?php echo e(asset('assets/img/no-image.png')); ?>" alt="Tidak ada gambar" class="img-fluid rounded" style="max-width: 200px;">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <h5 class="fw-bold"><?php echo e($kostum->nama_kostum); ?></h5>
                        <?php if($kostum->judul): ?>
                            <p class="text-muted mb-2"><?php echo e($kostum->judul); ?></p>
                        <?php endif; ?>
                        <p class="mb-1"><strong>Kategori:</strong> <?php echo e($kostum->kategori); ?></p>
                        <p class="mb-1"><strong>Brand:</strong> <?php echo e($kostum->brand ?: '-'); ?></p>
                        <p class="mb-1"><strong>Ukuran:</strong> <?php echo e($kostum->ukuran_kostum); ?></p>
                        <p class="mb-1"><strong>Harga Sewa:</strong> <span class="text-success fw-bold">Rp <?php echo e(number_format((float)$kostum->harga_sewa, 0, ',', '.')); ?></span> / <?php echo e($kostum->durasi_penyewaan); ?></p>
                        <p class="mb-1"><strong>Domisili:</strong> <?php echo e($kostum->domisili ?: '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peringatan -->
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div>
                <strong>Perhatian!</strong> Pastikan telah membaca & memahami aturan terkait syarat, ketentuan, larangan dan denda yang berlaku.
            </div>
        </div>

        <!-- Alerts -->
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form Penyewaan -->
        <form method="POST" action="<?php echo e(route('formulir.penyewaan.submit')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <!-- Data Penyewa -->
            <div class="form-section">
                <h4><i class="bi bi-person-fill"></i> Data Penyewa</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama', session('user_logged_in') ? (App\Models\User::find(session('user_id'))->nick_name ?? '') : '')); ?>" required placeholder="Masukkan nama lengkap sesuai dengan identitas" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Email</label>
                        <?php if(session('user_logged_in')): ?>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email', session('user_email'))); ?>" required autocomplete="off">
                            <small class="text-muted">Email akun Anda digunakan otomatis. Anda bisa mengubah/hapus jika perlu.</small>
                        <?php else: ?>
                            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required placeholder="nama@email.com">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-control" value="<?php echo e(old('nomor_telepon', session('user_logged_in') ? (App\Models\User::find(session('user_id'))->nomor_telepon ?? '') : '')); ?>" required placeholder="08xxx" autocomplete="off">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required-label">Nomor Telepon Pihak Kedua (Orang Tua/Wali/Tetangga/DLL)</label>
                        <input type="text" name="nomor_telepon_2" class="form-control" value="<?php echo e(old('nomor_telepon_2')); ?>" required placeholder="Contoh: 08xxx - Orang Tua" autocomplete="off">
                    </div>
                    <div class="col-12">
                        <label class="form-label required-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" required placeholder="Masukkan alamat pengambilan/penerimaan dengan lengkap" autocomplete="off"><?php echo e(old('alamat', session('user_logged_in') ? (App\Models\User::find(session('user_id'))->alamat ?? '') : '')); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Penyewaan -->
            <div class="form-section">
                <h4><i class="bi bi-calendar-check"></i> Detail Penyewaan</h4>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label required-label">Nama Kostum</label>
                        <input type="text" name="nama_kostum" class="form-control" value="<?php echo e($kostum->nama_kostum); ?>" readonly required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required-label">Tanggal Pemakaian</label>
                        <input type="date" name="tanggal_pemakaian" class="form-control" value="<?php echo e(old('tanggal_pemakaian')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required-label">Tanggal Pengembalian</label>
                        <input type="date" name="tanggal_pengembalian" class="form-control" value="<?php echo e(old('tanggal_pengembalian')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required-label">Total Harga (Termasuk Ongkir)</label>
                        <input type="number" name="total_harga" class="form-control" value="<?php echo e(old('total_harga')); ?>" required min="0" step="0.01" placeholder="Masukkan total harga">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label required-label">Metode Pembayaran</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_dana" value="Dana" <?php echo e(old('metode_pembayaran') === 'Dana' ? 'checked' : ''); ?> required>
                                    <label class="form-check-label" for="pembayaran_dana">
                                        E-Wallet (Dana, GoPay, ShopeePay)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="metode_pembayaran" id="pembayaran_transfer" value="Transfer Bank" <?php echo e(old('metode_pembayaran') === 'Transfer Bank' ? 'checked' : ''); ?> required>
                                    <label class="form-check-label" for="pembayaran_transfer">
                                        Transfer Bank (SeaBank)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identitas -->
            <div class="form-section">
                <h4><i class="bi bi-card-heading"></i> Kartu Identitas</h4>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label required-label">Jenis Kartu Identitas</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_pelajar" value="Kartu Pelajar" <?php echo e(old('kartu_identitas') === 'Kartu Pelajar' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_pelajar">
                                        Kartu Pelajar
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_kia" value="KIA" <?php echo e(old('kartu_identitas') === 'KIA' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_kia">
                                        KIA (Kartu Identitas Anak)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_ktm" value="KTM" <?php echo e(old('kartu_identitas') === 'KTM' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_ktm">
                                        KTM (Kartu Tanda Mahasiswa)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_ktp" value="KTP" <?php echo e(old('kartu_identitas') === 'KTP' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_ktp">
                                        KTP (Kartu Tanda Penduduk)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_sim" value="SIM" <?php echo e(old('kartu_identitas') === 'SIM' ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_sim">
                                        SIM (Surat Izin Mengemudi)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="identitas_lainnya" value="Lainnya" <?php echo e(old('kartu_identitas') === 'Lainnya' || (old('kartu_identitas') && !in_array(old('kartu_identitas'), ['Kartu Pelajar', 'KIA', 'KTM', 'KTP', 'SIM'])) ? 'checked' : ''); ?> onchange="toggleLainnyaInput()" required>
                                    <label class="form-check-label" for="identitas_lainnya">
                                        Lainnya
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="identitas_lainnya_input" style="display: none; margin-top: 1rem;">
                            <input type="text" name="kartu_identitas_lainnya" class="form-control" placeholder="Sebutkan jenis identitas lainnya..." value="<?php echo e(old('kartu_identitas_lainnya')); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required-label">Foto Kartu Identitas (NIK di sensor)</label>
                        <input type="file" name="foto_kartu_identitas" class="form-control file-input" data-max-size="5242880" required>
                        <small class="text-muted">Format: Bebas (Max 5MB)</small>
                        <div class="invalid-feedback d-block" id="foto_kartu_error" style="display: none;"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required-label">Selfie dengan Kartu Identitas (NIK di sensor)</label>
                        <input type="file" name="selfie_kartu_identitas" class="form-control file-input" data-max-size="5242880" required>
                        <small class="text-muted">Format: Bebas (Max 5MB)</small>
                        <div class="invalid-feedback d-block" id="selfie_kartu_error" style="display: none;"></div>
                    </div>
                </div>
            </div>

            <!-- Pernyataan -->
            <div class="form-section">
                <h4><i class="bi bi-file-text"></i> Pernyataan</h4>
                <input type="hidden" name="pernyataan" id="pernyataan_hidden" value="">
                
                <div class="alert alert-info mb-3" style="background-color: var(--bs-info-bg); border-color: var(--bs-info-border-color);">
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
                    <label class="form-check-label" for="agree">
                        Saya menyetujui semua ketentuan dan peraturan penyewaan kostum yang berlaku.
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg px-5">
                    <i class="bi bi-send"></i> Kirim Formulir
                </button>
            </div>
        </form>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Auto-hide only success and error alerts after 5 seconds, keep warning alerts visible
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.alert-success, .alert-danger');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Set pernyataan value
        const pernyataanHidden = document.getElementById('pernyataan_hidden');
        const pernyataan = `Dengan ini Saya menyatakan bahwa:
1. Wajib Membayar Lunas
2. Menggunakan/Menjaga/Merawat Secara Baik
3. Mengembalikan Secara Tepat Waktu

Apabila Saya Melanggar maka:
1. Siap Bertanggung Jawab
2. Siap Ganti Rugi
3. Menerima Konsekuensi`;
        
        pernyataanHidden.value = pernyataan;

        // Initialize toggle for Lainnya input
        toggleLainnyaInput();
    });

    // Function to toggle the Lainnya input field
    function toggleLainnyaInput() {
        const lainnyaRadio = document.getElementById('identitas_lainnya');
        const lainnyaInput = document.getElementById('identitas_lainnya_input');
        
        if (lainnyaRadio && lainnyaRadio.checked) {
            lainnyaInput.style.display = 'block';
        } else {
            lainnyaInput.style.display = 'none';
        }
    }

    // Validasi nomor telepon kedua tidak boleh sama dengan nomor telepon utama
    document.addEventListener('DOMContentLoaded', function () {
        const tel1 = document.querySelector('input[name="nomor_telepon"]');
        const tel2 = document.querySelector('input[name="nomor_telepon_2"]');
        if (tel1 && tel2) {
            function validateTel2() {
                if (tel1.value && tel2.value && tel1.value === tel2.value) {
                    tel2.setCustomValidity('Nomor telepon pihak kedua tidak boleh sama dengan nomor telepon utama!');
                } else {
                    tel2.setCustomValidity('');
                }
            }
            tel1.addEventListener('input', validateTel2);
            tel2.addEventListener('input', validateTel2);
        }
    });

    // File size validation
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function() {
            const maxSize = parseInt(this.dataset.maxSize); // 5242880 bytes = 5MB
            const file = this.files[0];
            const errorDiv = document.getElementById(this.name + '_error');
            
            if (file && file.size > maxSize) {
                errorDiv.style.display = 'block';
                errorDiv.textContent = `Ukuran file terlalu besar. Maksimal: 5MB, Ukuran file Anda: ${(file.size / 1024 / 1024).toFixed(2)}MB`;
                this.value = ''; // Clear the input
                this.classList.add('is-invalid');
            } else {
                errorDiv.style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/formulir-penyewaan.blade.php ENDPATH**/ ?>