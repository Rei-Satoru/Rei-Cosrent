<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\User;
use App\Models\ProfileContact;
use Illuminate\Support\Facades\Storage;

class DendaController extends Controller
{
    // Redirect helpers because UI is embedded on /admin/data-denda
    public function index()
    {
        return redirect()->route('admin.data-denda');
    }

    public function create()
    {
        return redirect()->route('admin.data-denda');
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nama_kostum' => 'nullable|string|max:255',
            'jenis_denda' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'jumlah_denda' => 'nullable|numeric',
            'status' => 'nullable|in:Belum Lunas,Lunas',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $data = $validated;

            // Ensure fields exist so MySQL strict mode doesn't fail when columns have no default
            $data['bukti_foto'] = '';
            $data['bukti_pembayaran'] = '';

            if ($request->hasFile('bukti_foto')) {
                $data['bukti_foto'] = $request->file('bukti_foto')->store('denda', 'public');
            }

            if ($request->hasFile('bukti_pembayaran')) {
                $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('denda', 'public');
            }

            Denda::create($data);

            return redirect()->route('admin.data-denda')->with('success', 'Data denda berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-denda')->with('error', 'Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        return redirect()->route('admin.data-denda');
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $denda = Denda::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nama_kostum' => 'nullable|string|max:255',
            'jenis_denda' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'jumlah_denda' => 'nullable|numeric',
            'status' => 'nullable|in:Belum Lunas,Lunas',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $data = $validated;

            if ($request->hasFile('bukti_foto')) {
                if ($denda->bukti_foto && Storage::disk('public')->exists($denda->bukti_foto)) {
                    Storage::disk('public')->delete($denda->bukti_foto);
                }
                $data['bukti_foto'] = $request->file('bukti_foto')->store('denda', 'public');
            }

            if ($request->hasFile('bukti_pembayaran')) {
                if ($denda->bukti_pembayaran && Storage::disk('public')->exists($denda->bukti_pembayaran)) {
                    Storage::disk('public')->delete($denda->bukti_pembayaran);
                }
                $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')->store('denda', 'public');
            }

            $denda->update($data);

            return redirect()->route('admin.data-denda')->with('success', 'Data denda berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-denda')->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        try {
            $denda = Denda::findOrFail($id);

            if ($denda->bukti_foto && Storage::disk('public')->exists($denda->bukti_foto)) {
                Storage::disk('public')->delete($denda->bukti_foto);
            }
            if ($denda->bukti_pembayaran && Storage::disk('public')->exists($denda->bukti_pembayaran)) {
                Storage::disk('public')->delete($denda->bukti_pembayaran);
            }

            $denda->delete();

            return redirect()->route('admin.data-denda')->with('success', 'Data denda berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.data-denda')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // User-facing list of denda for the logged-in user
    public function userIndex()
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));
        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        // Try to match denda by common user identifiers (nick_name or username or email)
        $dendas = Denda::where(function($q) use ($user) {
            $q->where('nama', $user->nick_name)
              ->orWhere('nama', $user->username)
              ->orWhere('nama', $user->email);
        })->orderBy('created_at', 'desc')->get();

        return view('user.denda-saya', [
            'dendas' => $dendas,
            'user' => $user,
        ]);
    }

    // Show payment page for a specific denda
    public function showPayment($id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));
        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        $denda = Denda::findOrFail($id);

        // Ensure the denda belongs to the logged-in user (match by nama field)
        $owns = ($denda->nama === $user->nick_name) || ($denda->nama === $user->username) || ($denda->nama === $user->email);
        if (!$owns) {
            return redirect()->route('user.denda-saya')->with('error', 'Anda tidak memiliki akses ke data denda ini.');
        }

        $profile = ProfileContact::find(1);

        return view('user.bayar-denda', [
            'denda' => $denda,
            'profile' => $profile,
        ]);
    }

    // Handle upload of bukti pembayaran for a denda and mark it as Lunas
    public function storePayment(Request $request, $id)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $user = User::find(session('user_id'));
        if (!$user) {
            return redirect()->route('login')->with('error', 'User tidak ditemukan.');
        }

        $denda = Denda::findOrFail($id);

        $owns = ($denda->nama === $user->nick_name) || ($denda->nama === $user->username) || ($denda->nama === $user->email);
        if (!$owns) {
            return redirect()->route('user.denda-saya')->with('error', 'Anda tidak memiliki akses ke data denda ini.');
        }

        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('bukti_pembayaran');
        $filename = 'bukti_denda_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('denda', $filename, 'public');

        try {
            // delete old if exists
            if ($denda->bukti_pembayaran && Storage::disk('public')->exists($denda->bukti_pembayaran)) {
                Storage::disk('public')->delete($denda->bukti_pembayaran);
            }

            $denda->bukti_pembayaran = $path;
            $denda->status = 'Lunas';
            $denda->save();
        } catch (\Exception $e) {
            return redirect()->route('user.denda-saya')->with('error', 'Gagal menyimpan bukti pembayaran: ' . $e->getMessage());
        }

        return redirect()->route('user.denda-saya')->with('success', 'Bukti pembayaran berhasil diunggah dan status denda diperbarui menjadi Lunas.');
    }
}
