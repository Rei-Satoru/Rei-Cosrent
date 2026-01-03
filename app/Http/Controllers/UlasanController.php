<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Ulasan;
use App\Models\Formulir;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096'
        ]);

        try {
            $data = [
                'rating' => $request->input('rating'),
                'review' => $request->filled('review') ? $request->input('review') : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('ulasan', 'order_id') && $request->filled('order_id')) {
                $data['order_id'] = $request->input('order_id');
            }

            // handle image uploads (optional) and map to gambar_1..gambar_5 if those columns exist
            if ($request->hasFile('gambar')) {
                $files = $request->file('gambar');
                $i = 1;
                foreach ($files as $file) {
                    if ($i > 5) break;
                    $path = $file->store('ulasan', 'public');
                    if (Schema::hasColumn('ulasan', 'gambar_' . $i)) {
                        $data['gambar_' . $i] = $path;
                    }
                    $i++;
                }
            }

            $id = DB::table('ulasan')->insertGetId($data);

            // remember in session that this order got an ulasan (useful if DB doesn't have order_id column linked)
            if (!empty($data['order_id'])) {
                $orders = session('ulasan_for_orders', []);
                if (!in_array($data['order_id'], $orders)) {
                    $orders[] = $data['order_id'];
                    session(['ulasan_for_orders' => $orders]);
                }
            }

            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:2000',
            'gambar.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096'
        ]);

        try {

            $update = [
                'rating' => $request->input('rating'),
                'review' => $request->filled('review') ? $request->input('review') : null,
                'updated_at' => now(),
            ];

            // handle new image uploads: append/replace gambar_1..gambar_5
            if ($request->hasFile('gambar')) {
                $files = $request->file('gambar');
                $i = 1;
                foreach ($files as $file) {
                    if ($i > 5) break;
                    $path = $file->store('ulasan', 'public');
                    if (Schema::hasColumn('ulasan', 'gambar_' . $i)) {
                        $update['gambar_' . $i] = $path;
                    }
                    $i++;
                }
            }
            DB::table('ulasan')->where('id', $id)->update($update);

            // if this update included an order_id, ensure session flag is present
            if ($request->filled('order_id')) {
                $oid = $request->input('order_id');
                $orders = session('ulasan_for_orders', []);
                if (!in_array($oid, $orders)) {
                    $orders[] = $oid;
                    session(['ulasan_for_orders' => $orders]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // if possible, attempt to find order_id to update session
            try {
                $row = DB::table('ulasan')->where('id', $id)->first();
                if ($row && isset($row->order_id) && $row->order_id) {
                    $orders = session('ulasan_for_orders', []);
                    $orders = array_values(array_filter($orders, function($v) use ($row) { return $v != $row->order_id; }));
                    session(['ulasan_for_orders' => $orders]);
                }
            } catch (\Exception $e) {}

            DB::table('ulasan')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Show review page by order id (only for logged-in user and their order)
    public function showByOrder($orderId)
    {
        if (!session('user_logged_in')) {
            return redirect()->route('login');
        }

        $userEmail = session('user_email');
        $order = Formulir::where('id', $orderId)->where('email', $userEmail)->first();
        if (!$order) {
            return redirect()->route('user.pesanan')->with('error', 'Pesanan tidak ditemukan atau bukan milik Anda.');
        }

        $existingReview = null;
        try {
            if (Schema::hasColumn('ulasan', 'order_id')) {
                $existingReview = DB::table('ulasan')->where('order_id', $orderId)->first();
            }
        } catch (\Exception $e) {
            $existingReview = null;
        }

        return view('user.ulasan', [
            'order' => $order,
            'existingReview' => $existingReview,
        ]);
    }
}
