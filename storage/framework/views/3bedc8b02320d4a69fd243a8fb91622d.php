

<?php $__env->startSection('title', 'Profil Admin - Rei Cosrent'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    body, section, .container, .row, .col-md-4, .col-md-8,
    .card, .card-header, .card-body, 
    .alert, .alert-success, .alert-danger,
    .btn, .btn-primary, .btn-lg, .btn-info, .btn-warning,
    .mb-3, hr, p, a, h3, h5, i, div, label, .badge {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }
    
    .btn:hover {
        transition: all 0.3s ease;
    }

    .stat-card {
        border-left: 4px solid var(--bs-primary);
    }

    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--bs-primary);
    }

    .menu-card {
        cursor: pointer;
        text-decoration: none;
        color: var(--bs-body-color);
        transition: transform 0.3s ease, box-shadow 0.3s ease,
            background-color 0s ease, color 0s ease, border-color 0s ease;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .menu-card h5,
    .menu-card p,
    .menu-card span,
    .menu-card .text-muted {
        color: var(--bs-body-color) !important;
    }

    [data-bs-theme="dark"] .menu-card h5,
    [data-bs-theme="dark"] .menu-card p,
    [data-bs-theme="dark"] .menu-card span,
    [data-bs-theme="dark"] .menu-card .text-muted {
        color: #e5e7eb !important;
    }

    .stat-number,
    .menu-icon,
    .badge,
    .text-muted,
    .card,
    .card-body,
    .card-header,
    h4, h5, h2, p, span {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }

    .menu-icon {
        font-size: 3rem;
        color: var(--bs-primary);
        margin-bottom: 1rem;
    }

    /* Adapt stat-number and total data text for dark/light mode */
    .stat-number {
        color: var(--bs-primary);
    }

    [data-bs-theme="dark"] .stat-number {
        color: #a855f7;
    }

    [data-bs-theme="light"] .stat-number {
        color: var(--bs-primary);
    }

    .total-data-label {
        transition: color 0s ease;
    }

    [data-bs-theme="dark"] .total-data-label {
        color: #d1d5db !important;
    }

    [data-bs-theme="light"] .total-data-label {
        color: #6c757d !important;
    }

    .section-label {
        letter-spacing: 0.02em;
    }

    .info-row i {
        font-size: 1.1rem;
    }
    /* Sidebar styles */
    .app-sidebar {
        position: fixed;
        left: 0;
        top: var(--nav-height, 56px);
        height: calc(100vh - var(--nav-height, 56px));
        width: 320px;
        max-width: 85vw;
        background: var(--bs-body-bg);
        transform: translateX(-110%);
        transition: transform 1s cubic-bezier(.2,.8,.2,1), box-shadow 0.2s ease;
        z-index: 1040;
        overflow-y: auto;
        padding-bottom: 3rem;
    }
    .app-sidebar.open {
        transform: translateX(0);
        box-shadow: 0 12px 40px rgba(2,6,23,0.12);
    }
    .sidebar-close {
        border: none;
        background: transparent;
        font-size: 1.2rem;
    }
    /* When sidebar is open, push main content (page wrapper) */
    #pageWrapper.shifted {
        margin-left: 320px;
        transition: margin-left 1s cubic-bezier(.2,.8,.2,1);
    }
    #pageWrapper {
        transition: margin-left 1s cubic-bezier(.2,.8,.2,1);
    }
    /* Make menu cards full width inside sidebar */
    .app-sidebar .menu-card { display: block; width: 100%; }
    .app-sidebar .card-body { padding: 1rem !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="py-5">
    <div id="pageWrapper">
    <div class="container">
        <div class="row justify-content-between mb-5">
            <div class="col">
                <h1 class="fw-bold mb-0">Profil Admin</h1>
                <p class="text-muted mb-0">Kelola sistem dan data aplikasi Rei Cosrent</p>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="card shadow-lg border-0 rounded-xl mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">Selamat datang, <?php echo e($profile_contact ? $profile_contact->name : (auth()->user()->name ?? 'Admin')); ?>!</h4>
                        <p class="text-muted mb-0">Anda login sebagai Administrator</p>
                        <?php if($profile_contact): ?>
                            <small class="text-primary"><?php echo e($profile_contact->title); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content Section -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="fw-bold mb-0">Profil & Kontak</h3>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo e(route('admin.profile-contact')); ?>" class="btn btn-outline-primary"><i class="bi bi-pencil-square"></i> Kelola Profil</a>
                <a href="<?php echo e(route('admin.logout')); ?>" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-xl mb-5">
            <div class="card-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center">
                        <?php
                            $adminPhoto = $profile_contact && $profile_contact->photo
                                ? asset('storage/' . $profile_contact->photo)
                                : $adminPhoto ?? null;
                            $profileName = $profile_contact ? $profile_contact->name : "<i class='bi bi-info-circle'></i> Belum diisi";
                            $profileTitle = $profile_contact ? $profile_contact->title : "<i class='bi bi-info-circle'></i> Jabatan belum diisi";
                            $profileVision = $profile_contact ? $profile_contact->vision : "<i class='bi bi-info-circle'></i> Belum ada deskripsi singkat.";
                            $profileAddress = $profile_contact ? $profile_contact->address : "<i class='bi bi-info-circle'></i> Belum diisi";
                            $profilePhone = $profile_contact ? $profile_contact->phone : "<i class='bi bi-info-circle'></i> Belum diisi";
                            $profileEmail = $profile_contact ? $profile_contact->email : "<i class='bi bi-info-circle'></i> Belum diisi";
                        ?>
                        <?php if($adminPhoto): ?>
                            <img src="<?php echo e($adminPhoto); ?>" alt="Foto Pengurus" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--bs-primary);">
                        <?php else: ?>
                            <div class="d-inline-flex align-items-center justify-content-center mb-3">
                                <i class="bi bi-person-circle text-primary" style="font-size: 120px;"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="fw-bold mb-0"><?php echo e($profileName); ?></h5>
                        <p class="text-primary mb-2"><?php echo e($profileTitle); ?></p>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Tentang Saya</p>
                            <div class="p-3 rounded bg-body-tertiary">
                                <span style="color: var(--bs-body-color);"><?php echo e($profileVision); ?></span>
                            </div>
                        </div>
                        <div class="row g-3 info-row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Alamat</p>
                                        <span class="fw-semibold"><?php echo e($profileAddress); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Telepon</p>
                                        <span class="fw-semibold"><?php echo e($profilePhone); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Email</p>
                                        <span class="fw-semibold"><?php echo e($profileEmail); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-person-badge-fill text-primary me-2"></i>
                                    <div>
                                        <p class="text-muted small mb-0">Nama Admin</p>
                                        <span class="fw-semibold"><?php echo e($profile_contact ? $profile_contact->name : (auth()->user()->name ?? 'Admin')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <!-- Dashboard Content Section -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="fw-bold mb-0">Konten Dashboard</h3>
            </div>
        </div>

        <!-- Summary & Charts -->
        <div class="card shadow-sm border-0 rounded-xl mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold mb-0">Ringkasan Pesanan & Pendapatan</h5>
                        <p class="text-muted small mb-0">Pilih rentang waktu untuk melihat data</p>
                    </div>
                    <div class="btn-group" role="group" aria-label="Timeframe buttons">
                        <button class="btn btn-outline-primary timeframe-btn active" data-period="day">Hari</button>
                        <button class="btn btn-outline-primary timeframe-btn" data-period="week">Minggu</button>
                        <button class="btn btn-outline-primary timeframe-btn" data-period="month">Bulan</button>
                        <button class="btn btn-outline-primary timeframe-btn" data-period="year">Tahun</button>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded stat-card bg-body-tertiary">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Total Pesanan</div>
                                    <div class="stat-number" id="totalOrders">-</div>
                                </div>
                                <div class="text-muted small"> <i class="bi bi-bag-check" style="font-size:1.6rem"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded stat-card bg-body-tertiary">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Total Pendapatan</div>
                                    <div class="stat-number" id="totalRevenue">-</div>
                                </div>
                                <div class="text-muted small"><i class="bi bi-cash-stack" style="font-size:1.6rem"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded bg-body-tertiary h-100">
                            <h6 class="mb-2 fw-semibold">Grafik Pesanan</h6>
                            <canvas id="ordersChart" height="140"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="p-3 rounded bg-body-tertiary h-100">
                            <h6 class="mb-2 fw-semibold">Grafik Pendapatan</h6>
                            <canvas id="revenueChart" height="140"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="admin-app">
            <!-- Sidebar (only shown to admin actor accounts) -->
            <?php if(session('admin_logged_in') && request()->routeIs('admin.profile')): ?>
            <aside id="appSidebar" class="app-sidebar">
                <div class="d-flex align-items-center justify-content-between px-3 pt-3">
                    <h5 class="mb-0">Kelola Data</h5>
                    <button id="sidebarClose" class="sidebar-close" aria-label="Tutup sidebar"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="p-3">
                    <div class="d-grid gap-3">

                        <a href="<?php echo e(route('admin.data-pengguna')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-people"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Data Pengguna</h6>
                                    <small class="text-muted">Total: <?php echo e($users_count); ?></small>
                                </div>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('admin.data-katalog')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-collection"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Data Katalog</h6>
                                    <small class="text-muted">Total: <?php echo e($katalog_count); ?></small>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.data-kostum')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-box"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Data Kostum</h6>
                                    <small class="text-muted">Total: <?php echo e($kostum_count); ?></small>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.data-aturan')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-file-earmark-text"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Data Aturan</h6>
                                    <small class="text-muted">Total: <?php echo e($aturan_count); ?></small>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.data-pesanan')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-bag-check"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Pesanan & Pembayaran</h6>
                                    <small class="text-muted">Total: <?php echo e($pesanan_count); ?></small>
                                </div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('admin.data-denda')); ?>" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-exclamation-triangle"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Data Denda & Kerusakan</h6>
                                    <small class="text-muted">Total: <?php echo e($denda_count); ?></small>
                                </div>
                            </div>
                        </a>

                        <a href="https://docs.google.com/spreadsheets/d/1Z3OneYIfDxKs0I0rX-_yZQfFLBb-UHf4TcC4P8oqZsI/edit" target="_blank" rel="noopener noreferrer" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                            <div class="card-body p-3 d-flex align-items-center">
                                <div class="menu-icon me-3 mb-0"><i class="bi bi-calendar3"></i></div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-0">Kelola Tanggal Pesanan</h6>
                                    <small class="text-muted">Google Sheets</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </aside>
            <?php endif; ?>

            <!-- Main area placeholder (actual page content is wrapped in #pageWrapper) -->
            <div class="admin-main" id="adminMain"></div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ordersCtx = document.getElementById('ordersChart');
        const revenueCtx = document.getElementById('revenueChart');
        let ordersChart = null;
        let revenueChart = null;

        function formatCurrency(v) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v);
        }

        async function loadStats(period = 'week') {
            try {
                const res = await fetch(`<?php echo e(url('/admin/stats')); ?>?period=${period}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Failed to load stats');
                const json = await res.json();

                document.getElementById('totalOrders').textContent = json.totals.orders;
                document.getElementById('totalRevenue').textContent = formatCurrency(json.totals.revenue || 0);

                const labels = json.labels;
                const ordersData = json.datasets.orders;
                const revenueData = json.datasets.revenue;

                // Orders chart (simple line)
                const ordersDataset = {
                    label: 'Pesanan',
                    data: ordersData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.08)',
                    tension: 0.2,
                    pointRadius: 3,
                    fill: true
                };

                // Revenue chart (line with currency ticks)
                const revenueDataset = {
                    label: 'Pendapatan (IDR)',
                    data: revenueData,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,0.08)',
                    tension: 0.2,
                    pointRadius: 3,
                    fill: true
                };

                if (ordersChart) {
                    ordersChart.data.labels = labels;
                    ordersChart.data.datasets = [ordersDataset];
                    ordersChart.update();
                } else {
                    ordersChart = new Chart(ordersCtx, {
                        type: 'line',
                        data: { labels: labels, datasets: [ordersDataset] },
                        options: {
                            responsive: true,
                            interaction: { mode: 'index', intersect: false },
                            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                            plugins: { legend: { display: false } }
                        }
                    });
                }

                if (revenueChart) {
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets = [revenueDataset];
                    revenueChart.update();
                } else {
                    revenueChart = new Chart(revenueCtx, {
                        type: 'line',
                        data: { labels: labels, datasets: [revenueDataset] },
                        options: {
                            responsive: true,
                            interaction: { mode: 'index', intersect: false },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value); }
                                    }
                                }
                            },
                            plugins: { legend: { display: false }, tooltip: { callbacks: { label: function(context) { return formatCurrency(context.parsed.y || 0); } } } }
                        }
                    });
                }
            } catch (err) {
                console.error(err);
            }
        }

        // timeframe buttons
        document.querySelectorAll('.timeframe-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.timeframe-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const period = this.getAttribute('data-period');
                loadStats(period);
            });
        });

        // initial load (week)
        loadStats('week');
    });
</script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById('openSidebar');
            const layoutToggle = document.getElementById('layoutSidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');
            const sidebar = document.getElementById('appSidebar');
            const pageWrapper = document.getElementById('pageWrapper');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('open');
                if (pageWrapper) pageWrapper.classList.add('shifted');
            }

            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('open');
                if (pageWrapper) pageWrapper.classList.remove('shifted');
            }

            if (openBtn) {
                openBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSidebar();
                    openBtn.setAttribute('aria-expanded', 'true');
                    if (layoutToggle) layoutToggle.setAttribute('aria-expanded', 'true');
                });
                // keyboard access (Enter / Space)
                openBtn.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        openBtn.click();
                    }
                });
            }

            // Also bind navbar layout toggle if present
            if (layoutToggle) {
                layoutToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    openSidebar();
                    layoutToggle.setAttribute('aria-expanded', 'true');
                    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
                });
                layoutToggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        layoutToggle.click();
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeSidebar();
                    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
                    if (layoutToggle) layoutToggle.setAttribute('aria-expanded', 'false');
                });
                closeBtn.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        closeBtn.click();
                    }
                });
            }

            // click outside to close
            document.addEventListener('click', function(e) {
                if (!sidebar || !sidebar.classList.contains('open')) return;
                const target = e.target;
                if (sidebar.contains(target) || (openBtn && openBtn.contains(target)) || (layoutToggle && layoutToggle.contains(target))) return;
                closeSidebar();
                if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
                if (layoutToggle) layoutToggle.setAttribute('aria-expanded', 'false');
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\rc_laravel\resources\views/admin/profile.blade.php ENDPATH**/ ?>