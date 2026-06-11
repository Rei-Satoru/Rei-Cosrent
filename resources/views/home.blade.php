@extends('layouts.main')

@section('title', 'Rei Cosrent - Sewa Kostum Cosplay')

@section('styles')
    /* AiStarterKit-like visual tweaks for homepage only */
    :root {
        --ak-card-bg: #f8fbff;
        --ak-card-border: rgba(37, 99, 235, 0.12);
        --ak-secondary-text: #0f172a;
        --ak-card-shadow: 0 12px 30px -12px rgba(16,24,40,0.12);
    }

    [data-bs-theme="dark"] {
        --ak-card-bg: #0f172a;
        --ak-card-border: rgba(96,165,250,0.16);
        --ak-secondary-text: #ffffff;
        --ak-card-shadow: 0 18px 40px -22px rgba(0,0,0,0.55);
    }

    .ak-hero {
        padding: clamp(2rem, 6vw, 4rem) 0;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(11, 18, 32, 0.2), rgba(37, 99, 235, 0.2));
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
    }
    .ak-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("{{ asset('assets/img/Header Pic.png') }}") center/cover no-repeat;
        filter: blur(3px) saturate(1.05);
        transform: scale(1.02);
        pointer-events: none;
        z-index: 0;
    }
    .ak-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(11, 18, 32, 0.42), rgba(37, 99, 235, 0.28));
        pointer-events: none;
        z-index: 0;
    }
    .ak-hero > * { position: relative; z-index: 1; }
    .ak-hero .ak-container { max-width: 1100px; margin: 0 auto; padding: 0 1rem; }
    .ak-hero h1 { font-size: clamp(1.8rem, 4.6vw, 3rem); color: #ffffff; font-weight:800; }
    .ak-hero p { color: #ffffff !important; max-width: 760px; margin: 0 auto; }
    .ak-cta { display:inline-flex; align-items:center; gap:.75rem; padding: .7rem 1.25rem; border-radius:999px; color:#fff; background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%) !important; box-shadow: 0 12px 30px -12px rgba(37,99,235,0.5); text-decoration: none; background-size: 200% auto; background-position: 0% center; transition: background-position 0.6s ease-in-out; }
    .ak-cta:hover { background-image: linear-gradient(97deg, #93c5fd 0%, #2563eb 140.21%) !important; background-position: 100% center; color: #fff; text-decoration: none; }
    .ak-cta:focus { background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%) !important; color: #fff; text-decoration: none; }

    .ak-glow { position:absolute; right:-10%; top:-10%; width:700px; height:400px; filter: blur(60px); opacity: .35; pointer-events:none; z-index:0; background: radial-gradient(circle at 30% 30%, #2563eb 0%, rgba(37,99,235,0.25) 30%, transparent 60%), radial-gradient(circle at 70% 70%, #60a5fa 0%, rgba(96,165,250,0.18) 20%, transparent 50%); }

    /* Catalog cards: rounded, soft shadow, gradient border */
    .ak-catalog .card.category-card { border-radius:22px; overflow:visible; background: var(--ak-card-bg); border: 1px solid var(--ak-card-border); box-shadow: var(--ak-card-shadow); color: var(--ak-secondary-text); }
    .ak-catalog .card.category-card img { border-radius:16px; width: 100%; aspect-ratio: 1 / 1; object-fit:cover; }    .ak-catalog .card-body h5 { color: var(--ak-secondary-text) !important; font-weight:700; }
    .ak-catalog .card-body p { color: var(--ak-secondary-text) !important; }
    .ak-catalog .alert,
    .ak-catalog .alert * { color: var(--ak-secondary-text) !important; }

    /* Profile card style */
    .ak-profile { background: transparent; }
    .ak-profile .profile-card { border-radius:18px; padding:1.25rem; box-shadow:0 24px 50px -24px rgba(16,24,40,0.2); background: var(--ak-card-bg); border: 1px solid var(--ak-card-border); color: var(--ak-secondary-text); }
    .ak-profile .profile-card .text-muted,
    .ak-profile .profile-card .card-title,
    .ak-profile .profile-card h4,
    .ak-profile .profile-card h5 { color: var(--ak-secondary-text) !important; }
    .ak-profile .profile-card .profile-heading,
    .ak-profile .profile-card .profile-name {
        color: var(--brand-blue) !important;
    }
    .ak-profile .profile-card .profile-about {
        color: var(--ak-secondary-text) !important;
    }
    .ak-profile .text-primary { color: var(--ak-secondary-text) !important; }
    .ak-profile img.rounded-circle { width:130px; height:130px; object-fit:cover; border-radius:999px; }

    /* Contact card */
    .ak-contact .card { border-radius:18px; box-shadow: 0 20px 40px -20px rgba(16,24,40,0.2); background: var(--ak-card-bg); border: 1px solid var(--ak-card-border); color: var(--ak-secondary-text); }
    .ak-contact .card-title,
    .ak-contact .contact-heading,
    .ak-contact .contact-label,
    .ak-contact .contact-value {
        color: var(--brand-blue) !important;
    }
    .ak-contact .card .text-muted { color: var(--ak-secondary-text) !important; }
    .ak-contact .text-secondary,
    .ak-contact .text-success,
    .ak-contact p,
    .ak-contact small,
    .ak-contact .list-group-item,
    .ak-contact .list-group-item p,
    .ak-contact .list-group-item small { color: var(--ak-secondary-text) !important; }

    /* Dark mode (follows your JS-driven data-bs-theme) */
    [data-bs-theme="dark"] .ak-hero::after {
        background: linear-gradient(135deg, rgba(4, 8, 16, 0.6), rgba(37, 99, 235, 0.35));
    }

    [data-bs-theme="dark"] .ak-cta {
        background-image: linear-gradient(97deg, #2563eb 0%, #93c5fd 140.21%) !important;
        color: #fff !important;
    }
    [data-bs-theme="dark"] .ak-cta:hover {
        background-image: linear-gradient(97deg, #93c5fd 0%, #2563eb 140.21%) !important;
        background-position: 100% center;
        color: #fff !important;
    }
@endsection

@section('content')
    <!-- Hero -->
    <header class="ak-hero text-center">
        <div class="ak-glow" aria-hidden="true"></div>
        <div class="ak-container" style="position:relative; z-index:10;">
            <h1 class="mb-3">Sewa Kostum Impian Anda!</h1>
            <p class="subheading mb-4">Menyediakan Kostum Cosplay untuk berbagai acara dengan pelayanan ramah dan koleksi lengkap.</p>
        </div>
    </header>

    <!-- Katalog -->
    <section id="kategori" class="py-5 ak-catalog">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold section-title">Katalog Kostum</h2>
            @if(isset($katalog) && $katalog->count() > 0)
                <div class="row justify-content-center row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    @foreach($katalog as $kategori)
                    <div class="col">
                        <a href="{{ url('/katalog_kostum?cat='. urlencode(strtolower($kategori->name))) }}" class="text-decoration-none">
                            <div class="card category-card h-100 border-0">
                                <div style="padding:1rem;">
                                    @php
                                        $imgRaw = $kategori->image ?? '';
                                        if (str_starts_with($imgRaw, 'http')) {
                                            $catImg = $imgRaw;
                                        } elseif (str_starts_with($imgRaw, '/storage/')) {
                                            $catImg = asset(ltrim($imgRaw, '/'));
                                        } elseif (str_starts_with($imgRaw, 'storage/')) {
                                            $catImg = asset($imgRaw);
                                        } elseif ($imgRaw) {
                                            $catImg = asset('storage/' . $imgRaw);
                                        } else {
                                            $catImg = null;
                                        }
                                    @endphp
                                    @if($catImg)
                                    <img src="{{ $catImg }}" class="w-100" style="aspect-ratio: 1/1; object-fit: cover; border-radius: 6px;" alt="{{ $kategori->name }}">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center" style="aspect-ratio: 1/1; background:#f5f5f5; border-radius:6px;">
                                            <i class="bi bi-image" style="font-size:28px;color:#9aa0a6;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body py-3 px-3">
                                    <h5 class="fw-bold">{{ $kategori->name }}</h5>
                                    <p class="small mb-0">{{ $kategori->description }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center shadow-sm rounded-xl">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Tidak ada katalog yang tersedia.
                </div>
            @endif
        </div>
    </section>

    <!-- Profil -->
    <section id="profil" class="py-5 ak-profile">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold section-title">Profil Pengurus</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="profile-card rounded-xl border-0 p-4">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4 text-center p-3">
                                @if($profile && $profile->photo)
                                    <img src="{{ asset('storage/' . $profile->photo) }}" class="img-fluid rounded-circle border mb-3" alt="Foto Pengurus">
                                @else
                                    <div class="mb-3">
                                        <i class="bi bi-person-circle text-primary" style="font-size: 150px;"></i>
                                    </div>
                                @endif
                                <h4 class="fw-bold profile-name">{{ optional($profile)->name }}</h4>
                                <p class="text-muted mb-0">{{ optional($profile)->title }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold profile-heading">Tentang Saya</h5>
                                    <p class="card-text text-muted profile-about">{!! nl2br(e(optional($profile)->vision)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-5 ak-contact">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold section-title">Alamat & Informasi Kontak</h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="ratio ratio-16x9 rounded-xl shadow-sm overflow-hidden">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.1736669438806!2d106.94508!3d-6.9254121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68485bbe95bb73%3A0x348aafd6cac33aa1!2s3XF2%2BVP3%2C%20Jl.%20Gn.%20Gede%20No.16%2C%20Cibeureum%20Hilir%2C%20Kec.%20Cibeureum%2C%20Kota%20Sukabumi%2C%20Jawa%20Barat%2043165!5e0!3m2!1sid!2sid!4v1702345678901!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <p class="text-muted mt-2 text-center small">Peta Lokasi Toko.</p>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100 rounded-xl border-0 p-3">
                        <div class="card-body">
                            @php
                                $instagramHandle = trim((string) optional($profile)->instagram);
                                $instagramHandle = ltrim($instagramHandle, '@');
                                $instagramUrl = $instagramHandle !== ''
                                    ? 'https://www.instagram.com/' . $instagramHandle . '/'
                                    : null;
                            @endphp
                            <h5 class="card-title fw-bold mb-3 contact-heading">Hubungi Kami</h5>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-geo-alt-fill text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block contact-label">Alamat:</small>
                                        <p class="mb-0 fw-bold contact-value">{{ optional($profile)->address }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-instagram text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block contact-label">Instagram:</small>
                                        <p class="mb-0 fw-bold contact-value">{{ $instagramHandle !== '' ? '@' . $instagramHandle : '-' }}</p>

                                        @if($instagramUrl)
                                            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer"
                                               class="btn btn-success btn-sm rounded-pill mt-2">
                                                <i class="bi bi-instagram me-1"></i> Buka Instagram
                                            </a>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-envelope-fill text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block contact-label">Email Resmi:</small>
                                        <p class="mb-0 fw-bold contact-value">{{ optional($profile)->email }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <!-- Scroll Tengah Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSection = document.getElementById('kategori');

            // Scroll Tengah Function
            function scrollToCenter(element) {
                if (!element) return;
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            // Scroll on nav link click
            document.querySelectorAll('.nav-link[href*="#"]').forEach(link => {
                link.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    const hashIndex = href.indexOf('#');
                    if (hashIndex !== -1) {
                        const targetId = href.substring(hashIndex);
                        const targetElement = document.querySelector(targetId);
                        if (targetElement) {
                            e.preventDefault();
                            scrollToCenter(targetElement);
                        }
                    }
                });
            });

            // Scroll on button click
            const scrollBtn = document.getElementById('scrollToKategori');
            if (scrollBtn) {
                scrollBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    scrollToCenter(kategoriSection);
                });
            }

            // Handle scrollTo parameter from other pages
            const urlParams = new URLSearchParams(window.location.search);
            const scrollTarget = urlParams.get('scrollTo');
            if(scrollTarget === 'kategori'){
                setTimeout(() => scrollToCenter(kategoriSection), 100);
            }

            // Handle hash on page load
            if (window.location.hash) {
                const targetElement = document.querySelector(window.location.hash);
                if (targetElement) {
                    setTimeout(() => scrollToCenter(targetElement), 100);
                }
            }
        });
    </script>
@endsection
