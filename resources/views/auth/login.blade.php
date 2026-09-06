@extends('layouts.main')

@section('title', 'Login - Rei Cosrent')

@section('styles')
<style>
    body, section, .container, .row, .col-lg-5, .col-md-7,
    .card, .card-header, .card-body, 
    form, .form-control, .form-select, .form-label, 
    .btn, .btn-primary, .btn-lg, .d-grid,
    .alert, .alert-success, .alert-danger,
    .mb-3, hr, p, a, small, h3, i,
    select option, input, button {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease, transform 0s ease;
    }
    
    .form-control, .form-select {
        transition: background-color 0s ease, color 0s ease, border-color 0s ease, box-shadow 0s ease;
    }
    
    .form-control:focus, .form-select:focus {
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    
    .btn:hover {
        transition: all 0.3s ease;
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
                    <div class="card-header bg-primary text-white text-center py-4 rounded-top">
                        <h3 class="mb-0 fw-bold">Login</h3>
                        <p class="mb-0 small">Masuk sebagai Admin atau User</p>
                    </div>
                    <div class="card-body p-4">
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

                        @php
                            $selectedLoginType = $defaultLoginType ?? request()->query('login_type', 'user');
                            if (!in_array($selectedLoginType, ['admin', 'user'], true)) {
                                $selectedLoginType = 'user';
                            }
                            $isAdminEntry = $selectedLoginType === 'admin';
                        @endphp

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <input type="hidden" name="context" value="{{ $isAdminEntry ? 'admin' : 'user' }}">

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">{{ $isAdminEntry ? 'Username' : 'Email / Username / Nama' }}</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="{{ $isAdminEntry ? 'Masukkan username admin' : 'Masukkan email, username, atau nama' }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required style="padding-right: 40px;">
                                    <button type="button" class="password-toggle" data-target="password" aria-label="Toggle password visibility" onclick="togglePassword(this)">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </div>

                            <hr>

                            <p class="text-center mb-0 auth-footer">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar Sekarang</a>
                            </p>
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
    });
    
    // Toggle password visibility (button -> target input)
    function togglePassword(button) {
        const targetId = button.getAttribute('data-target');
        const field = document.getElementById(targetId);
        const icon = button.querySelector('i') || document.getElementById(targetId + '-icon');
        if (!field) return;
        if (field.type === 'password') {
            field.type = 'text';
            if (icon) { icon.classList.remove('bi-eye'); icon.classList.add('bi-eye-slash'); }
        } else {
            field.type = 'password';
            if (icon) { icon.classList.remove('bi-eye-slash'); icon.classList.add('bi-eye'); }
        }
    }
</script>
@endsection
