@extends('layouts.main')

@section('title', 'Kelola Data Pengguna - Rei Cosrent')

@section('styles')
<style>
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
    
    table th {
        background-color: var(--bs-primary);
        color: white;
        text-align: center;
        font-size: 1.0rem;
    }

    table td {
        font-size: 0.95rem;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .table-responsive { overflow-x: auto; }
</style>
@endsection

@section('content')
<header class="py-4 text-center">
    <div class="container">
        <h1 class="fw-bolder page-title mb-3">Kelola Data Pengguna</h1>
        <p class="text-muted">Edit atau hapus akun pengguna</p>
    </div>
</header>

<!-- Konten -->
<section class="container py-4">

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

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                    <a href="{{ route('admin.profile') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <div></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nick Name</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Nomor Telepon</th>
                                <th>Jenis Kelamin</th>
                                <th>Gambar Profil</th>
                                <th style="width: 220px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->nick_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->alamat }}</td>
                                    <td>{{ $user->nomor_telepon }}</td>
                                    <td>{{ $user->jenis_kelamin }}</td>
                                    <td>
                                        @php
                                            $avatarPath = $user->gambar_profil ? asset('storage/' . $user->gambar_profil) : null;
                                        @endphp
                                        @if($avatarPath)
                                            <img src="{{ $avatarPath }}" alt="Avatar" style="width:48px; height:48px; object-fit:cover; border:2px solid var(--bs-primary); border-radius:0;">
                                        @else
                                            <i class="bi bi-person-square" style="font-size: 1.5rem; color: var(--bs-body-color);"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <form method="POST" action="{{ route('admin.pengguna.delete', $user->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pengguna ini? Tindakan tidak dapat dibatalkan.');"><i class="bi bi-trash"></i> Hapus</button>
                                            </form>
                                        </div>
                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-warning text-white">
                                                        <h5 class="modal-title" id="editUserLabel{{ $user->id }}">Edit Pengguna #{{ $user->id }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.pengguna.update') }}" enctype="multipart/form-data" class="user-edit-section" data-user-id="{{ $user->id }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id" value="{{ $user->id }}">
                                                            <input type="hidden" name="remove_photo" value="0">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Username</label>
                                                                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Nick Name</label>
                                                                    <input type="text" name="nick_name" class="form-control" value="{{ old('nick_name', $user->nick_name) }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Email</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Nomor Telepon</label>
                                                                    <input type="text" name="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Password (opsional, minimal 8 karakter)</label>
                                                                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah" minlength="8">
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="form-label fw-semibold">Alamat</label>
                                                                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="row g-3 align-items-start">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Gambar Profil</label>
                                                                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                                                                <img class="user-preview {{ $user->gambar_profil ? '' : 'd-none' }}" src="{{ $user->gambar_profil ? asset('storage/' . $user->gambar_profil) : '' }}" alt="Preview" style="width:96px; height:96px; object-fit:cover; border:2px solid var(--bs-primary); border-radius:0;">
                                                                                <div class="user-fallback {{ $user->gambar_profil ? 'd-none' : '' }}" style="width:96px; height:96px; display:flex; align-items:center; justify-content:center; border:2px dashed var(--bs-primary); border-radius:0;">
                                                                                    <i class="bi bi-person-square" style="font-size: 2rem; color: var(--bs-body-color);"></i>
                                                                                </div>
                                                                                <div class="d-flex flex-column gap-2">
                                                                                    <button type="button" class="btn btn-outline-primary btn-sm btn-upload-user"><i class="bi bi-upload"></i> Unggah</button>
                                                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-mark-delete-user" style="{{ $user->gambar_profil ? '' : 'display:none;' }}"><i class="bi bi-trash"></i> Hapus</button>
                                                                                    <span class="delete-photo-note text-danger small" style="display:none;">Foto akan dihapus setelah disimpan.</span>
                                                                                </div>
                                                                            </div>
                                                                            <input type="file" name="gambar_profil" class="d-none" accept="image/*">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                                                                            <div class="d-flex align-items-center gap-3">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkPria{{ $user->id }}" value="Pria" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Pria' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label" for="jkPria{{ $user->id }}">Pria</label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jkWanita{{ $user->id }}" value="Wanita" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Wanita' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label" for="jkWanita{{ $user->id }}">Wanita</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Simpan Perubahan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted"><i class="bi bi-info-circle"></i> Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 3 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                try { (new bootstrap.Alert(alert)).close(); } catch {}
            }, 3000);
        });

        // Bind upload/delete handlers for each user edit section
        document.querySelectorAll('.user-edit-section').forEach(section => {
            const uploadBtn = section.querySelector('.btn-upload-user');
            const fileInput = section.querySelector('input[type=file][name="gambar_profil"]');
            const previewImg = section.querySelector('img.user-preview');
            const fallbackIcon = section.querySelector('.user-fallback');
            const deleteBtn = section.querySelector('.btn-mark-delete-user');
            const removePhotoInput = section.querySelector('input[name="remove_photo"]');
            const deleteNote = section.querySelector('.delete-photo-note');

            function syncAvatarDisplay(hasImage) {
                if (hasImage) {
                    previewImg && previewImg.classList.remove('d-none');
                    fallbackIcon && fallbackIcon.classList.add('d-none');
                    deleteBtn && (deleteBtn.style.display = '');
                } else {
                    previewImg && previewImg.classList.add('d-none');
                    fallbackIcon && fallbackIcon.classList.remove('d-none');
                    deleteBtn && (deleteBtn.style.display = 'none');
                }
            }

            if (uploadBtn && fileInput) {
                uploadBtn.addEventListener('click', () => fileInput.click());
                fileInput.addEventListener('change', (e) => {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const url = URL.createObjectURL(file);
                    if (previewImg) previewImg.src = url;
                    syncAvatarDisplay(true);
                    // Reset delete flag if uploading new image
                    if (removePhotoInput) removePhotoInput.value = '0';
                    if (deleteNote) deleteNote.style.display = 'none';
                });
            }

            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => {
                    // Mark for deletion, clear file input
                    if (removePhotoInput) removePhotoInput.value = '1';
                    if (fileInput) fileInput.value = '';
                    if (deleteNote) deleteNote.style.display = '';
                    syncAvatarDisplay(false);
                });
            }
        });
    });
</script>
@endsection
