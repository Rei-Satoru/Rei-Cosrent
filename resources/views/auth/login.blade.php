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
</style>
@endsection

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-xl">
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

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold d-block">Login Sebagai</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="login_type" id="login_user" value="user" checked>
                                    <label class="form-check-label" for="login_user">User</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="login_type" id="login_admin" value="admin">
                                    <label class="form-check-label" for="login_admin">Admin</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email / Username / Nama</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email, username, atau nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required style="padding-right: 40px;">
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="bi bi-eye" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </button>
                            </div>

                            <div class="text-center mb-3">
                                <p class="text-muted mb-2">atau</p>
                                <a href="{{ route('auth.google') }}" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-google"></i> Login dengan Google
                                </a>
                            </div>

                            <hr>

                            <p class="text-center mb-0">
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
        const loginTypeRadios = document.querySelectorAll('input[name="login_type"]');
        const emailLabel = document.querySelector('label[for="email"]');
        const emailInput = document.getElementById('email');

        function syncLoginType() {
            const selected = document.querySelector('input[name="login_type"]:checked')?.value || 'user';
            if (selected === 'admin') {
                emailLabel.textContent = 'Username';
                emailInput.placeholder = 'Masukkan username admin';
                emailInput.type = 'text';
            } else {
                emailLabel.textContent = 'Email / Username / Nama';
                emailInput.placeholder = 'Masukkan email, username, atau nama';
                emailInput.type = 'text';
            }
        }

        loginTypeRadios.forEach(r => r.addEventListener('change', syncLoginType));
        syncLoginType();

        // Auto-hide alerts after 3 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 3000);
        });
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
