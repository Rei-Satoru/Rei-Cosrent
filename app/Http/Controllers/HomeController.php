<?php

namespace App\Http\Controllers;

use App\Models\Aturan;
use App\Models\DataKatalog;
use App\Models\DataKostum;
use App\Models\Formulir;
use App\Models\ProfileContact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

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

        return view('user.profile', [
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
            'alamat' => 'nullable|string|max:1000',
            'nomor_telepon' => 'nullable|regex:/^08[0-9]{8,13}$/',
            'jenis_kelamin' => 'nullable|in:Pria,Wanita',
            'password' => 'nullable|string|min:8|confirmed',
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
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
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

            if (!empty($request->input('password'))) {
                $user->password = Hash::make($request->input('password'));
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

            session([
                'user_name' => $user->username,
                'user_email' => $user->email,
                'user_gambar_profil' => $user->gambar_profil,
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

    public function submitFormulirPenyewaan(Request $request)
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
            'total_harga' => 'required|numeric|min:0',
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
            'total_harga.required' => 'Total harga wajib diisi.',
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

            Formulir::create([
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'nomor_telepon' => $request->input('nomor_telepon'),
                'nomor_telepon_2' => $request->input('nomor_telepon_2'),
                'nama_kostum' => $request->input('nama_kostum'),
                'tanggal_pemakaian' => $request->input('tanggal_pemakaian'),
                'tanggal_pengembalian' => $request->input('tanggal_pengembalian'),
                'total_harga' => $request->input('total_harga'),
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
            ->orderBy('created_at', 'desc')
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
}
