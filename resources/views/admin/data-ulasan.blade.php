@extends('layouts.main')

@section('title', 'Data Ulasan - Rei Cosrent')

@section('styles')
    /* Force all muted text (email/nick) to be white */
    .text-muted,
    .text-muted * { color: #ffffff !important; }

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

    .balasan-textarea {
        min-height: 110px;
        background-color: #0f172af5 !important;
        color: var(--bs-body-color) !important;
    }

    .ulasan-thumb {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 0;
        border: 0;
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
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Ulasan</h2>
                <p class="text-muted mb-0 small">Admin dapat membalas ulasan berdasarkan ID pesanan (Formulir)</p>
            </div>
            <div class="d-grid d-sm-block">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif

        <div class="card mb-3" style="background-color: #0f172af5; border: none;">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 w-100">
                    <div class="input-group" style="background-color: #94a3b829; border-radius: 0.75rem; border: 1px solid rgba(148,163,184,0.35);">
                        <span class="input-group-text bg-transparent border-0"><i class="bi bi-search"></i></span>
                        <input id="search-admin-ulasan" type="search" class="form-control border-0 bg-transparent" placeholder="Cari pesanan, pengguna, ulasan, rating..." aria-label="Cari ulasan">
                    </div>
                    <select id="sort-admin-ulasan" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6;">
                        <option value="">Urutkan data ulasan</option>
                        <option value="1:string:asc">Pesanan A–Z</option>
                        <option value="1:string:desc">Pesanan Z–A</option>
                        <option value="2:numeric:desc">Rating Tertinggi</option>
                        <option value="2:numeric:asc">Rating Terendah</option>
                        <option value="3:string:asc">Ulasan A–Z</option>
                        <option value="3:string:desc">Ulasan Z–A</option>
                    </select>

                    <select id="filter-admin-ulasan-tahun" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6; min-width: 130px;">
                        <option value="">Semua Tahun</option>
                    </select>

                    <select id="filter-admin-ulasan-bulan" class="form-select" style="background-color: #94a3b829; border: 1px solid rgba(148,163,184,0.35); color: #dee2e6; min-width: 130px;">
                        <option value="">Semua Bulan</option>
                    </select>
                </div>
                    <div class="col-md-3 text-md-end">
                        <button id="reset-admin-ulasan" type="button" class="btn btn-light w-100">Reset Pencarian</button>
                    </div>
                </div>
            </div>

        @if(isset($ulasanList) && $ulasanList->count() > 0)
            <div class="table-responsive">
                <table id="adminUlasanTable" class="table table-hover align-middle orders-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Pesanan</th>
                            <th style="width: 120px;">Rating</th>
                            <th>Ulasan</th>
                            <th class="d-none d-md-table-cell" style="width: 160px;">Gambar</th>
                            <th class="d-none d-md-table-cell" style="width: 360px;">Balasan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ulasanList as $index => $u)
                                @php
                                    $images = [];
                                    for ($i = 1; $i <= 5; $i++) {
                                        $field = 'gambar_' . $i;
                                        if (!empty($u->$field)) {
                                            $images[$i] = $u->$field;
                                        }
                                    }
                                @endphp
                                <tr data-date="{{ $u->created_at ? $u->created_at->toDateString() : '' }}">
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td class="text-start">
                                        <div class="fw-semibold">{{ $u->nama_kostum ?? '-' }}</div>
                                        <div class="text-muted" style="font-size:0.85rem;">{{ $u->nama_user ?? 'User' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-warning" aria-label="Rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ ((int)$u->rating >= $i) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="text-start">
                                        @if(!empty($u->review))
                                            {{ $u->review }}
                                        @else
                                            <span class="text-muted">(Tidak ada teks ulasan)</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if(!empty($images))
                                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#ulasanImagesModal{{ $u->id }}">
                                                <i class="bi bi-images"></i> Lihat Gambar
                                            </button>

                                            <div class="modal fade" id="ulasanImagesModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title">Gambar Ulasan</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                @foreach($images as $num => $img)
                                                                    <div class="col-6 col-md-4">
                                                                        <button
                                                                            type="button"
                                                                            class="btn p-0 border-0 bg-transparent"
                                                                            data-preview-src="{{ asset('storage/' . $img) }}"
                                                                            data-preview-title="Gambar {{ $num }}"
                                                                            onclick="return openUlasanAdminImagePreview(this.dataset.previewSrc, this.dataset.previewTitle)"
                                                                            aria-label="Lihat Gambar {{ $num }}"
                                                                        >
                                                                            <img src="{{ asset('storage/' . $img) }}" alt="Gambar {{ $num }}" class="img-fluid ulasan-thumb">
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="text-muted mt-2" style="font-size:0.85rem;">Klik gambar untuk membuka ukuran penuh.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-md-table-cell text-start">
                                        <form method="POST" action="{{ route('admin.ulasan.balas') }}">
                                            @csrf
                                            <input type="hidden" name="formulir_id" value="{{ $u->id }}">
                                            <textarea name="balasan" class="form-control balasan-textarea" placeholder="Tulis balasan admin...">{{ old('balasan', $u->balasan) }}</textarea>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="bi bi-send"></i> Simpan Balasan
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center mb-0">Belum ada ulasan.</div>
            @endif
        </div>
    </div>
</section>

<!-- Preview Modal Gambar Ulasan (reusable) -->
<div class="modal fade" id="ulasanAdminImagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="ulasanAdminImagePreviewTitle">Gambar Ulasan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="ulasanAdminImagePreviewImg" src="" alt="Preview" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openUlasanAdminImagePreview(src, title) {
        const img = document.getElementById('ulasanAdminImagePreviewImg');
        if (img) img.src = src;

        const titleEl = document.getElementById('ulasanAdminImagePreviewTitle');
        if (titleEl) titleEl.textContent = title || 'Gambar Ulasan';

        const modalEl = document.getElementById('ulasanAdminImagePreviewModal');
        if (!modalEl || !window.bootstrap) return false;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        return false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('ulasanAdminImagePreviewModal');
        if (!modalEl) return;
        modalEl.addEventListener('hidden.bs.modal', function () {
            const img = document.getElementById('ulasanAdminImagePreviewImg');
            if (img) img.src = '';

            const titleEl = document.getElementById('ulasanAdminImagePreviewTitle');
            if (titleEl) titleEl.textContent = 'Gambar Ulasan';
        });
    });
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

        // Apply Tahun/Bulan filter + Search/Sort
        const yearSelect = document.getElementById('filter-admin-ulasan-tahun');
        const monthSelect = document.getElementById('filter-admin-ulasan-bulan');
        const table = document.getElementById('adminUlasanTable');
        const searchInput = document.getElementById('search-admin-ulasan');
        const sortSelect = document.getElementById('sort-admin-ulasan');
        const resetButton = document.getElementById('reset-admin-ulasan');

        if (table && searchInput && sortSelect && resetButton && yearSelect && monthSelect) {
            const tbody = table.tBodies[0];
            const originalRows = tbody ? Array.from(tbody.rows) : [];

            const years = new Set();
            const months = new Set();
            originalRows.forEach(tr => {
                const d = tr.getAttribute('data-date') || '';
                const parts = d ? d.split('-') : [];
                if (parts.length >= 2) {
                    if (parts[0]) years.add(parts[0]);
                    if (parts[1]) months.add(parts[1]);
                }
            });

            const sortedYears = Array.from(years).sort((a,b)=>parseInt(a,10)-parseInt(b,10));
            yearSelect.innerHTML = '<option value="">Semua Tahun</option>';
            sortedYears.forEach(y => yearSelect.insertAdjacentHTML('beforeend', `<option value="${y}">${y}</option>`));

            const monthOrder = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            const bulanName = { '01':'Januari','02':'Februari','03':'Maret','04':'April','05':'Mei','06':'Juni','07':'Juli','08':'Agustus','09':'September','10':'Oktober','11':'November','12':'Desember' };
            monthSelect.innerHTML = '<option value="">Semua Bulan</option>';
            monthOrder.forEach(m => {
                if (months.has(m)) monthSelect.insertAdjacentHTML('beforeend', `<option value="${m}">${bulanName[m]}</option>`);
            });

            function updateRows() {
                const query = normalizeText(searchInput.value);
                const yearVal = yearSelect.value;
                const monthVal = monthSelect.value;

                let filtered = originalRows.filter(row => {
                    const d = row.getAttribute('data-date') || '';
                    const parts = d ? d.split('-') : [];
                    const y = parts.length >= 1 ? parts[0] : '';
                    const m = parts.length >= 2 ? parts[1] : '';
                    const okYear = (!yearVal) || (y === yearVal);
                    const okMonth = (!monthVal) || (m === monthVal);
                    return okYear && okMonth;
                }).filter(row => normalizeText(row.textContent).includes(query));

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
    });
</script>
@endsection
