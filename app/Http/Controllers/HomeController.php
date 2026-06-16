<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\BookingDate;
use App\Models\DataKatalog;
use App\Models\DataKostum;
use App\Models\Formulir;
use App\Models\Pengembalian;
use App\Models\ProfileContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $katalog = DataKatalog::orderBy('name')->get();
        $profile = ProfileContact::first();

        return view('home', [
            'katalog' => $katalog,
            'profile' => $profile,
        ]);
    }

    public function bookingDates()
    {
        // Build a month grid from DataKostum and Formulir bookings
        $years = Formulir::selectRaw('YEAR(tanggal_pemakaian) as y')->whereNotNull('tanggal_pemakaian')->groupBy('y')->orderBy('y', 'desc')->pluck('y')->toArray();
        $current = Carbon::now();
        $selectedYear = (int) request()->query('year', $current->year);
        $selectedMonth = (int) request()->query('month', $current->month);

        // fall back if year list empty
        if (empty($years)) {
            $years = [$current->year];
        }

        // dates in selected month
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $dates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dates[] = $startOfMonth->copy()->day($d)->format('Y-m-d');
        }

        $kostums = DataKostum::orderBy('judul')->orderBy('nama_kostum')->get();

        // map bookings: [nama_kostum][date] = form.nama
        $orders = Formulir::whereNotNull('tanggal_pemakaian')
            ->whereYear('tanggal_pemakaian', $selectedYear)
            ->whereMonth('tanggal_pemakaian', $selectedMonth)
            ->get();

        $bookingMap = [];
        foreach ($orders as $o) {
            $kname = trim((string) $o->nama_kostum);
            $date = Carbon::parse($o->tanggal_pemakaian)->format('Y-m-d');
            if (!$kname) continue;
            $bookingMap[$kname][$date] = $o->nama;
        }

        return view('tanggal-pemesanan', [
            'kostums' => $kostums,
            'dates' => $dates,
            'bookingMap' => $bookingMap,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'isAdmin' => false,
        ]);
    }

    public function katalogKostum(Request $request)
    {
        $categoryParam = strtolower((string) $request->query('cat', ''));

        $catalog = DataKatalog::when($categoryParam !== '', function ($query) use ($categoryParam) {
                return $query->whereRaw('LOWER(name) = ?', [$categoryParam]);
            })
            ->first();

        if (!$catalog && $categoryParam === '') {
            $catalog = DataKatalog::first();
        }

        $kostum = collect();
        $ukuranList = [];
        $sort = $request->input('sort', 'id_desc');

        if ($catalog) {
            $kostumQuery = DataKostum::whereRaw('LOWER(kategori) = ?', [strtolower($catalog->name)]);

            // Pencarian nama/brand (tanpa pencarian kategori)
            if ($request->filled('search')) {
                $search = $request->input('search');
                $kostumQuery->where(function ($q) use ($search) {
                    $q->where('nama_kostum', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%");
                });
            }

            // Filter jenis kelamin
            if ($request->filled('jenis_kelamin')) {
                $kostumQuery->where('jenis_kelamin', $request->input('jenis_kelamin'));
            }

            // Filter ukuran
            if ($request->filled('ukuran')) {
                $kostumQuery->where('ukuran_kostum', 'like', "%{$request->input('ukuran')}%");
            }

            // Sortir
            switch ($sort) {
                case 'nama_asc':
                    $kostumQuery->orderBy('nama_kostum', 'asc');
                    break;
                case 'nama_desc':
                    $kostumQuery->orderBy('nama_kostum', 'desc');
                    break;
                case 'harga_asc':
                    $kostumQuery->orderBy('harga_sewa', 'asc');
                    break;
                case 'harga_desc':
                    $kostumQuery->orderBy('harga_sewa', 'desc');
                    break;
                default:
                    $kostumQuery->orderBy('id_kostum', 'desc'); // terbaru
            }

            $kostum = $kostumQuery->get();

            // Kumpulkan pilihan ukuran untuk filter (pecah gabungan M & L, dsb.)
            $sizeRaw = DataKostum::whereRaw('LOWER(kategori) = ?', [strtolower($catalog->name)])->pluck('ukuran_kostum')->toArray();
            foreach ($sizeRaw as $sizeStr) {
                if (!is_string($sizeStr)) {
                    continue;
                }
                $parts = preg_split('/[,;&]/', $sizeStr);
                foreach ($parts as $p) {
                    $clean = trim($p);
                    if ($clean !== '') {
                        $ukuranList[] = $clean;
                    }
                }
            }
            $ukuranList = array_values(array_unique($ukuranList));
            $orderMap = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, 'XXXL' => 7];
            usort($ukuranList, function ($a, $b) use ($orderMap) {
                $aKey = strtoupper($a);
                $bKey = strtoupper($b);
                $aRank = $orderMap[$aKey] ?? 999;
                $bRank = $orderMap[$bKey] ?? 999;
                if ($aRank === $bRank) {
                    return strcasecmp($aKey, $bKey);
                }
                return $aRank <=> $bRank;
            });
        }

        return view('katalog-kostum', [
            'catalog' => $catalog,
            'kostum' => $kostum,
            'ukuran' => $ukuranList,
            'search' => $request->input('search'),
            'filter_jenis_kelamin' => $request->input('jenis_kelamin'),
            'filter_ukuran' => $request->input('ukuran'),
            'sort' => $sort,
        ]);
    }

    public function peraturan()
    {
        $aturan = Aturan::orderBy('created_at', 'desc')->get();

        return view('peraturan', [
            'aturan' => $aturan,
        ]);
    }

    public function userProfile()
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        // Ensure session copies reflect latest DB values for UI that may rely on them
        session([
            'user_name' => $user->username,
            'user_email' => $user->email,
            'user_gambar_profil' => $user->gambar_profil,
            'user_nick_name' => $user->nick_name,
        ]);

        if (trim((string) $user->instagram) !== '') {
            session(['user_instagram' => $user->instagram]);
        } else {
            session()->forget('user_instagram');
        }

        return view('user.profil', [
            'user' => $user,
        ]);
    }

    public function updateUserProfile(Request $request)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|lowercase|no_spaces|unique:users,username,' . $user->id,
            'nick_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'instagram' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:1000',


            'nomor_telepon' => 'nullable|regex:/^08[0-9]{8,13}$/',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'gambar_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'username.lowercase' => 'Username hanya boleh huruf kecil.',
            'username.no_spaces' => 'Username tidak boleh mengandung spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'alamat.max' => 'Alamat terlalu panjang (maksimal 1000 karakter).',
            'instagram.max' => 'Instagram maksimal 50 karakter.',
            'gambar_profil.image' => 'File harus berupa gambar.',
            'gambar_profil.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'gambar_profil.max' => 'Ukuran gambar maksimal 2MB.',
            'nomor_telepon.regex' => 'Nomor telepon harus diawali 08 dan berisi 10-15 digit.',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.profile')->withErrors($validator)->withInput();
        }

        try {
            $user->username = strtolower($request->input('username'));
            $user->nick_name = $request->input('nick_name');
            $user->email = $request->input('email');
            $user->alamat = $request->input('alamat');
            $user->nomor_telepon = $request->input('nomor_telepon');
            $user->jenis_kelamin = $request->input('jenis_kelamin');
            // Normalize Instagram handle: trim and remove any leading '@' characters
            $instagramRaw = trim((string) $request->input('instagram'));
            $instagramClean = $instagramRaw !== '' ? ltrim($instagramRaw, '@') : null;
            $user->instagram = $instagramClean;

            if (trim((string) $user->instagram) !== '') {
                session(['user_instagram' => $user->instagram]);
            } else {
                session()->forget('user_instagram');
            }

            $shouldRemovePhoto = $request->boolean('remove_photo');

            if ($shouldRemovePhoto && $user->gambar_profil) {
                if (Storage::disk('public')->exists($user->gambar_profil)) {
                    Storage::disk('public')->delete($user->gambar_profil);
                }
                $user->gambar_profil = null;
            }

            if ($request->hasFile('gambar_profil')) {
                if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                    Storage::disk('public')->delete($user->gambar_profil);
                }

                $path = $request->file('gambar_profil')->store('profile_images', 'public');
                $user->gambar_profil = $path;
            }

            $user->save();

            // Update session values so the UI shows the freshly saved data immediately
            session([
                'user_name' => $user->username,
                'user_email' => $user->email,
                'user_gambar_profil' => $user->gambar_profil,
                'user_instagram' => $user->instagram,
                'user_nick_name' => $user->nick_name,
            ]);

            return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('user.profile')->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function deleteProfilePhoto(Request $request)
    {
        return redirect()->route('user.profile')->with('error', 'Hapus foto sekarang dilakukan melalui Simpan Perubahan.');
    }

    public function deleteAccount(Request $request)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));

        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        $request->validate([
            'password' => 'required|string',
        ], [
            'password.required' => 'Password wajib diisi untuk konfirmasi penghapusan akun.',
        ]);

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->route('user.profile')->with('error', 'Password yang Anda masukkan salah! Akun tidak dapat dihapus.');
        }

        try {
            if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                Storage::disk('public')->delete($user->gambar_profil);
            }

            $username = $user->username;
            $user->delete();

            session()->flush();

            return redirect()->route('home')->with('success', "Akun '{$username}' berhasil dihapus. Terima kasih telah menggunakan layanan kami.");
        } catch (\Exception $e) {
            return redirect()->route('user.profile')->with('error', 'Gagal menghapus akun: ' . $e->getMessage());
        }
    }

    public function formulirPenyewaan($id_kostum)
    {
        $kostum = DataKostum::find($id_kostum);

        if (!$kostum) {
            return redirect()->route('katalog.kostum')->with('error', 'Kostum tidak ditemukan.');
        }

        return view('formulir-penyewaan', [
            'kostum' => $kostum,
        ]);
    }

    public function submitFormulirPenyewaan(Request $request, \App\Services\RajaOngkirService $raja)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => (session('user_logged_in') ? 'nullable' : 'required') . '|email|max:255',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'nomor_telepon_2' => 'required|string|max:100',
            'nama_kostum' => 'required|string|max:100',
            'tanggal_pemakaian' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_pemakaian',
            'total_harga' => 'nullable|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'kartu_identitas' => 'required|string|max:50',
            'foto_kartu_identitas' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'selfie_kartu_identitas' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'pernyataan' => 'required|string',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'alamat.required' => 'Alamat wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon_2.required' => 'Nomor telepon kedua wajib diisi.',
            'nama_kostum.required' => 'Nama kostum wajib diisi.',
            'tanggal_pemakaian.required' => 'Tanggal pemakaian wajib diisi.',
            'tanggal_pemakaian.date' => 'Format tanggal pemakaian tidak valid.',
            'tanggal_pengembalian.required' => 'Tanggal pengembalian wajib diisi.',
            'tanggal_pengembalian.date' => 'Format tanggal pengembalian tidak valid.',
            'tanggal_pengembalian.after_or_equal' => 'Tanggal pengembalian harus sama atau setelah tanggal pemakaian.',
            'total_harga.numeric' => 'Total harga harus berupa angka.',
            'total_harga.min' => 'Total harga tidak boleh negatif.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'kartu_identitas.required' => 'Jenis kartu identitas wajib dipilih.',
            'foto_kartu_identitas.required' => 'Foto kartu identitas wajib diupload.',
            'foto_kartu_identitas.image' => 'File foto kartu identitas harus berupa gambar.',
            'foto_kartu_identitas.mimes' => 'Format foto kartu identitas harus jpg, jpeg, png, atau webp.',
            'foto_kartu_identitas.max' => 'Ukuran foto kartu identitas maksimal 5MB.',
            'selfie_kartu_identitas.required' => 'Selfie dengan kartu identitas wajib diupload.',
            'selfie_kartu_identitas.image' => 'File selfie harus berupa gambar.',
            'selfie_kartu_identitas.mimes' => 'Format selfie harus jpg, jpeg, png, atau webp.',
            'selfie_kartu_identitas.max' => 'Ukuran selfie maksimal 5MB.',
            'pernyataan.required' => 'Pernyataan wajib diisi.',
        ]);

        try {
            $fotoKartuPath = '';
            $selfiePath = '';

            if ($request->hasFile('foto_kartu_identitas')) {
                $fotoKartuPath = $request->file('foto_kartu_identitas')->store('formulir_identitas', 'public');
            }

            if ($request->hasFile('selfie_kartu_identitas')) {
                $selfiePath = $request->file('selfie_kartu_identitas')->store('formulir_selfie', 'public');
            }

            // Compute ongkir server-side using RajaOngkirService
            // Use admin's origin city as destination (alamat user dan admin sudah sesuai)
            $originCity = env('RAJAONGKIR_ORIGIN_CITY_ID');
            $weight = 1000; // default weight in grams (1kg)
            $ongkir = 0;
            
            $admin = \App\Models\ProfileContact::first();
            $originCity = env('RAJAONGKIR_ORIGIN_CITY_ID');
            if (!$originCity && $admin) {
                $originCity = $admin->origin_city_id;
            }
            if (!$originCity && $admin && $admin->address) {
                $originCity = $raja->findCityIdFromAddress($admin->address);
            }

            $destinationCityId = $raja->findCityIdFromAddress($request->input('alamat'));
            if ($originCity && $destinationCityId && env('RAJAONGKIR_API_KEY')) {
                try {
                    $costResp = $raja->cost($originCity, $destinationCityId, $weight);
                    if ($costResp && isset($costResp['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'])) {
                        $ongkir = $costResp['rajaongkir']['results'][0]['costs'][0]['cost'][0]['value'];
                    } else {
                        $ongkir = $raja->estimateShippingCostLocal($admin?->address ?? '', $request->input('alamat'), $weight);
                        \Log::warning('RajaOngkir fallback estimate used', [
                            'originCity' => $originCity,
                            'destinationCity' => $destinationCityId,
                            'alamat' => $request->input('alamat'),
                            'admin_address' => $admin?->address,
                            'ongkir' => $ongkir,
                        ]);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('RajaOngkir cost failed: ' . $e->getMessage());
                    $ongkir = $raja->estimateShippingCostLocal($admin?->address ?? '', $request->input('alamat'), $weight);
                }
            } else {
                $ongkir = $raja->estimateShippingCostLocal($admin?->address ?? '', $request->input('alamat'), $weight);
                if (!env('RAJAONGKIR_API_KEY')) {
                    \Log::info('RajaOngkir API key missing, using local fallback estimate', [
                        'originCity' => $originCity,
                        'destination' => $request->input('alamat'),
                    ]);
                } else {
                    \Log::warning('RajaOngkir origin/destination lookup failed, using local fallback estimate', [
                        'originCity' => $originCity,
                        'destinationCity' => $destinationCityId,
                        'alamat' => $request->input('alamat'),
                    ]);
                }
            }

            $hargaSewa = $request->input('harga_sewa', 0);
            $computedTotal = (float)$hargaSewa + (float)$ongkir;

            Formulir::create([
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'nomor_telepon' => $request->input('nomor_telepon'),
                'nomor_telepon_2' => $request->input('nomor_telepon_2'),
                'nama_kostum' => $request->input('nama_kostum'),
                'tanggal_pemakaian' => $request->input('tanggal_pemakaian'),
                'tanggal_pengembalian' => $request->input('tanggal_pengembalian'),
                'total_harga' => $computedTotal,
                'ongkir' => $ongkir,
                'metode_pembayaran' => $request->input('metode_pembayaran'),
                'kartu_identitas' => $request->input('kartu_identitas'),
                'foto_kartu_identitas' => $fotoKartuPath,
                'selfie_kartu_identitas' => $selfiePath,
                // bukti_pembayaran column exists in DB and is non-nullable in some environments
                // ensure we provide a sensible default (empty string) to avoid SQL strict-mode errors
                'bukti_pembayaran' => '',
                'pernyataan' => $request->input('pernyataan'),
                'email' => session('user_logged_in') ? session('user_email') : $request->input('email'),
                'status' => 'proses',
            ]);

            return redirect()->route('formulir.berhasil')->with('formulir_success', 'Formulir penyewaan berhasil dikirim!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim formulir: ' . $e->getMessage())->withInput();
        }
    }

    public function formulirBerhasil()
    {
        // Show dedicated confirmation page with popup modal to avoid clashing with other success messages
        return view('formulir-berhasil', [
            'message' => session('formulir_success') ?? 'Formulir penyewaan berhasil dikirim!',
        ]);
    }

    public function pesananSaya()
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userId = session('user_id');
        // Guard: ensure required columns exist (email + status)
        if (!Schema::hasColumn('formulir', 'email') || !Schema::hasColumn('formulir', 'status')) {
            return redirect()->route('user.profile')->with('error', 'Fitur Pesanan Saya belum aktif karena migrasi belum dijalankan. Jalankan: artisan migrate untuk menambahkan kolom email dan status pada tabel formulir.');
        }

        $pesanan = Formulir::where('email', session('user_email'))
            ->orderBy('created_at', 'asc')
            ->get();

        return view('user.pesanan-saya', [
            'pesanan' => $pesanan,
        ]);
    }

    public function editPesanan($id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $id)->where('email', $userEmail)->firstOrFail();
        if (!in_array($order->status, ['proses', 'revisi'], true)) {
            return redirect()->route('user.pesanan')->with('error', 'Pesanan tidak dapat diedit karena statusnya bukan PROSES atau REVISI.');
        }

        return view('user.edit-pesanan', [
            'order' => $order,
        ]);
    }

    public function updatePesanan(Request $request, $id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $id)->where('email', $userEmail)->firstOrFail();
        if (!in_array($order->status, ['proses', 'revisi'], true)) {
            return redirect()->route('user.pesanan')->with('error', 'Pesanan tidak dapat diubah karena statusnya bukan PROSES atau REVISI.');
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'nomor_telepon_2' => 'required|string|max:100',
            'tanggal_pemakaian' => 'required|date',
            'tanggal_pengembalian' => 'required|date|after_or_equal:tanggal_pemakaian',
            'total_harga' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'kartu_identitas' => 'required|string|max:50',
            'foto_kartu_identitas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'selfie_kartu_identitas' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'pernyataan' => 'required|string',
        ]);

        // Handle Kartu Identitas "Lainnya"
        $kartuIdentitas = $request->input('kartu_identitas');
        if ($kartuIdentitas === 'Lainnya') {
            $manual = trim((string) $request->input('kartu_identitas_lainnya'));
            if ($manual !== '') {
                $kartuIdentitas = $manual;
            }
        }

        // File updates
        if ($request->hasFile('foto_kartu_identitas')) {
            if ($order->foto_kartu_identitas && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->foto_kartu_identitas)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($order->foto_kartu_identitas);
            }
            $order->foto_kartu_identitas = $request->file('foto_kartu_identitas')->store('formulir_identitas', 'public');
        }

        if ($request->hasFile('selfie_kartu_identitas')) {
            if ($order->selfie_kartu_identitas && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->selfie_kartu_identitas)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($order->selfie_kartu_identitas);
            }
            $order->selfie_kartu_identitas = $request->file('selfie_kartu_identitas')->store('formulir_selfie', 'public');
        }

        $order->nama = $request->input('nama');
        $order->alamat = $request->input('alamat');
        $order->nomor_telepon = $request->input('nomor_telepon');
        $order->nomor_telepon_2 = $request->input('nomor_telepon_2');
        $order->tanggal_pemakaian = $request->input('tanggal_pemakaian');
        $order->tanggal_pengembalian = $request->input('tanggal_pengembalian');
        $order->total_harga = $request->input('total_harga');
        $order->metode_pembayaran = $request->input('metode_pembayaran');
        $order->kartu_identitas = $kartuIdentitas;
        $order->pernyataan = $request->input('pernyataan');
        $order->save();

        return redirect()->route('user.pesanan')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function cancelPesanan(Request $request, $id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $id)->where('email', $userEmail)->firstOrFail();
        if (!in_array($order->status, ['proses', 'revisi'], true)) {
            return redirect()->route('user.pesanan')->with('error', 'Pesanan tidak dapat dibatalkan karena statusnya bukan PROSES atau REVISI.');
        }

        $order->status = 'revisi';
        $order->save();

        return redirect()->route('user.pesanan')->with('success', 'Status pesanan diubah menjadi revisi.');
    }

    public function deletePesanan(Request $request, $id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $id)->where('email', $userEmail)->firstOrFail();
        if (!in_array($order->status, ['proses', 'revisi'], true)) {
            return redirect()->route('user.pesanan')->with('error', 'Pesanan tidak dapat dihapus karena statusnya bukan PROSES atau REVISI.');
        }

        $order->delete();

        return redirect()->route('user.pesanan')->with('success', 'Pesanan berhasil dihapus.');
    }

    public function pengembalianSaya()
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');

        $pengembalianHasLinkColumn = Schema::hasColumn('pengembalian', 'formulir_id');

        $pembayaranHasLinkColumn = false;
        try {
            $pembayaranHasLinkColumn = Schema::hasColumn('pembayaran', 'formulir_id');
        } catch (\Exception $e) {
            $pembayaranHasLinkColumn = false;
        }

        $ordersQuery = Formulir::query();
        if ($pengembalianHasLinkColumn) {
            $ordersQuery->with('pengembalian');
        }
        if ($pembayaranHasLinkColumn) {
            $ordersQuery->with('pembayaran');
        }

        $orders = $ordersQuery
            ->where('email', $userEmail)
            ->orderBy('created_at', 'desc')
            ->get();

        $paidOrderIdsFromStorage = [];
        try {
            $files = Storage::disk('public')->files('bukti_pembayaran');
            foreach ($files as $file) {
                $filename = basename($file);
                if (preg_match('/^bukti_(\d+)_/i', $filename, $matches)) {
                    $paidOrderIdsFromStorage[(int) $matches[1]] = true;
                }
            }
        } catch (\Exception $e) {
            $paidOrderIdsFromStorage = [];
        }

        $profile_contact = ProfileContact::find(1);
        $returnRequests = collect();

        // Exclude orders that already have a pengembalian (or were just submitted)
        $recentFormulirId = session('recent_pengembalian_formulir_id');

        $activeOrders = $orders->filter(function ($o) use ($recentFormulirId, $pengembalianHasLinkColumn, $pembayaranHasLinkColumn, $paidOrderIdsFromStorage) {
            if (($o->status ?? null) !== 'diterima') {
                return false;
            }

            $hasPaymentProof = false;

            // Legacy field on formulir table
            if (!empty($o->bukti_pembayaran)) {
                $hasPaymentProof = true;
            }

            // Latest pembayaran relation (if available)
            if (!$hasPaymentProof && $pembayaranHasLinkColumn) {
                $hasPaymentProof = !empty(data_get($o, 'pembayaran.bukti_pembayaran'));
            }

            // Fallback: scan storage for bukti_{id}_* files
            if (!$hasPaymentProof && isset($paidOrderIdsFromStorage[$o->id])) {
                $hasPaymentProof = true;
            }

            if (!$hasPaymentProof) {
                return false;
            }

            // If pengembalian link exists, only exclude orders whose latest pengembalian is still pending/approved.
            // Rejected pengembalian should reappear here so the user can submit again.
            if ($pengembalianHasLinkColumn) {
                $latestReturnStatus = data_get($o, 'pengembalian.status');
                if ($latestReturnStatus && $latestReturnStatus !== 'ditolak') {
                    return false;
                }
            }

            // Also exclude the order if it was just submitted in this session
            if ($recentFormulirId && $o->id == $recentFormulirId) {
                return false;
            }

            return true;
        })->values();

        if ($pengembalianHasLinkColumn) {
            $returnRequests = Pengembalian::with('formulir')
                ->whereHas('formulir', function ($query) use ($userEmail) {
                    $query->where('email', $userEmail);
                })
                ->orderByDesc('created_at')
                ->get();

            // If a recent pengembalian was just created in this session but not linked, include it
            $recentId = session('recent_pengembalian_id');
            if ($recentId) {
                try {
                    $recent = Pengembalian::with('formulir')->find($recentId);
                    if ($recent && !$returnRequests->contains('id', $recent->id)) {
                        $returnRequests->prepend($recent);
                    }
                } catch (\Exception $e) {
                    // ignore
                }
            }
        }

        return view('user.pengembalian-saya', [
            'activeOrders' => $activeOrders,
            'returnRequests' => $returnRequests,
            'profile_contact' => $profile_contact,
            'pengembalianHasLinkColumn' => $pengembalianHasLinkColumn,
        ]);
    }

    public function submitPengembalian(Request $request, $id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $id)->where('email', $userEmail)->firstOrFail();

        if ($order->status !== 'diterima') {
            return redirect()->route('user.pengembalian')->with('error', 'Pesanan ini belum bisa diproses untuk pengembalian.');
        }

        $hasPaymentProof = false;

        // Legacy field on formulir table
        if (!empty($order->bukti_pembayaran)) {
            $hasPaymentProof = true;
        }

        // Latest pembayaran record (if schema supports it)
        if (!$hasPaymentProof) {
            $pembayaranSafe = $order->pembayaran_safe;
            if ($pembayaranSafe && !empty($pembayaranSafe->bukti_pembayaran)) {
                $hasPaymentProof = true;
            }
        }

        // Fallback: scan storage for bukti_{id}_* files
        if (!$hasPaymentProof) {
            try {
                $files = Storage::disk('public')->files('bukti_pembayaran');
                foreach ($files as $file) {
                    if (str_starts_with(basename($file), 'bukti_' . $order->id . '_')) {
                        $hasPaymentProof = true;
                        break;
                    }
                }
            } catch (\Exception $e) {
                $hasPaymentProof = false;
            }
        }

        if (!$hasPaymentProof) {
            return redirect()->route('user.pengembalian')->with('error', 'Pesanan ini belum bisa diajukan pengembalian karena bukti pembayaran belum diunggah.');
        }

        $latestPengembalian = null;
        $isReapply = false;
        if (Schema::hasColumn('pengembalian', 'formulir_id')) {
            $latestPengembalian = $order->pengembalian;
            $isReapply = $latestPengembalian && $latestPengembalian->status === 'ditolak';
        }

        $gambarRule = $isReapply
            ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
            : 'required|image|mimes:jpg,jpeg,png,webp|max:5120';

        $request->validate([
            'gambar1' => $gambarRule,
            'gambar2' => $gambarRule,
            'gambar3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $gambar1Path = $request->hasFile('gambar1')
            ? $request->file('gambar1')->store('pengembalian', 'public')
            : data_get($latestPengembalian, 'gambar1');
        $gambar2Path = $request->hasFile('gambar2')
            ? $request->file('gambar2')->store('pengembalian', 'public')
            : data_get($latestPengembalian, 'gambar2');
        $gambar3Path = $request->hasFile('gambar3')
            ? $request->file('gambar3')->store('pengembalian', 'public')
            : data_get($latestPengembalian, 'gambar3');

        if (!$gambar1Path || !$gambar2Path) {
            return redirect()->route('user.pengembalian')->with('error', 'Gambar pengembalian sebelumnya belum lengkap. Silakan unggah ulang Gambar 1 dan Gambar 2.')->withInput();
        }

        $catatan = trim((string) $request->input('catatan'));

        $pengembalianData = [
            'gambar1' => $gambar1Path,
            'gambar2' => $gambar2Path,
            'gambar3' => $gambar3Path,
            'status' => 'proses',
            'catatan' => $catatan !== '' ? $catatan : null,
        ];

        if ($pengembalianHasLinkColumn = Schema::hasColumn('pengembalian', 'formulir_id')) {
            $pengembalianData['formulir_id'] = $order->id;
        }

        $created = Pengembalian::create($pengembalianData);

        // Remember recent submission so the user sees it immediately in history
        session()->flash('recent_pengembalian_id', $created->id);
        session()->flash('recent_pengembalian_formulir_id', $order->id);

        return redirect()->route('user.pengembalian')->with('success', 'Pengembalian kostum berhasil diajukan dan sedang menunggu verifikasi admin.');
    }
}
