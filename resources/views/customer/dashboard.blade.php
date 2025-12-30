@extends('layouts.customer')

@section('title', 'Dashboard - Apparify')

@section('content')
<div class="container pb-5">
    {{-- Welcome Banner --}}
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #6096B4 0%, #93BFCF 100%); color: white; border-radius: 20px;">
        <div class="card-body py-5 px-4 px-md-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold mb-2 text-white">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="opacity-90 mb-0">Jelajahi koleksi kustom kami dan ciptakan gaya unikmu sendiri hari ini.</p>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="fas fa-user-rocket fa-5x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards: Menggunakan palet biru identitas --}}
    <div class="row mb-5 g-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="mb-2 p-3 d-inline-block rounded-circle" style="background-color: rgba(96, 150, 180, 0.1);">
                        <i class="fas fa-shopping-cart fa-lg" style="color: var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold mb-0" style="color: var(--dark-text)">{{ $totalOrders ?? 0 }}</h4>
                    <p class="text-muted small mb-0">Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="mb-2 p-3 d-inline-block rounded-circle" style="background-color: rgba(230, 126, 34, 0.1);">
                        <i class="fas fa-clock fa-lg" style="color: #E67E22;"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-warning">{{ $pendingOrders ?? 0 }}</h4>
                    <p class="text-muted small mb-0">Waiting Payment</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="mb-2 p-3 d-inline-block rounded-circle" style="background-color: rgba(147, 191, 207, 0.2);">
                        <i class="fas fa-truck fa-lg" style="color: var(--secondary);"></i>
                    </div>
                    <h4 class="fw-bold mb-0" style="color: var(--primary)">{{ $shippingOrders ?? 0 }}</h4>
                    <p class="text-muted small mb-0">Processing/Shipping</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100 text-center" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="mb-2 p-3 d-inline-block rounded-circle" style="background-color: rgba(46, 204, 113, 0.1);">
                        <i class="fas fa-check-circle fa-lg" style="color: #2ECC71;"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-success">{{ $completedOrders ?? 0 }}</h4>
                    <p class="text-muted small mb-0">Completed</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Popular Products Section --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0" style="color: var(--dark-text)">Produk Terpopuler</h4>
                <a href="{{ route('customer.products.index') }}" class="btn btn-sm fw-bold p-0" style="color: var(--primary); text-decoration: none;">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredProducts ?? [] as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 product-card border-0 shadow-sm transition-all" style="border-radius: 15px; overflow: hidden;">
                            {{-- Area Gambar: Background Putih Bersih --}}
                            <div class="position-relative bg-white d-flex align-items-center justify-content-center border-bottom" style="height: 220px; overflow: hidden;">
                                <img src="{{ $product->image_url }}" 
                                     class="w-100 h-100" 
                                     style="object-fit: contain; padding: 20px; transition: transform 0.3s;" 
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.png') }}';">
                                
                                <div class="badge bg-white shadow-sm position-absolute top-0 end-0 m-2 px-2 py-1 fw-bold" style="color: var(--primary); border-radius: 8px; font-size: 0.65rem;">
                                    Best Seller
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="mb-1 text-muted small" style="font-size: 0.7rem;">{{ $product->category->name ?? 'Apparify' }}</div>
                                <h6 class="fw-bold mb-2" style="color: var(--dark-text); font-size: 0.9rem;">{{ Str::limit($product->name, 25) }}</h6>
                                <p class="mb-3 fw-bold" style="color: var(--primary)">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                                
                                {{-- Tombol Detail: Gaya Pill & Tanpa Ikon --}}
                                <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-sm w-100 py-2 fw-bold rounded-pill shadow-sm transition-all" style="font-size: 0.8rem; background-color: var(--primary); border: none; color: white;">
                                    Detail Produk
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-white shadow-sm border" style="border-radius: 20px;">
                        <i class="fas fa-box-open fa-3x mb-3 opacity-25" style="color: var(--primary)"></i>
                        <p class="text-muted mb-0 fw-medium">Belum ada produk unggulan saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .product-card:hover img {
        transform: scale(1.08);
    }
    .btn-primary:hover {
        background-color: var(--secondary) !important;
        transform: scale(1.02);
    }
</style>
@endsection