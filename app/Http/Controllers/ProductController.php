<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Controller WEB (Blade) untuk produk. Endpoint API ada di
 * App\Http\Controllers\Api\ProductController.
 */
class ProductController extends Controller
{
    /**
     * Halaman katalog produk publik.
     */
    public function index(Request $request)
    {
        $products = Product::orderBy('id', 'asc')->get();
        return view('produk', compact('products'));
    }

    /**
     * Halaman admin: daftar produk.
     */
    public function adminIndex(Request $request)
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'type'           => 'required|in:kelas,bootcamp,modul',
            'description'    => 'nullable|string',
            'original_price' => 'required|integer|min:0',
            'price'          => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
            'video_url'      => 'nullable|string|max:255',
            'whatsapp_link'  => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = 'storage/' . $path;
        } else {
            $data['image_url'] = 'img/63815.png';
        }

        Product::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'type'           => 'required|in:kelas,bootcamp,modul',
            'description'    => 'nullable|string',
            'original_price' => 'required|integer|min:0',
            'price'          => 'required|integer|min:0',
            'image'          => 'nullable|image|max:2048',
            'video_url'      => 'nullable|string|max:255',
            'whatsapp_link'  => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = 'storage/' . $path;
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
