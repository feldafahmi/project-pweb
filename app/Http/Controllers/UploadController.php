<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    /**
     * POST /api/admin/uploads (admin only)
     *
     * Terima satu file gambar, simpan ke /public/uploads/catalog, dan
     * kembalikan path relatif (`/uploads/catalog/...`) untuk dipakai sebagai
     * `image_url` saat create/update produk, mentor, atau lomba.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $file = $request->file('image');
        $dir = public_path('uploads/catalog');

        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = 'cat_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $file->move($dir, $filename);

        return response()->json([
            'message' => 'Upload berhasil.',
            'url'     => '/uploads/catalog/' . $filename,
        ], 201);
    }
}
