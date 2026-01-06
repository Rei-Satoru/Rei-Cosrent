@php
    // SweetAlert on logout flash
    $logoutMessage = session('logout_message');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rei Cosrent')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-primary: #8a2be2;
            --bs-secondary: #ff69b4;
            --bs-link-color: var(--bs-primary);
            --bs-link-hover-color: var(--bs-secondary);
        }

        .btn-primary, .bg-primary {
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
        }

        .btn-primary:hover {
            background-color: #7a1cd1 !important;
            border-color: #7a1cd1 !important;
        }

        .hero-section {
            padding: 50px 0;
            min-height: 10vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(122, 28, 209, 0.6)),
                url("{{ asset('assets/img/Header Pic.png') }}") center/cover no-repeat;
            border-bottom: 5px solid var(--bs-primary);
            transition: background 0s ease, color 0s ease;
        }

        .hero-section .subheading {
            font-size: 1.5rem;
            font-weight: 400;
            color: #e0e0e0;
            line-height: 1.6;
        }

        [data-bs-theme="dark"] .hero-section {
            background:
                linear-gradient(rgba(0, 0, 0, 0.6), rgba(122, 28, 209, 0.6)),
                url("{{ asset('assets/img/Header Pic.png') }}") center/cover no-repeat;
            color: #f8f9fa;
        }

        .category-card, .profile-card, .card {
            transition: all 0s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .rounded-xl { border-radius: 1.5rem !important; }

        body, .hero-section, .category-card, .profile-card, .card, .navbar, section, footer {
            transition: all 0s ease;
        }

        .section-title {
            color: var(--bs-primary);
            transition: color 0s ease;
        }

        [data-bs-theme="dark"] .section-title {
            color: #a855f7;
        }

        [data-bs-theme="light"] .section-title {
            color: var(--bs-primary);
        }

        .contact-title {
            transition: color 0s ease;
        }

        [data-bs-theme="dark"] .contact-title {
            color: #ffffff;
        }

        [data-bs-theme="light"] .contact-title {
            color: #000000;
        }

        .blue-title {
            color: #0056b3;
            transition: color 0s ease;
        }

        [data-bs-theme="dark"] .blue-title {
            color: #0056b3;
        }

        [data-bs-theme="light"] .blue-title {
            color: #0056b3;
        }

        #theme-icon {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        #theme-icon.spin {
            transform: rotate(180deg);
            opacity: 0.6;
        }

        .navbar-brand span {
            font-size: 1.5rem !important;
            font-weight: 750 !important;
            vertical-align: middle;
        }

        /* Ensure sidebar toggle is always on top and interactive in all themes */
        .sidebar-toggle-btn {
            z-index: 2100 !important;
            pointer-events: auto !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Make icon visible in dark mode */
        [data-bs-theme="dark"] .sidebar-toggle-btn {
            color: #ffffff !important;
            background-color: rgba(0,0,0,0.45) !important;
            border-color: rgba(255,255,255,0.08) !important;
        }

        html.no-transition,
        html.no-transition *,
        html.no-transition *::before,
        html.no-transition *::after,
        body.no-transition,
        body.no-transition *,
        body.no-transition *::before,
        body.no-transition *::after {
            transition: none !important;
        }

        /* Sticky footer */
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        body > nav + * {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }

        /* Neutral modal header surface (consistent across admin pages; adapts to light/dark) */
        .modal-header-surface {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            border-bottom: 1px solid var(--bs-border-color);
        }

        .modal-header-surface .btn-close {
            filter: var(--bs-btn-close-filter, none);
        }

        @yield('styles')
    </style>
    <script>
        // Apply saved theme immediately before render and suppress transitions on load
        (function() {
            const savedMode = localStorage.getItem('themeMode') || 'auto';
            const root = document.documentElement;
            
            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            
            let actualTheme;
            if (savedMode === 'auto') {
                actualTheme = getSystemTheme();
            } else {
                actualTheme = savedMode;
            }
            
            root.setAttribute('data-bs-theme', actualTheme);
            root.classList.add('no-transition');

            window.addEventListener('DOMContentLoaded', function () {
                if (document.body) {
                    document.body.setAttribute('data-bs-theme', actualTheme);
                    document.body.classList.add('no-transition');
                }
                setTimeout(() => {
                    root.classList.remove('no-transition');
                    if (document.body) document.body.classList.remove('no-transition');
                }, 50);
            });
        })();
    </script>
</head>
<body data-bs-theme="light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background-color: var(--bs-body-bg);">
        <div class="container-fluid container">
            <!-- Sidebar toggle (only for admin on admin.profile to avoid showing globally) -->
            @if(session('admin_logged_in') && request()->routeIs('admin.profile'))
            <button id="layoutSidebarToggle" type="button" aria-controls="appSidebar" aria-expanded="false" class="sidebar-toggle-btn btn btn-primary d-flex align-items-center justify-content-center" aria-label="Toggle sidebar" style="position:absolute; left:8px; top:50%; transform:translateY(-50%); width:44px; height:44px; border-radius:8px;">
                <i class="bi bi-list"></i>
            </button>
            @endif

            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/Water Mark.png') }}" alt="Logo Rei Cosrent" width="48" height="48" class="me-2">
                <span>Rei Cosrent</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('peraturan') }}">Aturan</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link fw-semibold d-flex align-items-center gap-2" href="https://docs.google.com/spreadsheets/d/1Z3OneYIfDxKs0I0rX-_yZQfFLBb-UHf4TcC4P8oqZsI/edit?fbclid=PAZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQMMjU2MjgxMDQwNTU4AAGnnjkGZH13OPjB23XrUTuuZOd1TJ_ahNiYf7BzJYyJf2lT-rjeBQvIysJ4Dx0_aem_2v0rLLt0XGAhaE4v5iCgYQ&gid=0#gid=0" target="_blank" rel="noopener noreferrer"> 
                            Lihat Tanggal
                        </a>
                    </li>
                    @if(session('user_logged_in'))
                    @endif
                    @if(session('admin_logged_in'))
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-badge"></i> Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="bi bi-person-circle"></i> Profil Admin</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-submenu">
                                    <h6 class="dropdown-header"><i class="bi bi-circle-half"></i> Tema</h6>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="light">
                                        <i class="bi bi-brightness-high-fill"></i> Light
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="light"></i>
                                    </button>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="dark">
                                        <i class="bi bi-moon-fill"></i> Dark
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="dark"></i>
                                    </button>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="auto">
                                        <i class="bi bi-circle-half"></i> Auto
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="auto"></i>
                                    </button>
                                </li>
                            </ul>
                        </li>
                    @elseif(session('user_logged_in'))
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle fw-semibold d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if(session('user_gambar_profil'))
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background-size: cover; background-position: center; background-image: url('{{ asset('storage/' . session('user_gambar_profil')) }}'); border: 1px solid var(--bs-border-color);"></div>
                                @else
                                    <i class="bi bi-person-circle"></i>
                                @endif
                                Profil
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}"><i class="bi bi-person-circle"></i> Profil User</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li class="dropdown-submenu">
                                    <h6 class="dropdown-header"><i class="bi bi-circle-half"></i> Tema</h6>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="light">
                                        <i class="bi bi-brightness-high-fill"></i> Light
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="light"></i>
                                    </button>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="dark">
                                        <i class="bi bi-moon-fill"></i> Dark
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="dark"></i>
                                    </button>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="auto">
                                        <i class="bi bi-circle-half"></i> Auto
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="auto"></i>
                                    </button>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3"><a class="nav-link fw-semibold" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-circle-half"></i> Tema
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="light">
                                        <i class="bi bi-brightness-high-fill"></i> Light
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="light"></i>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="dark">
                                        <i class="bi bi-moon-fill"></i> Dark
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="dark"></i>
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item d-flex align-items-center gap-2" data-theme="auto">
                                        <i class="bi bi-circle-half"></i> Auto
                                        <i class="bi bi-check ms-auto text-primary theme-check d-none" data-theme-indicator="auto"></i>
                                    </button>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-primary text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 Rei Cosrent. Hak Cipta Dilindungi.</p>
            <small class="text-white-50">Dibuat dengan Bootstrap 5</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Adjust body padding for fixed navbar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.querySelector('nav.navbar.fixed-top');
            if (!nav) return;
            const setBodyPadding = () => {
                document.body.style.paddingTop = nav.offsetHeight + 'px';
            };
            setBodyPadding();
            window.addEventListener('resize', setBodyPadding);
        });
    </script>

    @if(!empty($logoutMessage))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Logout Berhasil!',
                text: @json($logoutMessage),
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
    @endif

    @if(session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
    @endif

    <!-- Theme System Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const themeButtons = document.querySelectorAll('[data-theme]');
            const themeIndicators = document.querySelectorAll('[data-theme-indicator]');

            // Remove no-transition class after initial render safety window
            setTimeout(() => {
                document.documentElement.classList.remove('no-transition');
                body.classList.remove('no-transition');
            }, 150);

            function getSystemTheme() {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            function applyTheme(mode) {
                let actualTheme;
                if (mode === 'auto') {
                    actualTheme = getSystemTheme();
                } else {
                    actualTheme = mode;
                }
                body.setAttribute('data-bs-theme', actualTheme);
                document.documentElement.setAttribute('data-bs-theme', actualTheme);
            }

            function updateIndicators(mode) {
                themeIndicators.forEach(indicator => {
                    const indicatorMode = indicator.getAttribute('data-theme-indicator');
                    if (indicatorMode === mode) {
                        indicator.classList.remove('d-none');
                        indicator.closest('.dropdown-item').classList.add('active');
                    } else {
                        indicator.classList.add('d-none');
                        indicator.closest('.dropdown-item').classList.remove('active');
                    }
                });
                // Set CSS var for navbar height and handle sidebar toggle from layout button
                const nav = document.querySelector('nav.navbar.fixed-top');
                if (nav) {
                    document.documentElement.style.setProperty('--nav-height', nav.offsetHeight + 'px');
                }

                // layout toggle listeners are bound once globally (see after init)
            }

            function setThemeMode(mode) {
                localStorage.setItem('themeMode', mode);
                applyTheme(mode);
                updateIndicators(mode);
            }

            // Initialize theme
            const savedMode = localStorage.getItem('themeMode') || 'auto';
            setThemeMode(savedMode);

            // Bind layout/sidebar toggle once to avoid duplicate listeners on theme changes
            (function bindLayoutToggleOnce(){
                if (window.__layoutToggleBound) return;
                window.__layoutToggleBound = true;

                const layoutToggle = document.getElementById('layoutSidebarToggle');
                const sidebar = document.getElementById('appSidebar');
                const wrapper = document.getElementById('pageWrapper');
                const sidebarClose = document.getElementById('sidebarClose');

                if (layoutToggle) {
                    layoutToggle.addEventListener('click', function (e) {
                        if (sidebar) sidebar.classList.toggle('open');
                        if (wrapper) wrapper.classList.toggle('shifted');
                        const expanded = layoutToggle.getAttribute('aria-expanded') === 'true' ? 'false' : 'true';
                        layoutToggle.setAttribute('aria-expanded', expanded);
                    });
                    layoutToggle.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); layoutToggle.click(); }
                    });
                }

                if (sidebarClose) {
                    sidebarClose.addEventListener('click', function () {
                        if (sidebar) sidebar.classList.remove('open');
                        if (wrapper) wrapper.classList.remove('shifted');
                        if (layoutToggle) layoutToggle.setAttribute('aria-expanded', 'false');
                    });
                    sidebarClose.addEventListener('keydown', function(e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); sidebarClose.click(); } });
                }

                // Click outside to close sidebar (use capture=false to avoid interfering with other handlers)
                document.addEventListener('click', function (e) {
                    const clickedOnToggle = layoutToggle && layoutToggle.contains(e.target);
                    const clickedOnSidebar = sidebar && sidebar.contains(e.target);
                    if (!clickedOnToggle && !clickedOnSidebar) {
                        if (sidebar && sidebar.classList.contains('open')) {
                            sidebar.classList.remove('open');
                            if (wrapper) wrapper.classList.remove('shifted');
                            if (layoutToggle) layoutToggle.setAttribute('aria-expanded', 'false');
                        }
                    }
                }, false);
            })();

            // Listen to theme button clicks
            themeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const mode = this.getAttribute('data-theme');
                    setThemeMode(mode);
                });
            });

            // Listen to system theme changes when in auto mode
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                const currentMode = localStorage.getItem('themeMode') || 'auto';
                if (currentMode === 'auto') {
                    applyTheme('auto');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
