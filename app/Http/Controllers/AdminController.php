<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataKatalog;
use App\Models\BookingDate;
use App\Models\DataKostum;
use App\Models\ProfileContact;
use App\Models\Aturan;
use App\Models\Formulir;
use App\Models\User;
use App\Models\Denda;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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
        $total_denda = Denda::sum('jumlah_denda');
        $ulasan_count = Ulasan::count();
        // Count pending pengembalian requests (use Pengembalian.status instead of polluting formulir.status)
        $pengembalian_count = Pengembalian::where('status', 'proses')->count();
        $profile_contact = ProfileContact::find(1);
        session(['admin_profile_photo' => $profile_contact->photo ?? null]);
        
        // Get latest 5 orders
        $latest_orders = Formulir::orderByDesc('created_at')->take(5)->get();
        
        // Get total revenue from accepted and completed orders only
        $revenueStatuses = ['confirmed', 'diterima', 'completed', 'selesai'];
        $total_revenue = Formulir::whereIn('status', $revenueStatuses)->sum('total_harga');
        
        // Get top 5 kostum
        $top_kostum = Formulir::selectRaw('nama_kostum, COUNT(*) as count')
            ->groupBy('nama_kostum')
            ->orderByDesc('count')
            ->take(5)
            ->get();
        
        // Get kostum breakdown for pie chart
        $top_3_kostum = $top_kostum->take(3);
        $top_3_total = $top_3_kostum->sum('count');
        $other_count = Formulir::count() - $top_3_total;
        
        // Get order statuses
        $order_statuses = Formulir::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        // Get payment methods
        $payment_methods = Formulir::selectRaw('metode_pembayaran, COUNT(*) as count')
            ->groupBy('metode_pembayaran')
            ->get();

        return view('admin.dashboard', [
            'admin_name' => $admin_name,
            'katalog_count' => $katalog_count,
            'kostum_count' => $kostum_count,
            'aturan_count' => $aturan_count,
            'pesanan_count' => $pesanan_count,
            'denda_count' => $denda_count,
            'total_denda' => $total_denda,
            'users_count' => $users_count,
            'ulasan_count' => $ulasan_count,
            'pengembalian_count' => $pengembalian_count,
            'profile_contact' => $profile_contact,
            'latest_orders' => $latest_orders,
            'total_revenue' => $total_revenue,
            'top_kostum' => $top_kostum,
            'top_3_kostum' => $top_3_kostum,
            'other_count' => $other_count,
            'order_statuses' => $order_statuses,
            'payment_methods' => $payment_methods,
        ]);
    }

    // Admin Data Tanggal (month view)
    public function dataTanggal(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $years = Formulir::selectRaw('YEAR(tanggal_pemakaian) as y')
            ->whereNotNull('tanggal_pemakaian')
            ->groupBy('y')
            ->orderBy('y', 'desc')
            ->pluck('y')
            ->toArray();

        $current = Carbon::now();
        $selectedYear = (int) $request->query('year', $current->year);
        $selectedMonth = (int) $request->query('month', $current->month);

        if (empty($years)) {
            $years = [$current->year];
        }

        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1);
        $daysInMonth = $startOfMonth->daysInMonth;
        $dates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dates[] = $startOfMonth->copy()->day($d)->format('Y-m-d');
        }

        $kostums = DataKostum::orderBy('judul')->orderBy('nama_kostum')->get();

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

        return view('admin.data-tanggal', [
            'kostums' => $kostums,
            'dates' => $dates,
            'bookingMap' => $bookingMap,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'isAdmin' => true,
        ]);
    }

    // ==================== DATA ULASAN ====================

    public function dataUlasan()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $ulasanList = Ulasan::query()
            ->leftJoin('formulir', 'ulasan.id', '=', 'formulir.id')
            ->select([
                'ulasan.*',
                'formulir.nama as nama_user',
                'formulir.email as email_user',
                'formulir.nama_kostum as nama_kostum',
                'formulir.status as status_pesanan',
            ])
            ->orderByDesc('ulasan.created_at')
            ->get();

        return view('admin.data-ulasan', [
            'ulasanList' => $ulasanList,
        ]);
    }

    public function balasUlasan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'formulir_id' => 'required|integer|exists:ulasan,id',
            'balasan' => 'nullable|string|max:5000',
        ], [
            'formulir_id.required' => 'ID pesanan wajib diisi.',
            'formulir_id.exists' => 'Ulasan untuk pesanan tersebut tidak ditemukan.',
        ]);

        try {
            $ulasan = Ulasan::findOrFail($validated['formulir_id']);
            $balasan = isset($validated['balasan']) ? trim((string) $validated['balasan']) : '';
            $ulasan->balasan = ($balasan === '') ? null : $balasan;
            $ulasan->save();

            return redirect()->route('admin.data-ulasan')->with('success', 'Balasan berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-ulasan')->with('error', 'Gagal menyimpan balasan: ' . $e->getMessage());
        }
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
                $start = $now->copy()->startOfDay();
                for ($h = 0; $h < 24; $h++) {
                    $from = $start->copy()->addHours($h);
                    $to = $from->copy()->endOfHour();
                    $labels[] = $from->format('H:00');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])
                        ->whereIn('status', ['confirmed', 'diterima', 'completed', 'selesai'])
                        ->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'month':
                $start = $now->copy()->subDays(29)->startOfDay();
                for ($d = 0; $d < 30; $d++) {
                    $from = $start->copy()->addDays($d)->startOfDay();
                    $to = $from->copy()->endOfDay();
                    $labels[] = $from->format('d M');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])
                        ->whereIn('status', ['confirmed', 'diterima', 'completed', 'selesai'])
                        ->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'year':
                $start = $now->copy()->startOfYear();
                for ($m = 0; $m < 12; $m++) {
                    $from = $start->copy()->addMonths($m)->startOfMonth();
                    $to = $from->copy()->endOfMonth();
                    $labels[] = $from->format('M');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])
                        ->whereIn('status', ['confirmed', 'diterima', 'completed', 'selesai'])
                        ->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
                break;
            case 'week':
            default:
                $start = $now->copy()->subDays(6)->startOfDay();
                for ($d = 0; $d < 7; $d++) {
                    $from = $start->copy()->addDays($d)->startOfDay();
                    $to = $from->copy()->endOfDay();
                    $labels[] = $from->format('D d');

                    $orders = Formulir::whereBetween('created_at', [$from, $to])->count();
                    $revenue = Formulir::whereBetween('created_at', [$from, $to])
                        ->whereIn('status', ['confirmed', 'diterima', 'completed', 'selesai'])
                        ->sum('total_harga');

                    $ordersData[] = $orders;
                    $revenueData[] = (float) $revenue;
                }
        }

        $periodStart = $start ?? $now->copy()->subDays(6)->startOfDay();
        $periodEnd = $to ?? $now->copy()->endOfDay();

        $totalOrders = Formulir::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalRevenue = Formulir::whereBetween('created_at', [$periodStart, $periodEnd])
            ->whereIn('status', ['confirmed', 'diterima', 'completed', 'selesai'])
            ->sum('total_harga');

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

    // ==================== DATA PENGGUNA ====================

    public function dataPengguna()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.data-pengguna', [
            'users' => $users,
        ]);
    }

    public function updatePengguna(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $rules = [
            'id' => 'required|integer|exists:users,id',
        ];

        if ($request->filled('username')) {
            $rules['username'] = 'required|string|max:255|unique:users,username,' . $request->input('id');
        }

        if ($request->filled('email')) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $request->input('id');
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        if ($request->has('nick_name')) {
            $rules['nick_name'] = 'nullable|string|max:255';
        }

        if ($request->has('instagram')) {
            $rules['instagram'] = 'nullable|string|max:50';
        }

        if ($request->has('jenis_kelamin')) {
            $rules['jenis_kelamin'] = 'nullable|in:Pria,Wanita';
        }

        $validated = $request->validate($rules);

        try {
            $user = User::findOrFail($validated['id']);
            if (array_key_exists('username', $validated)) {
                $user->username = $validated['username'];
            }
            if (array_key_exists('nick_name', $validated)) {
                $user->nick_name = $validated['nick_name'];
            }
            if (array_key_exists('instagram', $validated)) {
                $user->instagram = $validated['instagram'] ?? null;
            }
            if (array_key_exists('email', $validated)) {
                $user->email = $validated['email'];
            }
            if (array_key_exists('jenis_kelamin', $validated)) {
                $user->jenis_kelamin = $validated['jenis_kelamin'];
            }

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
                $user->remember_token = Str::random(60);
            }
            $user->save();

            return redirect()->route('admin.data-pengguna')->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengguna')->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function approvePenggunaReset($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $user = User::findOrFail($id);
            if (!$user->password_reset_requested_at) {
                return redirect()->route('admin.data-pengguna')->with('error', 'Tidak ada permintaan reset password untuk pengguna ini.');
            }

            $user->password_reset_approved_at = now();
            $user->save();

            return redirect()->route('admin.data-pengguna')->with('success', 'Permintaan reset password berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengguna')->with('error', 'Gagal menyetujui permintaan: ' . $e->getMessage());
        }
    }

    public function deletePengguna($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            User::findOrFail($id)->delete();
            return redirect()->route('admin.data-pengguna')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengguna')->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    // ==================== DATA KATALOG ====================

    public function dataKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->input('search', '');
        $filter_kategori = $request->input('kategori', '');
        $sort = $request->input('sort', 'id_desc');

        $katalogQuery = DataKatalog::query();

        if ($search !== '') {
            $katalogQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($filter_kategori !== '') {
            $katalogQuery->where('kategori', $filter_kategori);
        }

        switch ($sort) {
            case 'nama_asc':
                $katalogQuery->orderBy('name', 'asc');
                break;
            case 'nama_desc':
                $katalogQuery->orderBy('name', 'desc');
                break;
            default:
                $katalogQuery->orderBy('id', 'desc');
        }

        $katalog = $katalogQuery->get();
        $categories = DataKatalog::distinct()->pluck('kategori')->filter();

        return view('admin.data-katalog', [
            'katalog' => $katalog,
            'search' => $search,
            'filter_kategori' => $filter_kategori,
            'sort' => $sort,
            'categories' => $categories,
        ]);
    }

    public function storeKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:data_katalog,name',
            'kategori' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('katalog_images', 'public');
            }

            DataKatalog::create([
                'name' => $validated['name'],
                'kategori' => $validated['kategori'],
                'description' => $validated['description'],
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-katalog')->with('error', 'Gagal menambahkan katalog: ' . $e->getMessage());
        }
    }

    public function updateKatalog(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'id' => 'required|integer|exists:data_katalog,id',
            'name' => 'required|string|max:255|unique:data_katalog,name,' . $request->input('id'),
            'kategori' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $katalog = DataKatalog::findOrFail($validated['id']);
            $katalog->name = $validated['name'];
            $katalog->kategori = $validated['kategori'];
            $katalog->description = $validated['description'];

            if ($request->hasFile('image')) {
                if ($katalog->image && Storage::disk('public')->exists($katalog->image)) {
                    Storage::disk('public')->delete($katalog->image);
                }
                $katalog->image = $request->file('image')->store('katalog_images', 'public');
            }

            $katalog->save();

            return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-katalog')->with('error', 'Gagal memperbarui katalog: ' . $e->getMessage());
        }
    }

    public function deleteKatalog($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $katalog = DataKatalog::findOrFail($id);
            if ($katalog->image && Storage::disk('public')->exists($katalog->image)) {
                Storage::disk('public')->delete($katalog->image);
            }
            $katalog->delete();

            return redirect()->route('admin.data-katalog')->with('success', 'Katalog berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-katalog')->with('error', 'Gagal menghapus katalog: ' . $e->getMessage());
        }
    }

    // ==================== DATA KOSTUM ====================

    public function dataKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $search = $request->input('search', '');
        $category = $request->input('kategori', '');
        $filter_jenis_kelamin = $request->input('jenis_kelamin', '');
        $filter_ukuran = $request->input('ukuran', '');
        $sort = $request->input('sort', 'id_asc');

        $kostumQuery = DataKostum::query();

        if ($search !== '') {
            $kostumQuery->where(function ($q) use ($search) {
                $q->where('nama_kostum', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($category !== '') {
            $kostumQuery->where('kategori', $category);
        }

        if ($filter_jenis_kelamin !== '') {
            $kostumQuery->where('jenis_kelamin', $filter_jenis_kelamin);
        }

        if ($filter_ukuran !== '') {
            $kostumQuery->where('ukuran_kostum', 'like', "%{$filter_ukuran}%");
        }

        // Handle sorting
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
                $kostumQuery->orderBy('id_kostum', 'desc');
        }

        $kostum = $kostumQuery->orderBy('judul')->orderBy('nama_kostum')->paginate(20);
        $kategori = DataKatalog::distinct()->orderBy('name')->pluck('name');

        // Extract all available sizes from kostums for filter
        $ukuran = [];
        foreach (DataKostum::pluck('ukuran_kostum') as $sizeStr) {
            if (!is_string($sizeStr)) continue;
            $parts = preg_split('/[,;&]/', $sizeStr);
            foreach ($parts as $p) {
                $clean = trim($p);
                if ($clean !== '') {
                    $ukuran[] = $clean;
                }
            }
        }
        $ukuran = array_values(array_unique($ukuran));
        $orderMap = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, 'XXXL' => 7];
        usort($ukuran, function ($a, $b) use ($orderMap) {
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
            'search' => $search,
            'filter_kategori' => $category,
            'filter_jenis_kelamin' => $filter_jenis_kelamin,
            'filter_ukuran' => $filter_ukuran,
            'sort' => $sort,
        ]);
    }

    public function storeKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $rawUkuran = $request->input('ukuran_kostum');
        if (is_array($rawUkuran)) {
            $cleanSizes = array_filter(array_map('trim', $rawUkuran), function ($size) {
                return $size !== '';
            });
            $request->merge(['ukuran_kostum' => implode(', ', $cleanSizes)]);
        }

        $validated = $request->validate([
            'kategori' => 'required|string|max:255',
            'nama_kostum' => 'required|string|max:255',
            'judul' => 'nullable|string|max:255',
            'harga_sewa' => 'required|numeric|min:0',
            'durasi_penyewaan' => 'nullable|string|max:100',
            'ukuran_kostum' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita,Unisex',
            'include' => 'nullable|string|max:1000',
            'exclude' => 'nullable|string|max:1000',
            'domisili' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
        ]);

        try {
            // Handle gambar upload (store on public disk under kostum_images)
            if ($request->hasFile('gambar')) {
                $imagePath = $request->file('gambar')->store('kostum_images', 'public');
                $validated['gambar'] = $imagePath;
            }

            DataKostum::create($validated);
            return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-kostum')->with('error', 'Gagal menambahkan kostum: ' . $e->getMessage());
        }
    }

    public function updateKostum(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $rawUkuran = $request->input('ukuran_kostum');
        if (is_array($rawUkuran)) {
            $cleanSizes = array_filter(array_map('trim', $rawUkuran), function ($size) {
                return $size !== '';
            });
            $request->merge(['ukuran_kostum' => implode(', ', $cleanSizes)]);
        }

        $validated = $request->validate([
            'id_kostum' => 'required|integer|exists:data_kostum,id_kostum',
            'kategori' => 'required|string|max:255',
            'nama_kostum' => 'required|string|max:255',
            'judul' => 'nullable|string|max:255',
            'harga_sewa' => 'required|numeric|min:0',
            'durasi_penyewaan' => 'nullable|string|max:100',
            'ukuran_kostum' => 'nullable|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita,Unisex',
            'include' => 'nullable|string|max:1000',
            'exclude' => 'nullable|string|max:1000',
            'domisili' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
        ]);

        try {
            $kostum = DataKostum::findOrFail($validated['id_kostum']);
            // Handle gambar upload and delete previous image if present
            if ($request->hasFile('gambar')) {
                // delete old image
                if ($kostum->gambar && Storage::disk('public')->exists($kostum->gambar)) {
                    Storage::disk('public')->delete($kostum->gambar);
                }
                $validated['gambar'] = $request->file('gambar')->store('kostum_images', 'public');
            }

            $kostum->update($validated);

            return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-kostum')->with('error', 'Gagal memperbarui kostum: ' . $e->getMessage());
        }
    }

    public function deleteKostum($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            DataKostum::findOrFail($id)->delete();
            return redirect()->route('admin.data-kostum')->with('success', 'Kostum berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-kostum')->with('error', 'Gagal menghapus kostum: ' . $e->getMessage());
        }
    }

    public function deleteKostumImage($imageId)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // This is a placeholder - actual implementation depends on image storage structure
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus gambar: ' . $e->getMessage()], 500);
        }
    }

    // ==================== PROFILE CONTACT ====================

    public function profileContact()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $profile = ProfileContact::first() ?? new ProfileContact();

        return view('admin.profile-contact', [
            'profile' => $profile,
        ]);
    }

    public function updateProfileContact(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'vision' => 'nullable|string|max:5000',
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'instagram' => 'nullable|string|max:100',
            'nomor_ewallet' => 'nullable|string|max:100',
            'nomor_bank' => 'nullable|string|max:100',
            'origin_province_id' => 'nullable|numeric',
            'origin_city_id' => 'nullable|numeric',
            'origin_postal_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'qris' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo' => 'nullable|in:0,1',
            'remove_qris' => 'nullable|in:0,1',
        ]);

        try {
            $profile = ProfileContact::first();
            if (!$profile) {
                $profile = new ProfileContact();
            }

            // Update text fields
            $profile->name = $validated['name'];
            $profile->title = $validated['title'] ?? $profile->title;
            $profile->vision = $validated['vision'] ?? $profile->vision;
            $profile->address = $validated['address'] ?? $profile->address;
            $profile->phone = $validated['phone'] ?? $profile->phone;
            $profile->email = $validated['email'];
            $profile->instagram = $validated['instagram'] ?? $profile->instagram;
            $profile->nomor_ewallet = $validated['nomor_ewallet'] ?? $profile->nomor_ewallet;
            $profile->nomor_bank = $validated['nomor_bank'] ?? $profile->nomor_bank;
            $profile->origin_province_id = $validated['origin_province_id'] ?? $profile->origin_province_id;
            $profile->origin_city_id = $validated['origin_city_id'] ?? $profile->origin_city_id;
            $profile->origin_postal_code = $validated['origin_postal_code'] ?? $profile->origin_postal_code;

            // Handle photo upload/deletion
            if ($request->input('remove_photo') == '1') {
                // Delete photo if requested
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $profile->photo = '';
            } elseif ($request->hasFile('photo')) {
                // Upload new photo
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $profile->photo = $request->file('photo')->store('profile_contact', 'public');
            }

            // Handle QRIS upload/deletion
            if ($request->input('remove_qris') == '1') {
                // Delete QRIS if requested
                if ($profile->qris && Storage::disk('public')->exists($profile->qris)) {
                    Storage::disk('public')->delete($profile->qris);
                }
                $profile->qris = '';
            } elseif ($request->hasFile('qris')) {
                // Upload new QRIS
                if ($profile->qris && Storage::disk('public')->exists($profile->qris)) {
                    Storage::disk('public')->delete($profile->qris);
                }
                $profile->qris = $request->file('qris')->store('profile_contact', 'public');
            }

            $profile->save();

            // Flush session input agar form menampilkan data fresh dari database
            $request->session()->forget('_old_input');

            return redirect()->route('admin.profile-contact')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updateProfileContactPhoto(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $profile = ProfileContact::first();
            if (!$profile) {
                $profile = new ProfileContact();
            }

            if ($request->hasFile('photo')) {
                if ($profile->photo && Storage::disk('public')->exists($profile->photo)) {
                    Storage::disk('public')->delete($profile->photo);
                }
                $profile->photo = $request->file('photo')->store('profile_contact', 'public');
                $profile->save();
            }

            return redirect()->route('admin.profile-contact')->with('success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal memperbarui foto: ' . $e->getMessage());
        }
    }

    public function deleteProfileContactPhoto()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $profile = ProfileContact::first();
            if ($profile && $profile->photo && Storage::disk('public')->exists($profile->photo)) {
                Storage::disk('public')->delete($profile->photo);
                $profile->photo = '';
                $profile->save();
            }

            return redirect()->route('admin.profile-contact')->with('success', 'Foto profil berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }

    public function updatePaymentQris(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $request->validate([
            'qris' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $profile = ProfileContact::first();
            if (!$profile) {
                $profile = new ProfileContact();
            }

            if ($request->hasFile('qris')) {
                if ($profile->qris && Storage::disk('public')->exists($profile->qris)) {
                    Storage::disk('public')->delete($profile->qris);
                }
                $profile->qris = $request->file('qris')->store('profile_contact', 'public');
                $profile->save();
            }

            return redirect()->route('admin.profile-contact')->with('success', 'QRIS berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.profile-contact')->with('error', 'Gagal memperbarui QRIS: ' . $e->getMessage());
        }
    }

    public function deletePaymentQris()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $profile = ProfileContact::first();
            if ($profile && $profile->qris && Storage::disk('public')->exists($profile->qris)) {
                Storage::disk('public')->delete($profile->qris);
                $profile->qris = '';
                $profile->save();
            }

            return redirect()->route('admin.profile-contact')->with('success', 'QRIS berhasil dihapus.');
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

        return view('admin.data-aturan', [
            'aturan' => $aturan,
        ]);
    }

    public function storeAturan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'syarat_ketentuan' => 'required|string|max:10000',
            'larangan_dan_denda' => 'required|string|max:10000',
        ]);

        try {
            Aturan::create($validated);
            return redirect()->route('admin.data-aturan')->with('success', 'Aturan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal menambahkan aturan: ' . $e->getMessage());
        }
    }

    public function updateAturan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'id' => 'required|integer|exists:aturan,id',
            'syarat_ketentuan' => 'required|string|max:10000',
            'larangan_dan_denda' => 'required|string|max:10000',
        ]);

        try {
            $aturan = Aturan::findOrFail($validated['id']);
            $aturan->update([
                'syarat_ketentuan' => $validated['syarat_ketentuan'],
                'larangan_dan_denda' => $validated['larangan_dan_denda'],
            ]);

            return redirect()->route('admin.data-aturan')->with('success', 'Aturan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal memperbarui aturan: ' . $e->getMessage());
        }
    }

    public function deleteAturan($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            Aturan::findOrFail($id)->delete();
            return redirect()->route('admin.data-aturan')->with('success', 'Aturan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-aturan')->with('error', 'Gagal menghapus aturan: ' . $e->getMessage());
        }
    }

    // ==================== DATA PESANAN ====================

    public function dataPesanan(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $status = $request->input('status', '');
        $search = $request->input('search', '');

        $pesananQuery = Formulir::query();

        if ($status !== '') {
            $pesananQuery->where('status', $status);
        }

        if ($search !== '') {
            $pesananQuery->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nama_kostum', 'like', "%{$search}%");
            });
        }

        $pesanan = $pesananQuery->orderBy('created_at', 'desc')->paginate(20);

        $statusOptions = ['proses', 'revisi', 'diterima', 'selesai'];

        return view('admin.data-pesanan', [
            'pesanan' => $pesanan,
            'statusOptions' => $statusOptions,
            'currentStatus' => $status,
            'search' => $search,
        ]);
    }

    public function updatePesananStatus(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:proses,revisi,diterima,selesai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            $pesanan = Formulir::findOrFail($id);
            $pesanan->status = $validated['status'];
            if (array_key_exists('keterangan', $validated)) {
                $pesanan->keterangan = $validated['keterangan'];
            }
            $pesanan->save();

            return redirect()->route('admin.data-pesanan')->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pesanan')->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function deletePesanan($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            Formulir::findOrFail($id)->delete();
            return redirect()->route('admin.data-pesanan')->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pesanan')->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }

    // ==================== DATA PENGEMBALIAN ====================

    public function dataPengembalian()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // Ambil semua pengembalian dan eager-load formulir jika ada, to avoid relation not loaded
        $pengembalianRaw = Pengembalian::with('formulir')->orderBy('created_at', 'desc')->get();

        // Ambil semua formulir sekali untuk per-row proximity matching (non-destructive)
        $formulirAll = Formulir::orderByDesc('created_at')->get();
        
        // Build data pengembalian dengan informasi formulir
        $mapped = $pengembalianRaw->map(function($pengembalian) use ($formulirAll) {
            // Attach a conservative formulir match per-row (eager-loaded when available).
            if ($pengembalian->relationLoaded('formulir') && $pengembalian->formulir) {
                $formulir = $pengembalian->formulir;
            } else {
                $formulir = null;
                try {
                    if ($formulirAll->isNotEmpty() && $pengembalian->created_at) {
                        $closest = $formulirAll->sortBy(function ($f) use ($pengembalian) {
                            return abs(optional($f->created_at)->getTimestamp() - optional($pengembalian->created_at)->getTimestamp());
                        })->first();
                        if ($closest) {
                            $diff = abs(optional($closest->created_at)->getTimestamp() - optional($pengembalian->created_at)->getTimestamp());
                            if ($diff <= 604800) { // 7 days
                                $formulir = $closest;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $formulir = null;
                }
            }

            if ($formulir) {
                $pengembalian->display_nama = $formulir->nama ?? '-';
                $pengembalian->display_email = $formulir->email;
                $pengembalian->display_kostum = $formulir->nama_kostum ?? '-';
                $pengembalian->formulir = $formulir;
            } else {
                $pengembalian->display_nama = '-';
                $pengembalian->display_email = null;
                $pengembalian->display_kostum = '-';
            }

            return $pengembalian;
        });

        // Deduplicate pengembalian per formulir: prefer non-ditolak or the newest entry
        try {
            $grouped = [];
            $others = collect();
            foreach ($mapped as $p) {
                $fid = data_get($p, 'formulir.id');
                if ($fid) {
                    if (!isset($grouped[$fid])) {
                        $grouped[$fid] = $p;
                    } else {
                        $existing = $grouped[$fid];
                        $existingStatus = strtolower((string)($existing->status ?? ''));
                        $pStatus = strtolower((string)($p->status ?? ''));
                        if ($existingStatus === 'ditolak' && $pStatus !== 'ditolak') {
                            $grouped[$fid] = $p;
                        } elseif ($existingStatus !== 'ditolak' && $pStatus === 'ditolak') {
                            // keep existing
                        } else {
                            $existingTs = optional($existing->created_at)->getTimestamp() ?? 0;
                            $pTs = optional($p->created_at)->getTimestamp() ?? 0;
                            if ($pTs > $existingTs) {
                                $grouped[$fid] = $p;
                            }
                        }
                    }
                } else {
                    $others->push($p);
                }
            }

            $pengembalianList = collect(array_values($grouped))->merge($others)->sortByDesc('created_at')->values();
        } catch (\Exception $e) {
            $pengembalianList = $mapped;
        }

        $pendingCount = Pengembalian::where('status', 'proses')->count();

        return view('admin.data-pengembalian', [
            'pengembalianList' => $pengembalianList,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function verifikasiPengembalian(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }
        $validated = $request->validate([
            'aksi' => 'required|in:revisi,setujui',
            'catatan_admin' => 'nullable|string|max:255',
        ]);

        try {
            $pengembalian = Pengembalian::findOrFail($id);

            // Map form action to pengembalian status
            $aksi = $validated['aksi'];
            $newStatus = $aksi === 'setujui' ? 'diterima' : 'ditolak';

            $pengembalian->status = $newStatus;
            $pengembalian->catatan_admin = trim((string) ($validated['catatan_admin'] ?? '')) ?: null;
            $pengembalian->save();

            // If accepted, mark related formulir as selesai (if linked)
            if ($newStatus === 'diterima' && $pengembalian->formulir) {
                try {
                    $pengembalian->formulir->status = 'selesai';
                    $pengembalian->formulir->save();
                } catch (\Exception $e) {
                    // ignore formulir update failures
                }
            }

            return redirect()->route('admin.data-pengembalian')->with('success', 'Pengembalian berhasil diverifikasi.');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-pengembalian')->with('error', 'Gagal memverifikasi: ' . $e->getMessage());
        }
    }

    // ==================== DATA DENDA ====================

    public function dataDenda()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $dendas = Denda::orderBy('created_at', 'desc')->get();

        // Provide recent unique formulir names so the Add/Edit modals can auto-fill nama/nama_kostum
        $formulir = [];
        try {
            $formulir = \App\Models\Formulir::select('nama', 'nama_kostum')
                ->whereNotNull('nama')
                ->distinct()
                ->orderBy('nama')
                ->get();
        } catch (\Exception $e) {
            // fail silently; view will handle empty list
            $formulir = [];
        }

        return view('admin.data-denda', [
            'dendas' => $dendas,
            'formulir' => $formulir,
        ]);
    }

    // ==================== DATA TANGGAL ====================

    public function updateDataTanggal(Request $request, $sheetCode)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        // This method is for sheet-based updates, which has been replaced by month-grid
        // Keeping as placeholder for backward compatibility
        return redirect()->route('admin.data-tanggal')->with('info', 'Format pemesanan telah diperbarui ke tampilan bulanan.');
    }

    // ==================== LOGOUT ====================

    public function logout()
    {
        session()->flush();
        return redirect()->route('admin.login')->with('success', 'Anda telah logout.');
    }
}
