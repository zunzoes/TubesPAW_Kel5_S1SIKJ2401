@extends('layouts.customer')

@section('title', 'Our Products - Apparify')

@section('content')
<div class="container pb-5">
    <div class="mb-4">
        <h2 class="fw-bold" style="color: var(--dark-text)">Our Products</h2>
        <p class="text-muted">Temukan koleksi pakaian kustom berkualitas tinggi kami</p>
    </div>

    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="{{ route('customer.products.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-2">
                            <i class="fas fa-search me-1" style="color: var(--primary)"></i> Search
                        </label>
                        <input type="text" name="search" class="form-control border-light-subtle shadow-sm" placeholder="Search products..." value="{{ request('search') }}" style="border-radius: 10px;">
                    </div>

                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-2">
                            <i class="fas fa-tags me-1" style="color: var(--primary)"></i> Category
                        </label>
                        <select name="category" class="form-select border-light-subtle shadow-sm" onchange="this.form.submit()" style="border-radius: 10px;">
                            <option value="">All Categories</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-2">
                            <i class="fas fa-money-bill-wave me-1" style="color: var(--primary)"></i> Price Range
                        </label>
                        <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                            <input type="number" name="min_price" class="form-control border-light-subtle" placeholder="Min" value="{{ request('min_price') }}">
                            <span class="input-group-text bg-white border-light-subtle text-muted">-</span>
                            <input type="number" name="max_price" class="form-control border-light-subtle" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="small fw-bold text-muted mb-2">
                            <i class="fas fa-sort-amount-down me-1" style="color: var(--primary)"></i> Sort By
                        </label>
                        <select name="sort" class="form-select border-light-subtle shadow-sm" onchange="this.form.submit()" style="border-radius: 10px;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Best Rating</option> <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm" style="border-radius: 10px; height: 41px; background-color: var(--primary); border-color: var(--primary);">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($products) && $products->count() > 0)
        <div class="row g-4">
            @foreach($products as $product)
                <div class="col-md-3">
                    <div class="card h-100 border-0 shadow-sm product-card transition-all" style="border-radius: 15px;">
                        <div class="d-flex align-items-center justify-content-center p-3 position-relative" style="height: 280px; background-color: #ffffff; border-radius: 15px 15px 0 0;">
                            <img src="{{ $product->image_url }}" 
                                 class="img-fluid" 
                                 style="max-height: 100%; object-fit: contain; transition: transform 0.3s ease;" 
                                 alt="{{ $product->name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.png') }}';">
                            
                            @if(!$product->is_active)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.7); border-radius: 15px 15px 0 0;">
                                    <span class="badge bg-danger shadow-sm px-3 py-2">Sold Out</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column p-4">
                            <div class="mb-2 d-flex justify-content-between align-items-start">
                                <div>
                                    <span class="badge rounded-pill px-2 py-1" style="background-color: var(--neutral); color: var(--dark-text); font-size: 0.7rem;">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                    @if($product->has_design)
                                        <span class="badge rounded-pill px-2 py-1" style="background-color: #d1e7dd; color: #0f5132; font-size: 0.7rem;">Ready Design</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-2 d-flex align-items-center">
                                <div class="me-2" style="color: #F1C40F; font-size: 0.8rem;">
                                    @php $avgRating = round($product->average_rating ?? 0); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-muted small">({{ $product->feedbacks_count ?? 0 }})</span>
                            </div>
                            
                            <h6 class="fw-bold mb-1" style="color: var(--dark-text)">{{ $product->name }}</h6>
                            <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($product->description, 50) }}</p>
                            
                            <div class="mt-auto border-top pt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-bold fs-5" style="color: var(--primary)">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $product->variants->count() }} variants</small>
                                </div>
                                <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-outline-primary btn-sm w-100 fw-bold rounded-pill py-2 shadow-sm" style="border-color: var(--primary); color: var(--primary);">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5 card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <i class="fas fa-box-open fa-4x mb-3 opacity-25" style="color: var(--primary)"></i>
                <h4 class="fw-bold" style="color: var(--dark-text)">No Products Found</h4>
                <p class="text-muted mb-4 px-md-5">Sepertinya produk yang Anda cari belum tersedia di katalog kami.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary px-5 rounded-pill shadow-sm" style="background-color: var(--primary); border-color: var(--primary);">Reset Filters</a>
            </div>
        </div>
    @endif
</div>

<style>
    /* Hover Effects */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .product-card:hover img {
        transform: scale(1.05);
    }
    .btn-outline-primary:hover {
        background-color: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(96, 150, 180, 0.25);
    }
    /* Rating Styling */
    .fa-star {
        margin-right: 1px;
    }
</style>
@endsection