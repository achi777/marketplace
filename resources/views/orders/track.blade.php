<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Track Order') }} - {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam"></i> 
                            Order {{ $order->order_number }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'shipped' ? 'info' : 'warning') }} fs-6">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="col-md-3">
                                <strong>Order Date:</strong><br>
                                {{ $order->created_at->format('M d, Y') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Total:</strong><br>
                                ${{ number_format($order->total_amount, 2) }}
                            </div>
                            <div class="col-md-3">
                                <strong>Items:</strong><br>
                                {{ $order->items->count() }} item(s)
                            </div>
                        </div>

                        @if($order->tracking_number)
                            <div class="alert alert-info">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <strong><i class="bi bi-truck"></i> Tracking Number:</strong>
                                        <code class="ms-2">{{ $order->tracking_number }}</code>
                                        @if($order->carrier)
                                            <span class="badge bg-secondary ms-2">{{ $order->carrier }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <button class="btn btn-outline-primary btn-sm" onclick="refreshTracking()">
                                            <i class="bi bi-arrow-clockwise"></i> Refresh Tracking
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tracking Information -->
                @if($order->tracking_number && $order->carrier)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-geo-alt"></i> 
                                Tracking Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="tracking-loading" style="display: none;">
                                <div class="text-center py-4">
                                    <div class="spinner-border" role="status"></div>
                                    <div class="mt-2">Loading tracking information...</div>
                                </div>
                            </div>
                            
                            <div id="tracking-content">
                                <div class="text-center py-4">
                                    <button class="btn btn-primary" onclick="loadTracking()">
                                        <i class="bi bi-search"></i> Load Tracking Information
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
                                                 style="height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
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
                                        <strong>Qty: {{ $item->quantity }}</strong>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <strong>${{ number_format($item->total_price, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <address>
                            {{ $order->shipping_address['address'] }}<br>
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                            {{ $order->shipping_address['country'] }}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadTracking() {
            document.getElementById('tracking-loading').style.display = 'block';
            document.getElementById('tracking-content').innerHTML = '';

            fetch('{{ route('orders.track', $order) }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('tracking-loading').style.display = 'none';
                    
                    if (data.success) {
                        displayTrackingInfo(data.tracking);
                    } else {
                        document.getElementById('tracking-content').innerHTML = `
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('tracking-loading').style.display = 'none';
                    document.getElementById('tracking-content').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            Failed to load tracking information.
                        </div>
                    `;
                });
        }

        function refreshTracking() {
            loadTracking();
        }

        function displayTrackingInfo(tracking) {
            let html = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Package Status</h6>
                        <span class="badge bg-primary fs-6">${tracking.status}</span>
                    </div>
                    <div class="col-md-6">
                        <h6>Estimated Delivery</h6>
                        <strong>${new Date(tracking.estimated_delivery).toLocaleDateString()}</strong>
                    </div>
                </div>
                
                <h6>Tracking History</h6>
                <div class="timeline">
            `;

            tracking.tracking_events.forEach((event, index) => {
                const eventDate = new Date(event.date);
                html += `
                    <div class="timeline-item ${index === 0 ? 'active' : ''}">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${event.status}</strong>
                                    <div class="small text-muted">${event.location}</div>
                                </div>
                                <div class="text-muted small">
                                    ${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            
            document.getElementById('tracking-content').innerHTML = html;
        }
    </script>

    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            border-left: 2px solid #dee2e6;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-item.active {
            border-left-color: #0d6efd;
        }

        .timeline-marker {
            position: absolute;
            left: -6px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #dee2e6;
            border: 2px solid #fff;
        }

        .timeline-item.active .timeline-marker {
            background-color: #0d6efd;
        }

        .timeline-content {
            margin-left: 20px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .timeline-item.active .timeline-content {
            background-color: #e7f3ff;
        }
    </style>
</x-app-layout>