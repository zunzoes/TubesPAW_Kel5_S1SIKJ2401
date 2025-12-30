<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomDesign;
use App\Models\Product; // Ubah dari Category ke Product
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Menampilkan form pembuatan desain kustom.
     */
    public function create()
    {
        /**
         * Mengambil produk dasar (plain/polos) yang bisa dikustomisasi.
         * Kita asumsikan produk yang bisa dikustom memiliki kategori tertentu 
         * atau Anda bisa mengambil semua produk aktif.
         */
        $products = Product::where('is_active', true)->get();

        return view('customer.design.create', compact('products'));
    }

    /**
     * Menyimpan data desain kustom ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi: Menyesuaikan dengan input 'product_id' dan batas file sesuai gambar UI (5MB)
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'], 
            'design_file' => ['required', 'image', 'mimes:jpeg,png,jpg,svg', 'max:5120'], // Max 5MB sesuai gambar UI
            'design_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'product_id.required' => 'Silakan pilih jenis produk terlebih dahulu.',
            'design_file.max' => 'Ukuran file maksimal adalah 5MB.',
        ]);

        // 2. Simpan file desain ke folder storage/app/public/custom-designs
        $path = $request->file('design_file')->store('custom-designs', 'public');

        // 3. Buat data desain baru di database
        $design = CustomDesign::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'], // Menghubungkan ke produk polos yang dipilih
            'design_file' => $path,
            'design_notes' => $validated['design_notes'] ?? null,
            'status' => 'pending',
        ]);

        /**
         * 4. Alur Redirect:
         * Setelah desain disimpan, arahkan user ke halaman DETAIL PRODUK
         * dengan membawa parameter custom_design_id agar user bisa memilih UKURAN.
         */
        return redirect()->route('customer.products.show', [
            'product' => $validated['product_id'],
            'custom_design_id' => $design->id
        ])->with('success', 'Desain berhasil diunggah! Sekarang, silakan pilih ukuran pakaian Anda untuk dimasukkan ke keranjang.');
    }
}