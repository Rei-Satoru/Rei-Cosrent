@extends('layouts.main')

@section('title', 'Data Tanggal Booking - Rei Cosrent')

@section('styles')
<style>
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

    .booking-table {
        color: var(--booking-card-text);
        background: var(--booking-card-bg);
    }

    .booking-table thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: color-mix(in srgb, var(--booking-card-bg) 90%, var(--brand-blue));
        color: var(--brand-blue) !important;
        white-space: nowrap;
        vertical-align: middle;
        border-bottom: 1px solid var(--booking-card-border) !important;
    }

    .booking-table tbody td {
        vertical-align: middle;
        background: var(--booking-card-bg);
        border-color: var(--booking-card-border) !important;
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
</style>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Tanggal Booking</h2>
            </div>
            @if($isAdmin)
                <div class="d-grid d-sm-block w-100" style="max-width: 220px;">
                    <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-left"></i> Kembali</a>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card booking-surface-card mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">Pilih Tahun & Bulan</h5>
                    <p class="mb-0">Tampilkan kalender booking berdasarkan tahun dan bulan yang dipilih.</p>
                </div>
                <form method="GET" class="d-flex gap-2 align-items-center">
                    <label class="visually-hidden">Tahun</label>
                    <select name="year" class="form-select form-select-sm">
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <label class="visually-hidden">Bulan</label>
                    <select name="month" class="form-select form-select-sm">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == $selectedMonth ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary" type="submit">Tampilkan</button>
                </form>
            </div>
        </div>

        <div class="card booking-surface-card mb-4">
            <div class="card-header py-3">
                <h5 class="fw-bold mb-0">{{ DateTime::createFromFormat('!m', $selectedMonth)->format('F') }} {{ $selectedYear }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="booking-table-wrap">
                    <table class="table table-bordered mb-0 booking-table">
                        <thead>
                            <tr>
                                <th style="min-width: 70px;">No</th>
                                <th style="min-width: 180px;">Nama Kostum</th>
                                @foreach($dates as $d)
                                    <th style="min-width: 120px;">{{ \Carbon\Carbon::parse($d)->format('d M') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kostums as $index => $k)
                                <tr>
                                    <td class="text-center fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $k->nama_kostum }}</td>
                                    @foreach($dates as $d)
                                        <td>
                                            @if(isset($bookingMap[$k->nama_kostum][$d]))
                                                <span class="booking-slot-badge">{{ $bookingMap[$k->nama_kostum][$d] }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
