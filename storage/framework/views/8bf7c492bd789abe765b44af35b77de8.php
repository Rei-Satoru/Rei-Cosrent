

<?php $__env->startSection('title', 'Data Denda & Kerusakan - Rei Cosrent'); ?>

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
    table th { background-color: var(--bs-primary); color: #fff; text-align: center; }
    .action-buttons { display:flex; gap:8px; justify-content:center; }
    .thumb { max-width:100px; max-height:80px; object-fit:cover; }
    .page-title { color: #0056b3; transition: color 0s ease; }

    .bukti-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0;
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .bukti-thumb:hover { transform: scale(1.02); }

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

    [data-bs-theme="dark"] .page-title { color: #a855f7; }
    [data-bs-theme="light"] .page-title { color: #0056b3; }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Denda & Kerusakan</h2>
                <p class="text-muted mb-0 small">Kelola denda dan laporan kerusakan kostum.</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-lg"></i> Tambah
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

        <div class="card mb-3" style="background-color: #0f172af5; border: none;">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 w-100">
                    <div class="input-group" style="background-color: #94a3b829; border-radius: 0.75rem; border: 1px solid rgba(148,163,184,0.35);">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                        <input id="search-admin-denda" type="search" class="form-control border-0 bg-transparent" placeholder="Cari nama, nama kostum, jenis denda, status..." aria-label="Cari denda">
                    </div>
                        <select id="sort-admin-denda" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6;">
                        <option value="">Urutkan data denda</option>
                        <option value="1:string:asc">Nama A–Z</option>
                        <option value="1:string:desc">Nama Z–A</option>
                        <option value="2:string:asc">Nama Kostum A–Z</option>
                        <option value="2:string:desc">Nama Kostum Z–A</option>
                        <option value="3:string:asc">Jenis Denda A–Z</option>
                        <option value="3:string:desc">Jenis Denda Z–A</option>
                        <option value="5:currency:asc">Jumlah Terendah</option>
                        <option value="5:currency:desc">Jumlah Tertinggi</option>
                        <option value="6:string:asc">Status A–Z</option>
                        <option value="6:string:desc">Status Z–A</option>
                        <option value="7:date:asc">Dibuat Terawal</option>
                        <option value="7:date:desc">Dibuat Terbaru</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <button id="reset-admin-denda" type="button" class="btn btn-light w-100">Reset Pencarian</button>
                </div>
            </div>

            <?php
                // Build a map of unique names -> nama_kostum for selects/datalists
                $nameMap = [];
                if (isset($formulir) && is_iterable($formulir)) {
                    foreach ($formulir as $f) {
                        if (!isset($nameMap[$f->nama])) {
                            $nameMap[$f->nama] = $f->nama_kostum ?? '';
                        }
                    }
                }
            ?>

            <?php if(count($dendas) > 0): ?>
            <div class="table-responsive">
                <table id="adminDendaTable" class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Pengguna</th>
                            <th>Nama Kostum</th>
                            <th>Jenis Denda</th>
                            <th class="d-none d-md-table-cell">Keterangan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Dibuat</th>
                            <th class="d-none d-md-table-cell">Bukti Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="denda-row-<?php echo e($item->id); ?>" data-nama="<?php echo e(e($item->nama)); ?>" data-nama_kostum="<?php echo e(e($item->nama_kostum)); ?>" data-jenis_denda="<?php echo e(e($item->jenis_denda)); ?>" data-keterangan="<?php echo e(e($item->keterangan)); ?>" data-jumlah_denda="<?php echo e($item->jumlah_denda); ?>">
                            <td class="text-center"><?php echo e($loop->iteration); ?></td>
                            <td class="field-nama"><?php echo e($item->nama); ?></td>
                            <td class="field-nama_kostum"><?php echo e($item->nama_kostum); ?></td>
                            <td class="field-jenis_denda"><?php echo e($item->jenis_denda); ?></td>
                            <td class="d-none d-md-table-cell field-keterangan"><div style="max-height:120px;overflow:auto"><?php echo nl2br(e($item->keterangan)); ?></div></td>
                            <td class="field-jumlah_denda text-end">Rp<?php echo e($item->jumlah_denda ? number_format($item->jumlah_denda,0,',','.') : '-'); ?></td>
                            <?php
                                $st = strtolower($item->status ?? '');
                                $statusClassMap = [
                                    'proses' => 'bg-warning text-dark',
                                    'revisi' => 'bg-secondary',
                                    'diterima' => 'bg-info text-dark',
                                    'selesai' => 'bg-success',
                                    'dibatalkan' => 'bg-secondary',
                                    'belum lunas' => 'bg-warning text-dark',
                                    'lunas' => 'bg-success text-white',
                                ];
                                $statusIconMap = [
                                    'proses' => 'bi-clock',
                                    'revisi' => 'bi-pencil-square',
                                    'diterima' => 'bi-person-check',
                                    'selesai' => 'bi-check-circle',
                                    'dibatalkan' => 'bi-x-circle',
                                    'belum lunas' => 'bi-exclamation-circle',
                                    'lunas' => 'bi-check2',
                                ];
                                $badgeClass = $statusClassMap[$st] ?? 'bg-dark text-white';
                                $badgeIcon = $statusIconMap[$st] ?? 'bi-info-circle';
                            ?>
                            <td class="field-status text-center"><span class="badge <?php echo e($badgeClass); ?>"><i class="bi <?php echo e($badgeIcon); ?> me-1"></i> <?php echo e(ucfirst($item->status)); ?></span></td>
                            <td class="d-none d-md-table-cell text-center"><?php echo e($item->created_at ? $item->created_at->format('d/m/Y') : '-'); ?></td>
                            <td class="d-none d-md-table-cell text-center">
                                <?php
                                    $displayBuktiPath = null;
                                    $displayExt = null;
                                    $foundBuktiPath = null;
                                    try {
                                        $files = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                        foreach ($files as $f) {
                                            if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_denda_' . $item->id . '_')) {
                                                $foundBuktiPath = $f;
                                                break;
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        $foundBuktiPath = null;
                                    }

                                    if (!empty($item->bukti_pembayaran)) {
                                        $displayBuktiPath = asset('storage/' . $item->bukti_pembayaran);
                                        $displayExt = strtolower(pathinfo($item->bukti_pembayaran, PATHINFO_EXTENSION));
                                    } elseif (!empty($foundBuktiPath)) {
                                        $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                        $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                    }
                                ?>

                                <?php if($displayBuktiPath): ?>
                                    <?php if($displayExt === 'pdf'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminDendaBuktiModal-<?php echo e($item->id); ?>" title="Lihat Bukti (PDF)">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#adminDendaBuktiModal-<?php echo e($item->id); ?>" aria-label="Lihat bukti pembayaran denda">
                                            <img src="<?php echo e($displayBuktiPath); ?>" alt="Bukti Pembayaran Denda" class="bukti-thumb">
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons" id="action-buttons-<?php echo e($item->id); ?>">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#dendaDetailModal-<?php echo e($item->id); ?>" title="Detail">
                                        <i class="bi bi-info-circle"></i> Detail
                                    </button>
                                    <button class="btn btn-sm btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#editModal<?php echo e($item->id); ?>"><i class="bi bi-pencil"></i> Edit</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($item->id); ?>"><i class="bi bi-trash"></i> Hapus</button>
                                </div>
                            </td>
                        </tr>


                        <!-- Detail Modal -->
                        <div class="modal fade" id="dendaDetailModal-<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="dendaDetailLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="dendaDetailLabel-<?php echo e($item->id); ?>">
                                            <i class="bi bi-card-list"></i> Detail Denda
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br><?php echo e($item->nama ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br><?php echo e($item->nama_kostum ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Dibuat:</strong><br><?php echo e($item->created_at ? $item->created_at->format('d M Y H:i') : '-'); ?></div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Jenis Denda:</strong><br><?php echo e($item->jenis_denda ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Keterangan:</strong><br><?php echo nl2br(e($item->keterangan)); ?></div>
                                                <div class="mb-2"><strong>Jumlah Denda:</strong><br>Rp<?php echo e($item->jumlah_denda ? number_format($item->jumlah_denda,0,',','.') : '-'); ?></div>
                                            </div>
                                        </div>

                                        <?php
                                            $buktiFotos = collect([
                                                $item->bukti_foto_1 ?? null,
                                                $item->bukti_foto_2 ?? null,
                                                $item->bukti_foto_3 ?? null,
                                                $item->bukti_foto_4 ?? null,
                                                $item->bukti_foto_5 ?? null,
                                            ])->filter();
                                        ?>
                                        <hr>
                                        <div>
                                            <strong>Bukti Foto:</strong>
                                            <?php if($buktiFotos->isNotEmpty()): ?>
                                                <div class="row g-2 mt-1">
                                                    <?php $__currentLoopData = $buktiFotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="col-6 col-md-4 col-lg-3">
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 d-block" onclick="showDendaBuktiFotoPreview('<?php echo e(asset('storage/' . $bf)); ?>')" aria-label="Lihat bukti foto">
                                                                <img src="<?php echo e(asset('storage/' . $bf)); ?>" alt="Bukti Foto" class="img-fluid rounded" style="max-height:160px; object-fit:cover; width:100%; cursor:pointer;" onerror="this.outerHTML = '<a href=\'<?php echo e(asset('storage/' . $bf)); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary btn-sm\'>Lihat File</a>'">
                                                            </button>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-muted mt-1">Tidak tersedia</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Pembayaran Modal -->
                        <div class="modal fade" id="adminDendaBuktiModal-<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="adminDendaBuktiLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="adminDendaBuktiLabel-<?php echo e($item->id); ?>">Bukti Pembayaran</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                            $modalBuktiPath = null;
                                            $modalExt = null;
                                            $modalFound = null;
                                            try {
                                                $modalFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('denda');
                                                foreach ($modalFiles as $mf) {
                                                    if (\Illuminate\Support\Str::startsWith(basename($mf), 'bukti_denda_' . $item->id . '_')) {
                                                        $modalFound = $mf;
                                                        break;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $modalFound = null;
                                            }

                                            if (!empty($item->bukti_pembayaran)) {
                                                $modalBuktiPath = asset('storage/' . $item->bukti_pembayaran);
                                                $modalExt = strtolower(pathinfo($item->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (!empty($modalFound)) {
                                                $modalBuktiPath = asset('storage/' . $modalFound);
                                                $modalExt = strtolower(pathinfo($modalFound, PATHINFO_EXTENSION));
                                            }
                                        ?>

                                        <?php if($modalBuktiPath): ?>
                                            <?php if($modalExt === 'pdf'): ?>
                                                <embed src="<?php echo e($modalBuktiPath); ?>" type="application/pdf" width="100%" height="600px" />
                                            <?php else: ?>
                                                <img src="<?php echo e($modalBuktiPath); ?>" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'<?php echo e($modalBuktiPath); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-secondary">Belum ada bukti pembayaran untuk denda ini.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Edit Denda</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.denda.update', $item->id)); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama</label>
                                                    <div class="d-flex gap-2">
                                                        <select id="edit-nama-select-<?php echo e($item->id); ?>" class="form-select" style="max-width: 45%;" onchange="editSelectChange(<?php echo e($item->id); ?>)">
                                                            <option value="">-- Pilih dari daftar --</option>
                                                            <?php $__currentLoopData = $nameMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e(e($n)); ?>" <?php echo e($item->nama == $n ? 'selected' : ''); ?>><?php echo e($n); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                        <div style="flex:1">
                                                            <input id="edit-nama-input-<?php echo e($item->id); ?>" name="nama" class="form-control" list="formulir-names-<?php echo e($item->id); ?>" placeholder="Atau ketik untuk mencari nama..." value="<?php echo e(e($item->nama)); ?>" autocomplete="off" oninput="editInputChange(<?php echo e($item->id); ?>)">
                                                            <datalist id="formulir-names-<?php echo e($item->id); ?>">
                                                                <?php $__currentLoopData = $nameMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e(e($n)); ?>"></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </datalist>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Kostum</label>
                                                    <input type="text" id="edit-nama-kostum-<?php echo e($item->id); ?>" name="nama_kostum" class="form-control" value="<?php echo e(e($item->nama_kostum)); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jenis Denda</label>
                                                    <input type="text" name="jenis_denda" class="form-control" value="<?php echo e(e($item->jenis_denda)); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Jumlah Denda (angka)</label>
                                                    <input type="number" step="0.01" name="jumlah_denda" class="form-control" value="<?php echo e($item->jumlah_denda); ?>">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea name="keterangan" class="form-control" rows="4"><?php echo e(e($item->keterangan)); ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 1 (opsional)</label>
                                                    <input type="file" name="bukti_foto_1" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->bukti_foto_1)): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto_1)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 2 (opsional)</label>
                                                    <input type="file" name="bukti_foto_2" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->bukti_foto_2)): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto_2)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 3 (opsional)</label>
                                                    <input type="file" name="bukti_foto_3" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->bukti_foto_3)): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto_3)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 4 (opsional)</label>
                                                    <input type="file" name="bukti_foto_4" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->bukti_foto_4)): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto_4)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Foto Bukti 5 (opsional)</label>
                                                    <input type="file" name="bukti_foto_5" class="form-control" accept="image/*">
                                                    <?php if(!empty($item->bukti_foto_5)): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto_5)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
                                                    <?php endif; ?>
                                                </div>
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

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Hapus Data</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.denda.destroy', $item->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body">
                                            <p>Anda yakin ingin menghapus data denda ini?</p>
                                            <div><strong><?php echo e($item->nama); ?></strong> - <span class="text-muted"><?php echo e($item->nama_kostum); ?></span></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
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
                <div class="alert alert-info text-center"><i class="bi bi-info-circle"></i> Belum ada data denda.</div>
            <?php endif; ?>

        </div>
    </div>
