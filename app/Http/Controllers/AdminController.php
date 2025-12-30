<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKatalog;
use App\Models\DataKostum;
use App\Models\ProfileContact;
use App\Models\Aturan;
use App\Models\Formulir;
use App\Models\User;
use App\Models\Denda;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // Login form
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session('admin_logged_in')) {
            return redirect()->route('admin.profile');
        }
        
        return view('admin.login');
    }

    // Authenticate admin
    public function authenticate(Request $request)
    {
        $username = trim($request->input('username'));
        $password = trim($request->input('password'));

        $admin_username = "admin";

        $admin_password = null;
        try {
            $profile = ProfileContact::find(1);
            if ($profile && isset($profile->password) && trim($profile->password) !== '') {
                $admin_password = trim($profile->password);
            } else {
                return redirect()->route('admin.login')->with('error', 'Password admin belum disetel. Hubungi pengelola.');
            }
        } catch (\Exception $e) {
            \Log::warning('Could not read admin password from profile_contacts: ' . $e->getMessage());
            return redirect()->route('admin.login')->with('error', 'Gagal memeriksa password admin.');
        }

        if ($username === $admin_username && $password === $admin_password) {
            session(['admin_logged_in' => true, 'admin_name' => $username]);
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.profile')->with('error', 'Username atau password salah!');
        }
    }

    // Dashboard
    public function dashboard()
    {
        // Check if admin is logged in
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Always redirect to the profile handler so the view receives required data
        return redirect()->route('admin.profile');
    }

    // Admin Profile
    public function profile()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $admin_name = session('admin_name');
        $katalog_count = DataKatalog::count();
        $kostum_count = DataKostum::count();
        $aturan_count = Aturan::count();
        $pesanan_count = Formulir::count();
        $users_count = User::count();
        $denda_count = Denda::count();
        $profile_contact = ProfileContact::find(1);

        return view('admin.profile', [
            'admin_name' => $admin_name,
            'katalog_count' => $katalog_count,
            'kostum_count' => $kostum_count,
            'aturan_count' => $aturan_count,
            'pesanan_count' => $pesanan_count,
            'denda_count' => $denda_count,
            'users_count' => $users_count,
            'profile_contact' => $profile_contact,
        ]);
    }

    // AJAX stats endpoint for dashboard charts (orders, revenue)
    public function stats(Request $request)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $period = $request->input('period', 'week'); // day, week, month, year
        $now = Carbon::now();

        $labels = [];
        $ordersData = [];
        $revenueData = [];

        switch ($period) {
            case 'day':
                // 24 hours of today
                $start = $now->copy()->startOfDay();
                for ($h = 0; $h < 24; $h++) {
                    $from = $start->copy()->addHours($h);
                    $to = $from->copy()->endOfHour();
                    $labels[] = $from->format('H:00');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'month':
                // last 30 days
                $start = $now->copy()->subDays(29)->startOfDay();
                for ($d = 0; $d < 30; $d++) {
                    $from = $start->copy()->addDays($d)->startOfDay();
                    $to = $from->copy()->endOfDay();
                    $labels[] = $from->format('d M');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'year':
                // 12 months of current year
                $start = $now->copy()->startOfYear();
                for ($m = 0; $m < 12; $m++) {
                    $from = $start->copy()->addMonths($m)->startOfMonth();
                    $to = $from->copy()->endOfMonth();
                    $labels[] = $from->format('M');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'week':
            default:
                // last 7 days
                $start = $now->copy()->subDays(6)->startOfDay();
                for ($d = 0; $d < 7; $d++) {
                    $from = $start->copy()->addDays($d)->startOfDay();
                    $to = $from->copy()->endOfDay();
                    $labels[] = $from->format('D d');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
        }

        // totals for the whole period
        $periodStart = $start ?? $now->copy()->subDays(6)->startOfDay();
        $periodEnd = $to ?? $now->copy()->endOfDay();

        $totalOrders = Formulir::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalRevenue = Formulir::whereBetween('created_at', [$periodStart, $periodEnd])->sum('total_harga');

        return response()->json([
            'labels' => $labels,
            'datasets' => [
                'orders' => $ordersData,
                'revenue' => $revenueData,
            ],
            'totals' => [
                'orders' => $totalOrders,
                'revenue' => (float) $totalRevenue,
            ],
            'period' => $period,
        ]);
    }

    // Data Katalog - List
    public function dataKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $query = DataKatalog::query();

        // Pencarian berdasarkan nama atau deskripsi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        // Sortir
        $sort = $request->input('sort', 'id_desc');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $katalog = $query->get();
        $kategoriOptions = DataKatalog::select('kategori')->distinct()->pluck('kategori')->toArray();

        return view('admin.data-katalog', [
            'katalog' => $katalog,
            'search' => $request->input('search'),
            'filter_kategori' => $request->input('kategori'),
            'kategori_options' => $kategoriOptions,
            'sort' => $sort
        ]);
    }

    // Store new katalog
    public function storeKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $name = $request->input('name');
        $kategori = $request->input('kategori');
        $description = $request->input('description');
        $image = '';

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Store directly to public disk root (storage/app/public/)
            $file->storeAs('', $fileName, 'public');
            $image = 'storage/' . $fileName;
        }

        try {
            // Validate that required fields are not empty
            if (empty($name) || empty($kategori) || empty($description)) {
                return redirect()->route('admin.data-katalog')->with('error', 'Nama, Kategori, dan Deskripsi harus diisi!');
            }
            
            DataKatalog::create([
                'name' => $name,
                'kategori' => $kategori,
                'description' => $description,
                'image' => $image
            ]);

            return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Katalog store error: ' . $e->getMessage());
            return redirect()->route('admin.data-katalog')->with('error', 'Gagal menambahkan katalog: ' . $e->getMessage());
        }
    }

    // Update katalog
    public function updateKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $id = $request->input('id');
        $katalog = DataKatalog::find($id);

        if (!$katalog) {
            return redirect()->route('admin.data-katalog')->with('error', 'Katalog tidak ditemukan!');
        }

        $katalog->name = $request->input('name');
        $katalog->kategori = $request->input('kategori');
        $katalog->description = $request->input('description');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Store directly to public disk root (storage/app/public/)
            $file->storeAs('', $fileName, 'public');
            $katalog->image = 'storage/' . $fileName;
        }

        $katalog->save();

        return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil diperbarui!');
    }

    // Delete katalog
    public function deleteKatalog($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $katalog = DataKatalog::find($id);

        if (!$katalog) {
            return redirect()->route('admin.data-katalog')->with('error', 'Katalog tidak ditemukan!');
        }

        $katalog->delete();

        return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil dihapus!');
    }

    // Logout
    public function logout()
    {
        session()->flush();
        return redirect()->route('home')->with('logout_message', 'Anda telah keluar dari sesi admin.');
    }

    // Data Kostum - List
    public function dataKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $query = DataKostum::query();

        // Pencarian berdasarkan nama, brand, kategori
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_kostum', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        // Filter jenis kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        // Filter ukuran
        if ($request->filled('ukuran')) {
            $query->where('ukuran_kostum', 'like', "%{$request->input('ukuran')}%");
        }

        // Sortir
        $sort = $request->input('sort', 'id_asc');
        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama_kostum', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_kostum', 'desc');
                break;
            case 'harga_asc':
                $query->orderBy('harga_sewa', 'asc');
                break;
            case 'harga_desc':
                $query->orderBy('harga_sewa', 'desc');
                break;
            default:
                $query->orderBy('id_kostum', 'asc');
        }

        $kostum = $query->get();
        $kategori = DataKatalog::pluck('name')->toArray();
        // Get all unique sizes, split combined entries like "M & L" into individual sizes
        $allSizesRaw = DataKostum::get()->pluck('ukuran_kostum')->toArray();
        $sizeList = [];
        foreach ($allSizesRaw as $sizeStr) {
            if (!is_string($sizeStr)) {
                continue;
            }
            $parts = preg_split('/[,;&]/', $sizeStr);
            foreach ($parts as $p) {
                $clean = trim($p);
                if ($clean !== '') {
                    $sizeList[] = $clean;
                }
            }
        }
        $ukuran = array_values(array_unique($sizeList));
        $orderMap = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, 'XXXL' => 7];
        usort($ukuran, function($a, $b) use ($orderMap) {
            $aKey = strtoupper($a);
            $bKey = strtoupper($b);
            $aRank = $orderMap[$aKey] ?? 999;
            $bRank = $orderMap[$bKey] ?? 999;
            if ($aRank === $bRank) {
                return strcasecmp($aKey, $bKey);
            }
            return $aRank <=> $bRank;
        });
        
        return view('admin.data-kostum', [
            'kostum' => $kostum, 
            'kategori' => $kategori,
            'ukuran' => $ukuran,
            'search' => $request->input('search'),
            'filter_kategori' => $request->input('kategori'),
            'filter_jenis_kelamin' => $request->input('jenis_kelamin'),
            'filter_ukuran' => $request->input('ukuran'),
            'sort' => $sort
        ]);
    }

    // Store new kostum
    public function storeKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'kategori' => 'required',
            'nama_kostum' => 'required',
            'judul' => 'required',
            'harga_sewa' => 'required|numeric',
            'durasi_penyewaan' => 'required',
            'ukuran_kostum' => 'required',
            'jenis_kelamin' => 'required',
            'brand' => 'required',
            'include' => 'required',
            'exclude' => 'nullable',
            'domisili' => 'required',
            'gambar' => 'required|image|max:5120'
        ], [
            'kategori.required' => 'Kategori wajib diisi!',
            'nama_kostum.required' => 'Nama kostum wajib diisi!',
            'judul.required' => 'Judul wajib diisi!',
            'harga_sewa.required' => 'Harga sewa wajib diisi!',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka!',
            'durasi_penyewaan.required' => 'Durasi penyewaan wajib diisi!',
            'ukuran_kostum.required' => 'Ukuran kostum wajib diisi!',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih!',
            'brand.required' => 'Brand wajib diisi!',
            'include.required' => 'Include wajib diisi!',
            'domisili.required' => 'Domisili wajib diisi!',
            'gambar.required' => 'Gambar wajib diupload!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.max' => 'Ukuran gambar maksimal 5MB!'
        ]);

        $kategori = $request->input('kategori');
        $nama_kostum = $request->input('nama_kostum');
        $judul = $request->input('judul', '');
        $harga_sewa = $request->input('harga_sewa');
        $durasi_penyewaan = $request->input('durasi_penyewaan');
        $ukuran_kostum = $request->input('ukuran_kostum');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $brand = $request->input('brand', '');
        $include = $request->input('include');
        $exclude = $request->input('exclude');
        $domisili = $request->input('domisili', '');
        $gambar = '';

        // If there is an uploaded image, store it and set path
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $stored = $file->storeAs('', $fileName, 'public');
            $gambar = 'storage/' . $stored;
        }

        // Create kostum
        $kostum = DataKostum::create([
            'kategori' => $kategori,
            'nama_kostum' => $nama_kostum,
            'judul' => $judul,
            'harga_sewa' => $harga_sewa,
            'durasi_penyewaan' => $durasi_penyewaan,
            'ukuran_kostum' => $ukuran_kostum,
            'jenis_kelamin' => $jenis_kelamin,
            'brand' => $brand,
            'include' => $include,
            'exclude' => $exclude,
            'domisili' => $domisili,
            'gambar' => $gambar
        ]);

        return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil ditambahkan!');
    }

    // Update kostum
    public function updateKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'kategori' => 'required',
            'nama_kostum' => 'required',
            'judul' => 'required',
            'harga_sewa' => 'required|numeric',
            'durasi_penyewaan' => 'required',
            'ukuran_kostum' => 'required',
            'jenis_kelamin' => 'required',
            'brand' => 'required',
            'include' => 'required',
            'exclude' => 'nullable',
            'domisili' => 'required',
            'gambar' => 'nullable|image|max:5120'
        ], [
            'kategori.required' => 'Kategori wajib diisi!',
            'nama_kostum.required' => 'Nama kostum wajib diisi!',
            'judul.required' => 'Judul wajib diisi!',
            'harga_sewa.required' => 'Harga sewa wajib diisi!',
            'harga_sewa.numeric' => 'Harga sewa harus berupa angka!',
            'durasi_penyewaan.required' => 'Durasi penyewaan wajib diisi!',
            'ukuran_kostum.required' => 'Ukuran kostum wajib diisi!',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih!',
            'brand.required' => 'Brand wajib diisi!',
            'include.required' => 'Include wajib diisi!',
            'domisili.required' => 'Domisili wajib diisi!',
            'gambar.image' => 'File harus berupa gambar!',
            'gambar.max' => 'Ukuran gambar maksimal 5MB!'
        ]);

        $id = $request->input('id_kostum');
        $kostum = DataKostum::find($id);

        if (!$kostum) {
            return redirect()->route('admin.data-kostum')->with('error', 'Kostum tidak ditemukan!');
        }

        $kostum->kategori = $request->input('kategori');
        $kostum->nama_kostum = $request->input('nama_kostum');
        $kostum->judul = $request->input('judul') ?: '';
        $kostum->harga_sewa = $request->input('harga_sewa');
        $kostum->durasi_penyewaan = $request->input('durasi_penyewaan');
        $kostum->ukuran_kostum = $request->input('ukuran_kostum');
        $kostum->jenis_kelamin = $request->input('jenis_kelamin');
        $kostum->brand = $request->input('brand') ?: '';
        $kostum->include = $request->input('include');
        $kostum->exclude = $request->input('exclude');
        $kostum->domisili = $request->input('domisili') ?: '';

        // Handle single image replacement
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $stored = $file->storeAs('', $fileName, 'public');
            $kostum->gambar = 'storage/' . $stored;
        }

        $kostum->save();

        return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil diperbarui!');
    }

    // Delete image
    public function deleteKostumImage($imageId)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            // With single-image mode, simply return not supported
            return response()->json(['success' => false, 'message' => 'Multiple image management disabled'], 400);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Delete kostum
    public function deleteKostum($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $kostum = DataKostum::find($id);

        if (!$kostum) {
            return redirect()->route('admin.data-kostum')->with('error', 'Kostum tidak ditemukan!');
        }

        $kostum->delete();

        return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil dihapus!');
    }

    // Profile Contact - View
    public function profileContact()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $profile = ProfileContact::find(1);

        return view('admin.profile-contact', ['profile' => $profile]);
    }

    // Profile Contact - Update
    public function updateProfileContact(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
            'vision' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'nomor_ewallet' => 'nullable|string|max:50',
            'nomor_bank' => 'nullable|string|max:50',
            'qris' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5242880',
        ], [
            'name.required' => 'Nama pengurus wajib diisi.',
            'title.required' => 'Jabatan wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Gambar harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
            'remove_photo.boolean' => 'Parameter hapus foto tidak valid.',
            'vision.required' => 'Visi wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'phone.required' => 'Telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_ewallet.max' => 'Panjang nomor e-wallet maksimal 50 karakter.',
            'nomor_bank.max' => 'Panjang nomor bank maksimal 50 karakter.',
            'qris.image' => 'QRIS harus berupa gambar.',
            'qris.mimes' => 'Format QRIS harus jpg, jpeg, png, atau webp.',
            'qris.max' => 'Ukuran QRIS maksimal 5MB.',
        ]);

        try {
            $profile = ProfileContact::find(1);
            
            if (!$profile) {
                $profile = new ProfileContact();
            }

            $profile->name = $request->input('name');
            $profile->title = $request->input('title');
            $profile->vision = $request->input('vision');
            $profile->address = $request->input('address');
            $profile->phone = $request->input('phone');
            $profile->email = $request->input('email');
            $shouldRemovePhoto = $request->boolean('remove_photo');

            // Hapus foto jika ditandai untuk dihapus
            if ($shouldRemovePhoto && $profile->photo) {
                if (Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $publicPath = public_path('storage/' . $profile->photo);
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
                $profile->photo = null;
                $profile->save();
            }

            if ($request->hasFile('photo')) {
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $publicPath = public_path('storage/' . $profile->photo);
                if ($profile->photo && file_exists($publicPath)) {
                    @unlink($publicPath);
                }

                $path = $request->file('photo')->store('profile_photos', 'public');
                // Simpan relative path (tanpa prefix storage/) agar konsisten dengan disk public
                $profile->photo = $path;
            }

            $profile->save();

            // Persist payment information merged into profile_contacts
            try {
                $profile->nomor_ewallet = $request->input('nomor_ewallet');
                $profile->nomor_bank = $request->input('nomor_bank');

                // Handle QRIS file upload or removal stored on profile
                $shouldRemoveQris = $request->boolean('remove_qris');
                if ($shouldRemoveQris && $profile->qris) {
                    if (Storage::disk('public')->exists($profile->qris)) {
                        Storage::disk('public')->delete($profile->qris);
                    }
                    $publicPath = public_path('storage/' . $profile->qris);
                    if (file_exists($publicPath)) {
                        @unlink($publicPath);
                    }
                    $profile->qris = null;
                }

                if ($request->hasFile('qris')) {
                    if ($profile->qris && Storage::disk('public')->exists($profile->qris)) {
                        Storage::disk('public')->delete($profile->qris);
                    }
                    $publicPath = public_path('storage/' . $profile->qris);
                    if ($profile->qris && file_exists($publicPath)) {
                        @unlink($publicPath);
                    }

                    $path = $request->file('qris')->store('payment_qris', 'public');
                    $profile->qris = $path;
                }

                $profile->save();
            } catch (\Exception $e) {
                \Log::error('Failed to save pembayaran merged into profile_contacts: ' . $e->getMessage());
            }

            return redirect()->route('admin.profile-contact')->with('success', 'Profil & kontak berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    // Update Profile Contact Photo
    public function updateProfileContactPhoto(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.profile');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'photo.required' => 'Pilih foto terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Gambar harus jpg, jpeg, png, atau webp.',
            'photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $profile = ProfileContact::find(1);

            if (!$profile) {
                $profile = new ProfileContact();
            }

            // Delete old image if exists
            if ($profile->photo) {
                if (Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $publicPath = public_path($profile->photo);
                if (file_exists($publicPath)) {
                    unlink($publicPath);
                }
            }

            // Store new image
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('', 'public');
                $profile->photo = 'storage/' . $path;
            }

            $profile->save();

            return redirect()->route('admin.profile')->with('success', 'Foto profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile')->with('error', 'Gagal mengunggah foto: ' . $e->getMessage());
        }
    }

    // Delete Profile Contact Photo
    public function deleteProfileContactPhoto(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $profile = ProfileContact::find(1);

            if ($profile && $profile->photo) {
                // Delete from public disk
                if (Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                // Delete from public assets folder
                $publicPath = public_path('storage/' . $profile->photo);
                if (file_exists($publicPath)) {
                    unlink($publicPath);
                }

                $profile->photo = null;
                $profile->save();
            }

            return redirect()->route('admin.profile')->with('success', 'Foto profil berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile')->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    // Update payment QRIS image
    public function updatePaymentQris(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'qris' => 'required|image|mimes:jpg,jpeg,png,webp|max:5242880',
        ], [
            'qris.required' => 'Pilih gambar QRIS terlebih dahulu.',
            'qris.image' => 'File harus berupa gambar.',
            'qris.mimes' => 'Gambar harus jpg, jpeg, png, atau webp.',
            'qris.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        try {
            $profile = ProfileContact::find(1);
            if (!$profile) {
                $profile = new ProfileContact();
            }

            // delete old qris if exists
            if ($profile->qris) {
                if (Storage::disk('public')->exists($profile->qris)) {
                    Storage::disk('public')->delete($profile->qris);
                }
                $publicPath = public_path('storage/' . $profile->qris);
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
            }

            $path = $request->file('qris')->store('payment_qris', 'public');
            $profile->qris = $path;
            $profile->save();

            return redirect()->route('admin.profile-contact')->with('success', 'QRIS berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal mengunggah QRIS: ' . $e->getMessage());
        }
    }

    public function deletePaymentQris(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $profile = ProfileContact::find(1);
            if ($profile && $profile->qris) {
                if (Storage::disk('public')->exists($profile->qris)) {
                    Storage::disk('public')->delete($profile->qris);
                }
                $publicPath = public_path('storage/' . $profile->qris);
                if (file_exists($publicPath)) {
                    @unlink($publicPath);
                }
                $profile->qris = null;
                $profile->save();
            }

            return redirect()->route('admin.profile-contact')->with('success', 'QRIS berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal menghapus QRIS: ' . $e->getMessage());
        }
    }

    // ==================== DATA ATURAN ====================
    
    public function dataAturan()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $aturan = Aturan::orderBy('created_at', 'desc')->get();
        return view('admin.data-aturan', compact('aturan'));
    }

    public function storeAturan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'syarat_ketentuan' => 'required|string',
            'larangan_dan_denda' => 'required|string',
        ]);

        try {
            Aturan::create([
                'syarat_ketentuan' => $request->input('syarat_ketentuan'),
                'larangan_dan_denda' => $request->input('larangan_dan_denda'),
            ]);

            return redirect()->route('admin.data-aturan')->with('success', 'Data aturan berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function updateAturan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'id' => 'required|exists:aturan,id',
            'syarat_ketentuan' => 'required|string',
            'larangan_dan_denda' => 'required|string',
        ]);

        try {
            $aturan = Aturan::findOrFail($request->input('id'));
            $aturan->update([
                'syarat_ketentuan' => $request->input('syarat_ketentuan'),
                'larangan_dan_denda' => $request->input('larangan_dan_denda'),
            ]);

            return redirect()->route('admin.data-aturan')->with('success', 'Data aturan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function deleteAturan($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $aturan = Aturan::findOrFail($id);
            $aturan->delete();

            return redirect()->route('admin.data-aturan')->with('success', 'Data aturan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // ==================== DATA PESANAN ====================

    public function dataPesanan()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $pesanan = Formulir::orderBy('created_at', 'desc')->get();
        $statusOptions = ['proses', 'revisi', 'diterima', 'selesai'];

        return view('admin.data-pesanan', compact('pesanan', 'statusOptions'));
    }

    // ==================== DATA DENDA & KERUSAKAN ====================

    public function dataDenda()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Only eager-load pembayaran if the pembayaran.formulir_id column exists
        $canJoinPembayaran = false;
        try {
            $canJoinPembayaran = Schema::hasTable('pembayaran') && Schema::hasColumn('pembayaran', 'formulir_id');
        } catch (\Exception $e) {
            $canJoinPembayaran = false;
        }

        if ($canJoinPembayaran) {
            $formulir = Formulir::with('pembayaran')->orderBy('created_at', 'desc')->get();
        } else {
            // Fallback: load formulir only and use safe accessor in the view
            $formulir = Formulir::orderBy('created_at', 'desc')->get();
        }

        // Load denda list for CRUD management on the same page
        $dendas = [];
        try {
            $dendas = Denda::orderBy('id', 'desc')->get();
        } catch (\Exception $e) {
            $dendas = [];
        }

        return view('admin.data-denda', [
            'formulir' => $formulir,
            'dendas' => $dendas,
        ]);
    }

    public function updatePesananStatus(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'status' => 'required|in:proses,revisi,diterima,selesai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $pesanan = Formulir::findOrFail($id);
            $pesanan->status = $request->input('status');
            $pesanan->keterangan = $request->input('keterangan');
            $pesanan->save();

            return redirect()->route('admin.data-pesanan')->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pesanan')->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    // Admin: delete a pesanan (order)
    public function deletePesanan(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $order = Formulir::findOrFail($id);

            // delete identity images if present
            if ($order->foto_kartu_identitas && Storage::disk('public')->exists($order->foto_kartu_identitas)) {
                Storage::disk('public')->delete($order->foto_kartu_identitas);
            }
            if ($order->selfie_kartu_identitas && Storage::disk('public')->exists($order->selfie_kartu_identitas)) {
                Storage::disk('public')->delete($order->selfie_kartu_identitas);
            }

            // delete related pembayaran records and their files if the table exists
            try {
                if (Schema::hasTable('pembayaran')) {
                    $pembayarans = Pembayaran::where('formulir_id', $order->id)->get();
                    foreach ($pembayarans as $p) {
                        if ($p->bukti_pembayaran && Storage::disk('public')->exists($p->bukti_pembayaran)) {
                            Storage::disk('public')->delete($p->bukti_pembayaran);
                        }
                        $p->delete();
                    }
                }
            } catch (\Exception $e) {
                // If the pembayaran table doesn't exist or another error occurs, log and continue
                \Log::warning('Skipping pembayaran cleanup: ' . $e->getMessage());
            }

            $order->delete();

            return redirect()->route('admin.data-pesanan')->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Admin deletePesanan error: ' . $e->getMessage());
            return redirect()->route('admin.data-pesanan')->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }

    // ==================== DATA PENGGUNA ====================

    public function dataPengguna()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = User::orderBy('id', 'asc')->get();
        return view('admin.data-pengguna', compact('users'));
    }

    public function updatePengguna(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'id' => 'required|exists:users,id',
            'username' => 'required|string|max:255|lowercase|no_spaces|unique:users,username,' . $request->input('id'),
            'nick_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->input('id'),
            'alamat' => 'nullable|string|max:1000',
            'nomor_telepon' => 'nullable|regex:/^08[0-9]{8,13}$/',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'gambar_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        try {
            $user = User::findOrFail($request->input('id'));
            $user->username = strtolower($request->input('username'));
            $user->nick_name = $request->input('nick_name');
            $user->email = $request->input('email');
            $user->alamat = $request->input('alamat');
            $user->nomor_telepon = $request->input('nomor_telepon');
            $user->jenis_kelamin = $request->input('jenis_kelamin');
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
            }

            // Handle profile image deletion if requested
            $shouldRemovePhoto = $request->boolean('remove_photo');
            if ($shouldRemovePhoto && $user->gambar_profil) {
                if (Storage::disk('public')->exists($user->gambar_profil)) {
                    Storage::disk('public')->delete($user->gambar_profil);
                }
                $user->gambar_profil = null;
            }

            // Handle new image upload
            if ($request->hasFile('gambar_profil')) {
                if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                    Storage::disk('public')->delete($user->gambar_profil);
                }
                $path = $request->file('gambar_profil')->store('profile_images', 'public');
                $user->gambar_profil = $path;
            }

            $user->save();

            return redirect()->route('admin.data-pengguna')->with('success', 'Data pengguna berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengguna')->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function deletePengguna($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $user = User::findOrFail($id);

            // Remove stored profile image if exists
            if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                Storage::disk('public')->delete($user->gambar_profil);
            }

            $user->delete();

            return redirect()->route('admin.data-pengguna')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengguna')->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
