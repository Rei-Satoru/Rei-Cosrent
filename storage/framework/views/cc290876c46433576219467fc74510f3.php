

<?php $__env->startSection('title', 'Data Pesanan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
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

    footer {
        transition: background-color 1000ms;
    }

    body[data-bs-theme="light"] footer {
        background-color: #0d6efd !important;
    }

    body[data-bs-theme="dark"] footer {
        background-color: #8a2be2 !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Pesanan</h1>
        <p class="text-muted">Kelola pesanan pengguna dan ubah statusnya.</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <!-- Tombol di atas tabel -->
            <div class="d-flex justify-content-start mb-3 flex-wrap gap-2">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Success Alert -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Error Alert -->
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if($pesanan->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kostum</th>
                                    <th>Tgl Pakai</th>
                                    <th>Tgl Kembali</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Catatan</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pesanan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e((isset($pesanan) && method_exists($pesanan, 'firstItem') && $pesanan->firstItem() !== null) ? $pesanan->firstItem() + $loop->index : $loop->iteration); ?></td>
                                    <td><?php echo e($item->nama_kostum); ?></td>
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
                                    <td>
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
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminBuktiModal-<?php echo e($item->id); ?>">
                                                <i class="bi bi-eye"></i> Lihat Bukti
                                            </button>
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
                                                <select name="status" class="form-select form-select-sm" style="width: 120px;">
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
                                                <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title" id="pesananDetailLabel<?php echo e($item->id); ?>">
                                                <i class="bi bi-card-list"></i> Detail Pesanan #<?php echo e($item->id); ?>

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
                                                    <div class="mb-2"><strong>Nama:</strong><br><?php echo e($item->nama); ?></div>
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
                                                            <img src="<?php echo e(asset('storage/' . $item->foto_kartu_identitas)); ?>" alt="Foto Kartu Identitas" class="img-fluid rounded mb-2" style="max-width: 100%; height: auto;">
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2"><strong>Selfie Kartu Identitas:</strong><br>
                                                        <?php if($item->selfie_kartu_identitas): ?>
                                                            <img src="<?php echo e(asset('storage/' . $item->selfie_kartu_identitas)); ?>" alt="Selfie Kartu Identitas" class="img-fluid rounded mb-2" style="max-width: 100%; height: auto;">
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak tersedia</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <!-- Bukti Modal (per item) -->
                                <div class="modal fade" id="adminBuktiModal-<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="adminBuktiLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="adminBuktiLabel-<?php echo e($item->id); ?>">Bukti Pembayaran - Pesanan #<?php echo e($item->id); ?></h5>
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
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-pesanan.blade.php ENDPATH**/ ?>