</section>

    <!-- Bukti Foto Preview Modal (must be inside content section) -->
    <div class="modal fade" id="dendaBuktiFotoPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Bukti Foto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="dendaBuktiFotoPreviewImg" src="" alt="Preview" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Data Denda / Kerusakan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('admin.denda.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama (pilih dari formulir)</label>
                            <?php
                                $nameMap = [];
                                if (isset($formulir) && is_iterable($formulir)) {
                                    foreach ($formulir as $f) {
                                        if (!isset($nameMap[$f->nama])) {
                                            $nameMap[$f->nama] = $f->nama_kostum ?? '';
                                        }
                                    }
                                }
                            ?>
                            <div class="d-flex gap-2">
                                <select id="add-nama-select" class="form-select" style="max-width: 45%;">
                                    <option value="">-- Pilih dari daftar --</option>
                                    <?php $__currentLoopData = $nameMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e(e($n)); ?>"><?php echo e($n); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div style="flex:1">
                                    <input id="add-nama-input" name="nama" class="form-control" list="formulir-names" placeholder="Atau ketik untuk mencari nama..." autocomplete="off">
                                    <datalist id="formulir-names">
                                        <?php $__currentLoopData = $nameMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e(e($n)); ?>"></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Kostum</label>
                            <input type="text" id="add-nama-kostum" name="nama_kostum" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Denda</label>
                            <input type="text" name="jenis_denda" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Denda (angka)</label>
                            <input type="number" step="0.01" name="jumlah_denda" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 1 (opsional)</label>
                            <input type="file" name="bukti_foto_1" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 2 (opsional)</label>
                            <input type="file" name="bukti_foto_2" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 3 (opsional)</label>
                            <input type="file" name="bukti_foto_3" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 4 (opsional)</label>
                            <input type="file" name="bukti_foto_4" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Foto Bukti 5 (opsional)</label>
                            <input type="file" name="bukti_foto_5" class="form-control" accept="image/*">
                        </div>
                        <!-- Note: status kept minimal; bukti foto opsional -->
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
    document.addEventListener('DOMContentLoaded', function(){
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(a => setTimeout(()=> new bootstrap.Alert(a).close(), 3000));
    });
