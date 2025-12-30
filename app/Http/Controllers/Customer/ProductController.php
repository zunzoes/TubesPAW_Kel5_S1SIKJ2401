<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        /**
         * Eager loading relasi ditingkatkan dengan withAvg dan withCount.
         * Ini memungkinkan kita mengambil rata-rata rating dan total ulasan 
         * dalam satu query database yang efisien.
         */
        $query = Product::with(['primaryImage', 'variants', 'category'])
            ->withAvg('feedbacks as average_rating', 'rating') // Mengambil rata-rata kolom 'rating'
            ->withCount('feedbacks') // Mengambil total jumlah ulasan
            ->where('is_active', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting Logic (Ditambahkan opsi sort berdasarkan rating)
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name_asc': 
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc': 
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        /**
         * Memuat relasi 'feedbacks' untuk menampilkan daftar ulasan pelanggan
         * secara detail di halaman produk.
         */
        $product->load(['category', 'variants', 'images', 'feedbacks.user']);
        
        // Menghitung rata-rata secara manual untuk objek tunggal
        $product->average_rating = $product->feedbacks()->avg('rating');
        $product->feedbacks_count = $product->feedbacks()->count();

        // Mengambil produk serupa
        $relatedProducts = Product::with(['primaryImage', 'variants'])
            ->withAvg('feedbacks as average_rating', 'rating')
            ->withCount('feedbacks')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
}