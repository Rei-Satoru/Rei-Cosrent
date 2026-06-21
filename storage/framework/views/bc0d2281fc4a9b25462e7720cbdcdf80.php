

<?php $__env->startSection('title', 'Data Katalog - Rei Cosrent'); ?>

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
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
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

        .modal-content .modal-header {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }

        .modal-content .modal-body,
        .modal-content .modal-footer,
        .modal-content form {
            background-color: #0f172af5 !important;
        }

        /* Ensure form controls inside modals use the same dark surface */
        .modal-content .modal-body .form-control,
        .modal-content .modal-body .form-select,
        .modal-content .modal-body textarea,
        .modal-content .modal-body input,
        .modal-content .modal-body select,
        .modal-content .modal-body .form-check,
        .modal-content .modal-body .form-check-input,
        .modal-content .modal-body .form-floating > .form-control,
        .modal-content .modal-body .form-control:focus,
        .modal-content .modal-body textarea:focus,
        .modal-content .modal-body input:focus,
        .modal-content .modal-body .form-select:focus,
        .modal-content .modal-body .form-control[readonly],
        .modal-content .modal-body .form-control[disabled] {
            background-color: #0f172af5 !important;
            color: var(--bs-body-color) !important;
            border-color: rgba(148, 163, 184, 0.12) !important;
            box-shadow: none !important;
        }

        .modal-content .modal-body ::placeholder {
            color: rgba(148, 163, 184, 0.6) !important;
        }

        [data-bs-theme="dark"] .orders-table thead th,
        [data-bs-theme="dark"] .orders-table tbody td {
            border-right-color: rgba(148, 163, 184, 0.16);
        }

        [data-bs-theme="dark"] .orders-table tbody tr:hover td {
            background: rgba(59, 130, 246, 0.14);
        }

        .table img {
            max-width: 80px;
            height: auto;
        }

        .katalog-thumb {
            cursor: zoom-in;
            transition: transform .12s ease;
        }

        .katalog-thumb:hover {
            transform: scale(1.02);
        }

        footer {
            transition: background-color 1000ms;
        }

        body[data-bs-theme="light"] footer {
            background-color: #0d6efd !important;
        }

        body[data-bs-theme="dark"] footer {
            background-color: #8a2be2 !important;
        }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Katalog</h2>
                <p class="text-muted mb-0 small">Kelola daftar katalog kostum yang tampil di halaman utama.</p>
            </div>
            <div class="d-grid d-sm-block">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Tambah Katalog
                </button>
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

        <?php if($katalog->count() > 0): ?>
            <div class="card mb-3" style="background-color: #0f172af5; border: none;">
                <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 w-100">
                        <div class="input-group" style="background-color: #94a3b829; border-radius: 0.75rem; border: 1px solid rgba(148,163,184,0.35);">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                            <input id="search-admin-katalog" type="search" class="form-control border-0 bg-transparent" placeholder="Cari nama katalog, kategori, deskripsi..." aria-label="Cari katalog">
                        </div>
                        <select id="sort-admin-katalog" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6;">
                            <option value="">Urutkan data katalog</option>
                            <option value="1:string:asc">Nama Katalog A–Z</option>
                            <option value="1:string:desc">Nama Katalog Z–A</option>
                            <option value="2:string:asc">Kategori A–Z</option>
                            <option value="2:string:desc">Kategori Z–A</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button id="reset-admin-katalog" type="button" class="btn btn-light w-100">Reset Pencarian</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="adminKatalogTable" class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Katalog</th>
                            <th>Kategori</th>
                            <th class="d-none d-md-table-cell">Deskripsi</th>
                                <th class="d-none d-md-table-cell">Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $katalog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($item->name); ?></td>
                                <td><?php echo e($item->kategori); ?></td>
                                <td class="d-none d-md-table-cell"><?php echo e(Str::limit($item->description, 50)); ?></td>
                                <td class="d-none d-md-table-cell">
                                    <?php if(!empty($item->image)): ?>
                                        <?php
                                            $imgRaw = $item->image ?? '';
                                            if (str_starts_with($imgRaw, 'http')) {
                                                $katalogImageSrc = $imgRaw;
                                            } elseif (str_starts_with($imgRaw, '/storage/')) {
                                                $katalogImageSrc = asset(ltrim($imgRaw, '/'));
                                            } elseif (str_starts_with($imgRaw, 'storage/')) {
                                                $katalogImageSrc = asset($imgRaw);
                                            } elseif ($imgRaw) {
                                                $katalogImageSrc = asset('storage/' . $imgRaw);
                                            } else {
                                                $katalogImageSrc = null;
                                            }
                                        ?>
                                        <button type="button" class="btn p-0 border-0 bg-transparent js-katalog-image-preview" data-image-src="<?php echo e($katalogImageSrc); ?>" data-image-title="Gambar Katalog: <?php echo e($item->name); ?>" aria-label="Lihat gambar katalog <?php echo e($item->name); ?>">
                                            <img src="<?php echo e($katalogImageSrc); ?>" alt="<?php echo e($item->name); ?>" class="katalog-thumb" style="max-width:80px;">
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($item->id); ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <form action="<?php echo e(route('admin.katalog.delete', $item->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus katalog ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal<?php echo e($item->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-white">
                                            <h5 class="modal-title">Edit Katalog</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.katalog.update')); ?>" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Katalog</label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo e($item->name); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kategori</label>
                                                    <input type="text" name="kategori" class="form-control" value="<?php echo e($item->kategori); ?>" placeholder="Contoh: Anime, Game, Movie" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="description" class="form-control" rows="3" required><?php echo e($item->description); ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Gambar Katalog (Rasio 1:1)</label>
                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->image)): ?>
                                                        <small class="text-muted">Gambar saat ini: <?php echo e(basename($item->image)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <?php if($search || $filter_kategori || ($sort && $sort !== 'id_desc')): ?>
                    <div class="alert alert-warning text-center">
                        <i class="bi bi-search"></i> Pencarian tidak ditemukan. Coba ubah kata kunci atau reset.
                        <div class="mt-2">
                            <a href="<?php echo e(route('admin.data-katalog')); ?>" class="btn btn-sm btn-secondary">
                                <i class="bi bi-x-circle"></i> Reset Pencarian
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada data katalog. Silakan tambahkan data baru.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Modal Preview Gambar Katalog (reusable) -->
<div class="modal fade" id="adminKatalogImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="adminKatalogImagePreviewTitle">Gambar Katalog</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="adminKatalogImagePreviewImg" src="" alt="Preview Gambar Katalog" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Katalog Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.katalog.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Katalog</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Anime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Anime, Game, Movie" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat katalog" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar Katalog (Rasio 1:1)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Auto-hide alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });

        function showAdminKatalogImagePreview(src, title) {
            const img = document.getElementById('adminKatalogImagePreviewImg');
            const titleEl = document.getElementById('adminKatalogImagePreviewTitle');
            if (!img) return;

            img.src = src || '';
            if (titleEl) titleEl.textContent = title || 'Gambar Katalog';

            const modalEl = document.getElementById('adminKatalogImagePreviewModal');
            if (!modalEl || !window.bootstrap) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-katalog-image-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-image-src');
                const title = btn.getAttribute('data-image-title');
                showAdminKatalogImagePreview(src, title);
            });
        });

        const modalEl = document.getElementById('adminKatalogImagePreviewModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                const img = document.getElementById('adminKatalogImagePreviewImg');
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

        initAdminTableSearchSort('adminKatalogTable','search-admin-katalog','sort-admin-katalog','reset-admin-katalog');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-katalog.blade.php ENDPATH**/ ?>