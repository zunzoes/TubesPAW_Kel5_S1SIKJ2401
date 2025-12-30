@extends('layouts.admin')

@section('title', 'Orders - Apparify')
@section('page-title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="color: #6096B4;"><i class="fas fa-shopping-cart"></i> All Orders</h4>
    </div>

    <div class="card mb-3 border-0 shadow-sm" style="background-color: #EEE9DA; border-radius: 15px;">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control border-0" placeholder="Search by order number or customer..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select border-0">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control border-0" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control border-0" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn text-white w-100 shadow-sm d-flex align-items-center justify-content-center" style="background-color: #6096B4;">
                            <i class="fas fa-filter me-2" style="font-size: 0.85rem;"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            @if(isset($orders) && $orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: #BDCDD6; color: #2C3333;">
                            <tr>
                                <th class="ps-3 py-3" width="15%">Order Number</th>
                                <th width="20%">Customer</th>
                                <th width="12%">Total</th>
                                <th width="10%">Payment</th>
                                <th width="10%">Status</th>
                                <th width="15%">Date</th>
                                <th class="text-center" width="18%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="ps-3"><strong style="color: #6096B4; font-size: 0.9rem;">{{ $order->order_number }}</strong></td>
                                    <td>
                                        <div class="fw-bold" style="font-size: 0.9rem;">{{ $order->user->name }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $order->user->email }}</small>
                                    </td>
                                    <td class="fw-bold" style="font-size: 0.9rem;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if($order->payment)
                                            @php
                                                $paymentColors = [
                                                    'pending' => '#93BFCF',
                                                    'paid' => '#6096B4',
                                                    'failed' => '#dc3545',
                                                    'refunded' => '#BDCDD6'
                                                ];
                                                $bgColor = $paymentColors[$order->payment->status] ?? '#BDCDD6';
                                            @endphp
                                            <span class="badge" style="background-color: {{ $bgColor }}; color: white; font-size: 0.7rem; padding: 0.4em 0.8em;">
                                                {{ ucfirst($order->payment->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.7rem;">No Payment</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-warning',
                                                'paid' => 'bg-info',
                                                'processing' => 'bg-primary',
                                                'shipping' => 'bg-secondary',
                                                'completed' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            $badgeClass = $statusColors[$order->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}" style="font-size: 0.7rem; padding: 0.4em 0.8em;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size: 0.8rem;">{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm text-white d-flex align-items-center px-2 py-1" 
                                               style="background-color: #93BFCF; font-size: 0.75rem;">
                                                <i class="fas fa-eye me-1" style="font-size: 0.7rem;"></i> View
                                            </a>
                                            
                                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                                                <div class="btn-group">
                                                    <button type="button" 
                                                            class="btn btn-sm text-white dropdown-toggle d-flex align-items-center px-2 py-1" 
                                                            style="background-color: #6096B4; font-size: 0.75rem;" 
                                                            data-bs-toggle="dropdown">
                                                        <i class="fas fa-edit me-1" style="font-size: 0.7rem;"></i>
                                                    </button>
                                                    <ul class="dropdown-menu shadow border-0" style="font-size: 0.85rem;">
                                                        @if($order->status == 'pending')
                                                            <li>
                                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                                    @csrf @method('PATCH')
                                                                    <input type="hidden" name="status" value="paid">
                                                                    <button type="submit" class="dropdown-item">Mark as Paid</button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                                @csrf @method('PATCH')
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <button type="submit" class="dropdown-item text-danger">Cancel Order</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5" style="background-color: #FCF8EE;">
                    <i class="fas fa-shopping-cart fa-3x mb-3" style="color: #BDCDD6;"></i>
                    <p class="text-muted">No orders found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Merapikan tabel agar seragam */
    .table td, .table th {
        white-space: nowrap;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .btn-sm {
        line-height: 1.5;
        border-radius: 6px;
    }
    .dropdown-toggle::after {
        vertical-align: 0.15em;
        margin-left: 0.4em;
    }
    .table-hover tbody tr:hover { background-color: #FCF8EE; }
    .pagination .page-link { color: #6096B4; font-size: 0.85rem; }
    .pagination .page-item.active .page-link { background-color: #6096B4; border-color: #6096B4; }
</style>
@endsection