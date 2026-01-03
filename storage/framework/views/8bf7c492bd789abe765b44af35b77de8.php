

<?php $__env->startSection('title', 'Data Denda & Kerusakan - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    table th { background-color: var(--bs-primary); color: #fff; text-align: center; }
    .action-buttons { display:flex; gap:8px; justify-content:center; }
    .thumb { max-width:100px; max-height:80px; object-fit:cover; }
    .page-title { color: #0056b3; transition: color 0s ease; }

    [data-bs-theme="dark"] .page-title { color: #a855f7; }
    [data-bs-theme="light"] .page-title { color: #0056b3; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Data Denda & Kerusakan</h1>
        <p class="text-muted">Kelola denda dan laporan kerusakan kostum.</p>
    </div>
</header>

<section class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus-circle"></i> Tambah Denda</button>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif; ?>

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
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Nama Kostum</th>
                            <th>Jenis Denda</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Bukti Foto</th>
                            <th>Bukti Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $dendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr id="denda-row-<?php echo e($item->id); ?>" data-nama="<?php echo e(e($item->nama)); ?>" data-nama_kostum="<?php echo e(e($item->nama_kostum)); ?>" data-jenis_denda="<?php echo e(e($item->jenis_denda)); ?>" data-keterangan="<?php echo e(e($item->keterangan)); ?>" data-jumlah_denda="<?php echo e($item->jumlah_denda); ?>">
                            <td class="text-center"><?php echo e((isset($dendas) && method_exists($dendas, 'firstItem') && $dendas->firstItem() !== null) ? $dendas->firstItem() + $loop->index : $loop->iteration); ?></td>
                            <td class="field-nama"><?php echo e($item->nama); ?></td>
                            <td class="field-nama_kostum"><?php echo e($item->nama_kostum); ?></td>
                            <td class="field-jenis_denda"><?php echo e($item->jenis_denda); ?></td>
                            <td class="field-keterangan"><div style="max-height:120px;overflow:auto"><?php echo nl2br(e($item->keterangan)); ?></div></td>
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
                            <td class="text-center"><?php echo e($item->created_at ? $item->created_at->format('d/m/Y') : '-'); ?></td>
                            <td class="text-center">
                                <?php if(!empty($item->bukti_foto)): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->bukti_foto)); ?>" alt="Foto Bukti" class="thumb rounded">
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
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
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#adminDendaBuktiModal-<?php echo e($item->id); ?>">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
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
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="dendaDetailLabel-<?php echo e($item->id); ?>">
                                            <i class="bi bi-card-list"></i> Detail Denda #<?php echo e($item->id); ?>

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
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Pembayaran Modal -->
                        <div class="modal fade" id="adminDendaBuktiModal-<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="adminDendaBuktiLabel-<?php echo e($item->id); ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="adminDendaBuktiLabel-<?php echo e($item->id); ?>">Bukti Pembayaran - Denda #<?php echo e($item->id); ?></h5>
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
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning text-white">
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
                                                    <label class="form-label">Foto Bukti (opsional)</label>
                                                    <input type="file" name="bukti_foto" class="form-control" accept="image/*">
                                                    <?php if($item->bukti_foto): ?>
                                                        <div class="mt-2"><img src="<?php echo e(asset('storage/' . $item->bukti_foto)); ?>" alt="Preview" class="img-fluid rounded" style="max-height:120px; object-fit:contain;"></div>
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
                                    <div class="modal-header bg-danger text-white">
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
                            <label class="form-label">Foto Bukti (opsional)</label>
                            <input type="file" name="bukti_foto" class="form-control" accept="image/*">
                        </div>
                        <!-- Note: status kept minimal; bukti_foto can be uploaded here -->
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-denda.blade.php ENDPATH**/ ?>