

<?php $__env->startSection('title', 'Data Tanggal Booking - Rei Cosrent'); ?>

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
    :root {
        --booking-card-bg: #f8fbff;
        --booking-card-border: rgba(37, 99, 235, 0.12);
        --booking-card-text: #0f172a;
        --booking-card-shadow: 0 12px 30px -12px rgba(16, 24, 40, 0.12);
    }

    [data-bs-theme="dark"] {
        --booking-card-bg: #0f172a;
        --booking-card-border: rgba(96, 165, 250, 0.16);
        --booking-card-text: #ffffff;
        --booking-card-shadow: 0 18px 40px -22px rgba(0, 0, 0, 0.55);
    }

    .booking-surface-card {
        background: var(--booking-card-bg) !important;
        border: 1px solid var(--booking-card-border) !important;
        box-shadow: var(--booking-card-shadow) !important;
        color: var(--booking-card-text) !important;
        border-radius: 1.25rem !important;
    }

    .booking-surface-card .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--booking-card-border) !important;
    }

    .booking-surface-card .text-muted,
    .booking-surface-card p,
    .booking-surface-card small,
    .booking-surface-card label,
    .booking-surface-card th,
    .booking-surface-card td,
    .booking-surface-card h1,
    .booking-surface-card h2,
    .booking-surface-card h3,
    .booking-surface-card h4,
    .booking-surface-card h5,
    .booking-surface-card h6 {
        color: var(--booking-card-text) !important;
    }

    .sheet-tabs .nav-link {
        border-radius: 999px;
        color: var(--brand-blue) !important;
        font-weight: 600;
    }

    .sheet-tabs .nav-link.active {
        background: var(--brand-blue);
        color: #fff !important;
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

    .booking-group-row td {
        background: color-mix(in srgb, var(--booking-card-bg) 88%, var(--brand-blue)) !important;
        color: var(--brand-blue) !important;
        font-weight: 700;
    }

    .booking-slot-input {
        min-width: 150px;
        border-radius: 999px;
    }

    .booking-slot-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0.5rem 0.75rem;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.08);
        color: var(--brand-blue);
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .booking-legend .badge {
        border-radius: 999px;
        padding: 0.5rem 0.75rem;
        font-weight: 600;
    }

    .booking-table-wrap {
        overflow-x: auto;
    }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Tanggal Booking</h2>

            </div>
            <div class="d-grid d-sm-block">
                <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo e(session('success')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo e(session('error')); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="card shadow-sm mb-4" style="background-color: rgba(37, 99, 235, 0.06); border: 1px solid rgba(37, 99, 235, 0.12);">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#ffffff !important;">Pilih Tahun & Bulan</h5>
                    <p class="mb-0 small" style="color:#ffffff !important;">Tampilkan kalender booking berdasarkan tahun dan bulan yang dipilih.</p>
                </div>
                <form method="GET" class="d-flex gap-2 align-items-center">
                    <label class="visually-hidden">Tahun</label>
                    <select name="year" class="form-select form-select-sm" style="background-color: #94a3b829;">
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($y == $selectedYear ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <label class="visually-hidden">Bulan</label>
                    <select name="month" class="form-select form-select-sm" style="background-color: #94a3b829;">
                        <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m); ?>" <?php echo e($m == $selectedMonth ? 'selected' : ''); ?>><?php echo e(DateTime::createFromFormat('!m', $m)->format('F')); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="btn btn-sm btn-primary" type="submit">Tampilkan</button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-4" style="border-color: rgba(15, 23, 42, 0.06);">
            <div class="card-header py-3" style="background-color: #0f172af5;">
                <h5 class="fw-bold mb-0"><?php echo e(DateTime::createFromFormat('!m', $selectedMonth)->format('F')); ?> <?php echo e($selectedYear); ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 orders-table">
                        <thead>
                            <tr>
                                <th style="min-width: 70px; width: 50px;">No</th>
                                <th style="min-width: 180px;">Nama Kostum</th>
                                <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th style="min-width: 120px;"><?php echo e(\Carbon\Carbon::parse($d)->format('d M')); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $kostums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center fw-semibold"><?php echo e($index + 1); ?></td>
                                    <td class="fw-semibold"><?php echo e($k->nama_kostum); ?></td>
                                    <?php $__currentLoopData = $dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td>
                                            <?php if(isset($bookingMap[$k->nama_kostum][$d])): ?>
                                                <span class="booking-slot-badge"><?php echo e($bookingMap[$k->nama_kostum][$d]); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/data-tanggal.blade.php ENDPATH**/ ?>