</script>
<script>
    function showDendaBuktiFotoPreview(src) {
        const img = document.getElementById('dendaBuktiFotoPreviewImg');
        if (!img) return;
        img.src = src;

        const modalEl = document.getElementById('dendaBuktiFotoPreviewModal');
        if (!modalEl || !window.bootstrap) return;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('dendaBuktiFotoPreviewModal');
        if (!modalEl) return;
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('dendaBuktiFotoPreviewImg');
            if (img) img.src = '';
        });
    });
</script>
<script>
    // Edit modal helpers
    const nameMap = <?php echo json_encode($nameMap ?? []); ?>;
    function editSelectChange(id) {
        const sel = document.getElementById('edit-nama-select-' + id);
        const input = document.getElementById('edit-nama-input-' + id);
        const kostum = document.getElementById('edit-nama-kostum-' + id);
        if (!sel || !input) return;
        const val = sel.value || '';
        input.value = val;
        if (val && nameMap[val] !== undefined && kostum) {
            kostum.value = nameMap[val] || '';
        }
    }

    function editInputChange(id) {
        const input = document.getElementById('edit-nama-input-' + id);
        const kostum = document.getElementById('edit-nama-kostum-' + id);
        if (!input) return;
        const val = input.value || '';
        if (val && nameMap[val] !== undefined && kostum) {
            kostum.value = nameMap[val] || '';
        }
    }
