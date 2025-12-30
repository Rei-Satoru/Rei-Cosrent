@extends('layouts.main')

@section('title', 'Formulir Terkirim - Rei Cosrent')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Terima Kasih!</h2>
            <p class="text-muted">Formulir penyewaan Anda telah berhasil dikirim.</p>
        </div>

        <!-- Modal Triggered on Load -->
        <div class="modal fade" id="formSuccessModal" tabindex="-1" aria-labelledby="formSuccessModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="formSuccessModalLabel"><i class="bi bi-check-circle"></i> Berhasil Dikirim</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">{{ $message }}</p>
                        <ul class="mb-0 text-muted small">
                            <li>Tim kami akan memproses pesanan Anda.</li>
                            <li>Silakan pantau status pesanan di halaman Pesanan Saya.</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('user.pesanan') }}" class="btn btn-primary">
                            <i class="bi bi-receipt"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('katalog.kostum') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-shop"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('user.pesanan') }}" class="btn btn-success me-2">
                <i class="bi bi-receipt"></i> Lihat Pesanan Saya
            </a>
            <a href="{{ route('katalog.kostum') }}" class="btn btn-outline-primary">
                <i class="bi bi-shop"></i> Kembali ke Katalog Kostum
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('formSuccessModal');
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    });
</script>
@endsection