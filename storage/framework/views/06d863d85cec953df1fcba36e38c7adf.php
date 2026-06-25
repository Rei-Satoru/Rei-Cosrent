

<?php $__env->startSection('title', 'Pesanan Saya - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
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

    #orderEditModal .modal-content,
    #orderEditModal .modal-body,
    #orderEditModal .modal-footer {
        background-color: #0f172af5 !important;
        color: #fff !important;
    }

    #orderEditModal .modal-body label,
    #orderEditModal .modal-body .form-check-label,
    #orderEditModal .modal-body .form-control::placeholder,
    #orderEditModal .modal-body .form-text,
    #orderEditModal .modal-body strong,
    #orderEditModal .modal-body .form-check-input {
        color: #fff !important;
    }

    #orderEditModal .form-control {
        background-color: #0f172af5 !important;
        color: #fff !important;
        border-color: rgba(148, 163, 184, 0.3) !important;
    }

    #orderEditModal .order-edit-date-input {
        box-sizing: border-box !important;
        width: 100% !important;
        max-width: 375px !important;
        height: 38px !important;
        min-height: 38px !important;
        padding: 0.35rem 0.75rem !important;
    }

    #orderEditModal .form-control:focus {
        background-color: #0f172af5 !important;
        color: #fff !important;
        box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <div class="d-grid d-sm-block">
                <a href="<?php echo e(route('user.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Profil
                </a>
            </div>
        </div>

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

        <?php if($pesanan->isEmpty()): ?>
            <div class="alert alert-info text-center" role="alert">
                Anda belum memiliki pesanan.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kostum</th>
                            <th class="d-none d-md-table-cell">Pesanan Dibuat</th>
                            <th class="d-none d-md-table-cell">Pesanan Diupdate</th>
                            <th>Tgl Pakai</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="d-none d-md-table-cell">Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($order->nama_kostum ?? '-'); ?></td>
                            <td class="d-none d-md-table-cell text-center">
                                <?php if($order->created_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($order->created_at)->format('d-m-Y')); ?><br>
                                    <?php echo e(\Carbon\Carbon::parse($order->created_at)->format('H:i:s')); ?>

                                <?php endif; ?>
                            </td>
                            <td class="d-none d-md-table-cell text-center">
                                <?php if($order->updated_at): ?>
                                    <?php echo e(\Carbon\Carbon::parse($order->updated_at)->format('d-m-Y')); ?><br>
                                    <?php echo e(\Carbon\Carbon::parse($order->updated_at)->format('H:i:s')); ?>

                                <?php endif; ?>
                            </td>
                            <td><?php echo e($order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-'); ?></td>
                            <td><?php echo e($order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-'); ?></td>
                            <td>Rp <?php echo e(number_format((float) $order->total_harga, 0, ',', '.')); ?></td>
                            <td>
                                <?php
                                    $statusClass = [
                                        'proses' => 'bg-warning text-dark',
                                        'revisi' => 'bg-secondary',
                                        'selesai' => 'bg-success',
                                        'diterima' => 'bg-info text-dark',
                                        'dibatalkan' => 'bg-secondary',
                                    ][$order->status] ?? 'bg-dark';
                                ?>
                                <span class="badge <?php echo e($statusClass); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                            </td>
                            <td class="d-none d-md-table-cell"><?php echo e($order->keterangan ?? '-'); ?></td>
                            <td class="text-end">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#orderDetailModal-<?php echo e($order->id); ?>">
                                        <i class="bi bi-card-list"></i> Detail
                                    </button>

                                <?php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;

                                    if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                        $hasBukti = true;
                                    } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                        $hasBukti = true;
                                    } else {
                                        try {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $order->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $hasBukti = false;
                                        }
                                    }
                                ?>

                                <?php if($hasBukti): ?>
                                    <?php
                                        $directBuktiUrl = null;
                                        $directExt = null;

                                        if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                            $directBuktiUrl = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                            $directExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                        } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                            $directBuktiUrl = asset('storage/' . session('uploaded_bukti_path'));
                                            $directExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                        } elseif (!empty($foundBuktiPath)) {
                                            $directBuktiUrl = asset('storage/' . $foundBuktiPath);
                                            $directExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                        }
                                    ?>

                                    
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#buktiModal-<?php echo e($order->id); ?>">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                <?php else: ?>
                                    <?php if($order->status === 'diterima'): ?>
                                        <a href="<?php echo e(route('pembayaran', ['id' => $order->id])); ?>" class="btn btn-success btn-sm w-100">
                                            <i class="bi bi-cash-coin"></i> Lanjutkan ke Pembayaran
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(in_array($order->status, ['proses', 'revisi'])): ?>
                                    <button type="button" class="btn btn-sm btn-outline-primary w-100 order-edit-button" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderEditModal"
                                        data-order-id="<?php echo e($order->id); ?>"
                                        data-order-nama="<?php echo e($order->nama); ?>"
                                        data-order-nomor_telepon="<?php echo e($order->nomor_telepon); ?>"
                                        data-order-nomor_telepon_2="<?php echo e($order->nomor_telepon_2); ?>"
                                        data-order-tanggal_pemakaian="<?php echo e($order->tanggal_pemakaian); ?>"
                                        data-order-tanggal_pengembalian="<?php echo e($order->tanggal_pengembalian); ?>"
                                        data-order-kartu_identitas="<?php echo e($order->kartu_identitas); ?>"
                                        data-order-pernyataan="<?php echo e(e($order->pernyataan ?? '')); ?>"
                                        data-order-nama_kostum="<?php echo e($order->nama_kostum); ?>"
                                    >
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#orderActionModal-<?php echo e($order->id); ?>">
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                <?php endif; ?>

                                <?php if($order->status === 'selesai'): ?>
                                    <?php
                                        $hasUlasan = \App\Models\Ulasan::where('id', $order->id)->exists();
                                    ?>
                                    <a href="<?php echo e(route('user.ulasan.form', $order->id)); ?>" class="btn btn-sm btn-outline-warning w-100">
                                        <i class="bi bi-star"></i> <?php echo e($hasUlasan ? 'Edit Ulasan' : 'Beri Ulasan'); ?>

                                    </a>
                                <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="orderDetailModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="orderDetailLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #0d6efd; color: #fff;">
                                        <h5 class="modal-title" id="orderDetailLabel-<?php echo e($order->id); ?>">
                                            <i class="bi bi-card-list"></i> Detail Pesanan
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-white" style="background-color: #0f172af5; color: #fff;">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br><?php echo e($order->nama_kostum ?? '-'); ?></div>
                                                <div class="mb-2"><strong>Tgl Pakai:</strong><br><?php echo e($order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-'); ?></div>
                                                <div class="mb-2"><strong>Tgl Kembali:</strong><br><?php echo e($order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-'); ?></div>
                                                <div class="mb-2"><strong>Total Harga:</strong><br>Rp <?php echo e(number_format((float) $order->total_harga, 0, ',', '.')); ?></div>
                                                <div class="mb-2"><strong>Metode Pembayaran:</strong><br><?php echo e($order->metode_pembayaran ?? '-'); ?></div>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br><?php echo e($order->nama); ?></div>
                                                <div class="mb-2"><strong>Nomor Telepon:</strong><br><?php echo e($order->nomor_telepon); ?></div>
                                                <div class="mb-2"><strong>Nomor Telepon 2:</strong><br><?php echo e($order->nomor_telepon_2); ?></div>
                                                <div class="mb-2"><strong>Alamat:</strong><br><?php echo e($order->alamat); ?></div>
                                                <div class="mb-2"><strong>Kartu Identitas:</strong><br><?php echo e($order->kartu_identitas); ?></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Foto Kartu Identitas:</strong>
                                                <?php if($order->foto_kartu_identitas): ?>
                                                    <img src="<?php echo e(asset('storage/' . $order->foto_kartu_identitas)); ?>" alt="Foto Kartu Identitas" class="img-fluid rounded mt-2">
                                                <?php else: ?>
                                                    <div class="text-white">Tidak tersedia</div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Selfie Kartu Identitas:</strong>
                                                <?php if($order->selfie_kartu_identitas): ?>
                                                    <img src="<?php echo e(asset('storage/' . $order->selfie_kartu_identitas)); ?>" alt="Selfie Kartu Identitas" class="img-fluid rounded mt-2">
                                                <?php else: ?>
                                                    <div class="text-white">Tidak tersedia</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="buktiModalLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #0d6efd; color: #fff;">
                                        <h5 class="modal-title" id="buktiModalLabel-<?php echo e($order->id); ?>">Bukti Pembayaran</h5>
                                    </div>
                                    <div class="modal-body" style="background-color: #0f172af5; color: #fff;">
                                        <?php
                                            $displayBuktiPath = null;
                                            $displayExt = null;

                                            if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                                $displayBuktiPath = asset('storage/' . session('uploaded_bukti_path'));
                                                $displayExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        ?>

                                        <?php if($displayBuktiPath): ?>
                                                <?php
                                                    // If it's a PDF, embed it. Otherwise try to display as image.
                                                ?>
                                                <?php if($displayExt === 'pdf'): ?>
                                                    <embed src="<?php echo e($displayBuktiPath); ?>" type="application/pdf" width="100%" height="600px" />
                                                <?php else: ?>
                                                    <img src="<?php echo e($displayBuktiPath); ?>" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'<?php echo e($displayBuktiPath); ?>\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                                <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-secondary"><i class="bi bi-info-circle"></i> Belum ada bukti pembayaran untuk pesanan ini.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Modal (Hapus permanen) -->
                        <?php if(in_array($order->status, ['proses', 'revisi'])): ?>
                        <div class="modal fade" id="orderActionModal-<?php echo e($order->id); ?>" tabindex="-1" aria-labelledby="orderActionLabel-<?php echo e($order->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-surface">
                                        <h5 class="modal-title" id="orderActionLabel-<?php echo e($order->id); ?>"><i class="bi bi-x-octagon"></i> Hapus Pesanan Permanen</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pesanan #<?php echo e($order->id); ?> akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="orderActionForm-<?php echo e($order->id); ?>" method="POST" action="<?php echo e(url('/pesanan-saya')); ?>/<?php echo e($order->id); ?>/delete">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check-circle"></i> Hapus Sekarang
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Pesanan Modal -->
            <div class="modal fade" id="orderEditModal" tabindex="-1" aria-labelledby="orderEditLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #0d6efd; color: #fff;">
                            <h5 class="modal-title" id="orderEditLabel"><i class="bi bi-pencil-square"></i> Edit Pesanan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="orderEditForm" method="POST" action="" enctype="multipart/form-data" data-base-action="<?php echo e(url('/pesanan-saya')); ?>/">
                            <?php echo csrf_field(); ?>
                            <div class="modal-body text-white" style="background-color: #0f172af5; color: #fff;">
                                <input type="hidden" name="order_id" id="edit_order_id">
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="pernyataan" id="edit_pernyataan">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Nama Lengkap</label>
                                        <input type="text" name="nama" id="edit_nama" class="form-control bg-dark text-white border-secondary" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Nomor Telepon</label>
                                        <input type="text" name="nomor_telepon" id="edit_nomor_telepon" class="form-control bg-dark text-white border-secondary" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Nama Kostum</label>
                                        <input type="text" id="edit_nama_kostum" class="form-control bg-dark text-white border-secondary" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Nomor Telepon Pihak Kedua</label>
                                        <input type="text" name="nomor_telepon_2" id="edit_nomor_telepon_2" class="form-control bg-dark text-white border-secondary" required>
                                    </div>
                                </div>

                                <hr class="border-secondary">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Tanggal Pemakaian</label>
                                        <input type="date" name="tanggal_pemakaian" id="edit_tanggal_pemakaian" class="form-control bg-dark text-white border-secondary order-edit-date-input" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Tanggal Pengembalian</label>
                                        <input type="date" name="tanggal_pengembalian" id="edit_tanggal_pengembalian" class="form-control bg-dark text-white border-secondary order-edit-date-input" required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-12">
                                        <label class="form-label text-white">Kartu Identitas</label>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_pelajar" value="Kartu Pelajar" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_pelajar">Kartu Pelajar</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_kia" value="KIA" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_kia">KIA</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_ktm" value="KTM" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_ktm">KTM</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_ktp" value="KTP" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_ktp">KTP</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_sim" value="SIM" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_sim">SIM</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="kartu_identitas" id="edit_identitas_lainnya" value="Lainnya" required>
                                                    <label class="form-check-label text-white" for="edit_identitas_lainnya">Lainnya</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="edit_identitas_lainnya_input" style="display: none; margin-top: 1rem;">
                                            <input type="text" name="kartu_identitas_lainnya" id="edit_kartu_identitas_lainnya" class="form-control bg-dark text-white border-secondary" placeholder="Sebutkan jenis identitas lainnya...">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Foto Kartu Identitas</label>
                                        <input type="file" name="foto_kartu_identitas" class="form-control bg-dark text-white border-secondary" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-white">Selfie Kartu Identitas</label>
                                        <input type="file" name="selfie_kartu_identitas" class="form-control bg-dark text-white border-secondary" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="background-color: #0f172af5;">

                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            try {
                setTimeout(() => {
                    if (window.bootstrap && typeof window.bootstrap.Alert !== 'undefined') {
                        const instance = window.bootstrap.Alert.getOrCreateInstance(successAlert);
                        instance.close();
                    } else {
                        successAlert.remove();
                    }
                }, 5000);
            } catch (e) {}
        }

        const orderEditModal = document.getElementById('orderEditModal');
        if (orderEditModal) {
            orderEditModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (!button) return;

                const orderId = button.dataset.orderId;
                const baseAction = document.getElementById('orderEditForm').dataset.baseAction;
                const form = document.getElementById('orderEditForm');
                const action = baseAction + orderId + '/update';

                form.action = action;
                document.getElementById('edit_order_id').value = orderId || '';
                document.getElementById('edit_nama').value = button.dataset.orderNama || '';
                document.getElementById('edit_nomor_telepon').value = button.dataset.orderNomor_telepon || '';
                document.getElementById('edit_nomor_telepon_2').value = button.dataset.orderNomor_telepon_2 || '';
                document.getElementById('edit_pernyataan').value = button.dataset.orderPernyataan || '';
                document.getElementById('edit_nama_kostum').value = button.dataset.orderNama_kostum || '';
                document.getElementById('edit_tanggal_pemakaian').value = formatDateInputValue(button.dataset.orderTanggal_pemakaian || '');
                document.getElementById('edit_tanggal_pengembalian').value = formatDateInputValue(button.dataset.orderTanggal_pengembalian || '');

                const kartuValue = button.dataset.orderKartu_identitas || '';
                const kartuKnown = ['Kartu Pelajar','KIA','KTM','KTP','SIM'];
                const kartuButtons = document.querySelectorAll('#orderEditForm input[name="kartu_identitas"]');
                kartuButtons.forEach(function(radio) {
                    if (kartuKnown.includes(kartuValue)) {
                        radio.checked = radio.value === kartuValue;
                    } else {
                        radio.checked = radio.value === 'Lainnya';
                    }
                });
                const lainnyaContainer = document.getElementById('edit_identitas_lainnya_input');
                const lainnyaInput = document.getElementById('edit_kartu_identitas_lainnya');
                if (kartuKnown.includes(kartuValue) || kartuValue === '') {
                    if (lainnyaContainer) lainnyaContainer.style.display = 'none';
                    if (lainnyaInput) lainnyaInput.value = '';
                } else {
                    if (lainnyaContainer) lainnyaContainer.style.display = 'block';
                    if (lainnyaInput) lainnyaInput.value = kartuValue;
                }
            });

            const identitasRadios = document.querySelectorAll('#orderEditForm input[name="kartu_identitas"]');
            const identitasLainnyaContainer = document.getElementById('edit_identitas_lainnya_input');
            const identitasLainnyaInput = document.getElementById('edit_kartu_identitas_lainnya');
            if (identitasRadios.length && identitasLainnyaContainer) {
                identitasRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        if (this.value === 'Lainnya') {
                            identitasLainnyaContainer.style.display = 'block';
                        } else {
                            identitasLainnyaContainer.style.display = 'none';
                            if (identitasLainnyaInput) {
                                identitasLainnyaInput.value = '';
                            }
                        }
                    });
                });
            }
        }

        function formatDateInputValue(value) {
            if (!value) {
                return '';
            }
            const normalized = value.split(' ')[0].split('T')[0];
            return normalized;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/user/pesanan-saya.blade.php ENDPATH**/ ?>