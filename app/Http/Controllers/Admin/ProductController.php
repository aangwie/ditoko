<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:400',
            'external_link' => 'nullable|url|max:2048',
            'file_product' => 'nullable|file|mimes:pdf,zip,rar,doc,docx|max:102400',
        ]);

        // Require either file_product or external_link
        if (!$request->hasFile('file_product') && !$request->filled('external_link')) {
            return back()->withErrors(['file_product' => 'Upload file atau isi tautan eksternal.'])->withInput();
        }

        $validated['slug'] = Str::slug($validated['name']);
        $validated['price'] = (int) $validated['price'];

        // Upload cover image
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_cover.' . 'webp';
            Storage::disk('public')->put('products/covers/' . $imageName, file_get_contents($image));
            $validated['cover_image'] = 'products/covers/' . $imageName;
        }

        // Upload product file
        if ($request->hasFile('file_product')) {
            $file = $request->file('file_product');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('private/products', $fileName);
            $validated['file_path'] = 'products/' . $fileName;
        } else {
            $validated['file_path'] = null;
        }

        $validated['external_link'] = $request->input('external_link');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:400',
            'external_link' => 'nullable|url|max:2048',
            'file_product' => 'nullable|file|mimes:pdf,zip,rar,doc,docx|max:102400',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['price'] = (int) $validated['price'];

        // Upload new cover image
        if ($request->hasFile('cover_image')) {
            // Delete old cover
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $image = $request->file('cover_image');
            $imageName = time() . '_cover.' . 'webp';
            Storage::disk('public')->put('products/covers/' . $imageName, file_get_contents($image));
            $validated['cover_image'] = 'products/covers/' . $imageName;
        }

        // Upload new product file
        if ($request->hasFile('file_product')) {
            // Delete old file
            if ($product->file_path) {
                Storage::delete('private/' . $product->file_path);
            }
            $file = $request->file('file_product');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('private/products', $fileName);
            $validated['file_path'] = 'products/' . $fileName;
            $validated['external_link'] = null; // clear link if file uploaded
        } elseif ($request->has('external_link')) {
            $validated['file_path'] = null; // clear file if link set
        }

        $validated['external_link'] = $request->input('external_link');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete cover image
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }

        // Delete product file
        if ($product->file_path) {
            Storage::delete('private/' . $product->file_path);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
