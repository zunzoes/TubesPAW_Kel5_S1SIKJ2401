@extends('layouts.customer')

@section('title', 'My Orders - Apparify')

@section('content')
<div class="container pb-5">
    <div class="mb-4">
        {{-- Ikon dihapus dari judul utama sesuai permintaan sebelumnya --}}
        <h2 class="fw-bold" style="color: var(--dark-text)">
            My Orders
        </h2>
        <p class="text-muted small">Pantau status pesanan dan riwayat belanja Anda di sini.</p>
    </div>

    {{-- Navigasi Status: Bentuk Pill --}}
    <ul class="nav nav-pills mb-4 p-2 rounded-pill shadow-sm" style="max-width: fit-content; background-color: var(--neutral);">
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ !request('status') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                All Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'pending' ? 'active' : '' }}" 
               href="{{ route('customer.orders.index', ['status' => 'pending']) }}">
                Pending
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'processing' ? 'active' : '' }}" 
               href="{{ route('customer.orders.index', ['status' => 'processing']) }}">
                Processing
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'shipping' ? 'active' : '' }}" 
               href="{{ route('customer.orders.index', ['status' => 'shipping']) }}">
                Shipping
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'completed' ? 'active' : '' }}" 
               href="{{ route('customer.orders.index', ['status' => 'completed']) }}">
                Completed
            </a>
        </li>
    </ul>

    @if(isset($orders) && $orders->count() > 0)
        @foreach($orders as $order)
            <div class="card mb-4 shadow-sm border-0 overflow-hidden" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <span class="text-muted small">ID Pesanan:</span>
                        <strong class="ms-1" style="color: var(--dark-text)">#{{ $order->order_number }}</strong>
                        <span class="mx-2 text-muted">|</span>
                        <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> {{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 border bg-{{ $order->status_color }}-subtle text-{{ $order->status_color }} border-{{ $order->status_color }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            @foreach($order->orderItems->take(2) as $item)
                                <div class="d-flex align-items-center mb-3">
                                    @if($item->product)
                                        {{-- Area Gambar: Background Putih Bersih --}}
                                        <div class="bg-white rounded border d-flex align-items-center justify-content-center overflow-hidden shadow-sm me-3" style="width: 80px; height: 80px;">
                                            <img src="{{ $item->product->image_url }}" 
                                                 class="img-fluid" 
                                                 style="max-height: 100%; object-fit: contain; padding: 5px;"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.png') }}';">
                                        </div>
                                    @else
                                        <div class="rounded-3 me-3 d-flex align-items-center justify-content-center border" 
                                             style="width: 80px; height: 80px; background-color: var(--neutral);">
                                            <i class="fas fa-tshirt text-muted fa-lg"></i>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="color: var(--dark-text)">{{ $item->product_name }}</h6>
                                        <p class="text-muted mb-1 small">
                                            Varian: <span class="badge border-0 fw-normal" style="background-color: var(--neutral); color: var(--dark-text)">{{ $item->variant_name ?? 'Standard' }}</span> 
                                            <span class="mx-1">|</span> 
                                            Jumlah: <span class="fw-bold" style="color: var(--primary)">{{ $item->quantity }}</span>
                                        </p>
                                        <span class="fw-bold" style="color: var(--primary)">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 2)
                                <p class="small ms-2 d-inline-block px-2 py-1 rounded" style="background-color: var(--neutral); color: var(--dark-text)">
                                    <i class="fas fa-plus-circle me-1" style="color: var(--primary)"></i> {{ $order->orderItems->count() - 2 }} produk lainnya
                                </p>
                            @endif
                        </div>
                        
                        <div class="col-md-4 text-md-end border-start" style="border-color: var(--accent) !important;">
                            <div class="px-md-3">
                                <p class="text-muted mb-1 small">Total Transaksi</p>
                                <h4 class="fw-bold mb-4" style="color: var(--primary)">Rp {{ number_format($order->total, 0, ',', '.') }}</h4>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-custom rounded-pill px-4 shadow-sm">
                                        Detail Pesanan
                                    </a>
                                    
                                    @if($order->status === 'pending' && $order->payment && $order->payment->status === 'pending')
                                        <a href="{{ route('customer.payment.show', $order->id) }}" class="btn text-white rounded-pill px-4 shadow-sm fw-bold" style="background-color: #E67E22;">
                                            <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-center mt-5">
            {{ $orders->links() }}
        </div>
    @else
        {{-- Empty State: Mengikuti gaya halaman Cart --}}
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-box-open fa-5x opacity-25" style="color: var(--primary)"></i>
                </div>
                <h4 class="fw-bold" style="color: var(--dark-text)">Belum ada pesanan</h4>
                <p class="text-muted mb-4 px-md-5 small">Sepertinya Anda belum memiliki riwayat belanja dengan status ini.</p>
                <a href="{{ route('customer.products.index') }}" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold" style="background-color: var(--primary); border-color: var(--primary);">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    .nav-pills .nav-link {
        color: var(--dark-text);
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .nav-pills .nav-link.active {
        background-color: var(--primary) !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(96, 150, 180, 0.3);
    }
    .btn-outline-custom {
        color: var(--primary);
        border: 2px solid var(--primary);
        font-weight: 700;
        transition: all 0.3s;
    }
    .btn-outline-custom:hover {
        background-color: var(--primary);
        color: white;
    }
</style>
@endsection