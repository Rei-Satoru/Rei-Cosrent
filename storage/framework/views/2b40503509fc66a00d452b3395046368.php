

<?php $__env->startSection('title', ($catalog ? ($catalog->name . ' - Katalog Kostum') : 'Katalog Tidak Ditemukan')); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .costume-card {
        overflow: hidden;
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        transition: all 0s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        position: relative;
    }

    .costume-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .costume-thumb {
        aspect-ratio: 1 / 1;
        background: var(--bs-secondary-bg, #f8f9fa);
        overflow: hidden;
        transition: background-color 0s ease;
        border-radius: 1.5rem 1.5rem 0 0;
    }

    .costume-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .costume-card-body {
        background-color: var(--bs-body-bg);
        color: var(--bs-body-color);
        transition: background-color 0s ease, color 0s ease;
    }

    .costume-card-body .text-secondary {
        color: var(--bs-secondary-color, #6c757d) !important;
        transition: color 0ss ease;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="py-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold mb-0"><?php echo e($catalog ? 'Kostum: ' . $catalog->name : 'Katalog tidak ditemukan'); ?></h2>
                <a href="<?php echo e(route('home')); ?>#kategori" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <?php if(!$catalog): ?>
                <div class="alert alert-warning rounded-3">Katalog tidak ditemukan. <a href="<?php echo e(route('home')); ?>#kategori" class="alert-link">Kembali ke beranda</a>.</div>
            <?php else: ?>
                <?php if($catalog->description): ?>
                    <p class="text-muted mb-4"><?php echo e($catalog->description); ?></p>
                <?php endif; ?>

                <!-- Pencarian & Filter (tanpa pencarian kategori) -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="GET" action="<?php echo e(route('katalog.kostum')); ?>" class="row g-3 align-items-end">
                            <input type="hidden" name="cat" value="<?php echo e(request('cat')); ?>">
                            <div class="col-md-5">
                                <label class="form-label">Pencarian</label>
                                <input type="text" name="search" class="form-control" placeholder="Cari nama atau brand..." value="<?php echo e($search ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="Pria" <?php echo e(($filter_jenis_kelamin ?? '') === 'Pria' ? 'selected' : ''); ?>>Pria</option>
                                    <option value="Wanita" <?php echo e(($filter_jenis_kelamin ?? '') === 'Wanita' ? 'selected' : ''); ?>>Wanita</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Ukuran</label>
                                <select name="ukuran" class="form-select">
                                    <option value="">Semua</option>
                                    <?php $__currentLoopData = $ukuran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($uk); ?>" <?php echo e(($filter_ukuran ?? '') === $uk ? 'selected' : ''); ?>><?php echo e($uk); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Urutkan</label>
                                <select name="sort" class="form-select">
                                    <option value="id_desc" <?php echo e(($sort ?? '') === 'id_desc' ? 'selected' : ''); ?>>Terbaru</option>
                                    <option value="nama_asc" <?php echo e(($sort ?? '') === 'nama_asc' ? 'selected' : ''); ?>>Nama A - Z</option>
                                    <option value="nama_desc" <?php echo e(($sort ?? '') === 'nama_desc' ? 'selected' : ''); ?>>Nama Z - A</option>
                                    <option value="harga_asc" <?php echo e(($sort ?? '') === 'harga_asc' ? 'selected' : ''); ?>>Harga Termurah</option>
                                    <option value="harga_desc" <?php echo e(($sort ?? '') === 'harga_desc' ? 'selected' : ''); ?>>Harga Termahal</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-grid">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                            </div>
                        </form>
                        <?php if($search || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_desc')): ?>
                            <div class="mt-2">
                                <a href="<?php echo e(route('katalog.kostum', ['cat' => request('cat')])); ?>" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-x-circle"></i> Reset Pencarian
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($kostum->isEmpty()): ?>
                    <?php if($search || $filter_jenis_kelamin || $filter_ukuran || ($sort && $sort !== 'id_desc')): ?>
                        <div class="alert alert-warning rounded-3 text-center">
                            <i class="bi bi-search"></i> Pencarian tidak ditemukan. Coba ubah kata kunci atau reset.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info rounded-3">Belum ada data kostum untuk katalog ini.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="row g-3 row-cols-2 row-cols-md-4 row-cols-lg-5">
                        <?php $__currentLoopData = $kostum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <a href="#" class="card costume-card rounded-xl h-100 border-0 shadow-sm d-block text-decoration-none text-reset" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($k->id_kostum); ?>">
                                <div class="position-relative overflow-hidden costume-thumb">
                                    <?php
                                        $img = $k->gambar ?? '';
                                        $src = '';
                                        if ($img) {
                                            if (str_starts_with($img, 'http')) {
                                                $src = $img; // external URL
                                            } elseif (str_starts_with($img, 'storage/')) {
                                                $src = asset($img); // already in storage path
                                            } elseif (str_starts_with($img, 'public/')) {
                                                $src = asset(str_replace('public/', 'storage/', $img)); // convert public/ to storage/
                                            } elseif ($img) {
                                                $src = asset('storage/' . ltrim($img, '/')); // stored filename without prefix
                                            }
                                        }
                                    ?>
                                    <?php if($src): ?>
                                        <img src="<?php echo e($src); ?>" alt="<?php echo e($k->nama_kostum); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/img/no-image.png')); ?>" alt="Tidak ada gambar">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body py-2 px-3 costume-card-body">
                                    <div class="text-center">
                                        <div class="fw-bold" style="font-size:1.0rem;"><?php echo e($k->nama_kostum); ?></div>
                                        <?php if(!empty($k->judul)): ?>
                                            <div class="text-secondary" style="font-size:0.75rem;"><?php echo e($k->judul); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                        $sizes = array_filter(array_map('trim', preg_split('/[,&]/', (string)$k->ukuran_kostum)));
                                        $order = ['XS'=>1,'S'=>2,'M'=>3,'L'=>4,'XL'=>5,'XXL'=>6,'XXXL'=>7];
                                        usort($sizes, function($a,$b) use ($order){
                                            $aKey = strtoupper($a); $bKey = strtoupper($b);
                                            $aR = $order[$aKey] ?? 999; $bR = $order[$bKey] ?? 999;
                                            return $aR === $bR ? strcasecmp($aKey,$bKey) : ($aR <=> $bR);
                                        });
                                    ?>
                                    <div class="d-flex align-items-center mt-1 gap-2 flex-wrap">
                                        <div class="d-flex gap-1 flex-wrap">
                                            <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($size !== ''): ?>
                                                    <span class="badge" style="background:#374151;color:#fff;font-size:0.65rem;padding:4px 8px;border-radius:6px;"><?php echo e($size); ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php if(!empty($k->jenis_kelamin)): ?>
                                            <?php ($jk = strtolower($k->jenis_kelamin)); ?>
                                            <?php ($jkIcon = $jk === 'pria' ? 'bi-gender-male' : ($jk === 'wanita' ? 'bi-gender-female' : 'bi-gender-ambiguous')); ?>
                                            <span class="text-secondary" style="font-size:0.75rem;white-space:nowrap;"><i class="bi <?php echo e($jkIcon); ?>"></i> <?php echo e($k->jenis_kelamin); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-2 mt-2" style="font-size:0.8rem;color:#4ade80;">
                                        <strong>Rp <?php echo e(number_format((float)$k->harga_sewa, 0, ',', '.')); ?></strong> / <?php echo e($k->durasi_penyewaan); ?>

                                    </p>
                                    <p class="mb-1 text-secondary" style="font-size:0.75rem;"><i class="bi bi-tag"></i> <?php echo e($k->brand ?: '-'); ?></p>
                                    
                                    <?php if(!empty($k->domisili)): ?>
                                        <p class="mb-1 text-secondary mt-1" style="font-size:0.75rem;"><i class="bi bi-geo-alt-fill"></i> <?php echo e($k->domisili); ?></p>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>

                        <div class="modal fade" id="detailModal<?php echo e($k->id_kostum); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">Detail Kostum</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-5 text-center">
                                                <?php if($src): ?>
                                                    <img src="<?php echo e($src); ?>" alt="Gambar Kostum" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('assets/img/no-image.png')); ?>" alt="Tidak ada gambar" class="img-fluid rounded" style="aspect-ratio:1/1;object-fit:cover;">
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row mb-2"><div class="col-5 text-muted">Nama Kostum</div><div class="col-7">: <?php echo e($k->nama_kostum); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Judul</div><div class="col-7">: <?php echo e($k->judul ?: '-'); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Kategori</div><div class="col-7">: <?php echo e($k->kategori); ?></div></div>
                                                <?php if(!empty($k->jenis_kelamin)): ?>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Jenis Kelamin</div><div class="col-7">: <?php echo e($k->jenis_kelamin); ?></div></div>
                                                <?php endif; ?>
                                                <?php if(!empty($k->brand)): ?>
                                                    <div class="row mb-2"><div class="col-5 text-muted">Brand</div><div class="col-7">: <?php echo e($k->brand); ?></div></div>
                                                <?php endif; ?>
                                                <div class="row mb-2"><div class="col-5 text-muted">Harga Sewa</div><div class="col-7">: Rp <?php echo e(number_format((float)$k->harga_sewa, 0, ',', '.')); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Durasi Penyewaan</div><div class="col-7">: <?php echo e($k->durasi_penyewaan); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Ukuran</div><div class="col-7">: <?php echo e($k->ukuran_kostum); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Include</div><div class="col-7">: <?php echo nl2br(e($k->include)); ?></div></div>
                                                <div class="row mb-2"><div class="col-5 text-muted">Exclude</div><div class="col-7">: <?php echo nl2br(e($k->exclude)); ?></div></div>
                                                <div class="row"><div class="col-5 text-muted">Domisili</div><div class="col-7">: <?php echo e($k->domisili ?: '-'); ?></div></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <a href="https://docs.google.com/spreadsheets/d/1Z3OneYIfDxKs0I0rX-_yZQfFLBb-UHf4TcC4P8oqZsI/edit?fbclid=PAZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQMMjU2MjgxMDQwNTU4AAGnnjkGZH13OPjB23XrUTuuZOd1TJ_ahNiYf7BzJYyJf2lT-rjeBQvIysJ4Dx0_aem_2v0rLLt0XGAhaE4v5iCgYQ&gid=0#gid=0" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                                            <i class="bi bi-calendar3"></i> Lihat Tanggal
                                        </a>
                                        <?php if(session('user_logged_in') || auth()->check()): ?>
                                            <a href="<?php echo e(route('formulir.penyewaan', ['id_kostum' => $k->id_kostum])); ?>" class="btn btn-success">
                                                <i class="bi bi-clipboard-check"></i> Isi Formulir Penyewaan
                                            </a>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-success btn-guest-isi" data-login-url="<?php echo e(route('login')); ?>" data-bs-toggle="modal" data-bs-target="#guestLoginModal">
                                                <i class="bi bi-clipboard-check"></i> Isi Formulir Penyewaan
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <!-- Modal: Guest must login -->
    <div class="modal fade" id="guestLoginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perlu Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda harus login untuk mengisi formulir penyewaan. Masuk sekarang atau daftar jika belum punya akun.
                </div>
                <div class="modal-footer">
                    <a href="<?php echo e(route('login')); ?>" id="guestLoginModalLoginBtn" class="btn btn-primary">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">Daftar</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var guestModal = document.getElementById('guestLoginModal');
            if (!guestModal) return;
            guestModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var loginUrl = button ? button.getAttribute('data-login-url') || '<?php echo e(route('login')); ?>' : '<?php echo e(route('login')); ?>';
                var loginBtn = document.getElementById('guestLoginModalLoginBtn');
                if (loginBtn) loginBtn.setAttribute('href', loginUrl);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/katalog-kostum.blade.php ENDPATH**/ ?>