@extends('layouts.main')

@section('title', 'Pembayaran - Rei Cosrent')

@section('styles')
<style>
    .payment-card {
        background-color: var(--app-page-bg) !important;
        color: var(--bs-body-color);
        border: 1px solid rgba(148, 163, 184, 0.18);
        border-radius: 1rem;
    }

    .payment-card .card-body {
        background-color: var(--app-page-bg) !important;
    }

    .payment-card .card-title,
    .payment-card .payment-meta,
    .payment-card .payment-meta strong {
        color: var(--footer-secondary-text) !important;
    }

    .payment-card .payment-detail-title {
        color: var(--brand-blue) !important;
    }

    .payment-card .payment-instruction-title {
        color: var(--brand-blue) !important;
    }

    .payment-card .card-body,
    .payment-card p,
    .payment-card li,
    .payment-card label,
    .payment-card .text-muted {
        color: inherit;
    }

    [data-bs-theme="dark"] .payment-card {
        border-color: rgba(148, 163, 184, 0.24);
    }
</style>
@endsection

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-4">
            <h2 class="fw-bold mb-0">Pembayaran Pesanan</h2>
            <div class="d-grid d-sm-block w-100" style="max-width: 320px;">
                <a href="{{ route('user.pesanan') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Pesanan Saya
                </a>
            </div>
        </div>
        <div class="alert alert-info">
            Silakan lakukan pembayaran sesuai instruksi yang tertera di bawah ini.
        </div>
        <!-- Contoh konten pembayaran, silakan sesuaikan dengan kebutuhan -->
        <div class="card mb-4 payment-card">
            <div class="card-body">
                @php
                    $orderId = null;
                    $nama_kostum = '-';
                    $total_harga = 0;
                    $metode_pembayaran = '-';

                    if (is_object($order)) {
                        $orderId = $order->id ?? null;
                        $nama_kostum = $order->nama_kostum ?? '-';
                        $total_harga = $order->total_harga ?? 0;
                        $metode_pembayaran = $order->metode_pembayaran ?? '-';
                    } elseif (is_array($order)) {
                        $orderId = $order['id'] ?? null;
                        $nama_kostum = $order['nama_kostum'] ?? '-';
                        $total_harga = $order['total_harga'] ?? 0;
                        $metode_pembayaran = $order['metode_pembayaran'] ?? '-';
                    }
                @endphp
                <h5 class="card-title payment-detail-title">Detail Pembayaran</h5>
                <p class="mb-2 payment-meta"><strong>ID Pesanan:</strong> {{ $orderId ?? '-' }}</p>
                <p class="mb-2 payment-meta"><strong>Nama Kostum:</strong> {{ $nama_kostum }}</p>
                <p class="mb-2 payment-meta"><strong>Total Harga:</strong> Rp {{ number_format((float) $total_harga, 0, ',', '.') }}</p>
                <p class="mb-2 payment-meta"><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
                <hr>
                <h6 class="payment-instruction-title">Instruksi Pembayaran:</h6>
                <ul>
                    <li>Untuk transfer ke rekening berikut: <strong>{{ $profile->nomor_bank ?? '' }}</strong></li>
                    <li>Untuk pembayaran e-wallet, gunakan nomor: <strong>{{ $profile->nomor_ewallet ?? '' }}</strong></li>
                    <li>
                        Untuk pembayaran QRIS, scan kode berikut:
                        <div class="mt-2">
                            @php
                                $qrisSrc = null;
                                if (!empty($profile) && !empty($profile->qris)) {
                                    $qrisPath = (string) $profile->qris;
                                    $qrisSrc = str_starts_with($qrisPath, 'storage/')
                                        ? asset($qrisPath)
                                        : asset('storage/' . $qrisPath);
                                }
                            @endphp

                            @if($qrisSrc)
                                <img
                                    id="qris_img"
                                    src="{{ $qrisSrc }}"
                                    alt="QRIS"
                                    class="img-fluid rounded border"
                                    style="max-width: 260px;"
                                    onerror="this.classList.add('d-none'); document.getElementById('qris_fallback')?.classList.remove('d-none');">
                                <div id="qris_fallback" class="text-muted small d-none"><i class="bi bi-info-circle"></i> QRIS belum tersedia.</div>
                            @else
                                <div class="text-muted small"><i class="bi bi-info-circle"></i> QRIS belum tersedia.</div>
                            @endif
                        </div>
                    </li>
                    <li>Nomor rekening & e-wallet Atas Nama: <strong>{{ $profile->name ?? 'Rei Cosrent' }}</strong></li>
                    <li>Setelah transfer, upload bukti pembayaran di halaman ini.</li>
                </ul>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($orderId)
                    @if(!empty($pembayaran) && !empty($pembayaran->bukti_pembayaran))
                        <div class="mb-3">
                            <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-image"></i> Lihat Bukti Pembayaran
                            </a>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembayaran.upload', $orderId) }}" enctype="multipart/form-data">
                @else
                    <div class="alert alert-warning">Tidak ada ID pesanan untuk mengunggah bukti pembayaran.</div>
                @endif
                    @csrf
                    <div class="mb-3">
                        <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran" required>
                    </div>
                    <button type="submit" class="btn btn-success">Kirim Bukti Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
