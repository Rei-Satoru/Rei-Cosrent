@extends('layouts.main')

@section('title', 'Bayar Denda - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pembayaran Denda</h2>
            <a href="{{ route('user.denda-saya') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Denda Saya
            </a>
        </div>
        <div class="alert alert-info">
            Silakan lakukan pembayaran sesuai instruksi yang tertera di bawah ini.
        </div>
        <!-- Struktur sama seperti pada pembayaran.blade.php -->
        <div class="card mb-4">
            <div class="card-body">
                @php
                    $dendaId = null;
                    $nama_kostum = '-';
                    $total_harga = 0;
                    $metode_pembayaran = '-';

                    if (is_object($denda)) {
                        $dendaId = $denda->id ?? null;
                        $nama_kostum = $denda->nama_kostum ?? '-';
                        $total_harga = $denda->jumlah_denda ?? 0;
                        $metode_pembayaran = $denda->metode_pembayaran ?? '-';
                    } elseif (is_array($denda)) {
                        $dendaId = $denda['id'] ?? null;
                        $nama_kostum = $denda['nama_kostum'] ?? '-';
                        $total_harga = $denda['jumlah_denda'] ?? 0;
                        $metode_pembayaran = $denda['metode_pembayaran'] ?? '-';
                    }
                @endphp
                <h5 class="card-title">Detail Pembayaran</h5>
                <p class="mb-2"><strong>No. Urut Denda:</strong> {{ $dendaId ?? '-' }}</p>
                <p class="mb-2"><strong>ID Denda:</strong> {{ $dendaId ?? '-' }}</p>
                <p class="mb-2"><strong>Nama Kostum:</strong> {{ $nama_kostum }}</p>
                <p class="mb-2"><strong>Jumlah Denda:</strong> Rp {{ number_format((float) $total_harga, 0, ',', '.') }}</p>
                <p class="mb-2"><strong>Metode Pembayaran:</strong> {{ $metode_pembayaran }}</p>
                <hr>
                <h6>Instruksi Pembayaran:</h6>
                <ul>
                    <li>Untuk transfer ke rekening berikut: <strong>{{ $profile->nomor_bank ?? '' }}</strong></li>
                    <li>Untuk pembayaran e-wallet, gunakan nomor: <strong>{{ $profile->nomor_ewallet ?? '' }}</strong></li>
                    <li>Nomor rekening & e-wallet Atas Nama: <strong>{{ $profile->name ?? 'Rei Cosrent' }}</strong></li>
                    <li>Setelah transfer, upload bukti pembayaran di halaman ini.</li>
                </ul>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($dendaId)
                    <form method="POST" action="{{ route('denda.bayar.upload', $dendaId) }}" enctype="multipart/form-data">
                @else
                    <div class="alert alert-warning">Tidak ada ID denda untuk mengunggah bukti pembayaran.</div>
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
