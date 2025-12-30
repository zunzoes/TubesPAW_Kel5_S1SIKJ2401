@extends('layouts.customer')

@section('title', 'Order Details - Apparify')

@section('content')
<div class="container pb-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}" class="text-decoration-none" style="color: var(--primary)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}" class="text-decoration-none" style="color: var(--primary)">My Orders</a></li>
            <li class="breadcrumb-item active">Order #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-8">
            {{-- Status & Progress Card --}}
            <div class="card mb-4 shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-1 fw-bold" style="color: var(--dark-text)">Order #{{ $order->order_number }}</h4>
                            <p class="text-muted mb-0 small">
                                <i class="fas fa-calendar-alt me-1" style="color: var(--secondary)"></i> Ordered on {{ $order->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                        <span class="badge rounded-pill bg-{{ $order->status_color }}-subtle text-{{ $order->status_color }} border border-{{ $order->status_color }} px-4 py-2 fs-6">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="mb-2">
                        <div class="progress rounded-pill" style="height: 10px; background-color: var(--neutral);">
                            @php
                                $progress = match($order->status) {
                                    'pending' => 20,
                                    'paid' => 40,
                                    'processing' => 60,
                                    'shipping' => 80,
                                    'completed', 'cancelled' => 100,
                                    default => 0
                                };
                            @endphp
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: {{ $progress }}%; background-color: var(--primary);"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-3 small fw-medium">
                            <span class="{{ $order->status == 'pending' ? 'text-custom-primary' : 'text-muted' }}">Pending</span>
                            <span class="{{ $order->status == 'paid' ? 'text-custom-primary' : 'text-muted' }}">Paid</span>
                            <span class="{{ $order->status == 'processing' ? 'text-custom-primary' : 'text-muted' }}">Processing</span>
                            <span class="{{ $order->status == 'shipping' ? 'text-custom-primary' : 'text-muted' }}">Shipping</span>
                            <span class="{{ $order->status == 'completed' ? 'text-custom-primary' : 'text-muted' }}">Completed</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items Card --}}
            <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold" style="color: var(--dark-text)"><i class="fas fa-box me-2" style="color: var(--primary)"></i> Order Items</h5>
                </div>
                <div class="card-body p-0">
                    @foreach($order->orderItems as $item)
                        <div class="row align-items-center g-0 border-bottom p-4 item-row">
                            <div class="col-md-2 col-3 text-center">
                                @if($item->product)
                                    <div class="bg-white rounded border d-flex align-items-center justify-content-center overflow-hidden shadow-sm" style="width: 100px; height: 100px;">
                                        <img src="{{ $item->product->image_url }}" 
                                             class="img-fluid" 
                                             alt="{{ $item->product_name }}"
                                             style="max-height: 100%; object-fit: contain; padding: 5px;"
                                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.png') }}';">
                                    </div>
                                @else
                                    <div class="rounded-3 d-flex align-items-center justify-content-center border" style="height: 100px; width: 100px; background-color: var(--neutral);">
                                        <i class="fas fa-tshirt text-muted fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-9 ps-4">
                                <h6 class="mb-1 fw-bold" style="color: var(--dark-text)">{{ $item->product_name }}</h6>
                                <p class="text-muted mb-2 small">
                                    Size: <span class="badge border fw-normal" style="background-color: var(--neutral); color: var(--dark-text)">{{ $item->productVariant->size ?? 'N/A' }}</span> | 
                                    Color: <span class="badge border fw-normal" style="background-color: var(--neutral); color: var(--dark-text)">{{ $item->productVariant->color ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="col-md-2 col-6 text-md-center mt-3 mt-md-0">
                                <span class="text-muted small d-md-block">Quantity</span>
                                <span class="fw-bold" style="color: var(--dark-text)">{{ $item->quantity }}x</span>
                            </div>
                            <div class="col-md-2 col-6 text-end mt-3 mt-md-0 ps-md-0 pe-4">
                                <span class="text-muted small d-md-block">Subtotal</span>
                                <span class="fw-bold" style="color: var(--primary)">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- FITUR BARU: Feedback & Rating (Hanya jika Selesai) --}}
            @if($order->status == 'completed')
            <div class="card border-0 shadow-sm mb-4 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--dark-text)">Beri Nilai Produk</h5>
                    <form action="{{ route('customer.orders.feedback') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        
                        @foreach($order->orderItems as $index => $item)
                        <div class="mb-4 p-3 rounded-4 border bg-light-subtle">
                            <p class="fw-bold mb-3 small">{{ $item->product_name }}</p>
                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                            
                            <div class="mb-3">
                                <label class="form-label small text-muted">Rating:</label>
                                <div class="d-flex gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating[{{ $index }}]" id="star{{ $item->id }}{{ $i }}" value="{{ $i }}" class="btn-check" required>
                                        <label for="star{{ $item->id }}{{ $i }}" class="btn btn-outline-secondary rounded-pill px-3 py-1 small btn-rating-pill">
                                            {{ $i }} <i class="fas fa-star ms-1"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <textarea name="comment[{{ $index }}]" class="form-control border-0 shadow-sm p-3 small" 
                                      style="border-radius: 15px;" placeholder="Tulis ulasan pengalaman Anda..."></textarea>
                        </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm" style="background-color: var(--primary); border: none;">
                            Kirim Semua Ulasan
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            {{-- Order Summary --}}
            <div class="card mb-4 shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h5 class="mb-0 fw-bold" style="color: var(--dark-text)">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-secondary">Subtotal Items</span>
                        <span class="fw-bold" style="color: var(--dark-text)">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-secondary">
                        <span>Shipping Cost</span>
                        <span class="fw-bold" style="color: var(--dark-text)">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-3 opacity-50">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 fw-bold mb-0">Total</span>
                        <span class="h4 fw-bold mb-0" style="color: var(--primary)">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons Box --}}
            <div class="card shadow-sm border-0 rounded-4 p-2">
                <div class="card-body">
                    <div class="d-grid gap-3">
                        @if($order->payment && $order->payment->status === 'pending' && $order->status !== 'cancelled')
                            <a href="{{ route('customer.payment.show', $order->id) }}" class="btn text-white rounded-pill py-2 fw-bold shadow-sm" style="background-color: #E67E22;">
                                <i class="fas fa-credit-card me-2"></i> Complete Payment
                            </a>
                        @endif

                        @if($order->canBeCancelled())
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-bold">
                                    Batalkan Pesanan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('customer.orders.index') }}" class="btn rounded-pill py-2 fw-medium border shadow-sm" style="background-color: var(--neutral); color: var(--dark-text)">
                            Back to History
                        </a>
                        
                        @if($order->status === 'completed')
                            <a href="{{ route('customer.products.index', ['reorder' => $order->id]) }}" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm" style="background-color: var(--primary); border: none;">
                                Reorder Items
                            </a>
                        @endif

                        <a href="{{ route('customer.chat.index') }}" class="btn rounded-pill py-2 fw-bold shadow-sm" style="background-color: #6096B4; color: #FFFFFF; border: none;">
                            <i class="fas fa-comments me-2" style="color: #FFFFFF"></i>Chat with Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .item-row:hover { background-color: var(--bg-cream); transition: background 0.2s; }
    .text-custom-primary { color: var(--primary) !important; font-weight: 700; }
    .btn-rating-pill {
        border: 1px solid #ddd;
        color: #888;
        transition: 0.3s;
    }
    .btn-check:checked + .btn-rating-pill {
        background-color: #F1C40F !important; /* Warna Bintang Kuning */
        border-color: #F1C40F !important;
        color: white !important;
        box-shadow: 0 4px 8px rgba(241, 196, 15, 0.3);
    }
</style>
@endsection