@extends('layouts.main')

@section('title', 'Profil - Rei Cosrent')

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

    .user-profile-card .preview-info-table,
    .user-profile-card .preview-info-table tbody,
    .user-profile-card .preview-info-table tr,
    .user-profile-card .preview-info-table td {
        background-color: var(--profile-card-bg) !important;
        color: var(--profile-card-text) !important;
        border-color: var(--profile-card-border) !important;
    }

    body, section, .container, .row, .col-md-4, .col-md-8,
    .card, .card-header, .card-body, 
    .alert, .alert-success, .alert-danger,
    .form-control, .form-label, .btn, .btn-primary,
    .btn-warning, .btn-secondary, .mb-3, hr, p, a, h3, h5, i, div, label {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }
    
    .form-control, .form-select {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }
    
    .form-control:focus, .form-select:focus {
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

    /* Preview profile info alignment */
    .preview-info-table { transition: background-color 0s ease, color 0s ease, border-color 0s ease; }
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

    .profile-page-note {
        color: #000000 !important;
    }

    [data-bs-theme="dark"] .profile-page-note {
        color: #ffffff !important;
    }

    @media (max-width: 575.98px) {
        .preview-info-table td { padding: 0.25rem 0.25rem; }
        .preview-info-table .label-col { width: auto; white-space: normal; }
        .preview-info-table .colon-col { width: 10px; }
    }
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-5">
            <div>
                <h2 class="fw-bold mb-0">Profil Pengguna</h2>
                <p class="profile-page-note mb-0">Kelola informasi akun Anda</p>
            </div>
            <div class="d-grid d-sm-block w-100" style="max-width: 220px;">
                <a href="{{ route('home') }}#kategori" class="btn btn-outline-primary w-100"><i class="bi bi-arrow-left"></i> Kembali</a>
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
            <!-- Profile Info Card -->
            <div class="col-lg-4">
                <div class="card user-profile-card border-0 rounded-xl h-100">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-eye"></i> Ringkasan Profil</h5>
                    </div>
                    <div class="card-body text-center py-4 d-flex flex-column align-items-center">
                        <img
                            id="profile_image_preview"
                            src="{{ $user->gambar_profil ? asset('storage/' . $user->gambar_profil) : '' }}"
                            alt=""
                            class="img-fluid rounded-circle mb-3 {{ $user->gambar_profil ? '' : 'd-none' }}"
                            style="width: 150px; height: 150px; object-fit: cover; border: 1px solid var(--bs-border-color);">
                        <div id="profile_image_fallback" class="mb-3 {{ $user->gambar_profil ? 'd-none' : '' }}" style="width: 150px; height: 150px; align-items: center; justify-content: center; display: flex;">
                            <i class="bi bi-person-circle" style="font-size: 96px; color: var(--bs-body-color);"></i>
                        </div>
                        <button type="button" id="btn-upload-profile" class="btn btn-outline-primary mt-2" style="width: 100%; max-width: 200px;">
                            <i class="bi bi-upload"></i> {{ $user->gambar_profil ? 'Ganti Foto Profil' : 'Unggah Foto Profil' }}
                        </button>
                        <button type="button" id="btn-mark-delete-photo" class="btn btn-outline-danger mt-2" style="width: 100%; max-width: 200px; {{ $user->gambar_profil ? '' : 'display: none;' }}">
                            <i class="bi bi-trash"></i> Hapus Foto Profil
                        </button>
                        <div id="delete-photo-note" class="text-danger small mt-1" style="display: none;">
                            Foto akan dihapus setelah Anda klik Simpan Perubahan.
                        </div>
                        <hr class="my-4">
                        <div class="small text-muted">
                            <p class="mb-2">
                                <strong>Bergabung:</strong><br>
                                {{ $user->created_at->format('d M Y') }}
                            </p>
                            <p class="mb-0">
                                <strong>Terakhir Diperbarui:</strong><br>
                                {{ $user->updated_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <hr class="my-4">
                        <div class="mt-3 text-start align-self-stretch w-100">
                            <table class="table table-sm preview-info-table mb-0">
                                <tbody>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-person-badge"></i></td>
                                        <td class="label-col">Username</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col fw-semibold">{{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-person"></i></td>
                                        <td class="label-col">Nama</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $user->nick_name ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-envelope"></i></td>
                                        <td class="label-col">Email</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-envelope"></i></td>
                                        <td class="label-col">Instagram</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ trim((string) session('user_instagram')) ?: $user->instagram ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-geo-alt"></i></td>
                                        <td class="label-col">Alamat</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col vision-text">{{ $user->alamat ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-telephone"></i></td>
                                        <td class="label-col">Nomor Telepon</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $user->nomor_telepon ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="icon-col"><i class="bi bi-gender-ambiguous"></i></td>
                                        <td class="label-col">Jenis Kelamin</td>
                                        <td class="colon-col">:</td>
                                        <td class="value-col">{{ $user->jenis_kelamin ?: '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 w-100">
                            <div class="d-grid gap-2">
                                <a href="{{ route('user.pesanan') }}" class="btn btn-outline-primary d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-receipt"></i> Pesanan Saya
                                </a>
                                <a href="{{ route('user.pengembalian') }}" class="btn btn-outline-success d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pengembalian
                                </a>
                                <a href="{{ route('user.denda-saya') }}" class="btn btn-outline-warning d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-exclamation-triangle"></i> Denda Saya
                                </a>
                                <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center gap-2 w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    <i class="bi bi-trash"></i> Hapus Akun
                                </button>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-danger d-flex align-items-center justify-content-center gap-2 w-100">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="col-lg-8">
                <div class="card user-profile-card border-0 rounded-xl">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Edit Profil</h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="remove_photo" id="remove_photo" value="0">

                            <input type="file" class="d-none" id="profile_image_input" name="gambar_profil" accept="image/*">
                            @error('gambar_profil')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <h6 class="fw-bold mb-3 text-primary">Informasi Akun</h6>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Username</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required pattern="[a-z0-9_]+" title="Username hanya boleh huruf kecil, angka, dan underscore (_), tanpa spasi">
                                <small class="text-muted">Hanya huruf kecil, angka, dan underscore (_). Tidak boleh ada spasi.</small>
                                @error('username')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nick_name" class="form-label fw-semibold">Nama</label>
                                <input type="text" class="form-control @error('nick_name') is-invalid @enderror" id="nick_name" name="nick_name" value="{{ old('nick_name', $user->nick_name) }}" placeholder="Opsional">
                                @error('nick_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="instagram" class="form-label fw-semibold">Instagram</label>
                                    <input type="text"
                                        class="form-control @error('instagram') is-invalid @enderror"
                                        id="instagram"
                                        name="instagram"
                                        value="{{ old('instagram', trim((string) session('user_instagram')) ?: $user->instagram) }}"
                                        placeholder="Opsional"
                                        maxlength="50"
                                        autocomplete="off">
                                @error('instagram')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Masukkan username Instagram tanpa @.</small>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap anda">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" placeholder="08xxxxxxxxxx" pattern="08[0-9]{8,13}" title="Nomor telepon harus diawali 08 dan berisi 10-15 digit">
                                <small class="text-muted">Format: 08xxxxxxxxxx.</small>
                                @error('nomor_telepon')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-block">Jenis Kelamin</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_pria" value="Pria" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Pria' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenis_kelamin_pria">Pria</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jenis_kelamin_wanita" value="Wanita" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'Wanita' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenis_kelamin_wanita">Wanita</label>
                                </div>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2 flex-wrap">
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

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #0d6efd !important; color: #ffffff !important; border-color: #0d6efd !important;">

                <h5 class="modal-title" id="deleteAccountModalLabel" style="color: #ffffff !important;">
                    <i class="bi bi-exclamation-triangle" style="color: #ffffff !important;"></i> Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.account.delete') }}" id="deleteAccountForm">
                @csrf
                @method('DELETE')
                <div class="modal-body" style="background-color: #0f172af5 !important; color: #ffffff !important;">
                    <div class="alert alert-warning mb-3" style="background-color: #0f172af5 !important; border-color: rgba(255,255,255,0.15) !important; color: #ffffff !important;">


                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
                    </div>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label fw-semibold" style="color: #ffffff !important;">Masukkan Password untuk Konfirmasi</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="delete_password" name="password" required placeholder="Masukkan password Anda" style="padding-right: 40px; background-color: #0f172af5 !important; color: #ffffff !important; border-color: rgba(255, 255, 255, 0.15) !important;">

                            <button type="button" class="password-toggle" onclick="togglePassword('delete_password')" style="color: #ffffff !important;">
                                <i class="bi bi-eye" id="delete_password-icon" style="color: #ffffff !important;"></i>
                            </button>
                        </div>
                        <small class="text-muted" style="color: #ffffff !important;">Konfirmasi dengan memasukkan password Anda.</small>

                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required style="background-color: #0f172af5 !important; border-color: #0d6efd !important;" >
                        <label class="form-check-label" for="confirmDelete" style="color: #ffffff !important;">
                            Saya memahami bahwa akun saya akan dihapus permanen
                        </label>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #0f172af5 !important; border-top-color: #0f172af5 !important; color: #ffffff !important;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="color: #ffffff !important;">
                        <i class="bi bi-x-circle" style="color: #ffffff !important;"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn" disabled style="color: #ffffff !important; border-color: #0d6efd !important;">
                        <i class="bi bi-trash" style="color: #ffffff !important;"></i> Hapus Akun Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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

        // Profile image upload + preview
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

        // Delete account confirmation checkbox
        const confirmDeleteCheckbox = document.getElementById('confirmDelete');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const deletePasswordInput = document.getElementById('delete_password');
        
        if (confirmDeleteCheckbox && confirmDeleteBtn) {
            const checkDeleteValidity = () => {
                const isChecked = confirmDeleteCheckbox.checked;
                const hasPassword = deletePasswordInput && deletePasswordInput.value.length > 0;
                confirmDeleteBtn.disabled = !(isChecked && hasPassword);
            };
            
            confirmDeleteCheckbox.addEventListener('change', checkDeleteValidity);
            if (deletePasswordInput) {
                deletePasswordInput.addEventListener('input', checkDeleteValidity);
            }
        }

        // Delete account form submission
        const deleteAccountForm = document.getElementById('deleteAccountForm');
        if (deleteAccountForm) {
            deleteAccountForm.addEventListener('submit', (e) => {
                if (!confirm('TERAKHIR KALI: Apakah Anda BENAR-BENAR yakin ingin menghapus akun ini? Tindakan ini TIDAK DAPAT dibatalkan!')) {
                    e.preventDefault();
                }
            });
        }

        // Instagram input UX: strip leading @ on blur and normalize while typing
        const instagramInput = document.getElementById('instagram');
        if (instagramInput) {
            instagramInput.addEventListener('input', function () {
                // allow typing but remove any accidental leading spaces
                if (this.value && this.value.startsWith(' ')) {
                    this.value = this.value.trimStart();
                }
            });
            instagramInput.addEventListener('blur', function () {
                if (!this.value) return;
                // remove leading @ characters and surrounding whitespace
                this.value = this.value.replace(/^@+/, '').trim();
            });
        }
    });
    
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
