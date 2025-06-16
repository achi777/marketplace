<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                {{ __('Order Details') }} - {{ $order->order_number }}
            </h2>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Order Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Order Number:</strong><br>
                                {{ $order->order_number }}
                            </div>
                            <div class="col-md-3">
                                <strong>Order Date:</strong><br>
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                <span class="badge 
                                    @if($order->status === 'delivered') bg-success
                                    @elseif($order->status === 'shipped') bg-info
                                    @elseif($order->status === 'processing') bg-warning
                                    @else bg-secondary @endif fs-6">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Total Amount:</strong><br>
                                <span class="h5 text-primary">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        @if($order->tracking_number)
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Tracking Number:</strong><br>
                                    <code>{{ $order->tracking_number }}</code>
                                    @if($order->carrier)
                                        <span class="badge bg-secondary ms-2">{{ $order->carrier }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <a href="{{ route('orders.tracking', $order) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-truck"></i> Track Package
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($order->items as $item)
                            <div class="p-3 border-bottom">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if($item->productVariation->product->main_image)
                                            <img src="{{ $item->productVariation->product->main_image }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $item->product_name }}"
                                                 style="height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 80px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-5">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <small class="text-muted">
                                            by {{ $item->seller->name }}
                                        </small>
                                        @if($item->attribute_display)
                                            <div class="small text-muted">{{ $item->attribute_display }}</div>
                                        @endif
                                        <div class="small text-muted">SKU: {{ $item->product_sku }}</div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <strong>${{ number_format($item->unit_price, 2) }}</strong>
                                    </div>
                                    <div class="col-md-1 text-center">
                                        <strong>{{ $item->quantity }}</strong>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong>${{ number_format($item->total_price, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Information -->
                @if($order->payments->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Payment Information</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->payments as $payment)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ ucfirst($payment->gateway) }} Payment</strong>
                                        <div class="small text-muted">{{ $payment->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div>${{ number_format($payment->amount, 2) }}</div>
                                        <span class="badge 
                                            @if($payment->status === 'completed') bg-success
                                            @elseif($payment->status === 'pending') bg-warning
                                            @else bg-danger @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Order Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>${{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold h6">
                            <span>Total:</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Shipping Address</h6>
                    </div>
                    <div class="card-body">
                        <address class="mb-0">
                            {{ $order->shipping_address['address'] }}<br>
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                            {{ $order->shipping_address['country'] }}
                        </address>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        <address class="mb-0">
                            {{ $order->billing_address['address'] }}<br>
                            {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}<br>
                            {{ $order->billing_address['country'] }}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>