

<?php $__env->startSection('title', 'Dashboard Admin - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
    :root {
        --admin-card-bg: #f8fbff;
        --admin-card-border: rgba(37, 99, 235, 0.12);
        --admin-card-text: #0f172a;
        --admin-card-shadow: 0 12px 30px -12px rgba(16, 24, 40, 0.12);
        --admin-main-text: var(--brand-blue);
        --admin-sub-text: #0f172a;
        --admin-hero-bg: linear-gradient(135deg, rgba(248, 251, 255, 0.98), rgba(219, 234, 254, 0.92));
    }

    [data-bs-theme="dark"] {
        --admin-card-bg: #0f172a;
        --admin-card-border: rgba(96, 165, 250, 0.16);
        --admin-card-text: #ffffff;
        --admin-card-shadow: 0 18px 40px -22px rgba(0, 0, 0, 0.55);
        --admin-sub-text: #ffffff;
        --admin-hero-bg: linear-gradient(135deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.92));
    }

    .admin-surface-card {
        background: var(--admin-card-bg) !important;
        border: 1px solid var(--admin-card-border) !important;
        box-shadow: var(--admin-card-shadow) !important;
        color: var(--admin-sub-text) !important;
        border-radius: 1.25rem !important;
    }

    .admin-surface-card .card-header {
        background: transparent !important;
        color: var(--brand-blue) !important;
        border-bottom: 1px solid var(--admin-card-border) !important;
    }

    .admin-surface-card .card-body,
    .admin-surface-card p,
    .admin-surface-card small,
    .admin-surface-card td,
    .admin-surface-card th,
    .admin-surface-card label {
        color: var(--admin-sub-text) !important;
    }

    .admin-surface-card h1,
    .admin-surface-card h2,
    .admin-surface-card h3,
    .admin-surface-card h4,
    .admin-surface-card h5,
    .admin-surface-card h6,
    .admin-surface-card .data-card-value,
    .admin-surface-card .metric-value,
    .admin-surface-card .stats-table th {
        color: var(--admin-main-text) !important;
    }

    .admin-surface-card .text-muted {
        color: color-mix(in srgb, var(--admin-sub-text) 70%, transparent) !important;
    }

    .admin-title-blue {
        color: var(--admin-main-text) !important;
    }

    .admin-surface-card .table {
        color: var(--admin-sub-text) !important;
    }

    .admin-surface-card .table > :not(caption) > * > * {
        background: var(--admin-card-bg) !important;
        border-color: var(--admin-card-border) !important;
    }

    .admin-surface-card .btn-outline-secondary,
    .admin-surface-card .btn-outline-primary,
    .admin-surface-card .btn-outline-warning,
    .admin-surface-card .btn-outline-danger,
    .admin-surface-card .btn-primary,
    .admin-surface-card .btn-danger {
        border-radius: 999px;
    }

    .dashboard-hero {
        position: relative;
        overflow: hidden;
        background: var(--admin-hero-bg);
        border-radius: 1.5rem;
        padding: 2rem;
        color: var(--admin-main-text);
        margin-bottom: 2rem;
        box-shadow: var(--admin-card-shadow);
        border: 1px solid var(--admin-card-border);
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 15% 20%, rgba(255,255,255,0.18), transparent 32%), radial-gradient(circle at 85% 80%, rgba(255,255,255,0.12), transparent 28%);
        pointer-events: none;
    }

    .dashboard-hero > * {
        position: relative;
        z-index: 1;
    }

    .dashboard-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .dashboard-hero p {
        color: var(--admin-sub-text);
        opacity: 0.95;
        margin-bottom: 1rem;
    }

    .hero-profile-summary {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 1rem;
        padding: 1rem;
        border-radius: 12px;
        background: color-mix(in srgb, var(--admin-card-bg) 88%, #ffffff);
        backdrop-filter: blur(6px);
    }

    .hero-profile-photo {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid color-mix(in srgb, var(--admin-card-border) 80%, #ffffff);
        flex-shrink: 0;
    }

    .hero-profile-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .hero-profile-title,
    .hero-profile-vision {
        margin-bottom: 0.25rem;
        color: var(--admin-sub-text);
        opacity: 0.95;
    }

    .hero-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        display: inline-block;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .hero-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
    }

    .metric-card {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: var(--admin-card-shadow);
        color: var(--admin-sub-text);
    }

    .metric-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .metric-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: space-between;
        width: 100%;
    }

    .metric-row > .col-md-6,
    .metric-row > .col-lg-3 {
        flex: 1 1 0;
        max-width: none;
        padding-left: 0;
        padding-right: 0;
    }

    .metric-row .metric-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 992px) {
        .metric-row > .col-md-6,
        .metric-row > .col-lg-3 {
            flex: 1 1 calc(50% - 0.5rem);
        }
    }

    @media (max-width: 576px) {
        .metric-row > .col-md-6,
        .metric-row > .col-lg-3 {
            flex: 1 1 100%;
        }
    }

    .metric-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--admin-main-text);
    }

    .metric-label {
        font-size: 0.875rem;
        color: color-mix(in srgb, var(--admin-sub-text) 70%, transparent);
        margin-top: 0.5rem;
    }

    .metric-change {
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .metric-change.positive {
        color: #10b981;
    }

    .metric-change.negative {
        color: #ef4444;
    }

    .ideas-carousel {
        border-radius: 1.25rem;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(96, 165, 250, 0.92));
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ideas-carousel h3 {
        margin-bottom: 1rem;
    }

    .chart-container {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--admin-card-shadow);
        color: var(--admin-sub-text);
    }

    .chart-subtitle {
        font-size: 0.875rem;
        color: color-mix(in srgb, var(--admin-sub-text) 70%, transparent);
        margin-top: 0.25rem;
    }

    #periodDropdown {
        min-width: 170px;
        border-radius: 14px;
        border: 1px solid var(--admin-card-border);
        background: var(--admin-card-bg);
        color: var(--admin-sub-text);
        box-shadow: none;
    }

    #periodDropdown:focus {
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        border-color: rgba(37, 99, 235, 0.35);
    }

    .stats-table {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 12px;
        overflow: hidden;
        color: var(--admin-sub-text);
    }

    .stats-table th {
        background: color-mix(in srgb, var(--admin-card-bg) 92%, var(--brand-blue));
        border-bottom: 1px solid var(--admin-card-border);
        padding: 1rem;
        font-weight: 600;
        color: var(--admin-main-text);
        font-size: 0.875rem;
    }

    .stats-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--admin-card-border);
    }

    .stats-table tbody tr:last-child td {
        border-bottom: none;
    }

    .stats-table tbody tr:hover {
        background-color: color-mix(in srgb, var(--admin-card-bg) 94%, var(--brand-blue));
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .product-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .data-card {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 1.25rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: var(--admin-card-shadow);
        color: var(--admin-sub-text);
    }

    .data-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .quick-action-btn {
        min-height: 48px;
        padding: 0.85rem 1rem;
        border-radius: 14px;
        border: 1px solid rgba(37, 99, 235, 0.16);
        background: var(--admin-card-bg);
        color: var(--admin-main-text) !important;
        font-weight: 600;
        text-align: left;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.75rem;
    }

    .quick-action-btn:hover,
    .quick-action-btn:focus {
        background: color-mix(in srgb, var(--admin-card-bg) 92%, #ffffff);
        color: var(--admin-main-text) !important;
        box-shadow: 0 10px 20px rgba(16, 24, 40, 0.08);
    }

    .data-card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: var(--brand-blue);
    }

    .data-card-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--admin-main-text);
    }

    /* Keep specific dashboard numeric displays using theme text (black/white depending on mode) */
    #totalOverallRevenue,
    #periodRevenueTotal,
    #periodOrdersTotal,
    #totalOverallOrders,
    #totalOverallDenda {
        color: var(--admin-sub-text) !important;
    }

    .data-card-label {
        font-size: 0.875rem;
        color: var(--brand-blue) !important;
        margin-top: 0.5rem;
    }

    .chart-plot-wrapper {
        width: 100%;
        min-height: 320px;
    }

    #ordersSortSelect {
        background: var(--admin-card-bg);
        border-color: var(--admin-card-border);
        color: var(--admin-sub-text);
    }

    #ordersSortSelect:focus {
        box-shadow: none;
        border-color: var(--brand-blue);
    }

    .btn-manage {
        background: var(--brand-blue);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 1rem;
    }

    .btn-manage:hover {
        background: var(--brand-blue-hover);
        color: white;
        text-decoration: none;
    }

    .profile-hero {
        background: var(--admin-hero-bg);
        border-radius: 1.25rem;
        padding: 2rem;
        color: var(--admin-main-text);
        margin-bottom: 2rem;
        border: 1px solid var(--admin-card-border);
        box-shadow: var(--admin-card-shadow);
    }

    .profile-card {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 1.25rem;
        padding: 2rem;
        margin-bottom: 2rem;
        color: var(--admin-sub-text);
        box-shadow: var(--admin-card-shadow);
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #7c3aed;
        margin-bottom: 1rem;
    }

    .profile-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--admin-main-text);
        margin-bottom: 0.5rem;
    }

    .profile-title {
        color: var(--brand-blue);
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .profile-info-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--admin-card-border);
    }

    .profile-info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .profile-info-icon {
        color: var(--brand-blue);
        font-size: 1.25rem;
        margin-right: 1rem;
        min-width: 30px;
    }

    .profile-info-label {
        font-size: 0.875rem;
        color: color-mix(in srgb, var(--admin-sub-text) 70%, transparent);
        margin-bottom: 0.25rem;
    }

    .profile-info-value {
        font-weight: 600;
        color: var(--admin-sub-text);
    }

    .location-progress {
        margin-bottom: 1.5rem;
    }

    .location-progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
    }

    .progress-bar {
        border-radius: 4px;
        background: var(--brand-blue);
    }

    @media (max-width: 768px) {
        .dashboard-hero h1 {
            font-size: 1.5rem;
        }

        .metric-value {
            font-size: 1.5rem;
        }

        .stats-table th,
        .stats-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    [data-bs-theme="dark"] .dashboard-hero,
    [data-bs-theme="dark"] .profile-hero {
        box-shadow: 0 18px 40px -18px rgba(0, 0, 0, 0.55);
    }

    [data-bs-theme="dark"] .hero-profile-summary {
        background: rgba(255, 255, 255, 0.08);
    }
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="pageWrapper">
    <section class="py-4">
        <div class="container-fluid">
            <!-- Hero Section -->
        <?php
            $adminPhoto = $profile_contact && $profile_contact->photo
                ? asset('storage/' . $profile_contact->photo)
                : null;
            $profileName = $profile_contact ? $profile_contact->name : 'Belum diisi';
            $profileTitle = $profile_contact ? $profile_contact->title : 'Jabatan belum diisi';
        ?>

        <div class="row g-3 mb-4 align-items-stretch">
            <div class="col-lg-8">
                <div class="dashboard-hero admin-surface-card h-100 mb-0">
                    <div class="d-flex flex-column h-100">
                        <div class="mb-3">
                            <h1 class="admin-title-blue" style="color: var(--brand-blue) !important;">Selamat Datang Admin</h1>
                            <p class="mb-0">Kelola sistem dan data aplikasi Rei Cosrent</p>
                        </div>
                        <div class="hero-profile-summary mt-auto">
                            <?php if($adminPhoto): ?>
                                <img src="<?php echo e($adminPhoto); ?>" alt="Foto Admin" class="hero-profile-photo">
                            <?php else: ?>
                                <div class="hero-profile-photo d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.18);">
                                    <i class="bi bi-person" style="font-size: 2rem; color: rgba(255,255,255,0.9);"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="hero-profile-name"><?php echo e($profileName); ?></div>
                                <div class="hero-profile-title"><?php echo e($profileTitle); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-surface-card h-100 p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-2 admin-title-blue" style="color: var(--brand-blue) !important;">Aksi Cepat</h5>
                        <p class="mb-4">Kelola profil admin atau akhiri sesi masuk dari panel ini.</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="#" onclick="history.back(); return false;" class="btn btn-sm btn-secondary quick-action-btn w-100 text-start">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                        <a href="<?php echo e(route('admin.profile-contact')); ?>" class="btn btn-sm btn-secondary quick-action-btn w-100 text-start">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profil
                        </a>
                        <a href="<?php echo e(route('admin.logout')); ?>" class="btn btn-sm btn-secondary quick-action-btn w-100 text-start">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col">
                    <div class="data-card admin-surface-card h-100 p-3 text-start">
                        <div class="data-card-label fw-bold" style="font-weight: 700;">Total Seluruh Pendapatan</div>
                    <div class="data-card-value" id="totalOverallRevenue">Rp <?php echo e(number_format($total_revenue ?? 0, 0, ',', '.')); ?></div>
                </div>
            </div>
            <div class="col">
                <div class="data-card admin-surface-card h-100 p-3 text-start">
                    <div class="data-card-label fw-bold" style="font-weight: 700;">Total Seluruh Pesanan</div>
                    <div class="data-card-value" id="totalOverallOrders"><?php echo e($pesanan_count ?? 0); ?></div>
                </div>
            </div>
            <div class="col">
                <div class="data-card admin-surface-card h-100 p-3 text-start">
                    <div class="data-card-label fw-bold" style="font-weight: 700;">Total Seluruh Denda</div>
                    <div class="data-card-value" id="totalOverallDenda"><?php echo e(number_format($denda_count ?? 0, 0, ',', '.')); ?></div>
                </div>
            </div>
        </div>

        <!-- Revenue Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-container admin-surface-card p-0 overflow-hidden">
                    <div class="d-flex flex-column gap-3 px-4 py-3 border-bottom" style="border-color: var(--admin-card-border) !important;">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                            <div>
                                <h5 id="revenueChartTitle" class="fw-bold mb-0 admin-title-blue" style="color: var(--brand-blue) !important;">Grafik Pendapatan Minggu Ini</h5>
                                <div class="chart-subtitle">Tampilkan grafik harga sewa dan jumlah pesanan berdasarkan periode terpilih.</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label for="periodDropdown" class="visually-hidden">Pilih periode</label>
                                <select id="periodDropdown" class="form-select form-select-sm w-auto">
                                    <option value="day">Hari Ini</option>
                                    <option value="week" selected>Minggu Ini</option>
                                    <option value="month">Bulan Ini</option>
                                    <option value="year">Tahun Ini</option>
                                </select>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-2 g-3">
                            <div class="col">
                                <div class="data-card admin-surface-card h-100 p-3 text-start">
                                    <div class="data-card-label">Pendapatan Harga</div>
                                    <div class="data-card-value" id="periodRevenueTotal">Rp <?php echo e(number_format($total_revenue ?? 0, 0, ',', '.')); ?></div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="data-card admin-surface-card h-100 p-3 text-start">
                                    <div class="data-card-label">Jumlah Pesanan</div>
                                    <div class="data-card-value" id="periodOrdersTotal"><?php echo e($pesanan_count ?? 0); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-3 px-md-4 py-4">
                        <div class="row g-4">
                            <div class="col-12 col-lg-6">
                                <div class="admin-surface-card p-3 h-100">
                                    <h6 class="fw-semibold mb-3 admin-title-blue" style="color: var(--brand-blue) !important;">Grafik Harga</h6>
                                    <div class="chart-plot-wrapper" style="min-height: 320px;">
                                        <canvas id="revenueChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="admin-surface-card p-3 h-100">
                                    <h6 class="fw-semibold mb-3 admin-title-blue" style="color: var(--brand-blue) !important;">Grafik Pesanan</h6>
                                    <div class="chart-plot-wrapper" style="min-height: 320px;">
                                        <canvas id="ordersChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="chart-container admin-surface-card mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="fw-bold mb-0 admin-title-blue" style="color: var(--brand-blue) !important;">Pesanan</h5>
                <div class="d-flex align-items-center gap-2">
                    <select id="ordersSortSelect" class="form-select form-select-sm" style="width: 220px;">
                        <option value="created_desc">Terbaru (Dibuat)</option>
                        <option value="created_asc">Terlama (Dibuat)</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive mb-4">
                <table class="stats-table w-100">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kostum</th>
                            <th>Total Harga</th>
                            <th>Tanggal Pemakaian</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $latest_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-created-at="<?php echo e($order->created_at ? $order->created_at->format('Y-m-d H:i:s') : ''); ?>" data-tanggal-pemakaian="<?php echo e($order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('Y-m-d') : ''); ?>">
                                <td><?php echo e($order->nama); ?></td>
                                <td><?php echo e($order->nama_kostum); ?></td>
                                <td>Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y')); ?></td>
                                <td>
                                    <?php
                                        $status_badge = [
                                            'pending' => 'badge-warning',
                                            'confirmed' => 'badge-info',
                                            'completed' => 'badge-success',
                                            'cancelled' => 'badge-danger',
                                        ];
                                        $badge_class = $status_badge[$order->status] ?? 'badge-info';
                                    ?>
                                    <span class="product-badge <?php echo e($badge_class); ?>"><?php echo e(ucfirst($order->status ?? 'pending')); ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.data-pesanan', ['open_detail' => $order->id])); ?>" class="text-primary text-decoration-none small">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada pesanan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div> <!-- End pageWrapper -->

<!-- Sidebar Navigation -->
<aside id="appSidebar" class="app-sidebar">
    <div class="d-flex align-items-center justify-content-between px-3 pt-3">
        <h5 class="mb-0">Kelola Data</h5>
    </div>
    <div class="p-3">
        <div class="d-grid gap-3">
            <a href="<?php echo e(route('admin.data-tanggal')); ?>" aria-label="Kelola Data Tanggal" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-calendar2-week"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Kelola Data Tanggal</h6>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-pengguna')); ?>" aria-label="Data Pengguna" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-people"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Pengguna</h6>
                        <small class="text-muted">Total: <?php echo e($users_count); ?></small>
                    </div>
                </div>
            </a>
            
            <a href="<?php echo e(route('admin.data-katalog')); ?>" aria-label="Data Katalog" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-collection"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Katalog</h6>
                        <small class="text-muted">Total: <?php echo e($katalog_count); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-kostum')); ?>" aria-label="Data Kostum" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-box"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Kostum</h6>
                        <small class="text-muted">Total: <?php echo e($kostum_count); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-aturan')); ?>" aria-label="Data Aturan" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Aturan</h6>
                        <small class="text-muted">Total: <?php echo e($aturan_count); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-pesanan')); ?>" aria-label="Data Pesanan & Pembayaran" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-bag-check"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Pesanan & Pembayaran</h6>
                        <small class="text-muted">Total: <?php echo e($pesanan_count); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-pengembalian')); ?>" aria-label="Data Pengembalian" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-arrow-counterclockwise"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Pengembalian</h6>
                        <small class="text-muted">Menunggu Verifikasi: <?php echo e($pengembalian_count ?? 0); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-denda')); ?>" aria-label="Data Denda & Kerusakan" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Denda & Kerusakan</h6>
                        <small class="text-muted">Total: <?php echo e($denda_count); ?></small>
                    </div>
                </div>
            </a>

            <a href="<?php echo e(route('admin.data-ulasan')); ?>" aria-label="Data Ulasan" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-chat-square-text"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Ulasan</h6>
                        <small class="text-muted">Total: <?php echo e($ulasan_count); ?></small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</aside>

<!-- Sidebar Styles -->
<style>
    .app-sidebar {
        position: fixed;
        left: 0;
        top: var(--nav-height, 56px);
        height: calc(100vh - var(--nav-height, 56px));
        width: 56px; /* collapsed by default */
        max-width: 85vw;
        background: var(--admin-card-bg);
        border-right: 1px solid var(--admin-card-border);
        transition: width 0.32s cubic-bezier(.2,.8,.2,1), box-shadow 0.2s ease, transform 0.32s ease;
        z-index: 1040;
        overflow-y: auto;
        overflow-x: hidden;
        padding-bottom: 3rem;
    }
    .app-sidebar.open {
        width: 320px; /* expands when open */
        box-shadow: 0 12px 40px rgba(2,6,23,0.12);
    }

    .app-sidebar::before {
        content: '';
        position: absolute;
        right: 0;
        top: 1.5rem;
        width: 56px;
        height: 72px;
        background: linear-gradient(135deg, #2563eb 0%, #93c5fd 100%);
        border-radius: 0 1rem 1rem 0;
        box-shadow: 2px 10px 24px rgba(37, 99, 235, 0.18);
        pointer-events: none;
        transform: translateX(100%);
    }
    /* Reserve space for collapsed sidebar by default so page is not covered */
    #pageWrapper {
        transition: margin-left 0.3s cubic-bezier(.2,.8,.2,1);
        margin-left: 56px;
    }
    #pageWrapper.shifted {
        margin-left: 320px;
        transition: margin-left 0.3s cubic-bezier(.2,.8,.2,1);
    }

    footer {
        transition: margin-left 0.3s cubic-bezier(.2,.8,.2,1);
        margin-left: 56px;
    }

    #pageWrapper.shifted + footer,
    #pageWrapper.shifted ~ footer {
        margin-left: 320px;
    }

    @media (max-width: 768px) {
        footer {
            margin-left: 0 !important;
        }
    }
    .app-sidebar .menu-card { 
        display: block; 
        width: 100%; 
        cursor: pointer;
        text-decoration: none !important;
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border) !important;
        transition: all 0.3s ease;
    }
    .app-sidebar .menu-card:hover {
        transform: translateY(-2px);
        background: color-mix(in srgb, var(--admin-card-bg) 92%, #ffffff);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
    .app-sidebar .card-body { 
        padding: 1rem !important; 
    }
    .app-sidebar .menu-icon {
        font-size: 1.5rem;
        color: var(--bs-primary);
        margin-bottom: 0 !important;
    }
    .app-sidebar h6 {
        color: var(--admin-main-text);
    }
    .app-sidebar small {
        color: var(--admin-sub-text);
        opacity: 0.8;
    }

    @media (max-width: 768px) {
        #pageWrapper.shifted {
            margin-left: 0;
        }
    }

    [data-bs-theme="dark"] .app-sidebar {
        background: var(--admin-card-bg);
        border-right: 1px solid var(--admin-card-border);
    }

    [data-bs-theme="dark"] .app-sidebar .menu-card {
        background: var(--admin-card-bg);
        border-color: var(--admin-card-border) !important;
    }

    [data-bs-theme="dark"] .app-sidebar .menu-card:hover {
        background: color-mix(in srgb, var(--admin-card-bg) 88%, #ffffff);
    }

    [data-bs-theme="dark"] .app-sidebar h6 {
        color: var(--admin-main-text);
    }

    [data-bs-theme="dark"] .app-sidebar small {
        color: var(--admin-sub-text);
    }

    /* Collapsed / icon-only sidebar: hide labels and totals, keep icons visible */
    .app-sidebar:not(.open) {
        width: 56px;
        transform: translateX(0);
        overflow-x: hidden;
        overflow-y: hidden;
        scrollbar-width: none;
    }

    .app-sidebar:not(.open)::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    .app-sidebar:not(.open) .menu-card .card-body {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
        justify-content: center;
    }

    .app-sidebar:not(.open) .menu-icon {
        margin: 0 auto !important;
        display: block;
        font-size: 1.35rem;
    }

    .app-sidebar:not(.open) .menu-card .flex-grow-1,
    .app-sidebar:not(.open) .menu-card h6,
    .app-sidebar:not(.open) .menu-card small {
        display: none !important;
    }

    .app-sidebar::before {
        right: 0;
        transform: translateX(100%);
    }

    /* More refined collapsed appearance: center icons vertically, remove card chrome */
    .app-sidebar:not(.open) > .d-flex { /* hide the header/title when collapsed */
        display: none !important;
    }

    .app-sidebar:not(.open) .p-3 {
        padding: 0 !important;
    }

    .app-sidebar:not(.open) .d-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
        padding: 0.75rem 0;
    }

    .app-sidebar:not(.open) .menu-card {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
        width: auto;
    }

    .app-sidebar:not(.open) .menu-card .card-body {
        padding: 0.25rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 48px;
        height: 48px;
    }

    .app-sidebar:not(.open) .menu-card:hover {
        transform: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    /* make pseudo folder-edge slightly smaller when collapsed */
    .app-sidebar:not(.open)::before {
        right: 0;
        width: 48px;
        height: 64px;
        transform: translateX(100%);
    }
</style>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
<script>
    const statsEndpoint = <?php echo json_encode(route('admin.stats'), 15, 512) ?>;

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        initializePeriodFilters();
        fetchDashboardStats('week');
    });

    let latestDashboardData = null;
    let revenueChartInstance = null;
    let ordersChartInstance = null;

    function initializeCharts() {
        if (revenueChartInstance && ordersChartInstance) return;

        const revCanvas = document.getElementById('revenueChart');
        const ordersCanvas = document.getElementById('ordersChart');
        if (!revCanvas || !ordersCanvas) {
            console.warn('initializeCharts: missing revenueChart or ordersChart canvas');
            return;
        }

        const createCharts = () => {
            const revenueCtx = revCanvas.getContext('2d');
            const ordersCtx = ordersCanvas.getContext('2d');

            revenueChartInstance = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Pendapatan (Harga Sewa)',
                            data: [],
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#7c3aed',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'yRevenue'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false } },
                    scales: {
                        yRevenue: {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: { callback: function(value) { try { return 'Rp ' + Number(value).toLocaleString('id-ID'); } catch (e) { return value; } } }
                        }
                    }
                }
            });

            ordersChartInstance = new Chart(ordersCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Jumlah Pesanan',
                            data: [],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });

            console.log('initializeCharts: charts created');
            if (latestDashboardData && latestDashboardData.labels && latestDashboardData.datasets) {
                try {
                    revenueChartInstance.data.labels = latestDashboardData.labels;
                    revenueChartInstance.data.datasets[0].data = latestDashboardData.datasets.revenue || [];
                    revenueChartInstance.update();

                    ordersChartInstance.data.labels = latestDashboardData.labels;
                    ordersChartInstance.data.datasets[0].data = latestDashboardData.datasets.orders || [];
                    ordersChartInstance.update();
                } catch (e) {
                    console.error('initializeCharts: failed to apply latestDashboardData', e);
                }
            }
        };

        if (typeof Chart === 'undefined') {
            console.warn('initializeCharts: Chart not found, loading dynamically');
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js';
            s.onload = () => { console.log('Chart.js loaded dynamically'); createCharts(); };
            s.onerror = () => console.error('Failed to load Chart.js');
            document.head.appendChild(s);
        } else {
            createCharts();
        }
    }

    function updateRevenueTitle(period = 'week') {
        const titleEl = document.getElementById('revenueChartTitle');
        if (!titleEl) return;

        const titles = {
            day: 'Grafik Pendapatan Hari Ini',
            week: 'Grafik Pendapatan Minggu Ini',
            month: 'Grafik Pendapatan Bulan Ini',
            year: 'Grafik Pendapatan Tahun Ini'
        };

        titleEl.textContent = titles[period] || titles.week;
    }

    function initializePeriodFilters() {
        const periodDropdown = document.getElementById('periodDropdown');
        if (periodDropdown) {
            periodDropdown.addEventListener('change', function() {
                updateRevenueTitle(this.value || 'week');
                fetchDashboardStats(this.value || 'week');
            });
            updateRevenueTitle(periodDropdown.value || 'week');
        }
        // Orders sort select
        const ordersSortSelect = document.getElementById('ordersSortSelect');
        if (ordersSortSelect) {
            ordersSortSelect.addEventListener('change', function() {
                sortOrdersTable(this.value);
            });
            // apply default sort on load
            sortOrdersTable(ordersSortSelect.value || 'created_desc');
        }
    }

    function fetchDashboardStats(period = 'week') {
        updateRevenueTitle(period);
        const statsUrl = new URL(statsEndpoint, window.location.origin);
        statsUrl.searchParams.set('period', period);

        fetch(statsUrl.toString(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            cache: 'no-store'
        })
            .then(async (response) => {
                console.log('fetchDashboardStats: http', response.status, response.statusText);
                try {
                    return await response.json();
                } catch (e) {
                    const txt = await response.text();
                    console.warn('fetchDashboardStats: invalid JSON response', txt);
                    return {};
                }
            })
            .then(data => {
                console.log('fetchDashboardStats: parsed data', data);
                // keep latest response to apply once chart exists
                latestDashboardData = data || null;
                // update totals safely (elements may have been removed)
                if (data.totals && data.totals.orders !== undefined) {
                    const periodOrdersEl = document.getElementById('periodOrdersTotal');
                    if (periodOrdersEl) periodOrdersEl.textContent = data.totals.orders;
                }
                if (data.totals && data.totals.revenue !== undefined) {
                    const periodRevenueEl = document.getElementById('periodRevenueTotal');
                    if (periodRevenueEl) periodRevenueEl.textContent = 'Rp ' + Number(data.totals.revenue).toLocaleString('id-ID');
                }

                if (revenueChartInstance && ordersChartInstance && data.labels && data.datasets) {
                    revenueChartInstance.data.labels = data.labels;
                    revenueChartInstance.data.datasets[0].data = data.datasets.revenue || [];
                    revenueChartInstance.update();

                    ordersChartInstance.data.labels = data.labels;
                    ordersChartInstance.data.datasets[0].data = data.datasets.orders || [];
                    ordersChartInstance.update();
                }

                // re-apply current table sort after data update (if any)
                const ordersSortSelect = document.getElementById('ordersSortSelect');
                if (ordersSortSelect) sortOrdersTable(ordersSortSelect.value || 'created_desc');
            })
            .catch(error => {
                console.log('Stats fetch error:', error);
            });
    }

    // Client-side sorter for orders table (uses data-* attributes on <tr>)
    function sortOrdersTable(criteria) {
        const tbody = document.querySelector('.stats-table tbody');
        if (!tbody) return;

        // collect rows that represent orders (have data-created-at)
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.dataset && r.dataset.createdAt);
        if (!rows.length) return;

        rows.sort((a, b) => {
            const aCreated = a.dataset.createdAt ? new Date(a.dataset.createdAt) : null;
            const bCreated = b.dataset.createdAt ? new Date(b.dataset.createdAt) : null;
            const aPemakaian = a.dataset.tanggalPemakaian ? new Date(a.dataset.tanggalPemakaian) : null;
            const bPemakaian = b.dataset.tanggalPemakaian ? new Date(b.dataset.tanggalPemakaian) : null;

            switch (criteria) {
                case 'created_desc':
                    return (bCreated || 0) - (aCreated || 0);
                case 'created_asc':
                    return (aCreated || 0) - (bCreated || 0);
                case 'tanggal_pemakaian_desc':
                    return (bPemakaian || 0) - (aPemakaian || 0);
                case 'tanggal_pemakaian_asc':
                    return (aPemakaian || 0) - (bPemakaian || 0);
                default:
                    return 0;
            }
        });

        // reattach rows in new order
        rows.forEach(r => tbody.appendChild(r));
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>