<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-check-circle-fill"></i>
                            Order Confirmed!
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-bag-check display-1 text-success"></i>
                        </div>
                        
                        <h4 class="mb-3">Thank you for your order!</h4>
                        <p class="text-muted mb-4">
                            Your order has been successfully placed and is being processed.
                        </p>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted">Order Number</h6>
                                    <h5 class="mb-0">{{ $order->order_number }}</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <h6 class="text-muted">Total Amount</h6>
                                    <h5 class="mb-0">${{ number_format($order->total_amount, 2) }}</h5>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>What's next?</strong><br>
                            We'll send you an email confirmation with your order details.
                            You can track your order status in your account dashboard.
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-amazon w-100">
                                    <i class="bi bi-speedometer2"></i> View Dashboard
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-arrow-left"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Details</h5>
                    </div>
                    <div class="card-body">
                        @foreach($order->items as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->productVariation->product->name }}</h6>
                                    <small class="text-muted">
                                        by {{ $item->productVariation->product->seller->name }}
                                        @if($item->productVariation->attribute_display)
                                            - {{ $item->productVariation->attribute_display }}
                                        @endif
                                    </small>
                                    <div class="small text-muted">Quantity: {{ $item->quantity }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">${{ number_format($item->total_price, 2) }}</div>
                                </div>
                            </div>
                        @endforeach

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Shipping Address</h6>
                                <address class="text-muted">
                                    {{ $order->shipping_address['address'] }}<br>
                                    {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                                    {{ $order->shipping_address['country'] }}
                                </address>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tax:</td>
                                            <td class="text-end">${{ number_format($order->tax_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Shipping:</td>
                                            <td class="text-end">${{ number_format($order->shipping_cost, 2) }}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td class="fw-bold">Total:</td>
                                            <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>