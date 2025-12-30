@extends('layouts.customer')

@section('title', 'Checkout - Apparify')

@section('content')
<div class="container pb-5">
    <h2 class="mb-4 fw-bold" style="color: var(--dark-text)">Checkout</h2>

    <form action="{{ route('customer.checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-8">
                {{-- Shipping Information --}}
                <div class="card mb-3 border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Shipping Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label small fw-bold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" 
                                       value="{{ old('full_name', Auth::user()->name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number" class="form-label small fw-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3 @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" 
                                       value="{{ old('phone_number', Auth::user()->phone) }}" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="shipping_address" class="form-label small fw-bold">Shipping Address <span class="text-danger">*</span></label>
                                <textarea class="form-control rounded-3 @error('shipping_address') is-invalid @enderror" 
                                          id="shipping_address" name="shipping_address" rows="3" required 
                                          placeholder="Enter your full address here...">{{ old('shipping_address', Auth::user()->address) }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="card mb-3 border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Shipping Method</h5>
                    </div>
                    <div class="card-body p-4">
                        {{-- Update Harga Regular Shipping menjadi Rp 5.000 --}}
                        <div class="form-check p-3 border rounded-3 mb-2 shipping-option">
                            <input class="form-check-input ms-0 me-3 custom-radio" type="radio" name="shipping_method" 
                                   id="regular" value="regular" data-cost="5000" checked>
                            <label class="form-check-label d-flex justify-content-between w-100" for="regular">
                                <span>Regular Shipping (3-5 days)</span>
                                <strong style="color: #6096B4">Rp 5.000</strong>
                            </label>
                        </div>
                        {{-- Update Harga Express Shipping menjadi Rp 10.000 --}}
                        <div class="form-check p-3 border rounded-3 mb-2 shipping-option">
                            <input class="form-check-input ms-0 me-3 custom-radio" type="radio" name="shipping_method" 
                                   id="express" value="express" data-cost="10000">
                            <label class="form-check-label d-flex justify-content-between w-100" for="express">
                                <span>Express Shipping (1-2 days)</span>
                                <strong style="color: #6096B4">Rp 10.000</strong>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="card mb-3 border-0 shadow-sm" style="border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Payment Method</h5>
                    </div>
                    <div class="card-body p-4">
                        <select name="payment_method" class="form-select rounded-3 @error('payment_method') is-invalid @enderror" required>
                            <option value="">Select Payment Method</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer (Manual Verification)</option>
                            <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 20px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $subtotal = $cart->cartItems->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp

                        <div class="mb-4">
                            @foreach($cart->cartItems as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-white border rounded-3 p-1 me-3 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; flex-shrink: 0;">
                                        @php
                                            $imagePath = $item->product->primaryImage->image_path ?? 'placeholder.jpg';
                                            $imageUrl = str_starts_with($imagePath, 'http') ? $imagePath : asset('storage/' . $imagePath);
                                        @endphp
                                        <img src="{{ $imageUrl }}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="small fw-bold mb-0 text-truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</p>
                                        <p class="small text-muted mb-0">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-end ps-2">
                                        <p class="small fw-bold mb-0">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-3 opacity-50">

                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Shipping</span>
                            {{-- Default Shipping Cost di Summary diubah menjadi Rp 5.000 --}}
                            <span class="fw-bold text-dark" id="shipping-cost">Rp 5.000</span>
                        </div>
                        <hr class="my-3 opacity-50">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 fw-bold mb-0 text-dark">Total</span>
                            {{-- Default Grand Total diubah menyesuaikan shipping Rp 5.000 --}}
                            <span class="h4 fw-bold mb-0" style="color: #6096B4" id="grand-total">Rp {{ number_format($subtotal + 5000, 0, ',', '.') }}</span>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm rounded-pill" style="background-color: #6096B4; border: none; font-size: 1.1rem;">
                                Place Order
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted" style="font-size: 0.8rem;">
                                <i class="fas fa-shield-alt me-1"></i> Secure Payment Guaranteed
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Mengubah warna lingkaran radio button menjadi #6096B4 */
    .custom-radio.form-check-input:checked {
        background-color: #6096B4;
        border-color: #6096B4;
    }
    .custom-radio.form-check-input:focus {
        border-color: #6096B4;
        box-shadow: 0 0 0 0.25rem rgba(96, 150, 180, 0.25);
    }
    
    .shipping-option {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .shipping-option:hover {
        background-color: #f8f9fa;
        border-color: #6096B4 !important;
    }
    .form-control:focus, .form-select:focus {
        border-color: #6096B4;
        box-shadow: 0 0 0 0.25rem rgba(96, 150, 180, 0.25);
    }
</style>
@endsection

@push('scripts')
<script>
const subtotal = {{ $subtotal }};

document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = parseInt(this.getAttribute('data-cost'));
        const total = subtotal + shippingCost;

        document.getElementById('shipping-cost').textContent = 'Rp ' + shippingCost.toLocaleString('id-ID');
        document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
});
</script>
@endpush