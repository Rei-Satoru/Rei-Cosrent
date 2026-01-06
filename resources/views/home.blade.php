@extends('layouts.main')

@section('title', 'Rei Cosrent - Sewa Kostum Cosplay')

@section('content')
    <!-- Hero -->
    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-3 fw-bolder mb-3 text-primary">Sewa Kostum Impian Anda!</h1>
            <p class="subheading mb-4 px-lg-5">Temukan dan sewa kostum cosplay berkualitas tinggi</p>
            <a href="#kategori" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg" id="scrollToKategori">Jelajahi Kostum Sekarang!</a>
        </div>
    </header>

    <!-- Katalog -->
    <section id="kategori" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold section-title">Katalog Kostum</h2>
            @if(isset($katalog) && $katalog->count() > 0)
                <div class="row justify-content-center row-cols-2 row-cols-md-3 g-3">
                    @foreach($katalog as $kategori)
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ url('/katalog_kostum?cat='. urlencode(strtolower($kategori->name))) }}" class="text-decoration-none text-dark">
                            <div class="card category-card h-100 rounded-xl border-0 shadow-sm">
                                <img src="{{ str_starts_with($kategori->image, 'http') ? $kategori->image : (str_starts_with($kategori->image, 'storage/') ? '/storage/' . basename($kategori->image) : asset($kategori->image)) }}" class="card-img-top" alt="{{ $kategori->name }}" style="aspect-ratio:1/1;width:100%;object-fit:cover;border-radius:1.5rem 1.5rem 0 0;">
                                <div class="card-body py-2 px-3">
                                    <h5 class="fw-bold text-primary">{{ $kategori->name }}</h5>
                                    <p class="text-muted small mb-0">{{ $kategori->description }}</p>
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
    <section id="profil" class="py-5 bg-body-tertiary">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold section-title">Profil Pengurus</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card profile-card rounded-xl border-0 shadow-lg p-4">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-4 text-center p-3">
                                @if($profile && $profile->photo)
                                    <img src="{{ asset('storage/' . $profile->photo) }}" class="img-fluid rounded-circle border mb-3" alt="Foto Pengurus" style="width: 150px; height: 150px; object-fit: cover; border-color: var(--bs-border-color) !important;">
                                @else
                                    <div class="mb-3">
                                        <i class="bi bi-person-circle text-primary" style="font-size: 150px;"></i>
                                    </div>
                                @endif
                                <h4 class="fw-bold text-primary">{{ optional($profile)->name }}</h4>
                                <p class="text-muted mb-0">{{ optional($profile)->title }}</p>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold blue-title">Tentang Saya</h5>
                                    <p class="card-text text-muted">{!! nl2br(e(optional($profile)->vision)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-5">
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
                    <div class="card h-100 rounded-xl border-0 shadow-sm p-3">
                        <div class="card-body">
                            <h5 class="card-title fw-bold blue-title mb-3">Hubungi Kami</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-geo-alt-fill text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block">Alamat:</small>
                                        <p class="mb-0 fw-bold">{{ optional($profile)->address }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-whatsapp text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block">Nomor Telepon (WhatsApp):</small>
                                        <p class="mb-0 fw-bold text-success">{{ optional($profile)->phone }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center bg-transparent px-0">
                                    <i class="bi bi-envelope-fill text-secondary me-3 h5 mb-0"></i>
                                    <div>
                                        <small class="text-muted d-block">Email Resmi:</small>
                                        <p class="mb-0 fw-bold">{{ optional($profile)->email }}</p>
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
            const profilSection = document.getElementById('profil');
            const kontakSection = document.getElementById('kontak');

            // Apply dark mode background changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'data-bs-theme') {
                        const theme = document.body.getAttribute('data-bs-theme');
                        if (theme === 'dark') {
                            kategoriSection.classList.remove('bg-body-tertiary');
                            kontakSection.classList.remove('bg-body-tertiary');
                        } else {
                            profilSection.classList.add('bg-body-tertiary');
                        }
                    }
                });
            });

            observer.observe(document.body, {
                attributes: true
            });

            // Scroll Tengah Function
            function scrollToCenter(element) {
                if (element) {
                    const elementPosition = element.getBoundingClientRect().top + window.scrollY;
                    const offset = (window.innerHeight / 2) - (element.offsetHeight / 2);
                    window.scrollTo({
                        top: elementPosition - offset,
                        behavior: 'smooth'
                    });
                }
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
