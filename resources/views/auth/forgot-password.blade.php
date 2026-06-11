@extends('layouts.main')

@section('title', 'Lupa Password - Rei Cosrent')

@section('styles')
<style>
    body, section, .container, .row, .col-lg-5, .col-md-7,
    .card, .card-header, .card-body,
    form, .form-control, .form-label,
    .btn, .btn-primary, .btn-lg, .d-grid,
    .alert, .alert-success, .alert-danger,
    .mb-3, hr, p, a, small, h3, i,
    input, button, label, div {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease, transform 0s ease;
    }

    .form-control:focus {
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    :root {
        --auth-card-bg: var(--app-surface);
        --auth-card-border: var(--bs-border-color);
        --auth-card-text: var(--brand-blue);
        --auth-input-bg: rgba(255, 255, 255, 0.92);
        --auth-input-text: var(--brand-blue);
        --auth-placeholder: rgba(15, 23, 42, 0.6);
        --auth-muted: var(--brand-blue);
    }

    [data-bs-theme="dark"] {
        --auth-card-bg: rgba(15, 23, 42, 0.95);
        --auth-card-border: rgba(96, 165, 250, 0.2);
        --auth-card-text: #ffffff;
        --auth-input-bg: rgba(15, 23, 42, 0.7);
        --auth-input-text: #ffffff;
        --auth-placeholder: rgba(255, 255, 255, 0.6);
        --auth-muted: #ffffff;
    }

    .auth-card {
        background: var(--auth-card-bg);
        border: 1px solid var(--auth-card-border) !important;
        color: var(--auth-card-text);
    }

    .auth-card .card-header {
        background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%);
        background-color: transparent;
        color: #ffffff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    }

    .auth-card .card-header p {
        color: #ffffff !important;
    }

    .auth-card .form-control,
    .auth-card .form-select {
        background: var(--auth-input-bg);
        color: var(--auth-input-text);
        border-color: var(--auth-card-border);
    }

    .auth-card .form-control::placeholder {
        color: var(--auth-placeholder);
    }

    .auth-card,
    .auth-card p,
    .auth-card label,
    .auth-card .form-label,
    .auth-card a,
    .auth-card small {
        color: var(--auth-card-text) !important;
    }

    .auth-card .text-muted {
        color: var(--auth-muted) !important;
    }

    .auth-card .auth-footer,
    .auth-card .auth-footer a {
        color: #ffffff !important;
    }
</style>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-xl auth-card">
                    <div class="card-header text-center py-4 rounded-top">
                        <h3 class="mb-0 fw-bold">Lupa Password</h3>
                        <p class="mb-0 small">Masukkan email Anda untuk meminta reset password.</p>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> {{ session('status') }}
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

                        @if(!empty($approved) && !empty($email))
                            <div class="alert alert-success">
                                <strong>Permintaan reset sudah disetujui.</strong>
                                <div class="small">Silakan masukkan password baru dan konfirmasi di bawah.</div>
                            </div>
                            <form method="POST" action="{{ route('password.update.approved') }}">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email', $email) }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ old('email', $email) }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-shield-lock"></i> Simpan Password Baru
                                    </button>
                                </div>

                                <hr>

                                <p class="text-center mb-0 auth-footer">
                                    Kembali ke <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
                                </p>
                            </form>
                        @else
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="Masukkan email akun" required autofocus>
                                    <small class="text-muted">Kami akan memberitahu admin bahwa Anda meminta reset password.</small>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-envelope"></i> Kirim Permintaan Reset
                                    </button>
                                </div>

                                <hr>

                                <p class="text-center mb-0 auth-footer">
                                    Kembali ke <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
                                </p>
                            </form>
                        @endif
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
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });
    });
</script>
@endsection
