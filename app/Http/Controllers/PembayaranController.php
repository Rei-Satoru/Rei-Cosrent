<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulir;
use App\Models\ProfileContact;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Schema;

class PembayaranController extends Controller
{
    public function show($id)
    {
        $order = Formulir::findOrFail($id);
        $profile = ProfileContact::find(1);
        $pembayaran = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('pembayaran', 'formulir_id')) {
                $pembayaran = Pembayaran::where('formulir_id', $id)->latest()->first();
            }
        } catch (\Exception $e) {
            $pembayaran = null;
        }

        return view('pembayaran', compact('order', 'profile', 'pembayaran'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $file = $request->file('bukti_pembayaran');
        // Name the file with the order id so it can be discovered later even if DB relation lags
        $filename = 'bukti_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('bukti_pembayaran', $filename, 'public');

        $data = [
            'bukti_pembayaran' => $path,
        ];

        try {
            if (Schema::hasColumn('pembayaran', 'formulir_id')) {
                $data['formulir_id'] = $id;
            }

            Pembayaran::create($data);
        } catch (\Exception $e) {
            // If insert fails for any reason, log and continue gracefully
            logger()->error('Failed to save pembayaran: ' . $e->getMessage());
        }

        // flash the uploaded path so the orders page can immediately show the proof
        session()->flash('uploaded_bukti_for', $id);
        session()->flash('uploaded_bukti_path', $path);

        return redirect()->route('user.pesanan')->with('success', 'Bukti pembayaran berhasil diunggah.');
    }
}
