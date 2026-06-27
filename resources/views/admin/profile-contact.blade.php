@extends('layouts.main')

@section('title', 'Profil & Kontak Admin - Rei Cosrent')

@section('styles')
    :root {
        --profile-card-bg: #f8fbff;
        --profile-card-border: rgba(37, 99, 235, 0.12);
        --profile-card-text: #0f172a;
        --profile-card-shadow: 0 12px 30px -12px rgba(16, 24, 40, 0.12);
    }

    [data-bs-theme="dark"] {
        --profile-card-bg: #0f172a;
        --profile-card-border: rgba(96, 165, 250, 0.16);
        --profile-card-text: #ffffff;
        --profile-card-shadow: 0 18px 40px -22px rgba(0, 0, 0, 0.55);
    }

    .user-profile-card {
        background: var(--profile-card-bg) !important;
        border: 1px solid var(--profile-card-border) !important;
        box-shadow: var(--profile-card-shadow) !important;
        color: var(--profile-card-text) !important;
    }

    .user-profile-card .card-header {
        background: transparent !important;
        color: var(--brand-blue) !important;
        border-bottom: 1px solid var(--profile-card-border) !important;
    }

    .user-profile-card .card-header h5,
    .user-profile-card .card-header i {
        color: var(--brand-blue) !important;
    }

    .user-profile-card .card-body,
    .user-profile-card .text-muted,
    .user-profile-card .form-label,
    .user-profile-card p,
    .user-profile-card label,
    .user-profile-card h5,
    .user-profile-card h6,
    .user-profile-card td,
    .user-profile-card .table {
        color: var(--profile-card-text) !important;
    }

    .user-profile-card .text-primary {
        color: var(--brand-blue) !important;
    }

    .user-profile-card .form-control,
    .user-profile-card .form-select,
    .user-profile-card textarea {
        background-color: var(--profile-card-bg) !important;
        color: var(--profile-card-text) !important;
        border: 1px solid var(--profile-card-border) !important;
    }

    .user-profile-card .form-control::placeholder,
    .user-profile-card textarea::placeholder {
        color: color-mix(in srgb, var(--profile-card-text) 70%, transparent) !important;
    }

    .user-profile-card .form-control:focus,
    .user-profile-card .form-select:focus,
    .user-profile-card textarea:focus {
        background-color: var(--profile-card-bg) !important;
        color: var(--profile-card-text) !important;
        border-color: var(--profile-card-border) !important;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.16) !important;
    }

    .preview-info-table {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease;
    }

    .preview-info-table td {
        padding: 0.25rem 0.5rem;
        vertical-align: top;
        transition: background-color 0s ease, color 0s ease, border-color 0s ease;
    }

    .preview-info-table .icon-col {
        width: 28px;
        color: var(--bs-primary);
        transition: color 0s ease;
    }

    .preview-info-table .label-col {
        width: 150px;
        white-space: nowrap;
        color: var(--bs-secondary-color);
        transition: color 0s ease;
    }

    .preview-info-table .colon-col {
        width: 12px;
        color: var(--bs-secondary-color);
        transition: color 0s ease;
    }

    .preview-info-table .value-col {
        word-break: break-word;
        transition: color 0s ease;
    }

    .preview-info-table .vision-text {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .preview-info-table tbody,
    .preview-info-table tr,
    .preview-info-table td {
        background-color: var(--profile-card-bg) !important;
    }

    body, section, .container, .row, .col-md-4, .col-md-8,
    .card, .card-header, .card-body,
    .alert, .alert-success, .alert-danger,
    .form-control, .form-label, .btn, .btn-primary,
    .btn-warning, .btn-secondary, .mb-3, hr, p, a, h3, h5, i, div, label, textarea {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }

    .form-control, .form-select, textarea {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }

    .form-control:focus, .form-select:focus, textarea:focus {
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    @media (max-width: 575.98px) {
        .preview-info-table td {
            padding: 0.25rem 0.25rem;
        }

        .preview-info-table .label-col {
            width: auto;
            white-space: normal;
        }

        .preview-info-table .colon-col {
            width: 10px;
        }
    }
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-5">
            <div>
                <h2 class="fw-bold mb-0">Profil & Kontak Admin</h2>
                <p class="text-muted mb-0">Kelola ringkasan profil, kontak, dan pembayaran</p>
            </div>
            <div class="d-grid d-sm-block w-100" style="max-width: 220px;">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            @php
                $profilePhotoSrc = $profile && $profile->photo
                    ? (str_starts_with($profile->photo, 'storage/') ? asset($profile->photo) : asset('storage/' . $profile->photo))
                    : null;
                $qrisSrc = $profile && $profile->qris
                    ? (str_starts_with($profile->qris, 'storage/') ? asset($profile->qris) : asset('storage/' . $profile->qris))
                    : null;
            @endphp

            <!-- Ringkasan Profil -->
            <div class="col-lg-4">
                <div class="card user-profile-card border-0 rounded-xl h-100">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-eye"></i> Ringkasan Profil</h5>
                    </div>
                    <div class="card-body text-center py-4 d-flex flex-column align-items-center">
                        <img
                            id="profile_image_preview"
                            src="{{ $profilePhotoSrc ?? '' }}"
                            alt=""
                            class="img-fluid rounded-circle mb-3 {{ $profilePhotoSrc ? '' : 'd-none' }}"
                            style="width: 150px; height: 150px; object-fit: cover; border: 1px solid var(--bs-border-color);">
                        <div id="profile_image_fallback" class="mb-3 {{ $profilePhotoSrc ? 'd-none' : '' }}" style="width: 150px; height: 150px; align-items: center; justify-content: center; display: flex;">
                            <i class="bi bi-person-circle" style="font-size: 96px; color: var(--bs-body-color);"></i>
                        </div>
                        <button type="button" id="btn-upload-profile" class="btn btn-outline-primary mt-2" style="width: 100%; max-width: 200px;">
                            <i class="bi bi-upload"></i> {{ $profilePhotoSrc ? 'Ganti Foto Profil' : 'Unggah Foto Profil' }}
                        </button>
                        <button type="button" id="btn-mark-delete-photo" class="btn btn-outline-danger mt-2" style="width: 100%; max-width: 200px; {{ $profilePhotoSrc ? '' : 'display: none;' }}">
                            <i class="bi bi-trash"></i> Hapus Foto Profil
                        </button>
                        <div id="delete-photo-note" class="text-danger small mt-1" style="display: none;">
                            Foto akan dihapus setelah Anda klik Simpan Perubahan.
                        </div>
                        <hr class="my-4">
                        <div class="mt-3 text-start align-self-stretch w-100">
                            <table class="table table-sm preview-info-table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-person"></i></td>
                                        <td class="label-col">Nama</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col fw-semibold">{{ $profile->name ?? 'Belum diisi' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-briefcase"></i></td>
                                        <td class="label-col">Jabatan</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $profile->title ?? 'Belum diisi' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-chat-left-text"></i></td>
                                        <td class="label-col">Tentang Saya</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col"><span class="vision-text">{{ $profile->vision ?? 'Belum diisi' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-geo-alt"></i></td>
                                        <td class="label-col">Alamat</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col"><span class="vision-text">{{ $profile->address ?? 'Belum diisi' }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-telephone"></i></td>
                                        <td class="label-col">Telepon</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $profile->phone ?? 'Belum diisi' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-instagram"></i></td>
                                        <td class="label-col">Instagram</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $profile && $profile->instagram ? '@' . ltrim($profile->instagram, '@') : 'Belum diisi' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-envelope"></i></td>
                                        <td class="label-col">Email</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $profile->email ?? 'Belum diisi' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profil -->
            <div class="col-lg-8">
                <div class="card user-profile-card border-0 rounded-xl">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Edit Profil</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="profileForm" method="POST" action="{{ route('admin.profile-contact.update') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                            <input type="hidden" name="remove_qris" id="remove_qris" value="0">
                            <input type="file" class="d-none" id="profile_image_input" name="photo" accept="image/*">
                            @error('photo')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <h6 class="fw-bold mb-3 text-primary">Data Pengurus Utama</h6>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $profile->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label fw-semibold">Jabatan</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $profile->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="vision" class="form-label fw-semibold">Tentang Saya</label>
                                <textarea class="form-control @error('vision') is-invalid @enderror" id="vision" name="vision" rows="3" required>{{ old('vision', $profile->vision ?? '') }}</textarea>
                                @error('vision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3 text-primary">Informasi Kontak</h6>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $profile->address ?? '') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="origin_province_id" class="form-label fw-semibold">ID Provinsi</label>
                                    <input type="number" class="form-control @error('origin_province_id') is-invalid @enderror" id="origin_province_id" name="origin_province_id" value="{{ old('origin_province_id', $profile->origin_province_id ?? '') }}" placeholder="ex: 12">
                                    @error('origin_province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="origin_city_id" class="form-label fw-semibold">ID Kota</label>
                                    <input type="number" class="form-control @error('origin_city_id') is-invalid @enderror" id="origin_city_id" name="origin_city_id" value="{{ old('origin_city_id', $profile->origin_city_id ?? '') }}" placeholder="ex: 3307">
                                    @error('origin_city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="origin_postal_code" class="form-label fw-semibold">Kode Pos</label>
                                    <input type="text" class="form-control @error('origin_postal_code') is-invalid @enderror" id="origin_postal_code" name="origin_postal_code" value="{{ old('origin_postal_code', $profile->origin_postal_code ?? '') }}" placeholder="ex: 43165">
                                    @error('origin_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="instagram" class="form-label fw-semibold">Instagram</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" id="instagram" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" placeholder="Contoh: rei_cosrent">
                                @error('instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $profile->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold mb-3 text-primary">Informasi Pembayaran</h6>

                            <div class="mb-3">
                                <label for="nomor_ewallet" class="form-label fw-semibold">Nomor E-Wallet</label>
                                <input type="text" class="form-control @error('nomor_ewallet') is-invalid @enderror" id="nomor_ewallet" name="nomor_ewallet" value="{{ old('nomor_ewallet', $profile->nomor_ewallet ?? '') }}" placeholder="Contoh: 081234567890">
                                @error('nomor_ewallet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nomor_bank" class="form-label fw-semibold">Nomor Rekening / Bank</label>
                                <input type="text" class="form-control @error('nomor_bank') is-invalid @enderror" id="nomor_bank" name="nomor_bank" value="{{ old('nomor_bank', $profile->nomor_bank ?? '') }}" placeholder="Contoh: 1234567890 - Bank ABC">
                                @error('nomor_bank')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 row align-items-center">
                                <label for="qris" class="form-label fw-semibold">QRIS</label>
                                <div class="col-md-6 mb-2">
                                    <img id="qris_preview" src="{{ $qrisSrc ?? '' }}" alt="QRIS" class="img-fluid rounded {{ $qrisSrc ? '' : 'd-none' }}" style="max-width: 240px;">
                                    <div id="qris_none" class="text-muted {{ $qrisSrc ? 'd-none' : '' }}"><i class="bi bi-info-circle"></i> QRIS belum tersedia.</div>
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="qris" id="qris" class="form-control" accept="image/*">
                                    <button type="button" id="btn-mark-delete-qris" class="btn btn-outline-danger mt-2" style="width: 100%; max-width: 240px; {{ $qrisSrc ? '' : 'display: none;' }}">
                                        <i class="bi bi-trash"></i> Hapus QRIS
                                    </button>
                                    <div id="delete-qris-note" class="text-danger small mt-1" style="display: none;">
                                        QRIS akan dihapus setelah Anda klik Simpan Perubahan.
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 3 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });

        // Profile image upload + preview with deferred deletion
        const uploadBtn = document.getElementById('btn-upload-profile');
        const fileInput = document.getElementById('profile_image_input');
        const previewImg = document.getElementById('profile_image_preview');
        const fallbackIcon = document.getElementById('profile_image_fallback');
        const deleteToggleBtn = document.getElementById('btn-mark-delete-photo');
        const removePhotoInput = document.getElementById('remove_photo');
        const deleteNote = document.getElementById('delete-photo-note');
        const qrisInput = document.getElementById('qris');
        const qrisPreview = document.getElementById('qris_preview');
        const qrisNone = document.getElementById('qris_none');
        const deleteQrisToggleBtn = document.getElementById('btn-mark-delete-qris');
        const removeQrisInput = document.getElementById('remove_qris');
        const deleteQrisNote = document.getElementById('delete-qris-note');
        let initialPhotoSrc = previewImg ? previewImg.getAttribute('src') : '';
        let initialQrisSrc = qrisPreview ? qrisPreview.getAttribute('src') : '';
        let qrisLoadFailed = false;

        if (qrisPreview) {
            const currentSrc = qrisPreview.getAttribute('src');
            if (currentSrc && qrisPreview.complete && qrisPreview.naturalWidth === 0) {
                qrisLoadFailed = true;
            }
        }

        function setUploadButtonText(hasPhoto) {
            if (uploadBtn) {
                uploadBtn.innerHTML = hasPhoto
                    ? '<i class="bi bi-upload"></i> Ganti Foto Profil'
                    : '<i class="bi bi-upload"></i> Unggah Foto Profil';
            }
        }

        // Ensure correct initial display between image preview and icon
        function syncAvatarDisplay() {
            const rawSrc = previewImg ? previewImg.getAttribute('src') : '';
            const hasSrc = !!rawSrc;
            if (hasSrc) {
                previewImg.classList.remove('d-none');
                if (fallbackIcon) fallbackIcon.classList.add('d-none');
            } else {
                if (previewImg) previewImg.classList.add('d-none');
                if (fallbackIcon) fallbackIcon.classList.remove('d-none');
            }

            setUploadButtonText(hasSrc);
        }

        if (uploadBtn && fileInput) {
            uploadBtn.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', (e) => {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const previewUrl = URL.createObjectURL(file);
                
                // Update preview image and swap icon
                if (previewImg) {
                    previewImg.setAttribute('src', previewUrl);
                    previewImg.classList.remove('d-none');
                }
                if (fallbackIcon) {
                    fallbackIcon.classList.add('d-none');
                }

                // Reset remove flag when uploading new photo
                if (removePhotoInput) {
                    removePhotoInput.value = '0';
                }

                // Ensure delete toggle is visible after selecting a file
                if (deleteToggleBtn) {
                    deleteToggleBtn.style.display = 'block';
                    setRemoveState(false);
                }

                setUploadButtonText(true);
            });
        }

        function setRemoveState(isRemoving) {
            if (removePhotoInput) {
                removePhotoInput.value = isRemoving ? '1' : '0';
            }
            if (deleteNote) {
                deleteNote.style.display = isRemoving ? 'block' : 'none';
            }
            if (deleteToggleBtn) {
                deleteToggleBtn.classList.toggle('btn-danger', isRemoving);
                deleteToggleBtn.classList.toggle('btn-outline-danger', !isRemoving);
                deleteToggleBtn.innerHTML = isRemoving
                    ? '<i class="bi bi-arrow-counterclockwise"></i> Batal Hapus Foto'
                    : '<i class="bi bi-trash"></i> Hapus Foto Profil';
            }

            if (previewImg) {
                const rawSrc = previewImg.getAttribute('src');
                const hasSrc = !!rawSrc;
                previewImg.classList.toggle('d-none', isRemoving || !hasSrc);
            }

            if (fallbackIcon) {
                const rawSrc = previewImg ? previewImg.getAttribute('src') : '';
                const hasSrc = !!rawSrc;
                const shouldShowFallback = isRemoving || !hasSrc;
                fallbackIcon.classList.toggle('d-none', !shouldShowFallback);
            }
        }

        if (deleteToggleBtn) {
            deleteToggleBtn.addEventListener('click', () => {
                const isRemoving = removePhotoInput && removePhotoInput.value === '0';
                setRemoveState(isRemoving);

                if (isRemoving && fileInput) {
                    fileInput.value = '';
                }

                // Re-sync avatar display after toggling remove
                syncAvatarDisplay();

                // If removing, also clear preview src so image disappears immediately
                if (isRemoving && previewImg) {
                    previewImg.setAttribute('src', '');
                }

                // If cancelling removal and there was an original photo, restore it
                if (!isRemoving && initialPhotoSrc) {
                    if (previewImg) {
                        previewImg.setAttribute('src', initialPhotoSrc);
                        previewImg.classList.remove('d-none');
                    }
                    if (fallbackIcon) fallbackIcon.classList.add('d-none');
                }

                setUploadButtonText(!isRemoving && !!initialPhotoSrc);
            });
        }

        // Initialize button state based on existing photo
        if (deleteToggleBtn && removePhotoInput) {
            setRemoveState(removePhotoInput.value === '1');
        }

        // Final initial sync so icon shows when no image
        syncAvatarDisplay();

        function setRemoveQrisState(isRemoving) {
            if (removeQrisInput) {
                removeQrisInput.value = isRemoving ? '1' : '0';
            }

            if (deleteQrisNote) {
                deleteQrisNote.style.display = isRemoving ? 'block' : 'none';
            }

            if (deleteQrisToggleBtn) {
                deleteQrisToggleBtn.classList.toggle('btn-danger', isRemoving);
                deleteQrisToggleBtn.classList.toggle('btn-outline-danger', !isRemoving);
                deleteQrisToggleBtn.innerHTML = isRemoving
                    ? '<i class="bi bi-arrow-counterclockwise"></i> Batal Hapus QRIS'
                    : '<i class="bi bi-trash"></i> Hapus QRIS';
            }

            const rawSrc = qrisPreview ? qrisPreview.getAttribute('src') : '';
            const hasSrc = !!rawSrc && !qrisLoadFailed;

            if (qrisPreview) {
                qrisPreview.classList.toggle('d-none', isRemoving || !hasSrc);
            }

            if (qrisNone) {
                const shouldShowNone = isRemoving || !hasSrc;
                qrisNone.classList.toggle('d-none', !shouldShowNone);
            }

            if (deleteQrisToggleBtn) {
                const hasInitial = !!initialQrisSrc;
                const hasSelectedFile = !!(qrisInput && qrisInput.files && qrisInput.files.length > 0);
                deleteQrisToggleBtn.style.display = (isRemoving || hasInitial || hasSelectedFile) ? 'block' : 'none';
            }
        }

        if (qrisInput && qrisPreview) {
            qrisInput.addEventListener('change', function(e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const previewUrl = URL.createObjectURL(file);
                qrisLoadFailed = false;
                qrisPreview.src = previewUrl;
                setRemoveQrisState(false);
            });
        }

        if (qrisPreview) {
            qrisPreview.addEventListener('load', () => {
                qrisLoadFailed = false;
                setRemoveQrisState(removeQrisInput && removeQrisInput.value === '1');
            });

            qrisPreview.addEventListener('error', () => {
                qrisLoadFailed = true;
                setRemoveQrisState(removeQrisInput && removeQrisInput.value === '1');
            });
        }

        if (deleteQrisToggleBtn) {
            deleteQrisToggleBtn.addEventListener('click', () => {
                const isRemoving = removeQrisInput && removeQrisInput.value === '0';

                if (isRemoving) {
                    if (qrisInput) {
                        qrisInput.value = '';
                    }
                    if (qrisPreview) {
                        qrisPreview.setAttribute('src', '');
                    }
                } else {
                    if (qrisPreview) {
                        qrisPreview.setAttribute('src', initialQrisSrc || '');
                    }
                }

                setRemoveQrisState(isRemoving);
            });
        }

        // Initialize QRIS remove state + visibility
        if (removeQrisInput) {
            setRemoveQrisState(removeQrisInput.value === '1');
        } else {
            setRemoveQrisState(false);
        }
    });
</script>
@endsection