</script>
<script>
    // Auto-fill nama_kostum in Add Modal based on selected formulir name
    (function(){
        const nameMap = <?php echo json_encode($nameMap ?? []); ?>;
        const input = document.getElementById('add-nama-input');
        const select = document.getElementById('add-nama-select');
        const kostumInput = document.getElementById('add-nama-kostum');
        if (select && input) {
            select.addEventListener('change', function(){
                const val = this.value || '';
                input.value = val; // mirror into input
                if (val && nameMap[val] !== undefined && kostumInput) {
                    kostumInput.value = nameMap[val] || '';
                }
            });
        }
        if (input && kostumInput) {
            // when user selects from datalist or types exact name
            input.addEventListener('input', function(){
                const val = this.value || '';
                if (val && nameMap[val] !== undefined) {
                    kostumInput.value = nameMap[val] || '';
                }
            });

            // also support blur: if exact match found on blur, fill
            input.addEventListener('blur', function(){
                const val = this.value || '';
                if (val && nameMap[val] !== undefined) {
                    kostumInput.value = nameMap[val] || '';
                }
            });
        }
    })();
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

        initAdminTableSearchSort('adminDendaTable','search-admin-denda','sort-admin-denda','reset-admin-denda');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-denda.blade.php ENDPATH**/ ?>