<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Formulir;
use App\Models\DataKostum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UlasanController extends Controller
{
    /**
     * Public page: list reviews for a given costume.
     */
    public function lihatUlasanKostum($id_kostum)
    {
        $kostum = DataKostum::find($id_kostum);

        if (!$kostum) {
            return redirect()->route('katalog.kostum')->with('error', 'Kostum tidak ditemukan.');
        }

        // Ulasan uses shared primary key with formulir.id.
        // We filter by formulir.nama_kostum because formulir table stores the costume name.
        $ulasanList = Ulasan::query()
            ->join('formulir', 'ulasan.id', '=', 'formulir.id')
            ->where('formulir.nama_kostum', $kostum->nama_kostum)
            ->select([
                'ulasan.*',
                'formulir.nama as nama_user',
                'formulir.email as email_user',
            ])
            ->orderByDesc('ulasan.created_at')
            ->get();

        return view('lihat-ulasan', [
            'kostum' => $kostum,
            'ulasanList' => $ulasanList,
        ]);
    }

    /**
     * Show the form for creating a new review or editing existing one.
     */
    public function createOrEdit($formulirId)
    {
        $formulir = Formulir::findOrFail($formulirId);
        
        // Check if user owns this order
        if ($formulir->user_id !== Auth::id()) {
            return redirect()->route('user.pesanan')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Review is stored using the same id as formulir id
        $ulasan = Ulasan::find($formulirId);

        return view('user.ulasan', compact('formulir', 'ulasan'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, $formulirId)
    {
        $formulir = Formulir::findOrFail($formulirId);
        
        // Check if user owns this order
        if ($formulir->user_id !== Auth::id()) {
            return redirect()->route('user.pesanan')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:5000',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_4' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_5' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'id' => $formulirId,
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ];

        // Handle image uploads
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = 'gambar_' . $i;
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                $filename = 'ulasan_' . $formulirId . '_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('ulasan', $filename, 'public');
                $data[$fieldName] = $path;
            }
        }

        Ulasan::updateOrCreate(['id' => $formulirId], $data);

        return redirect()->route('user.pesanan')->with('success', 'Ulasan berhasil ditambahkan!');
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, $formulirId)
    {
        $formulir = Formulir::findOrFail($formulirId);
        
        // Check if user owns this order
        if ($formulir->user_id !== Auth::id()) {
            return redirect()->route('user.pesanan')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        $ulasan = Ulasan::findOrFail($formulirId);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:5000',
            'gambar_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_4' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_5' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'rating' => $validated['rating'],
            'review' => $validated['review'],
        ];

        // Handle image uploads
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = 'gambar_' . $i;
            if ($request->hasFile($fieldName)) {
                // Delete old image if exists
                if ($ulasan->$fieldName) {
                    Storage::disk('public')->delete($ulasan->$fieldName);
                }
                
                $file = $request->file($fieldName);
                $filename = 'ulasan_' . $formulirId . '_' . $i . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('ulasan', $filename, 'public');
                $data[$fieldName] = $path;
            }
        }

        $ulasan->update($data);

        return redirect()->route('user.pesanan')->with('success', 'Ulasan berhasil diperbarui!');
    }

    /**
     * Remove the specified image from review.
     */
    public function deleteImage($formulirId, $imageNumber)
    {
        // Ensure user owns the related order
        $formulir = Formulir::findOrFail($formulirId);
        if ($formulir->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $ulasan = Ulasan::findOrFail($formulirId);

        $fieldName = 'gambar_' . $imageNumber;
        
        if ($ulasan->$fieldName) {
            Storage::disk('public')->delete($ulasan->$fieldName);
            $ulasan->$fieldName = null;
            $ulasan->save();
        }

        return response()->json(['success' => true]);
    }
}
