

<?php $__env->startSection('title', 'Kelola Data Pengguna - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .page-title {
    color: #0056b3;
    transition: color 0s ease;
    }

    [data-bs-theme="dark"] .page-title {
        color: #a855f7;
    }

    [data-bs-theme="light"] .page-title {
        color: #0056b3;
    }
    
    table th {
        background-color: var(--bs-primary);
        color: white;
        text-align: center;
        font-size: 1.0rem;
    }

    table td {
        font-size: 0.95rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .table-responsive { overflow-x: auto; }

    .avatar-thumb {
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .avatar-thumb:hover {
        transform: scale(1.02);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Kelola Data Pengguna</h1>
        <p class="text-muted">Edit atau hapus akun pengguna</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">

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

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                    <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div></div>
                </div>
                <?php if(isset($users) && $users->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Nick Name</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Nomor Telepon</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Gambar Profil</th>
                                    <th style="width: 220px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($user->username); ?></td>
                                        <td><?php echo e($user->nick_name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e($user->alamat); ?></td>
                                        <td><?php echo e($user->nomor_telepon); ?></td>
                                        <td><?php echo e($user->jenis_kelamin); ?></td>
                                        <td>
                                            <?php
                                                $avatarPath = $user->gambar_profil ? asset('storage/' . $user->gambar_profil) : null;
                                            ?>
                                            <?php if($avatarPath): ?>
                                                <button type="button" class="btn p-0 border-0 bg-transparent js-user-avatar-preview" data-avatar-src="<?php echo e($avatarPath); ?>" data-avatar-title="Gambar Profil: <?php echo e($user->username); ?>" aria-label="Lihat gambar profil <?php echo e($user->username); ?>">
                                                    <img src="<?php echo e($avatarPath); ?>" alt="Avatar" class="avatar-thumb" style="width:72px; height:72px; object-fit:cover; border:1px solid var(--bs-border-color); border-radius:0;">
                                                </button>
                                            <?php else: ?>
                                                <i class="bi bi-person-square" style="font-size: 2rem; color: var(--bs-body-color);"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo e($user->id); ?>">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </button>
                                                <form method="POST" action="<?php echo e(route('admin.pengguna.delete', $user->id)); ?>" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini? Tindakan tidak dapat dibatalkan.');"><i class="bi bi-trash"></i> Hapus</button>
                                                </form>
                                            </div>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editUserModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="editUserLabel<?php echo e($user->id); ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-warning text-white">
                                                            <h5 class="modal-title" id="editUserLabel<?php echo e($user->id); ?>">Edit Pengguna #<?php echo e($user->id); ?></h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST" action="<?php echo e(route('admin.pengguna.update')); ?>" enctype="multipart/form-data" class="user-edit-section text-start" data-user-id="<?php echo e($user->id); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                                                                <input type="hidden" name="remove_photo" value="0">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Username</label>
                                                                        <input type="text" name="username" class="form-control" value="<?php echo e(old('username', $user->username)); ?>" required>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Nick Name</label>
                                                                        <input type="text" name="nick_name" class="form-control" value="<?php echo e(old('nick_name', $user->nick_name)); ?>">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Email</label>
                                                                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Nomor Telepon</label>
                                                                        <input type="text" name="nomor_telepon" class="form-control" value="<?php echo e(old('nomor_telepon', $user->nomor_telepon)); ?>">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Alamat</label>
                                                                        <textarea name="alamat" class="form-control" rows="3"><?php echo e(old('alamat', $user->alamat)); ?></textarea>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold">Password (opsional, minimal 8 karakter)</label>
                                                                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah" minlength="8">
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div class="row g-3 align-items-start">
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Gambar Profil</label>
                                                                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                                                                    <img class="user-preview <?php echo e($user->gambar_profil ? '' : 'd-none'); ?>" src="<?php echo e($user->gambar_profil ? asset('storage/' . $user->gambar_profil) : ''); ?>" alt="Preview" style="width:140px; height:140px; object-fit:cover; border:1px solid var(--bs-border-color); border-radius:0;">
                                                                                    <div class="user-fallback <?php echo e($user->gambar_profil ? 'd-none' : ''); ?>" style="width:140px; height:140px; display:flex; align-items:center; justify-content:center; border:2px dashed var(--bs-border-color); border-radius:0;">
                                                                                        <i class="bi bi-person-square" style="font-size: 2.25rem; color: var(--bs-body-color);"></i>
                                                                                    </div>
                                                                                    <div class="d-flex flex-column gap-2">
                                                                                        <button type="button" class="btn btn-outline-primary btn-sm btn-upload-user"><i class="bi bi-upload"></i> Unggah</button>
                                                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-mark-delete-user" style="<?php echo e($user->gambar_profil ? '' : 'display:none;'); ?>"><i class="bi bi-trash"></i> Hapus</button>
                                                                                        <span class="delete-photo-note text-danger small" style="display:none;">Foto akan dihapus setelah disimpan.</span>
                                                                                    </div>
                                                                                </div>
                                                                                <input type="file" name="gambar_profil" class="d-none" accept="image/*">
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-semibold">Jenis Kelamin</label>
                                                                                <div class="d-flex align-items-center gap-3">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkPria<?php echo e($user->id); ?>" value="Pria" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Pria' ? 'checked' : ''); ?>>
                                                                                        <label class="form-check-label" for="jkPria<?php echo e($user->id); ?>">Pria</label>
                                                                                    </div>
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkWanita<?php echo e($user->id); ?>" value="Wanita" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin) == 'Wanita' ? 'checked' : ''); ?>>
                                                                                        <label class="form-check-label" for="jkWanita<?php echo e($user->id); ?>">Wanita</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Simpan Perubahan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center"><i class="bi bi-info-circle"></i> Belum ada pengguna.</div>
                <?php endif; ?>
        </div>
    </div>
</section>

<!-- Modal Preview Foto Profil (reusable) -->
<div class="modal fade" id="adminUserAvatarPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminUserAvatarPreviewTitle">Gambar Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="adminUserAvatarPreviewImg" src="" alt="Preview Gambar Profil" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.user-edit-section').forEach(section => {
            const uploadBtn = section.querySelector('.btn-upload-user');
            const fileInput = section.querySelector('input[type=file][name="gambar_profil"]');
            const previewImg = section.querySelector('img.user-preview');
            const fallbackIcon = section.querySelector('.user-fallback');
            const deleteBtn = section.querySelector('.btn-mark-delete-user');
            const removePhotoInput = section.querySelector('input[name="remove_photo"]');
            const deleteNote = section.querySelector('.delete-photo-note');

            function syncAvatarDisplay(hasImage) {
                if (hasImage) {
                    previewImg && previewImg.classList.remove('d-none');
                    fallbackIcon && fallbackIcon.classList.add('d-none');
                    deleteBtn && (deleteBtn.style.display = '');
                } else {
                    previewImg && previewImg.classList.add('d-none');
                    fallbackIcon && fallbackIcon.classList.remove('d-none');
                    deleteBtn && (deleteBtn.style.display = 'none');
                }
            }

            if (uploadBtn && fileInput) {
                uploadBtn.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    if (previewImg) previewImg.src = url;
                    syncAvatarDisplay(true);
                    // Reset delete flag if uploading new image
                    if (removePhotoInput) removePhotoInput.value = '0';
                    if (deleteNote) deleteNote.style.display = 'none';
                });
            }

            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => {
                    // Mark for deletion, clear file input
                    if (removePhotoInput) removePhotoInput.value = '1';
                    if (fileInput) fileInput.value = '';
                    if (deleteNote) deleteNote.style.display = '';
                    syncAvatarDisplay(false);
                });
            }
        });

        function showAdminUserAvatarPreview(src, title) {
            const img = document.getElementById('adminUserAvatarPreviewImg');
            const titleEl = document.getElementById('adminUserAvatarPreviewTitle');
            if (!img) return;

            img.src = src || '';
            if (titleEl) titleEl.textContent = title || 'Gambar Profil';

            const modalEl = document.getElementById('adminUserAvatarPreviewModal');
            if (!modalEl || !window.bootstrap) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-user-avatar-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-avatar-src');
                const title = btn.getAttribute('data-avatar-title');
                showAdminUserAvatarPreview(src, title);
            });
        });

        const modalEl = document.getElementById('adminUserAvatarPreviewModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                const img = document.getElementById('adminUserAvatarPreviewImg');
                if (img) img.src = '';
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-pengguna.blade.php ENDPATH**/ ?>