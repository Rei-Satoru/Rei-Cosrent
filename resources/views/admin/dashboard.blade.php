@extends('layouts.main')

@section('title', 'Dashboard Admin - Rei Cosrent')

@section('styles')
<style>
    :root {
        --bs-primary: #7c3aed;
        --bs-success: #10b981;
        --bs-danger: #ef4444;
    }

    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .dashboard-hero h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .dashboard-hero p {
        opacity: 0.9;
        margin-bottom: 1rem;
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
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .metric-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
    }

    .metric-label {
        font-size: 0.875rem;
        color: #6b7280;
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
        border-radius: 12px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .tab-buttons {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 1rem;
    }

    .tab-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        margin-bottom: -1.5rem;
    }

    .tab-btn.active {
        color: #7c3aed;
        border-bottom-color: #7c3aed;
    }

    .stats-table {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .stats-table th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 1rem;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
    }

    .stats-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .stats-table tbody tr:last-child td {
        border-bottom: none;
    }

    .stats-table tbody tr:hover {
        background-color: #f9fafb;
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
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .data-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .data-card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #7c3aed;
    }

    .data-card-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: #1f2937;
    }

    .data-card-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.5rem;
    }

    .btn-manage {
        background: #7c3aed;
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
        background: #6d28d9;
        color: white;
        text-decoration: none;
    }

    .profile-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
    }

    .profile-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
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
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .profile-title {
        color: #7c3aed;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .profile-info-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .profile-info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .profile-info-icon {
        color: #7c3aed;
        font-size: 1.25rem;
        margin-right: 1rem;
        min-width: 30px;
    }

    .profile-info-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .profile-info-value {
        font-weight: 600;
        color: #1f2937;
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
        background: #7c3aed;
    }

    @media (max-width: 768px) {
        .dashboard-hero h1 {
            font-size: 1.5rem;
        }

        .metric-value {
            font-size: 1.5rem;
        }

        .tab-buttons {
            flex-direction: column;
        }

        .stats-table th,
        .stats-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
    }

    [data-bs-theme="dark"] .metric-card,
    [data-bs-theme="dark"] .chart-container,
    [data-bs-theme="dark"] .stats-table,
    [data-bs-theme="dark"] .data-card,
    [data-bs-theme="dark"] .profile-card {
        background: #1f2937;
        border-color: #374151;
    }

    [data-bs-theme="dark"] .metric-value,
    [data-bs-theme="dark"] .data-card-value,
    [data-bs-theme="dark"] .profile-name {
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .metric-label,
    [data-bs-theme="dark"] .profile-info-label {
        color: #9ca3af;
    }

    [data-bs-theme="dark"] .stats-table th {
        background: #111827;
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .stats-table tbody tr:hover {
        background-color: #111827;
    }

    [data-bs-theme="dark"] .profile-info-value {
        color: #f3f4f6;
    }
</style>
@endsection

@section('content')
<div id="pageWrapper">
    <section class="py-4">
        <div class="container-fluid">
            <!-- Hero Section -->
        <div class="dashboard-hero">
            <div class="row align-items-center">
                <div class="col">
                    <h1>👋 Selamat Datang {{ auth()->user()->name ?? 'Admin' }}</h1>
                    <p class="mb-2">Kelola sistem dan data aplikasi Rei Cosrent</p>
                    @if($profile_contact)
                        <small style="opacity: 0.9;">Berposisi sebagai: <strong>{{ $profile_contact->title }}</strong></small>
                    @endif
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.profile-contact') }}" class="hero-btn me-2">
                        <i class="bi bi-pencil-square"></i> Edit Profil
                    </a>
                    <a href="{{ route('admin.logout') }}" class="hero-btn">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Total Pesanan</div>
                    <div class="metric-value" id="totalOrders">{{ $pesanan_count ?? 0 }}</div>
                    <div class="metric-change positive">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Total Pendapatan</div>
                    <div class="metric-value" id="totalRevenue">Rp {{ number_format($total_revenue ?? 0, 0, ',', '.') }}</div>
                    <div class="metric-change positive">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-3">
                <div class="metric-card">
                    <div class="metric-label">Total Pengguna</div>
                    <div class="metric-value">{{ $users_count ?? 0 }}</div>
                    <div class="metric-change positive">
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Grafik Pendapatan</h5>
                        <div class="tab-buttons">
                            <button class="tab-btn active" onclick="switchChart('income')">Total Pendapatan</button>
                            <button class="tab-btn" onclick="switchChart('expense')">Total Pengeluaran</button>
                        </div>
                    </div>
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>

            <!-- Product Sales -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="fw-bold mb-3">Kostum Paling Sering Dipesankan</h5>
                    <canvas id="productChart" height="100"></canvas>
                    <div class="mt-3">
                        @php
                            $total_top = $top_3_kostum->sum('count');
                        @endphp
                        @foreach($top_3_kostum as $index => $item)
                            @php
                                $percentage = $total_top > 0 ? ($item->count / $total_top) * 100 : 0;
                            @endphp
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted small">{{ $item->nama_kostum }}</span>
                                <strong>{{ number_format($percentage, 1) }}%</strong>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                            </div>
                        @endforeach
                        @if($other_count > 0)
                            @php
                                $other_percentage = $total_top > 0 ? ($other_count / ($total_top + $other_count)) * 100 : 0;
                            @endphp
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted small">Lainnya ({{ $other_count }})</span>
                                <strong>{{ number_format($other_percentage, 1) }}%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $other_percentage }}%"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        <!-- Data Kostum Overview -->
        @php
            $overviewItems = [
                ['label' => 'Data Katalog', 'count' => (int) ($katalog_count ?? 0), 'icon' => '📚'],
                ['label' => 'Data Kostum', 'count' => (int) ($kostum_count ?? 0), 'icon' => '👗'],
                ['label' => 'Data Ulasan', 'count' => (int) ($ulasan_count ?? 0), 'icon' => '⭐'],
                ['label' => 'Data Aturan', 'count' => (int) ($aturan_count ?? 0), 'icon' => '📄'],
            ];
            $overviewLabels = collect($overviewItems)->pluck('label')->toArray();
            $overviewCounts = collect($overviewItems)->pluck('count')->toArray();
            $hasOverviewData = collect($overviewCounts)->sum() > 0;
        @endphp

        <div class="chart-container mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0">Data Kostum</h5>
                    <p class="text-muted mb-0">Ringkasan data dari database: katalog, kostum, ulasan, dan aturan</p>
                </div>
            </div>

            @if($hasOverviewData)
                <div class="row g-4 mb-4">
                    <div class="col-1">
                        <canvas id="overviewChart" height="220"></canvas>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 align-items-stretch">
                    @foreach($overviewItems as $item)
                        <div class="col">
                            <div class="data-card h-100 d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="data-card-icon">{{ $item['icon'] }}</div>
                                <div class="data-card-value">{{ $item['count'] }}</div>
                                <div class="data-card-label">{{ $item['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-4 text-muted"></i>
                    <h6 class="mt-3 mb-1">Belum ada data</h6>
                    <p class="text-muted mb-0">Data katalog, kostum, ulasan, dan aturan masih kosong.</p>
                </div>
            @endif
        </div>

        <!-- Profile Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="profile-card">
                    <h5 class="fw-bold mb-3">Profil Admin</h5>
                    @php
                        $adminPhoto = $profile_contact && $profile_contact->photo
                            ? asset('storage/' . $profile_contact->photo)
                            : null;
                        $profileName = $profile_contact ? $profile_contact->name : "Belum diisi";
                        $profileTitle = $profile_contact ? $profile_contact->title : "Jabatan belum diisi";
                        $profileVision = $profile_contact ? $profile_contact->vision : "Belum ada deskripsi singkat";
                        $profileAddress = $profile_contact ? $profile_contact->address : "Belum diisi";
                        $profilePhone = $profile_contact ? $profile_contact->phone : "Belum diisi";
                        $profileEmail = $profile_contact ? $profile_contact->email : "Belum diisi";
                    @endphp
                    <div class="text-center mb-3">
                        @if($adminPhoto)
                            <img src="{{ $adminPhoto }}" alt="Foto Admin" class="profile-photo">
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 4px solid #7c3aed;">
                                <i class="bi bi-person" style="font-size: 3rem; color: #9ca3af;"></i>
                            </div>
                        @endif
                        <h4 class="profile-name">{{ $profileName }}</h4>
                        <p class="profile-title">{{ $profileTitle }}</p>
                        <span class="badge product-badge badge-success">Aktif</span>
                    </div>
                    <hr>
                    <div class="profile-info-row">
                        <i class="bi bi-quote profile-info-icon"></i>
                        <div>
                            <div class="profile-info-label">Tentang</div>
                            <div class="profile-info-value">{{ $profileVision }}</div>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <i class="bi bi-geo-alt profile-info-icon"></i>
                        <div>
                            <div class="profile-info-label">Alamat</div>
                            <div class="profile-info-value">{{ $profileAddress }}</div>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <i class="bi bi-telephone profile-info-icon"></i>
                        <div>
                            <div class="profile-info-label">Telepon</div>
                            <div class="profile-info-value">{{ $profilePhone }}</div>
                        </div>
                    </div>
                    <div class="profile-info-row">
                        <i class="bi bi-envelope profile-info-icon"></i>
                        <div>
                            <div class="profile-info-label">Email</div>
                            <div class="profile-info-value">{{ $profileEmail }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gender Sales -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                    <canvas id="genderChart" height="150"></canvas>
                    <div class="mt-3 small">
                        @forelse($payment_methods as $method)
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted">{{ $method->metode_pembayaran ?? 'Belum ditentukan' }}</span>
                                <strong>{{ $method->count }}</strong>
                            </div>
                        @empty
                            <p class="text-muted">Belum ada data</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="chart-container">
            <h5 class="fw-bold mb-3">Pesanan Terbaru</h5>
            <div class="table-responsive">
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
                        @forelse($latest_orders as $order)
                            <tr>
                                <td>{{ $order->nama }}</td>
                                <td>{{ $order->nama_kostum }}</td>
                                <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') }}</td>
                                <td>
                                    @php
                                        $status_badge = [
                                            'pending' => 'badge-warning',
                                            'confirmed' => 'badge-info',
                                            'completed' => 'badge-success',
                                            'cancelled' => 'badge-danger',
                                        ];
                                        $badge_class = $status_badge[$order->status] ?? 'badge-info';
                                    @endphp
                                    <span class="product-badge {{ $badge_class }}">{{ ucfirst($order->status ?? 'pending') }}</span>
                                </td>
                                <td><a href="#" class="text-primary text-decoration-none small"><i class="bi bi-eye"></i> Lihat</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="fw-bold mb-3">Status Pesanan</h5>
                    <div class="row g-3">
                        @php
                            $status_colors = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                            ];
                        @endphp
                        @forelse($order_statuses as $status_item)
                            @php
                                $color = $status_colors[$status_item->status] ?? 'secondary';
                                $percentage = $pesanan_count > 0 ? ($status_item->count / $pesanan_count) * 100 : 0;
                            @endphp
                            <div class="col-md-6">
                                <div class="location-progress">
                                    <div class="location-progress-label">
                                        <span>{{ ucfirst($status_item->status) }}</span>
                                        <span>{{ $status_item->count }} ({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">
                                Belum ada data pesanan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="fw-bold mb-3">Top Kostum Dipesankan</h5>
                    <div class="table-responsive">
                        <table class="stats-table w-100" style="font-size: 0.875rem;">
                            <thead>
                                <tr>
                                    <th>Kostum</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($top_kostum->take(6) as $item)
                                    <tr>
                                        <td>{{ Str::limit($item->nama_kostum, 20) }}</td>
                                        <td><strong>{{ $item->count }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-2">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div> <!-- End pageWrapper -->

<!-- Sidebar Navigation -->
<aside id="appSidebar" class="app-sidebar">
    <div class="d-flex align-items-center justify-content-between px-3 pt-3">
        <h5 class="mb-0">Kelola Data</h5>
        <button id="sidebarClose" class="sidebar-close" aria-label="Tutup sidebar"><i class="bi bi-x-lg"></i></button>
    </div>
    <div class="p-3">
        <div class="d-grid gap-3">
            <a href="{{ route('admin.data-pengguna') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-people"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Pengguna</h6>
                        <small class="text-muted">Total: {{ $users_count }}</small>
                    </div>
                </div>
            </a>
            
            <a href="{{ route('admin.data-katalog') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-collection"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Katalog</h6>
                        <small class="text-muted">Total: {{ $katalog_count }}</small>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data-kostum') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-box"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Kostum</h6>
                        <small class="text-muted">Total: {{ $kostum_count }}</small>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data-aturan') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Aturan</h6>
                        <small class="text-muted">Total: {{ $aturan_count }}</small>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data-pesanan') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-bag-check"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Pesanan & Pembayaran</h6>
                        <small class="text-muted">Total: {{ $pesanan_count }}</small>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data-denda') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Denda & Kerusakan</h6>
                        <small class="text-muted">Total: {{ $denda_count }}</small>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.data-ulasan') }}" class="card menu-card shadow-sm border-0 rounded-xl text-decoration-none">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="menu-icon me-3 mb-0"><i class="bi bi-chat-square-text"></i></div>
                    <div class="flex-grow-1">
                        <h6 class="fw-semibold mb-0">Data Ulasan</h6>
                        <small class="text-muted">Total: {{ $ulasan_count }}</small>
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
        width: 320px;
        max-width: 85vw;
        background: var(--bs-body-bg);
        transform: translateX(-110%);
        transition: transform 0.3s cubic-bezier(.2,.8,.2,1), box-shadow 0.2s ease;
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
        cursor: pointer;
        color: var(--bs-body-color);
    }
    #pageWrapper {
        transition: margin-left 0.3s cubic-bezier(.2,.8,.2,1);
    }
    #pageWrapper.shifted {
        margin-left: 320px;
        transition: margin-left 0.3s cubic-bezier(.2,.8,.2,1);
    }
    .app-sidebar .menu-card { 
        display: block; 
        width: 100%; 
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.3s ease;
    }
    .app-sidebar .menu-card:hover {
        transform: translateY(-2px);
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
        color: var(--bs-body-color);
    }
    .app-sidebar small {
        color: var(--bs-body-color);
        opacity: 0.7;
    }

    @media (max-width: 768px) {
        #pageWrapper.shifted {
            margin-left: 0;
        }
    }

    [data-bs-theme="dark"] .app-sidebar {
        background: #1f2937;
        border-right: 1px solid #374151;
    }

    [data-bs-theme="dark"] .app-sidebar .menu-card {
        background: #111827;
        border-color: #374151 !important;
    }

    [data-bs-theme="dark"] .app-sidebar .menu-card:hover {
        background: #1f2937;
    }

    [data-bs-theme="dark"] .app-sidebar h6 {
        color: #f3f4f6;
    }

    [data-bs-theme="dark"] .app-sidebar small {
        color: #9ca3af;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.0/dist/apexcharts.min.js"></script>
<script>
    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        fetchDashboardStats();
    });

    function initializeCharts() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [30000, 35000, 32000, 40000, 45000, 50000],
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#7c3aed',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        const overviewCanvas = document.getElementById('overviewChart');
        if (overviewCanvas) {
            const overviewLabels = {!! json_encode($overviewLabels) !!};
            const overviewCounts = {!! json_encode($overviewCounts) !!};

            new Chart(overviewCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: overviewLabels,
                    datasets: [{
                        data: overviewCounts,
                        backgroundColor: ['#7c3aed', '#667eea', '#f093fb', '#10b981'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Product Chart - Kostum breakdown
        const productCtx = document.getElementById('productChart').getContext('2d');
        const kostumData = {!! json_encode($top_3_kostum->pluck('nama_kostum')->toArray()) !!};
        const kostumCounts = {!! json_encode($top_3_kostum->pluck('count')->toArray()) !!};
        
        const colors = ['#7c3aed', '#667eea', '#a78bfa'];
        
        new Chart(productCtx, {
            type: 'doughnut',
            data: {
                labels: kostumData,
                datasets: [{
                    data: kostumCounts,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Payment Methods Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const paymentMethods = {!! json_encode($payment_methods->pluck('metode_pembayaran')->toArray()) !!};
        const paymentCounts = {!! json_encode($payment_methods->pluck('count')->toArray()) !!};
        
        const paymentColors = ['#667eea', '#f093fb', '#f5576c', '#ffa502'];
        
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethods,
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: paymentColors.slice(0, paymentMethods.length),
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function switchChart(type) {
        // Switch between income and expense charts
        console.log('Switching to:', type);
    }

    function fetchDashboardStats() {
        // Fetch stats from API if needed
        fetch('/admin/stats')
            .then(response => response.json())
            .then(data => {
                if (data.totals && data.totals.orders) {
                    document.getElementById('totalOrders').textContent = data.totals.orders;
                }
                if (data.totals && data.totals.revenue) {
                    document.getElementById('totalRevenue').textContent = 'Rp ' + parseInt(data.totals.revenue).toLocaleString('id-ID');
                }
            })
            .catch(error => console.log('Stats fetch error:', error));
    }
</script>
@endpush

@endsection
