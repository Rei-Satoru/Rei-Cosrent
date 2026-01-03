@extends('layouts.main')

@section('title', 'Pesanan Saya - Rei Cosrent')

@section('content')
<section class="py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <a href="{{ route('user.profile') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Kembali ke Profil
            </a>
        </div>

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

        @if($pesanan->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                Anda belum memiliki pesanan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kostum</th>
                            <th>Tgl Pakai</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Dibuat</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->nama_kostum ?? '-' }}</td>
                            <td>{{ $order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-' }}</td>
                            <td>{{ $order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-' }}</td>
                            <td>Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d M Y') : '-' }}</td>
                            <td>
                                @php
                                    $statusClass = [
                                        'proses' => 'bg-warning text-dark',
                                        'revisi' => 'bg-secondary',
                                        'selesai' => 'bg-success',
                                        'diterima' => 'bg-info text-dark',
                                        'dibatalkan' => 'bg-secondary'
                                    ][$order->status] ?? 'bg-dark';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->keterangan ?? '-' }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#orderDetailModal-{{ $order->id }}">
                                    <i class="bi bi-card-list"></i> Detail
                                </button>

                                @php
                                    $hasBukti = false;
                                    $foundBuktiPath = null;

                                    if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                        $hasBukti = true;
                                    } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                        $hasBukti = true;
                                    } else {
                                        try {
                                            $files = \Illuminate\Support\Facades\Storage::disk('public')->files('bukti_pembayaran');
                                            foreach ($files as $f) {
                                                if (\Illuminate\Support\Str::startsWith(basename($f), 'bukti_' . $order->id . '_')) {
                                                    $hasBukti = true;
                                                    $foundBuktiPath = $f;
                                                    break;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $hasBukti = false;
                                        }
                                    }
                                @endphp

                                @if($hasBukti)
                                    @php
                                        $directBuktiUrl = null;
                                        $directExt = null;

                                        if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                            $directBuktiUrl = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                            $directExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                        } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                            $directBuktiUrl = asset('storage/' . session('uploaded_bukti_path'));
                                            $directExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                        } elseif (!empty($foundBuktiPath)) {
                                            $directBuktiUrl = asset('storage/' . $foundBuktiPath);
                                            $directExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                        }
                                    @endphp

                                    {{-- Always open the bukti modal; modal will display image/embed --}}
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#buktiModal-{{ $order->id }}">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </button>
                                @else
                                    @if($order->status === 'diterima')
                                        <a href="{{ route('pembayaran', ['id' => $order->id]) }}" class="btn btn-success btn-sm ms-2">
                                            <i class="bi bi-cash-coin"></i> Lanjutkan ke Pembayaran
                                        </a>
                                    @endif
                                @endif

                                @if(in_array($order->status, ['proses', 'revisi']))
                                    <a href="{{ route('user.pesanan.edit', ['id' => $order->id]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#orderActionModal-{{ $order->id }}">
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-x-octagon"></i> Batalkan/Hapus
                                    </button>
                                @endif
                                @if($order->status === 'selesai')
                                    @php
                                        $hasReview = false;
                                        try {
                                            if (\Illuminate\Support\Facades\Schema::hasColumn('ulasan', 'order_id')) {
                                                $hasReview = \Illuminate\Support\Facades\DB::table('ulasan')->where('order_id', $order->id)->exists();
                                            }
                                        } catch (\Exception $e) {
                                            $hasReview = false;
                                        }

                                        // also check recent session flags (covers cases where DB doesn't have order_id linkage)
                                        try {
                                            $sessionUl = session('ulasan_for_orders', []);
                                            if (!$hasReview && is_array($sessionUl) && in_array($order->id, $sessionUl)) {
                                                $hasReview = true;
                                            }
                                        } catch (\Exception $e) {}
                                    @endphp

                                    @if($hasReview)
                                        <a href="{{ route('user.ulasan.show', ['orderId' => $order->id]) }}" class="btn btn-sm btn-outline-success ms-2">
                                            <i class="bi bi-pencil-square"></i> Edit Ulasan
                                        </a>
                                        <span class="badge bg-success ms-2">Ulasan Terkirim</span>
                                    @else
                                        <a href="{{ route('user.ulasan.show', ['orderId' => $order->id]) }}" class="btn btn-sm btn-outline-success ms-2">
                                            <i class="bi bi-chat-left-text"></i> Berikan Ulasan
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="orderDetailModal-{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title" id="orderDetailLabel-{{ $order->id }}">
                                            <i class="bi bi-card-list"></i> Detail Pesanan #{{ $order->id }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama Kostum:</strong><br>{{ $order->nama_kostum ?? '-' }}</div>
                                                <div class="mb-2"><strong>Tgl Pakai:</strong><br>{{ $order->tanggal_pemakaian ? \Carbon\Carbon::parse($order->tanggal_pemakaian)->format('d M Y') : '-' }}</div>
                                                <div class="mb-2"><strong>Tgl Kembali:</strong><br>{{ $order->tanggal_pengembalian ? \Carbon\Carbon::parse($order->tanggal_pengembalian)->format('d M Y') : '-' }}</div>
                                                <div class="mb-2"><strong>Total Harga:</strong><br>Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}</div>
                                                <div class="mb-2"><strong>Metode Pembayaran:</strong><br>{{ $order->metode_pembayaran ?? '-' }}</div>
                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2"><strong>Nama:</strong><br>{{ $order->nama }}</div>
                                                <div class="mb-2"><strong>Nomor Telepon:</strong><br>{{ $order->nomor_telepon }}</div>
                                                <div class="mb-2"><strong>Nomor Telepon 2:</strong><br>{{ $order->nomor_telepon_2 }}</div>
                                                <div class="mb-2"><strong>Alamat:</strong><br>{{ $order->alamat }}</div>
                                                <div class="mb-2"><strong>Kartu Identitas:</strong><br>{{ $order->kartu_identitas }}</div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <strong>Foto Kartu Identitas:</strong>
                                                @if($order->foto_kartu_identitas)
                                                    <img src="{{ asset('storage/' . $order->foto_kartu_identitas) }}" alt="Foto Kartu Identitas" class="img-fluid rounded mt-2">
                                                @else
                                                    <div class="text-muted">Tidak tersedia</div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Selfie Kartu Identitas:</strong>
                                                @if($order->selfie_kartu_identitas)
                                                    <img src="{{ asset('storage/' . $order->selfie_kartu_identitas) }}" alt="Selfie Kartu Identitas" class="img-fluid rounded mt-2">
                                                @else
                                                    <div class="text-muted">Tidak tersedia</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Modal -->
                        <div class="modal fade" id="buktiModal-{{ $order->id }}" tabindex="-1" aria-labelledby="buktiModalLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="buktiModalLabel-{{ $order->id }}">Bukti Pembayaran - Pesanan #{{ $order->id }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            $displayBuktiPath = null;
                                            $displayExt = null;

                                            if (isset($order->pembayaran_safe) && !empty($order->pembayaran_safe->bukti_pembayaran)) {
                                                $displayBuktiPath = asset('storage/' . $order->pembayaran_safe->bukti_pembayaran);
                                                $displayExt = strtolower(pathinfo($order->pembayaran_safe->bukti_pembayaran, PATHINFO_EXTENSION));
                                            } elseif (session('uploaded_bukti_for') == $order->id && session('uploaded_bukti_path')) {
                                                $displayBuktiPath = asset('storage/' . session('uploaded_bukti_path'));
                                                $displayExt = strtolower(pathinfo(session('uploaded_bukti_path'), PATHINFO_EXTENSION));
                                            } elseif (!empty($foundBuktiPath)) {
                                                $displayBuktiPath = asset('storage/' . $foundBuktiPath);
                                                $displayExt = strtolower(pathinfo($foundBuktiPath, PATHINFO_EXTENSION));
                                            }
                                        @endphp

                                        @if($displayBuktiPath)
                                                @php
                                                    // If it's a PDF, embed it. Otherwise try to display as image.
                                                @endphp
                                                @if($displayExt === 'pdf')
                                                    <embed src="{{ $displayBuktiPath }}" type="application/pdf" width="100%" height="600px" />
                                                @else
                                                    <img src="{{ $displayBuktiPath }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height:600px; object-fit:contain; width:100%;" onerror="this.outerHTML = '<a href=\'{{ $displayBuktiPath }}\' target=\'_blank\' class=\'btn btn-outline-secondary\'>Download / Lihat File</a>'">
                                                @endif
                                        @else
                                            <div class="alert alert-secondary"><i class="bi bi-info-circle"></i> Belum ada bukti pembayaran untuk pesanan ini.</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Modal (Hapus permanen) -->
                        @if(in_array($order->status, ['proses', 'revisi']))
                        <div class="modal fade" id="orderActionModal-{{ $order->id }}" tabindex="-1" aria-labelledby="orderActionLabel-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title" id="orderActionLabel-{{ $order->id }}"><i class="bi bi-x-octagon"></i> Hapus Pesanan Permanen</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-3">Pesanan #{{ $order->id }} akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form id="orderActionForm-{{ $order->id }}" method="POST" action="{{ url('/pesanan-saya') }}/{{ $order->id }}/delete">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="bi bi-check-circle"></i> Hapus Sekarang
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-dismiss success alert after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            try {
                setTimeout(() => {
                    if (window.bootstrap && typeof window.bootstrap.Alert !== 'undefined') {
                        const instance = window.bootstrap.Alert.getOrCreateInstance(successAlert);
                        instance.close();
                    } else {
                        successAlert.remove();
                    }
                }, 5000);
            } catch (e) {}
        }

        // Star rating click handlers (delegated)
        document.querySelectorAll('.rating-stars').forEach(function(starWrap) {
            const orderId = starWrap.getAttribute('data-order-id');
            const hiddenInput = document.querySelector('#reviewForm-' + orderId + ' input[name="rating"]');

            // highlight initial
            const initial = parseInt(hiddenInput.value) || 0;
            if (initial > 0) {
                Array.from(starWrap.children).forEach(function(lbl) {
                    const val = parseInt(lbl.getAttribute('data-value'));
                    lbl.style.color = (val <= initial) ? '#ffc107' : '#ddd';
                });
            }

            // handle click (robust to text-node targets) and keyboard
            starWrap.addEventListener('click', function(e) {
                let el = e.target;
                while (el && el !== starWrap && el.nodeType === 3) el = el.parentElement; // climb from text nodes
                while (el && el !== starWrap && el.tagName !== 'LABEL') el = el.parentElement;
                if (!el || el === starWrap) return;
                const val = el.getAttribute('data-value');
                if (!hiddenInput) return;
                hiddenInput.value = val;
                Array.from(starWrap.children).forEach(function(ch) {
                    const chVal = parseInt(ch.getAttribute('data-value')) || 0;
                    ch.style.color = (chVal <= parseInt(val)) ? '#ffc107' : '#ddd';
                });
            });

            // support keyboard (focus on labels) - allow Enter/Space to set
            Array.from(starWrap.children).forEach(function(lbl) {
                lbl.setAttribute('tabindex', '0');
                lbl.addEventListener('keydown', function(ev) {
                    if (ev.key === 'Enter' || ev.key === ' ') {
                        ev.preventDefault();
                        const v = lbl.getAttribute('data-value');
                        if (!hiddenInput) return;
                        hiddenInput.value = v;
                        Array.from(starWrap.children).forEach(function(ch) {
                            const chVal = parseInt(ch.getAttribute('data-value')) || 0;
                            ch.style.color = (chVal <= parseInt(v)) ? '#ffc107' : '#ddd';
                        });
                    }
                });
            });
        });

    // Save / Delete handlers (delegated)
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const action = btn.getAttribute('data-action');
            const orderId = btn.getAttribute('data-order-id');

                if (action === 'save') {
                const form = document.getElementById('reviewForm-' + orderId);
                if (!form) return;

                const formElem = form;
                const fd = new FormData(formElem);
                // include files from input (some browsers require explicit append)
                // collect files from all gambar[] inputs (five separate inputs)
                const fileInputs = formElem.querySelectorAll('input[name="gambar[]"]');
                const appended = [];
                if (fileInputs && fileInputs.length) {
                    Array.from(fileInputs).forEach(function(fi){
                        if (fi.files && fi.files.length) {
                            Array.from(fi.files).forEach(function(f){
                                if (appended.length < 5) {
                                    fd.append('gambar[]', f);
                                    appended.push(f);
                                }
                            });
                        }
                    });
                }

                const ulasanId = fd.get('ulasan_id');
                // client-side check: rating must be set
                const ratingVal = fd.get('rating');
                if (!ratingVal || ratingVal === '0') {
                    alert('Silakan pilih rating (bintang) terlebih dahulu.');
                    return;
                }
                let url = '/user/ulasan';
                if (ulasanId && ulasanId.length) {
                    // use POST with _method override to support file uploads
                    fd.append('_method', 'PUT');
                    url = '/user/ulasan/' + ulasanId;
                }
                const saveBtn = btn;
                saveBtn.disabled = true;
                const originalText = saveBtn.innerHTML;
                saveBtn.innerHTML = 'Menyimpan...';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: fd
                }).then(async (r) => {
                    let body = null;
                    try { body = await r.json(); } catch (e) { body = null; }
                    console.log('Ulasan save response', r.status, body);
                    if (r.ok && body && body.success) {
                        // set ulasan_id if returned
                        try {
                            const formIdInput = formElem.querySelector('input[name="ulasan_id"]');
                            if (body.id && formIdInput) {
                                formIdInput.value = body.id;
                            }
                        } catch (e) {}

                        // close modal
                        try {
                            const modalEl = document.getElementById('reviewModal-' + orderId);
                            if (modalEl && window.bootstrap && window.bootstrap.Modal) {
                                const inst = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
                                inst.hide();
                            }
                        } catch (e) { console.warn(e); }

                        // update badge near the button if not exists
                        try {
                            const opener = document.querySelector('button[data-bs-target="#reviewModal-' + orderId + '"]');
                            if (opener) {
                                const existingBadge = opener.parentElement.querySelector('.badge.ulasan-sent');
                                if (!existingBadge) {
                                    const b = document.createElement('span');
                                    b.className = 'badge bg-success ms-2 ulasan-sent';
                                    b.textContent = 'Ulasan Terkirim';
                                    opener.parentElement.appendChild(b);
                                }
                            }
                        } catch (e) { console.warn(e); }

                        // show inline alert success
                        try {
                            const container = document.querySelector('.container');
                            if (container) {
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                                alertDiv.role = 'alert';
                                alertDiv.innerHTML = '<strong>Sukses!</strong> Ulasan berhasil dikirim.' +
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                                container.insertBefore(alertDiv, container.firstChild);
                                // auto dismiss after 5s
                                setTimeout(() => {
                                    try {
                                        if (window.bootstrap && window.bootstrap.Alert) {
                                            const ainst = window.bootstrap.Alert.getOrCreateInstance(alertDiv);
                                            ainst.close();
                                        } else {
                                            alertDiv.remove();
                                        }
                                    } catch (e) { alertDiv.remove(); }
                                }, 5000);
                            }
                        } catch (e) { console.warn(e); }

                        return;
                    }

                    // show validation errors or message
                    if (body && body.errors) {
                        const msgs = Object.values(body.errors).flat().join('\n');
                        alert(msgs);
                    } else if (body && body.message) {
                        alert(body.message);
                    } else if (!r.ok) {
                        alert('Gagal menyimpan ulasan. Status: ' + r.status);
                    }
                }).catch(err => {
                    console.error('Fetch error saving ulasan', err);
                    alert('Terjadi kesalahan saat menyimpan ulasan. Cek console untuk detail.');
                }).finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                });
            }

            if (action === 'delete') {
                const ulasanId = btn.getAttribute('data-ulasan-id');
                if (!ulasanId) return alert('ID ulasan tidak ditemukan');

                if (!confirm('Hapus ulasan ini?')) return;

                fetch('/user/ulasan/' + ulasanId, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(r => r.json()).then(data => {
                    if (data && data.success) {
                        location.reload();
                    } else {
                        alert((data && data.message) ? data.message : 'Gagal menghapus ulasan');
                    }
                }).catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat menghapus ulasan.');
                });
            }
        });

        // per-input preview: when a specific input changes, show its preview beneath it
        document.querySelectorAll('.gambar-input').forEach(function(inp) {
            inp.addEventListener('change', function(e) {
                const orderId = inp.getAttribute('data-order-id');
                const idx = inp.getAttribute('data-index');
                const preview = document.getElementById('preview-' + orderId + '-' + idx);
                if (!preview) return;
                preview.innerHTML = '';
                if (inp.files && inp.files.length) {
                    // show first file only for this slot
                    const f = inp.files[0];
                    const url = URL.createObjectURL(f);
                    const img = document.createElement('img');
                    img.src = url;
                    img.style.maxHeight = '90px';
                    img.style.display = 'block';
                    img.style.marginTop = '6px';
                    preview.appendChild(img);
                }
            });
        });
    });
</script>
@endsection
