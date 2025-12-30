@extends('layouts.main')

@section('title', 'Kelola Profil & Kontak - Rei Cosrent')

@section('styles')
<style>
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

    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--bs-secondary);
        padding: 0;
        font-size: 1.2rem;
        line-height: 1;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: var(--bs-primary);
    }
</style>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-between mb-5">
            <div class="col">
                <h2 class="fw-bold mb-0">Kelola Profil & Kontak</h2>
                <p class="text-muted mb-0">Update informasi pengurus dan kontak</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
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
            <!-- Current Profile Preview -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-xl h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-eye"></i> Preview Profil</h5>
                    </div>
                    <div class="card-body text-center py-4 d-flex flex-column align-items-center">
                        <img
                            id="profile_image_preview"
                            src="{{ $profile && $profile->photo ? asset('storage/' . $profile->photo) : '' }}"
                            alt=""
                            class="img-fluid rounded-circle mb-3 {{ $profile && $profile->photo ? '' : 'd-none' }}"
                            style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--bs-primary);">
                        <div id="profile_image_fallback" class="mb-3 {{ $profile && $profile->photo ? 'd-none' : '' }}">
                            <i class="bi bi-person-circle text-primary" style="font-size: 150px;"></i>
                        </div>
                        <button type="button" id="btn-upload-profile" class="btn btn-outline-primary mt-2" style="width: 100%; max-width: 200px;">
                            <i class="bi bi-upload"></i> Unggah Foto Profil
                        </button>
                        <button type="button" id="btn-mark-delete-photo" class="btn btn-outline-danger mt-2" style="width: 100%; max-width: 200px; {{ $profile && $profile->photo ? '' : 'display: none;' }}">
                            <i class="bi bi-trash"></i> Hapus Foto Profil
                        </button>
                        <div id="delete-photo-note" class="text-danger small mt-1" style="display: none;">
                            Foto akan dihapus setelah Anda klik Simpan Perubahan.
                        </div>
                        <hr class="my-4">
                        <div class="text-start small">
                            <h4 class="fw-bold mb-1 text-center">{{ $profile->name ?? 'Belum diisi' }}</h4>
                            <p class="text-primary mb-3 text-center">{{ $profile->title ?? 'Jabatan belum diisi' }}</p>
                            <p class="mb-2"><i class="bi bi-geo-alt-fill text-primary"></i> <strong>Alamat:</strong><br>{{ $profile->address ?? 'Belum diisi' }}</p>
                            <p class="mb-2"><i class="bi bi-telephone-fill text-primary"></i> <strong>Telepon:</strong><br>{{ $profile->phone ?? 'Belum diisi' }}</p>
                            <p class="mb-0"><i class="bi bi-envelope-fill text-primary"></i> <strong>Email:</strong><br>{{ $profile->email ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-xl">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Edit Informasi</h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="profileForm" method="POST" action="{{ route('admin.profile-contact.update') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="remove_photo" id="remove_photo" value="0">
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

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required>
                                @error('phone')
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
                                                @if($profile && $profile->qris)
                                                    <img id="qris_preview" src="{{ asset('storage/' . $profile->qris) }}" alt="QRIS" class="img-fluid rounded" style="max-width: 240px;">
                                                @else
                                                    <img id="qris_preview" src="" alt="QRIS" class="img-fluid rounded d-none" style="max-width: 240px;">
                                                    <div id="qris_none" class="text-muted"><i class="bi bi-info-circle"></i> Belum ada QRIS diunggah.</div>
                                                @endif
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <input type="file" name="qris" id="qris" class="form-control">
                                </div>
                            </div>
                    
                        <hr class="my-4">

                        <!-- Save button for main profile form (placed after QRIS section to swap positions) -->
                        <div class="d-flex mt-3">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('profileForm').submit();">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>
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
        let initialPhotoSrc = previewImg ? previewImg.getAttribute('src') : '';

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
            });
        }

        // Initialize button state based on existing photo
        if (deleteToggleBtn && removePhotoInput) {
            setRemoveState(removePhotoInput.value === '1');
        }

        // Final initial sync so icon shows when no image
        syncAvatarDisplay();
    });
</script>
@endsection
