<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'primaryImage']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru beserta varian massal.
     */
    public function store(Request $request)
    {
        // FIX: Konversi switch menjadi boolean
        $request->merge([
            'is_active' => $request->has('is_active') ? 1 : 0,
            'has_design' => $request->has('has_design') ? 1 : 0,
        ]);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'has_design' => ['boolean'],
            'is_active' => ['boolean'],
            // Validasi data varian array
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.size' => ['required', 'string'],
            'variants.*.color' => ['required', 'string'],
            'variants.*.color_code' => ['required', 'string'],
            'variants.*.stock' => ['required', 'integer', 'min:0'],
            'variants.*.additional_price' => ['required', 'numeric', 'min:0'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // 1. Simpan Produk
        $product = Product::create($validated);

        // 2. Simpan Varian secara massal
        foreach ($request->variants as $variantData) {
            $product->variants()->create($variantData);
        }

        // 3. Handle Images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product and variants created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'variants', 'images']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['variants', 'images']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // FIX: Konversi switch menjadi boolean agar nilai 0 tetap terkirim
        $request->merge([
            'is_active' => $request->has('is_active') ? 1 : 0,
            'has_design' => $request->has('has_design') ? 1 : 0,
        ]);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'has_design' => ['boolean'],
            'is_active' => ['boolean'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            $currentImagesCount = $product->images()->count();
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0,
                    'sort_order' => $currentImagesCount + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroyImage(ProductImage $image)
    {
        try {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            $isPrimary = $image->is_primary;
            $productId = $image->product_id;
            $image->delete();

            if ($isPrimary) {
                $nextImage = ProductImage::where('product_id', $productId)->first();
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }

            return back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete image.');
        }
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    // Variant Management

    /**
     * Digunakan untuk menambah satu varian baru dari halaman Edit.
     */
    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'size' => ['required', 'string', 'max:10'],
            'color' => ['required', 'string', 'max:50'],
            'color_code' => ['required', 'string', 'max:7'],
            'stock' => ['required', 'integer', 'min:0'],
            'additional_price' => ['required', 'numeric', 'min:0'],
        ]);

        $product->variants()->create($validated);

        return back()->with('success', 'Variant added successfully.');
    }

    public function updateVariant(Request $request, ProductVariant $variant)
    {
        $validated = $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
            'additional_price' => ['required', 'numeric', 'min:0'],
        ]);

        $variant->update($validated);

        return back()->with('success', 'Variant updated successfully.');
    }

    public function destroyVariant(ProductVariant $variant)
    {
        $variant->delete();
        return back()->with('success', 'Variant deleted successfully.');
    }
}