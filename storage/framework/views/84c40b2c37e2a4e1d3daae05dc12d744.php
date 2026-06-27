

<?php $__env->startSection('title', 'Data Pengguna - Rei Cosrent'); ?>

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
        text-align: center;
    }
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
        font-size: 0.95rem;
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

    .ellipsis-cell {
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-user-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--bs-body-color);
    }

    .detail-user-table th,
    .detail-user-table td {
        padding: 0.75rem 0.5rem;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        vertical-align: top;
        color: var(--bs-body-color);
        background-color: #0f172af5;
    }

    .detail-user-table th {
        width: 180px;
        font-weight: 600;
        white-space: nowrap;
    }

    .detail-user-table td {
        word-break: break-word;
    }

    .detail-user-table tr:last-child th,
    .detail-user-table tr:last-child td {
        border-bottom: 0;
    }

    .detail-user-hr {
        border: 0;
        border-top: 1px solid rgba(148, 163, 184, 0.22);
        margin: 1.5rem 0;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Pengguna</h2>
                <p class="text-muted mb-0 small" style="color:#ffffff !important;">Edit atau hapus akun pengguna</p>
            </div>
            <div class="d-grid d-sm-block">
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

        <?php if(isset($users) && $users->count() > 0): ?>
            <div class="card mb-3" style="background-color: #0f172af5; border: none;">
                <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 w-100">
                        <div class="input-group" style="background-color: #94a3b829; border-radius: 0.75rem; border: 1px solid rgba(148,163,184,0.35);">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                            <input id="search-admin-users" type="search" class="form-control border-0 bg-transparent" placeholder="Cari username, email, nama, Instagram, alamat, status..." aria-label="Cari pengguna">
                        </div>
                        <select id="sort-admin-users" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6;">
                            <option value="">Urutkan data pengguna</option>
                            <option value="1:string:asc">Username A–Z</option>
                            <option value="1:string:desc">Username Z–A</option>
                            <option value="2:string:asc">Nama Lengkap A–Z</option>
                            <option value="2:string:desc">Nama Lengkap Z–A</option>
                            <option value="3:string:asc">Email A–Z</option>
                            <option value="3:string:desc">Email Z–A</option>
                            <option value="7:string:asc">Jenis Kelamin A–Z</option>
                            <option value="7:string:desc">Jenis Kelamin Z–A</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button id="reset-admin-users" type="button" class="btn btn-light w-100">Reset Pencarian</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="adminUsersTable" class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                                    <th>Nama Pengguna</th>
                                    <th class="d-none d-md-table-cell">Nama Lengkap</th>
                                    <th>Email</th>
                                    <th class="d-none d-md-table-cell">Instagram</th>
                                    <th class="d-none d-md-table-cell">Alamat</th>
                                    <th class="d-none d-md-table-cell">Nomor Telepon</th>
                                    <th class="d-none d-md-table-cell">Jenis Kelamin</th>
                                    <th style="width: 220px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($user->username); ?></td>
                                        <td class="d-none d-md-table-cell"><?php echo e($user->nick_name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td class="d-none d-md-table-cell"><?php echo e($user->instagram ?: '-'); ?></td>
                                        <td class="d-none d-md-table-cell ellipsis-cell"><?php echo e($user->alamat ?: '-'); ?></td>
                                        <td class="d-none d-md-table-cell"><?php echo e($user->nomor_telepon); ?></td>
                                        <td class="d-none d-md-table-cell"><?php echo e($user->jenis_kelamin); ?></td>
                                        <?php
                                            $avatarPath = $user->gambar_profil ? asset('storage/' . $user->gambar_profil) : null;
                                        ?>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-info btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#detailUserModal<?php echo e($user->id); ?>">
                                                    <i class="bi bi-person-lines-fill"></i> Detail
                                                </button>
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
                                                            <h5 class="modal-title" id="editUserLabel<?php echo e($user->id); ?>">Ganti Password Pengguna</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form method="POST" action="<?php echo e(route('admin.pengguna.update')); ?>" class="text-start">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?php echo e($user->id); ?>">

                                                        <div class="mb-3">
                                                        <label class="form-label fw-semibold">Password Baru</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Password baru (min 8)" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Konfirmasi Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                    <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Simpan Password</button>
                                                </div>
                                            </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="detailUserModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="detailUserLabel<?php echo e($user->id); ?>" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title" id="detailUserLabel<?php echo e($user->id); ?>">Detail Pengguna</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-4">
                                                                <div class="col-md-4 text-center">
                                                                    <?php if($avatarPath): ?>
                                                                        <img src="<?php echo e($avatarPath); ?>" alt="Avatar <?php echo e($user->username); ?>" class="img-fluid rounded mb-3" style="max-height: 220px; object-fit: cover; border: 1px solid var(--bs-border-color);">
                                                                    <?php else: ?>
                                                                        <div class="d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded mb-3" style="height: 220px;">
                                                                            <i class="bi bi-person-circle" style="font-size: 4rem; color: var(--bs-body-color);"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <table class="table detail-user-table mb-0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th style="width: 160px;">Username</th>
                                                                                <td><?php echo e($user->username); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Nama Lengkap</th>
                                                                                <td><?php echo e($user->nick_name ?: '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Email</th>
                                                                                <td><?php echo e($user->email); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Instagram</th>
                                                                                <td><?php echo e($user->instagram ?: '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Alamat</th>
                                                                                <td><?php echo e($user->alamat ?: '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Nomor Telepon</th>
                                                                                <td><?php echo e($user->nomor_telepon ?: '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Jenis Kelamin</th>
                                                                                <td><?php echo e($user->jenis_kelamin ?: '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Dibuat</th>
                                                                                <td><?php echo e($user->created_at ? $user->created_at->format('d M Y H:i') : '-'); ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Diupdate</th>
                                                                                <td><?php echo e($user->updated_at ? $user->updated_at->format('d M Y H:i') : '-'); ?></td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
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
            <div class="alert alert-info text-center mb-0"><i class="bi bi-info-circle"></i> Belum ada pengguna.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal Preview Foto Profil (reusable) -->
<div class="modal fade" id="adminUserAvatarPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="adminUserAvatarPreviewTitle">Gambar Profil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function normalizeText(value) {
            return String(value || '').trim().toLowerCase();
        }

        function parseDateValue(value) {
            const text = String(value || '').trim();
            if (!text) return 0;
            const parsed = Date.parse(text);
            if (!Number.isNaN(parsed)) return parsed;
            const months = {jan:0,feb:1,mar:2,apr:3,may:4,jun:5,jul:6,aug:7,sep:8,oct:9,nov:10,dec:11};
            const parts = text.replace(/,/g, '').split(/\s+/);
            if (parts.length >= 3) {
                const day = parseInt(parts[0], 10);
                const month = months[parts[1].slice(0,3).toLowerCase()] ?? 0;
                const year = parseInt(parts[2], 10);
                if (!Number.isNaN(day) && !Number.isNaN(year)) {
                    return new Date(year, month, day).getTime();
                }
            }
            return 0;
        }

        function parseCurrencyValue(value) {
            const text = String(value || '');
            const digits = text.replace(/[^\d.-]/g, '');
            const parsed = parseFloat(digits);
            return Number.isNaN(parsed) ? 0 : parsed;
        }

        function compareValues(a, b, type, direction) {
            let result = 0;
            if (type === 'date') {
                result = parseDateValue(a) - parseDateValue(b);
            } else if (type === 'currency' || type === 'numeric') {
                result = parseCurrencyValue(a) - parseCurrencyValue(b);
            } else {
                result = normalizeText(a).localeCompare(normalizeText(b), undefined, { numeric: true, sensitivity: 'base' });
            }
            return direction === 'desc' ? -result : result;
        }

        function initAdminTableSearchSort(tableId, searchId, sortId, resetId) {
            const table = document.getElementById(tableId);
            const searchInput = document.getElementById(searchId);
            const sortSelect = document.getElementById(sortId);
            const resetButton = resetId ? document.getElementById(resetId) : null;
            if (!table || !searchInput || !sortSelect) return;

            const tbody = table.tBodies[0];
            if (!tbody) return;
            const rows = Array.from(tbody.rows);

            function updateRows() {
                const query = normalizeText(searchInput.value);
                const [colIndex, type, direction] = sortSelect.value.split(':');
                let filtered = rows.filter(row => normalizeText(row.textContent).includes(query));
                if (colIndex !== undefined && colIndex !== '' && type && direction) {
                    const index = parseInt(colIndex, 10);
                    filtered.sort((a, b) => compareValues(
                        a.cells[index]?.textContent || '',
                        b.cells[index]?.textContent || '',
                        type,
                        direction
                    ));
                }
                tbody.innerHTML = '';
                filtered.forEach(row => tbody.appendChild(row));
            }

            searchInput.addEventListener('input', updateRows);
            sortSelect.addEventListener('change', updateRows);
            if (resetButton) {
                resetButton.addEventListener('click', () => {
                    searchInput.value = '';
                    sortSelect.value = '';
                    updateRows();
                });
            }
            updateRows();
        }

        initAdminTableSearchSort('adminUsersTable','search-admin-users','sort-admin-users','reset-admin-users');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-pengguna.blade.php ENDPATH**/ ?>