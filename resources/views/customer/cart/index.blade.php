@extends('layouts.customer')

@section('title', 'Shopping Cart - Apparify')

@section('content')
<div class="container pb-5">
    {{-- Judul Tanpa Ikon --}}
    <h2 class="mb-4 fw-bold" style="color: var(--dark-text)">Shopping Cart</h2>

    @if(isset($cart) && $cart->cartItems->count() > 0)
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-0">
                        @foreach($cart->cartItems as $item)
                            <div class="row align-items-center border-bottom mx-0 py-3">
                                {{-- Kolom Gambar Produk: Ukuran Diperkecil --}}
                                <div class="col-md-2 col-4">
                                    <div class="bg-white rounded border d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="height: 80px; width: 80px; margin-left: 15px;">
                                        @php
                                            $imagePath = $item->product->primaryImage->image_path ?? 'placeholder.jpg';
                                            $imageUrl = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath);
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             class="img-fluid" 
                                             style="max-height: 100%; object-fit: contain; padding: 5px;"
                                             alt="{{ $item->product->name }}"
                                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.png') }}';">
                                    </div>
                                </div>

                                {{-- Detail Produk --}}
                                <div class="col-md-4 col-8">
                                    <h6 class="mb-1 fw-bold small" style="color: var(--dark-text)">{{ $item->product->name }}</h6>
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">Size: {{ $item->productVariant->size }}</span>
                                        <span class="badge bg-light text-dark border" style="font-size: 0.65rem;">Color: {{ $item->productVariant->color }}</span>
                                    </div>
                                    @if($item->custom_design_id)
                                        <small style="color: var(--primary); font-size: 0.7rem;"><i class="fas fa-magic me-1"></i>Custom</small>
                                    @endif
                                </div>

                                {{-- Harga Satuan --}}
                                <div class="col-md-2 col-4 text-md-center">
                                    <p class="mb-0 fw-semibold small">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>

                                {{-- Kontrol Kuantitas --}}
                                <div class="col-md-2 col-4 text-center">
                                    <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" id="form-qty-{{ $item->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm border rounded-pill overflow-hidden bg-white shadow-sm mx-auto" style="border-color: var(--accent) !important; height: 30px; max-width: 90px;">
                                            <button class="btn btn-white border-0 px-2" type="button" 
                                                    onclick="updateQuantity({{ $item->id }}, -1, {{ $item->productVariant->stock }})">
                                                <i class="fas fa-minus" style="color: var(--primary); font-size: 0.6rem;"></i>
                                            </button>
                                            <input type="number" class="form-control text-center border-0 fw-bold bg-white p-0 shadow-none" 
                                                   name="quantity" id="qty-{{ $item->id }}" 
                                                   value="{{ $item->quantity }}" readonly style="font-size: 0.75rem;">
                                            <button class="btn btn-white border-0 px-2" type="button" 
                                                    onclick="updateQuantity({{ $item->id }}, 1, {{ $item->productVariant->stock }})">
                                                <i class="fas fa-plus" style="color: var(--primary); font-size: 0.6rem;"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Total & Hapus --}}
                                <div class="col-md-2 col-4 text-end pe-4">
                                    <p class="mb-0 fw-bold small" style="color: var(--primary)">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 mt-1" style="font-size: 0.8rem;" onclick="return confirm('Remove?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan: Kelas sticky-top DIHAPUS agar tidak ikut saat di-scroll --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $subtotal = $cart->cartItems->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Subtotal ({{ $cart->cartItems->count() }} items)</span>
                            <span class="fw-bold small">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted small">Shipping</span>
                            <span class="small italic text-muted" style="font-size: 0.7rem;">Calculated at checkout</span>
                        </div>
                        <hr class="opacity-50">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h6 fw-bold mb-0">Total</span>
                            <span class="h6 fw-bold mb-0" style="color: var(--primary)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary fw-bold rounded-pill py-2 shadow-sm" style="background-color: var(--primary); border-color: var(--primary); font-size: 0.9rem;">
                                Proceed to Checkout <i class="fas fa-arrow-right ms-2" style="font-size: 0.85rem;"></i>
                            </a>
                            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-custom rounded-pill py-2" style="font-size: 0.9rem;">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Promo Code --}}
                <div class="card mt-3 border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-2 small" style="color: var(--dark-text)">Have a promo code?</h6>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control border-light-subtle rounded-start-pill ps-3" placeholder="Enter code">
                            <button class="btn btn-primary rounded-end-pill px-3" type="button" style="background-color: var(--primary); border-color: var(--primary);">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-box-open fa-5x opacity-25" style="color: var(--primary)"></i>
            </div>
            <h4 class="fw-bold" style="color: var(--dark-text)">Your cart is empty</h4>
            <p class="text-muted">Looks like you haven't added any apparel to your cart yet.</p>
            <a href="{{ route('customer.products.index') }}" class="btn btn-primary px-5 py-2 mt-3 fw-bold rounded-pill shadow-sm" style="background-color: var(--primary); border-color: var(--primary);">
                Start Shopping
            </a>
        </div>
    @endif
</div>

<style>
    .btn-outline-custom {
        color: var(--dark-text);
        border: 1px solid var(--accent);
        transition: all 0.3s ease;
    }
    .btn-outline-custom:hover {
        background-color: var(--neutral);
        color: var(--primary);
        border-color: var(--primary);
    }
    .rounded-start-pill { border-radius: 50px 0 0 50px !important; }
    .rounded-end-pill { border-radius: 0 50px 50px 0 !important; }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>
@endsection

@push('scripts')
<script>
function updateQuantity(itemId, change, maxStock) {
    const qtyInput = document.getElementById('qty-' + itemId);
    let currentQty = parseInt(qtyInput.value);
    let newQty = currentQty + change;
    
    if (newQty >= 1 && newQty <= maxStock) {
        qtyInput.value = newQty;
        document.getElementById('form-qty-' + itemId).submit();
    } else if (newQty > maxStock) {
        alert('Maaf, stok hanya tersedia ' + maxStock + ' unit.');
    }
}
</script>
@endpush