@extends('layouts.main')

@section('title', ($ulasan ? 'Edit' : 'Beri') . ' Ulasan - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">{{ $ulasan ? 'Edit' : 'Beri' }} Ulasan</h2>
            <a href="{{ route('user.pesanan') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pesanan
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-star-fill"></i> Ulasan untuk Pesanan #{{ $formulir->id }}</h5>
                    </div>
                    <div class="card-body">

                        <form method="POST" action="{{ $ulasan ? route('user.ulasan.update', $formulir->id) : route('user.ulasan.store', $formulir->id) }}" enctype="multipart/form-data">
                            @csrf
                            @if($ulasan)
                                @method('PUT')
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                                <div class="rating-stars" id="ratingStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ ($ulasan && $ulasan->rating >= $i) ? '-fill text-warning' : '' }} fs-2" data-rating="{{ $i }}" style="cursor: pointer;"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="{{ $ulasan->rating ?? '' }}" required>
                                @error('rating')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="review" class="form-label fw-bold">Ulasan Anda</label>
                                <textarea class="form-control @error('review') is-invalid @enderror"
                                          id="review"
                                          name="review"
                                          rows="5"
                                          placeholder="Ceritakan pengalaman Anda...">{{ old('review', $ulasan->review ?? '') }}</textarea>
                                @error('review')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Foto (Opsional - Maksimal 5 foto)</label>
                                <div class="row g-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="col-md-6">
                                            <div class="card h-100">
                                                <div class="card-body text-center">
                                                    @if($ulasan && $ulasan->{'gambar_' . $i})
                                                        <div class="position-relative mb-2">
                                                            <img src="{{ asset('storage/' . $ulasan->{'gambar_' . $i}) }}"
                                                                 alt="Gambar {{ $i }}"
                                                                 class="img-fluid rounded mb-2"
                                                                 style="max-height: 200px; object-fit: cover;"
                                                                 id="preview_{{ $i }}">
                                                            <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                                    onclick="deleteImage({{ $formulir->id }}, {{ $i }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="mb-2" id="preview_container_{{ $i }}" style="display: none;">
                                                            <img src=""
                                                                 alt="Preview {{ $i }}"
                                                                 class="img-fluid rounded mb-2"
                                                                 style="max-height: 200px; object-fit: cover;"
                                                                 id="preview_{{ $i }}">
                                                        </div>
                                                    @endif
                                                    <label for="gambar_{{ $i }}" class="btn btn-outline-secondary btn-sm w-100">
                                                        <i class="bi bi-image"></i> Pilih Foto {{ $i }}
                                                    </label>
                                                    <input type="file"
                                                           class="d-none"
                                                           id="gambar_{{ $i }}"
                                                           name="gambar_{{ $i }}"
                                                           accept="image/jpeg,image/png,image/jpg"
                                                           onchange="previewImage({{ $i }})">
                                                    @error('gambar_' . $i)
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB per foto.</small>
                            </div>

                            @if($ulasan && $ulasan->balasan)
                                <div class="alert alert-success">
                                    <h6 class="fw-bold"><i class="bi bi-chat-left-text"></i> Balasan dari Admin:</h6>
                                    <p class="mb-0">{{ $ulasan->balasan }}</p>
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> {{ $ulasan ? 'Update Ulasan' : 'Kirim Ulasan' }}
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
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;

            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating <= rating) {
                    s.classList.remove('bi-star');
                    s.classList.add('bi-star-fill', 'text-warning');
                } else {
                    s.classList.remove('bi-star-fill', 'text-warning');
                    s.classList.add('bi-star');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('data-rating');
            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating <= rating) {
                    s.classList.add('text-warning');
                }
            });
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = ratingInput.value;
            stars.forEach(s => {
                const starRating = s.getAttribute('data-rating');
                if (starRating > currentRating) {
                    s.classList.remove('text-warning');
                }
            });
        });
    });

    function previewImage(number) {
        const input = document.getElementById('gambar_' + number);
        const preview = document.getElementById('preview_' + number);
        const container = document.getElementById('preview_container_' + number);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                if (container) {
                    container.style.display = 'block';
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function deleteImage(formulirId, imageNumber) {
        if (confirm('Apakah Anda yakin ingin menghapus gambar ini?')) {
            fetch(`/ulasan/${formulirId}/delete-image/${imageNumber}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus gambar');
            });
        }
    }
</script>
@endsection
