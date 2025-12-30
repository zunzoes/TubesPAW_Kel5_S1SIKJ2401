@extends('layouts.customer')

@section('title', $product->name . ' - Apparify')

@section('content')
<div class="container pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none" style="color: var(--primary)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.products.index') }}" class="text-decoration-none" style="color: var(--primary)">Products</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5 align-items-stretch mb-5">
        {{-- Bagian Kiri: Galeri Gambar --}}
        <div class="col-md-6 d-flex flex-column">
            <div class="card border-0 shadow-sm overflow-hidden flex-grow-1" style="border-radius: 20px;">
                <div class="bg-white p-2 d-flex align-items-center justify-content-center border h-100" style="min-height: 500px; border-radius: 20px;">
                    @if($product->images->isNotEmpty())
                        <div id="productCarousel" class="carousel slide w-100 h-100 d-flex align-items-center" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->images as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <img src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" 
                                             class="d-block mx-auto img-fluid" 
                                             style="max-height: 480px; width: auto; object-fit: contain;"
                                             alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            @if($product->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-2"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-2"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img src="{{ $product->image_url }}" class="img-fluid" style="max-height: 480px; width: auto; object-fit: contain;">
                    @endif
                </div>
            </div>

            @if($product->images->count() > 1)
                <div class="row g-2 mt-3">
                    @foreach($product->images as $index => $image)
                        <div class="col-3">
                            <img src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" 
                                 class="img-thumbnail cursor-pointer border shadow-sm thumb-img" 
                                 style="height: 70px; width: 100%; object-fit: cover; border-radius: 10px; background-color: white;"
                                 data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Bagian Kanan: Info Produk --}}
        <div class="col-md-6">
            <div class="ps-md-4 d-flex flex-column h-100">
                <div class="mb-2">
                    <span class="badge rounded-pill px-3 py-2" style="background-color: var(--neutral); color: var(--dark-text); border: 1px solid var(--accent);">{{ $product->category->name }}</span>
                    @if($product->has_design)
                        <span class="badge bg-success-subtle text-success border border-success px-3 rounded-pill ms-1">Ready Design</span>
                    @endif
                </div>
                
                <h2 class="fw-bold mb-1" style="color: var(--dark-text)">{{ $product->name }}</h2>
                
                {{-- Rating Summary --}}
                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2">
                        @php $avgRating = round($product->average_rating ?? 0); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-muted small">({{ $product->feedbacks_count ?? 0 }} Customer Reviews)</span>
                </div>

                <h3 class="fw-bold mb-3" id="display-price" style="color: var(--primary)">
                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                </h3>

                <div class="mb-4 p-3 rounded-4" style="background-color: #ffffff; border: 1px solid var(--accent);">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Product Description</h6>
                    <p class="text-secondary mb-0 small" style="line-height: 1.5;">{{ $product->description }}</p>
                </div>

                @if($product->is_active)
                    <form action="{{ route('customer.cart.add') }}" method="POST" id="add-to-cart-form" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" id="variant_id_input">

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Choose Your Size</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($product->variants->unique('size') as $variant)
                                    <div class="size-option">
                                        <input type="radio" class="btn-check" name="size" 
                                               id="size-{{ $variant->size }}" 
                                               value="{{ $variant->size }}" 
                                               data-variant-id="{{ $variant->id }}"
                                               data-price="{{ $product->base_price + $variant->additional_price }}">
                                        <label class="btn btn-outline-custom-primary px-3 py-2 d-flex flex-column align-items-center" for="size-{{ $variant->size }}" style="border-radius: 12px; min-width: 70px;">
                                            <span class="fw-bold">{{ $variant->size }}</span>
                                            @if($variant->additional_price > 0)
                                                <small style="font-size: 0.6rem; opacity: 0.8;">+{{ number_format($variant->additional_price / 1000, 0) }}k</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4 col-lg-3">
                                <h6 class="fw-bold mb-2">Quantity</h6>
                                <div class="input-group rounded-pill overflow-hidden border shadow-sm" style="border-color: var(--accent) !important; height: 38px;">
                                    <button class="btn btn-white border-0 px-2" type="button" id="decrease-qty" style="width: 35px;"><i class="fas fa-minus" style="color: var(--primary); font-size: 0.8rem;"></i></button>
                                    <input type="number" class="form-control text-center border-0 fw-bold shadow-none p-0" name="quantity" id="quantity" value="1" min="1" readonly style="background-color: white; font-size: 0.9rem;">
                                    <button class="btn btn-white border-0 px-2" type="button" id="increase-qty" style="width: 35px;"><i class="fas fa-plus" style="color: var(--primary); font-size: 0.8rem;"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow rounded-pill py-3" id="add-to-cart-btn" disabled style="background-color: var(--primary); border-color: var(--primary);">
                                Add to Cart
                            </button>
                            <a href="{{ route('customer.design.create') }}" class="btn btn-outline-success btn-lg fw-bold rounded-pill py-3">
                                Customize Design
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Customer Reviews Section --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <h5 class="fw-bold mb-0" style="color: var(--dark-text)">Customer Reviews</h5>
                </div>
                <div class="card-body p-4">
                    @forelse($product->feedbacks as $feedback)
                        <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1" style="color: var(--dark-text)">{{ $feedback->user->name }}</h6>
                                    <div class="text-warning" style="font-size: 0.8rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $feedback->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $feedback->created_at->format('d M Y') }}</small>
                            </div>
                            <p class="text-secondary small mb-0">{{ $feedback->comment ?: 'No comment provided.' }}</p>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-3x mb-3 opacity-25" style="color: var(--primary)"></i>
                            <p class="text-muted">No reviews yet for this product. Be the first to buy and review!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .thumb-img:hover { border: 2px solid var(--primary) !important; }
    .btn-outline-custom-primary {
        color: var(--primary);
        border: 2px solid var(--primary);
        background-color: transparent;
        transition: all 0.2s ease;
    }
    .btn-outline-custom-primary:hover {
        background-color: rgba(96, 150, 180, 0.1);
        color: var(--primary);
    }
    .btn-check:checked + .btn-outline-custom-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(96, 150, 180, 0.3);
    }
    .text-warning { color: #F1C40F !important; }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantInput = document.getElementById('variant_id_input');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const displayPrice = document.getElementById('display-price');

        document.querySelectorAll('input[name="size"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const vId = this.dataset.variantId;
                const price = parseInt(this.dataset.price);
                variantInput.value = vId;
                displayPrice.textContent = 'Rp ' + price.toLocaleString('id-ID');
                addToCartBtn.disabled = false;
            });
        });

        document.getElementById('increase-qty').addEventListener('click', () => {
            const qty = document.getElementById('quantity');
            qty.value = parseInt(qty.value) + 1;
        });

        document.getElementById('decrease-qty').addEventListener('click', () => {
            const qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) { qty.value = parseInt(qty.value) - 1; }
        });
    });
</script>
@endpush