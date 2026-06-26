

<?php $__env->startSection('title', 'Data Pesanan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
    /* Ensure modal detail text is always white */
    [id^="pesananDetail"] * { color: #ffffff !important; }



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
    .input-group .form-control[type="search"],
    input[type="search"],
    .card-body .input-group input.form-control {
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

    .bukti-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border: 1px solid var(--bs-border-color);
        border-radius: 0;
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .bukti-thumb:hover {
        transform: scale(1.02);
    }

    .identitas-thumb {
        cursor: zoom-in;
        transition: transform .12s ease;
    }

    .identitas-thumb:hover {
        transform: scale(1.01);
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

    .keterangan-input {
        width: 100%;
        min-width: 260px;
        min-height: 48px;
        background-color: #0f172af5 !important;
        color: #dee2e6 !important;
        border: 1px solid rgba(148,163,184,0.35) !important;
        padding: 0.55rem 0.75rem !important;
    }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Pesanan</h2>
                <p class="text-muted mb-0 small">Kelola pesanan pengguna dan ubah statusnya.</p>
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

        <?php if($pesanan->count() > 0): ?>
            <div class="card mb-3" style="background-color: #0f172af5; border: none;">
                <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 w-100">
                        <div class="input-group" style="background-color: #94a3b829; border-radius: 0.75rem; border: 1px solid rgba(148,163,184,0.35);">
                            <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                            <input id="search-admin-pesanan" type="search" class="form-control border-0 bg-transparent" placeholder="Cari nama kostum, status, catatan, pembayaran..." aria-label="Cari pesanan">
                        </div>
                        <select id="sort-admin-pesanan" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6;">
                            <option value="">Urutkan data pesanan</option>
                            <option value="1:string:asc">Nama Kostum A–Z</option>
                            <option value="1:string:desc">Nama Kostum Z–A</option>
                            <option value="4:date:asc">Tanggal Pakai Terawal</option>
                            <option value="4:date:desc">Tanggal Pakai Terbaru</option>
                            <option value="5:date:asc">Tanggal Kembali Terawal</option>
                            <option value="5:date:desc">Tanggal Kembali Terbaru</option>
                            <option value="6:currency:asc">Total Harga Terendah</option>
                            <option value="6:currency:desc">Total Harga Tertinggi</option>
                            <option value="7:string:asc">Status A–Z</option>
                            <option value="7:string:desc">Status Z–A</option>
                        </select>

                        <select id="filter-admin-pesanan-tahun" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6; min-width: 130px;">
                            <option value="">Semua Tahun</option>
                        </select>

                        <select id="filter-admin-pesanan-bulan" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6; min-width: 130px;">
                            <option value="">Semua Bulan</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button id="reset-admin-pesanan" type="button" class="btn btn-light w-100">Reset Pencarian</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="adminPesananTable" class="table table-hover align-middle orders-table">
                    <thead>
                        <tr style="background-color: rgba(37, 99, 235, 0.08); border-bottom: 2px solid rgba(37, 99, 235, 0.15);">
                            <th style="width: 50px; color: var(--bs-body-color);">No</th>
                                    <th>Nama Kostum</th>
                                    <th class="d-none d-md-table-cell">Pesanan Dibuat</th>
                                    <th class="d-none d-md-table-cell">Pesanan Diupdate</th>
                                    <th>Tgl Pakai</th>
                                    <th>Tgl Kembali</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th class="d-none d-md-table-cell">Catatan</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
<?php $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr data-date="<?php echo e($item->tanggal_pemakaian ? \Carbon\Carbon::parse($item->tanggal_pemakaian)->toDateString() : ($item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->toDateString() : '')); ?>">
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($item->nama_kostum); ?></td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if($item->created_at): ?>
                                            <?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d-m-Y')); ?><br>
                                            <?php echo e(\Carbon\Carbon::parse($item->created_at)->format('H:i:s')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php if($item->updated_at): ?>
                                            <?php echo e(\Carbon\Carbon::parse($item->updated_at)->format('d-m-Y')); ?><br>
                                            <?php echo e(\Carbon\Carbon::parse($item->updated_at)->format('H:i:s')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($item->tanggal_pemakaian ? \Carbon\Carbon::parse($item->tanggal_pemakaian)->format('d M Y') : '-'); ?></td>
                                    <td><?php echo e($item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') : '-'); ?></td>
                                    <td>Rp <?php echo e(number_format((float) $item->total_harga, 0, ',', '.')); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = [
                                                'proses' => 'bg-warning text-dark',
                                                'revisi' => 'bg-secondary',
                                                'diterima' => 'bg-info text-dark',
                                                'selesai' => 'bg-success',
                                                'dibatalkan' => 'bg-secondary'
                                            ][$item->status] ?? 'bg-dark';
                                        ?>
                                        <span class="badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($item->status)); ?></span>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <input type="text"
                                               id="keterangan-<?php echo e($item->id); ?>"
                                               class="form-control form-control-sm keterangan-input"
                                               placeholder="Tambahkan keterangan"
                                               value="<?php echo e($item->keterangan); ?>"
                                               data-hidden="hidden-keterangan-<?php echo e($item->id); ?>"
                                               maxlength="255">
                                    </td>
                                    <td>
                                        <?php
                                            $displayBuktiPath = null;
                                            $displayExt = null;
                                            $foundBuktiPath = null;
                                            try {
                                                $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                                foreach ($files as $f) {
                                                    if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $item->id . '_')) {
                                                        $foundBuktiPath = $f;
                                                        break;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $foundBuktiPath = null;
                                            }

                                            if (isset($item->pembayaran_safe) && !empty($item->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $item->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($item->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        ?>

                                        <?php if($displayBuktiPath): ?>
                                            <?php if($displayExt === 'pdf'): ?>
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminBuktiModal-<?php echo e($item->id); ?>" title="Lihat Bukti (PDF)">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#adminBuktiModal-<?php echo e($item->id); ?>" aria-label="Lihat bukti pembayaran">
                                                    <img src="<?php echo e($displayBuktiPath); ?>" alt="Bukti Pembayaran" class="bukti-thumb">
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#pesananDetail<?php echo e($item->id); ?>" title="Detail">
                                                <i class="bi bi-info-circle"></i> Detail
                                            </button>
                                            <form id="updateForm-<?php echo e($item->id); ?>" action="<?php echo e(route('admin.pesanan.update-status', $item->id)); ?>" method="POST" class="d-flex gap-2 align-items-center">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="keterangan" id="hidden-keterangan-<?php echo e($item->id); ?>" value="<?php echo e($item->keterangan); ?>">
                                                <select name="status" class="form-select form-select-sm w-100 w-md-auto" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35);">
                                                    <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($status); ?>" <?php echo e($item->status === $status ? 'selected' : ''); ?>>
                                                            <?php echo e(ucfirst($status)); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.pesanan.delete', $item->id)); ?>" method="POST" style="display:inline; margin-left:6px;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                            <!-- Detail Modal -->
                            <div class="modal fade" id="pesananDetail<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="pesananDetailLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="pesananDetailLabel<?php echo e($item->id); ?>">
                                                <i class="bi bi-card-list"></i> Detail Pesanan
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Nama Kostum:</strong><br><?php echo e($item->nama_kostum ?? '-'); ?></div>
                                                    <div class="mb-2"><strong>Tgl Pakai:</strong><br><?php echo e($item->tanggal_pemakaian ? \Carbon\Carbon::parse($item->tanggal_pemakaian)->format('d M Y') : '-'); ?></div>
                                                    <div class="mb-2"><strong>Tgl Kembali:</strong><br><?php echo e($item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d M Y') : '-'); ?></div>
                                                    <div class="mb-2"><strong>Total Harga:</strong><br>Rp <?php echo e(number_format((float) $item->total_harga, 0, ',', '.')); ?></div>
                                                    <div class="mb-2"><strong>Metode Pembayaran:</strong><br><?php echo e($item->metode_pembayaran ?? '-'); ?></div>
                                                    
                                                    
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Nama Pengguna:</strong><br><?php echo e($item->nama); ?></div>
                                                    <div class="mb-2"><strong>Nomor Telepon:</strong><br><?php echo e($item->nomor_telepon ?? '-'); ?></div>
                                                    <div class="mb-2"><strong>Nomor Telepon 2:</strong><br><?php echo e($item->nomor_telepon_2 ?? '-'); ?></div>
                                                    <div class="mb-2"><strong>Alamat:</strong><br><?php echo e($item->alamat ?? '-'); ?></div>
                                                    <div class="mb-2"><strong>Kartu Identitas:</strong><br><?php echo e($item->kartu_identitas ?? '-'); ?></div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Foto Kartu Identitas:</strong><br>
                                                        <?php if($item->foto_kartu_identitas): ?>
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 text-start js-admin-identitas-preview" data-src="<?php echo e(asset('storage/' . $item->foto_kartu_identitas)); ?>" data-title="Foto Kartu Identitas" aria-label="Lihat foto kartu identitas">
                                                                <img src="<?php echo e(asset('storage/' . $item->foto_kartu_identitas)); ?>" alt="Foto Kartu Identitas" class="img-fluid rounded mb-2 identitas-thumb" style="max-width: 100%; height: auto;">
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Selfie Kartu Identitas:</strong><br>
                                                        <?php if($item->selfie_kartu_identitas): ?>
                                                            <button type="button" class="btn p-0 border-0 bg-transparent w-100 text-start js-admin-identitas-preview" data-src="<?php echo e(asset('storage/' . $item->selfie_kartu_identitas)); ?>" data-title="Selfie Kartu Identitas" aria-label="Lihat selfie kartu identitas">
                                                                <img src="<?php echo e(asset('storage/' . $item->selfie_kartu_identitas)); ?>" alt="Selfie Kartu Identitas" class="img-fluid rounded mb-2 identitas-thumb" style="max-width: 100%; height: auto;">
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                    
                                    </div>
                                </div>
                            </div>
                                <!-- Bukti Modal (per item) -->
                                <div class="modal fade" id="adminBuktiModal-<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="adminBuktiLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="adminBuktiLabel-<?php echo e($item->id); ?>">Bukti Pembayaran</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                    $modalBuktiPath = null;
                                                    $modalExt = null;
                                                    $modalFound = null;
                                                    try {
                                                        $modalFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                                        foreach ($modalFiles as $mf) {
                                                            if (\Illuminate\Support\Str::startsWith(basename($mf), 'bukti_' . $item->id . '_')) {
                                                                $modalFound = $mf;
                                                                break;
                                                            }
                                                        }
                                                    } catch (\Exception $e) {
                                                        $modalFound = null;
                                                    }

                                                    if (isset($item->pembayaran_safe) && !empty($item->pembayaran_safe->bukti_pembayaran)) {
                                                        $modalBuktiPath = asset('storage/' . $item->pembayaran_safe->bukti_pembayaran);
                                                        $modalExt = strtolower(pathinfo($item->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
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
                                                    <div class="alert alert-secondary">Belum ada bukti pembayaran untuk pesanan ini.</div>
                                                <?php endif; ?>
                                            </div>                                           
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Belum ada data pesanan.
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- Modal Preview Identitas (reusable) -->
<div class="modal fade" id="adminIdentitasPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="adminIdentitasPreviewTitle">Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <embed id="adminIdentitasPreviewEmbed" src="" type="application/pdf" width="100%" height="600px" class="d-none" />
                <img id="adminIdentitasPreviewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openDetailId = <?php echo json_encode(request()->query('open_detail'), 15, 512) ?>;

        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });

        // Close modal after 'Simpan Perubahan' is clicked
        document.querySelectorAll('form[id^="updateForm-"]').forEach(form => {
            form.addEventListener('submit', function() {
                // Find the closest modal and hide it
                const modal = form.closest('.modal');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        });

        // Sink visible keterangan inputs into their hidden form fields before submit
        document.querySelectorAll('.keterangan-input').forEach(input => {
            const hiddenId = input.getAttribute('data-hidden');
            const hiddenField = hiddenId ? document.getElementById(hiddenId) : null;

            const syncValue = () => {
                if (hiddenField) {
                    hiddenField.value = input.value;
                }
            };

            syncValue();
            input.addEventListener('input', syncValue);
        });

        function showAdminIdentitasPreview(src, title) {
            const titleEl = document.getElementById('adminIdentitasPreviewTitle');
            const imgEl = document.getElementById('adminIdentitasPreviewImg');
            const embedEl = document.getElementById('adminIdentitasPreviewEmbed');
            const modalEl = document.getElementById('adminIdentitasPreviewModal');
            if (!modalEl || !window.bootstrap) return;

            if (titleEl) titleEl.textContent = title || 'Preview';

            const lower = (src || '').toLowerCase();
            const isPdf = lower.includes('.pdf');

            if (isPdf) {
                if (embedEl) {
                    embedEl.src = src || '';
                    embedEl.classList.remove('d-none');
                }
                if (imgEl) {
                    imgEl.src = '';
                    imgEl.classList.add('d-none');
                }
            } else {
                if (imgEl) {
                    imgEl.src = src || '';
                    imgEl.classList.remove('d-none');
                }
                if (embedEl) {
                    embedEl.src = '';
                    embedEl.classList.add('d-none');
                }
            }

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.js-admin-identitas-preview').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-src');
                const title = btn.getAttribute('data-title');
                showAdminIdentitasPreview(src, title);
            });
        });

        const identitasModalEl = document.getElementById('adminIdentitasPreviewModal');
        if (identitasModalEl) {
            identitasModalEl.addEventListener('hidden.bs.modal', function () {
                const imgEl = document.getElementById('adminIdentitasPreviewImg');
                const embedEl = document.getElementById('adminIdentitasPreviewEmbed');
                if (imgEl) imgEl.src = '';
                if (embedEl) embedEl.src = '';
            });
        }

        if (openDetailId) {
            const detailModalEl = document.getElementById(`pesananDetail${openDetailId}`);
            if (detailModalEl && window.bootstrap) {
                const detailModal = bootstrap.Modal.getOrCreateInstance(detailModalEl);
                detailModal.show();
            }
        }
    });
</script><script>
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

        // Apply Tahun/Bulan filter + Search/Sort
        const yearSelect = document.getElementById('filter-admin-pesanan-tahun');
        const monthSelect = document.getElementById('filter-admin-pesanan-bulan');
        if (yearSelect && monthSelect) {
            // populate Tahun/Minggu options based on data rows
            const years = new Set();
            const months = new Set();
            Array.from(document.querySelectorAll('#adminPesananTable tbody tr[data-date]')).forEach(tr => {
                const d = tr.getAttribute('data-date') || '';
                if (!d) return;
                // d expected: YYYY-MM-DD
                const parts = d.split('-');
                if (parts.length >= 2) {
                    const y = parts[0];
                    const m = parts[1];
                    if (y) years.add(y);
                    if (m) months.add(m);
                }
            });

            const sortedYears = Array.from(years).sort((a,b)=>parseInt(a,10)-parseInt(b,10));
            yearSelect.innerHTML = '<option value="">Semua Tahun</option>';
            sortedYears.forEach(y => {
                yearSelect.insertAdjacentHTML('beforeend', `<option value="${y}">${y}</option>`);
            });

            const monthOrder = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            const bulanName = { '01':'Januari','02':'Februari','03':'Maret','04':'April','05':'Mei','06':'Juni','07':'Juli','08':'Agustus','09':'September','10':'Oktober','11':'November','12':'Desember' };
            monthSelect.innerHTML = '<option value="">Semua Bulan</option>';
            monthOrder.forEach(m => {
                if (months.has(m)) {
                    monthSelect.insertAdjacentHTML('beforeend', `<option value="${m}">${bulanName[m]}</option>`);
                }
            });
        }

        // Re-init with year/month filter by wrapping existing behavior
        const table = document.getElementById('adminPesananTable');
        const searchInput = document.getElementById('search-admin-pesanan');
        const sortSelect = document.getElementById('sort-admin-pesanan');
        const resetButton = document.getElementById('reset-admin-pesanan');
        if (table && searchInput && sortSelect && resetButton && yearSelect && monthSelect) {
            const tbody = table.tBodies[0];
            if (tbody) {
                const originalRows = Array.from(tbody.rows);

                function updateRows() {
                    const query = normalizeText(searchInput.value);
                    const yearVal = yearSelect.value;
                    const monthVal = monthSelect.value;

                    const filteredByYearMonth = originalRows.filter(row => {
                        const date = row.getAttribute('data-date') || '';
                        const parts = date ? date.split('-') : [];
                        const y = parts.length >= 1 ? parts[0] : '';
                        const m = parts.length >= 2 ? parts[1] : '';
                        const okYear = (!yearVal) || (y === yearVal);
                        const okMonth = (!monthVal) || (m === monthVal);
                        return okYear && okMonth;
                    });

                    let filtered = filteredByYearMonth.filter(row => normalizeText(row.textContent).includes(query));

                    const sortParts = (sortSelect.value || '').split(':');
                    const colIndex = sortParts[0];
                    const type = sortParts[1];
                    const direction = sortParts[2];
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
                    filtered.forEach(r => tbody.appendChild(r));
                }

                searchInput.addEventListener('input', updateRows);
                sortSelect.addEventListener('change', updateRows);
                yearSelect.addEventListener('change', updateRows);
                monthSelect.addEventListener('change', updateRows);

                resetButton.addEventListener('click', () => {
                    searchInput.value = '';
                    sortSelect.value = '';
                    yearSelect.value = '';
                    monthSelect.value = '';
                    updateRows();
                });

                updateRows();
            }
        }
    });
</script><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-pesanan.blade.php ENDPATH**/ ?>