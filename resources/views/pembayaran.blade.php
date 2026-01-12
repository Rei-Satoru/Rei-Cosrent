@extends('layouts.main')

@section('title', 'Pembayaran - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pembayaran Pesanan</h2>
            <a href="{{ route('user.pesanan') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Pesanan Saya
            </a>
        </div>
        <div class="alert alert-info">
            Silakan lakukan pembayaran sesuai instruksi yang tertera di bawah ini.
        </div>
        <!-- Contoh konten pembayaran, silakan sesuaikan dengan kebutuhan -->
        <div class="card mb-4">
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
                <h5 class="card-title">Detail Pembayaran</h5>
                <p class="mb-2"><strong>ID Pesanan:</strong> {{ $orderId ?? '-' }}</p>
                <p class="mb-2"><strong>Nama Kostum:</strong> {{ $nama_kostum }}</p>
                <p class="mb-2"><strong>Total Harga:</strong> Rp {{ number_format((float) $total_harga, 0, ',', '.') }}</p>
                <p class="mb-2"><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
                <hr>
                <h6>Instruksi Pembayaran:</h6>
                <ul>
                    <li>Untuk transfer ke rekening berikut: <strong>{{ $profile->nomor_bank ?? '' }}</strong></li>
                    <li>Untuk pembayaran e-wallet, gunakan nomor: <strong>{{ $profile->nomor_ewallet ?? '' }}</strong></li>
                    <li>
                        Untuk pembayaran QRIS, scan kode berikut:
                        <div class="mt-2">
                            @if(!empty($profile) && !empty($profile->qris))
                                <img src="{{ asset('storage/' . $profile->qris) }}" alt="QRIS" class="img-fluid rounded border" style="max-width: 260px;">
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
