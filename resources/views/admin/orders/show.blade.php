@extends('layouts.admin')

@section('title', 'Order Detail - Apparify')
@section('page-title', 'Order Detail')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <!-- Order Info -->
            <div class="card mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Order #{{ $order->order_number }}</h5>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'paid' => 'info',
                            'processing' => 'primary',
                            'shipping' => 'secondary',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $color = $statusColors[$order->status] ?? 'secondary';
                    @endphp
                    <span class="badge bg-{{ $color }} fs-6">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->user->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Shipping Information</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $order->shipping_name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                            <p class="mb-1"><strong>Address:</strong><br>{{ $order->shipping_address }}</p>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-sticky-note"></i> Order Notes:</strong><br>
                            {{ $order->notes }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Variant</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->customDesign)
                                                <br><small class="text-info"><i class="fas fa-paint-brush"></i> Custom Design</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $details = json_decode($item->variant_details);
                                            @endphp
                                            Size: <strong>{{ $details->size }}</strong><br>
                                            Color: <strong>{{ $details->color }}</strong>
                                        </td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                    <td><strong>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><strong class="fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->payment)
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Method:</strong> {{ strtoupper($order->payment->payment_method) }}</p>
                                <p class="mb-1"><strong>Amount:</strong> Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                                <p class="mb-1">
                                    <strong>Status:</strong> 
                                    @php
                                        $paymentColors = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'secondary'
                                        ];
                                        $color = $paymentColors[$order->payment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($order->payment->status) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if($order->payment->payment_proof)
                                    <p class="mb-1"><strong>Payment Proof:</strong></p>
                                    <img src="{{ asset('storage/' . $order->payment->payment_proof) }}" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Update Status -->
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-edit"></i> Update Status</h5>
                </div>
                <div class="card-body">
                    @if($order->status !== 'completed' && $order->status !== 'cancelled')
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Change Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Shipping</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i> Order is {{ $order->status }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Order Timeline</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled timeline">
                        <li class="mb-3">
                            <i class="fas fa-circle text-primary"></i>
                            <strong>Order Created</strong>
                            <br><small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                        </li>
                        @if($order->payment && $order->payment->paid_at)
                            <li class="mb-3">
                                <i class="fas fa-circle text-success"></i>
                                <strong>Payment Confirmed</strong>
                                <br><small class="text-muted">{{ $order->payment->paid_at }}</small>
                            </li>
                        @endif
                        @if($order->status == 'completed')
                            <li class="mb-3">
                                <i class="fas fa-circle text-success"></i>
                                <strong>Order Completed</strong>
                                <br><small class="text-muted">{{ $order->updated_at->format('d M Y H:i') }}</small>
                            </li>
                        @endif
                        @if($order->status == 'cancelled')
                            <li class="mb-3">
                                <i class="fas fa-circle text-danger"></i>
                                <strong>Order Cancelled</strong>
                                <br><small class="text-muted">{{ $order->updated_at->format('d M Y H:i') }}</small>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Orders
                        </a>
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print"></i> Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection