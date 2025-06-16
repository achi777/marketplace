<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    
                    <!-- Billing Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-credit-card"></i> Billing Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="billing_address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="billing_address" name="billing_address" 
                                           value="{{ auth()->user()->address }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="billing_city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="billing_city" name="billing_city" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="billing_state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="billing_state" name="billing_state" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="billing_zip" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="billing_zip" name="billing_zip" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="billing_country" class="form-label">Country</label>
                                    <select class="form-control" id="billing_country" name="billing_country" required>
                                        <option value="US" selected>United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="AU">Australia</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="same_as_billing" name="same_as_billing" checked>
                                <label class="form-check-label" for="same_as_billing">
                                    Same as billing address
                                </label>
                            </div>
                            
                            <div id="shipping-fields" style="display: none;">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="shipping_address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="shipping_address" name="shipping_address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="shipping_city" name="shipping_city">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="shipping_state" class="form-label">State</label>
                                        <input type="text" class="form-control" id="shipping_state" name="shipping_state">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="shipping_zip" class="form-label">ZIP Code</label>
                                        <input type="text" class="form-control" id="shipping_zip" name="shipping_zip">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="shipping_country" class="form-label">Country</label>
                                        <select class="form-control" id="shipping_country" name="shipping_country">
                                            <option value="US">United States</option>
                                            <option value="CA">Canada</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="AU">Australia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Options -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Options</h5>
                        </div>
                        <div class="card-body">
                            <div id="shipping-rates-loading" style="display: none;">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status"></div>
                                    <span class="ms-2">Calculating shipping rates...</span>
                                </div>
                            </div>
                            
                            <div id="shipping-rates-container">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    Please complete your shipping address to see shipping options.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-credit-card-2-front"></i> Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                                <label class="form-check-label" for="stripe">
                                    <i class="bi bi-credit-card"></i> Credit/Debit Card (Stripe)
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="2checkout" value="2checkout">
                                <label class="form-check-label" for="2checkout">
                                    <i class="bi bi-paypal"></i> PayPal & More (2Checkout)
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <div class="order-items mb-3">
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small">{{ $item->productVariation->product->name }}</h6>
                                        <small class="text-muted">
                                            by {{ $item->productVariation->product->seller->name }}
                                            @if($item->productVariation->attribute_display)
                                                - {{ $item->productVariation->attribute_display }}
                                            @endif
                                        </small>
                                        <div class="small text-muted">Qty: {{ $item->quantity }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">${{ number_format($item->total_price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals -->
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal ({{ $cart->item_count }} items):</span>
                            <span>${{ number_format($cart->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>${{ number_format($cart->tax_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="shipping-cost" class="text-success">FREE</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold h5">
                            <span>Total:</span>
                            <span>${{ number_format($cart->total, 2) }}</span>
                        </div>

                        <!-- Shipping Info -->
                        @if($itemsBySeller->count() > 1)
                            <div class="alert alert-info small mt-3">
                                <i class="bi bi-info-circle"></i>
                                This order contains items from {{ $itemsBySeller->count() }} different sellers and may arrive in separate packages.
                            </div>
                        @endif

                        <button type="submit" form="checkout-form" class="btn btn-amazon w-100 mt-3" id="place-order-btn">
                            <i class="bi bi-lock"></i> Place Order
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check"></i> Secure 256-bit SSL encryption
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Security Info -->
                <div class="card mt-3">
                    <div class="card-body text-center">
                        <h6 class="card-title">Secure Checkout</h6>
                        <div class="row text-center">
                            <div class="col-4">
                                <i class="bi bi-shield-lock display-6 text-success"></i>
                                <small class="d-block">SSL Secure</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-credit-card display-6 text-primary"></i>
                                <small class="d-block">Safe Payment</small>
                            </div>
                            <div class="col-4">
                                <i class="bi bi-arrow-return-left display-6 text-info"></i>
                                <small class="d-block">Easy Returns</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle shipping address fields
        document.getElementById('same_as_billing').addEventListener('change', function() {
            const shippingFields = document.getElementById('shipping-fields');
            const shippingInputs = shippingFields.querySelectorAll('input, select');
            
            if (this.checked) {
                shippingFields.style.display = 'none';
                shippingInputs.forEach(input => {
                    input.removeAttribute('required');
                });
                // Calculate shipping rates using billing address
                calculateShippingRates();
            } else {
                shippingFields.style.display = 'block';
                shippingInputs.forEach(input => {
                    if (input.name !== 'shipping_country') {
                        input.setAttribute('required', 'required');
                    }
                });
            }
        });

        // Calculate shipping rates when address changes
        function calculateShippingRates() {
            const sameAsBilling = document.getElementById('same_as_billing').checked;
            
            let addressData;
            if (sameAsBilling) {
                addressData = {
                    shipping_address: document.getElementById('billing_address').value,
                    shipping_city: document.getElementById('billing_city').value,
                    shipping_state: document.getElementById('billing_state').value,
                    shipping_zip: document.getElementById('billing_zip').value,
                    shipping_country: document.getElementById('billing_country').value,
                };
            } else {
                addressData = {
                    shipping_address: document.getElementById('shipping_address').value,
                    shipping_city: document.getElementById('shipping_city').value,
                    shipping_state: document.getElementById('shipping_state').value,
                    shipping_zip: document.getElementById('shipping_zip').value,
                    shipping_country: document.getElementById('shipping_country').value,
                };
            }

            // Check if all required fields are filled
            if (!addressData.shipping_address || !addressData.shipping_city || 
                !addressData.shipping_state || !addressData.shipping_zip) {
                return;
            }

            // Show loading
            document.getElementById('shipping-rates-loading').style.display = 'block';
            document.getElementById('shipping-rates-container').style.display = 'none';

            fetch('{{ route('checkout.shipping-rates') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(addressData)
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('shipping-rates-loading').style.display = 'none';
                document.getElementById('shipping-rates-container').style.display = 'block';
                
                if (data.success && data.rates.length > 0) {
                    displayShippingRates(data.rates);
                } else {
                    document.getElementById('shipping-rates-container').innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            No shipping options available for this address.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('shipping-rates-loading').style.display = 'none';
                document.getElementById('shipping-rates-container').style.display = 'block';
                document.getElementById('shipping-rates-container').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle"></i>
                        Failed to calculate shipping rates.
                    </div>
                `;
            });
        }

        function displayShippingRates(rates) {
            let html = '<h6 class="mb-3">Select shipping method:</h6>';
            
            rates.forEach((rate, index) => {
                html += `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="shipping_method" 
                               id="shipping_${index}" value="${rate.service_code}" 
                               data-cost="${rate.cost}" ${index === 0 ? 'checked' : ''}>
                        <label class="form-check-label d-flex justify-content-between w-100" for="shipping_${index}">
                            <div>
                                <strong>${rate.carrier} ${rate.service}</strong><br>
                                <small class="text-muted">Estimated delivery: ${rate.estimated_days} business days</small>
                            </div>
                            <div class="text-end">
                                <strong>$${rate.cost.toFixed(2)}</strong>
                            </div>
                        </label>
                    </div>
                `;
            });
            
            document.getElementById('shipping-rates-container').innerHTML = html;
            
            // Update shipping cost when selection changes
            document.querySelectorAll('input[name="shipping_method"]').forEach(input => {
                input.addEventListener('change', function() {
                    const cost = parseFloat(this.dataset.cost);
                    document.getElementById('shipping-cost').textContent = '$' + cost.toFixed(2);
                    if (cost > 0) {
                        document.getElementById('shipping-cost').classList.remove('text-success');
                        document.getElementById('shipping-cost').classList.add('text-dark');
                    }
                });
            });
            
            // Set initial shipping cost
            if (rates.length > 0) {
                const firstRate = rates[0];
                document.getElementById('shipping-cost').textContent = '$' + firstRate.cost.toFixed(2);
                if (firstRate.cost > 0) {
                    document.getElementById('shipping-cost').classList.remove('text-success');
                    document.getElementById('shipping-cost').classList.add('text-dark');
                }
            }
        }

        // Add event listeners to address fields
        ['billing_address', 'billing_city', 'billing_state', 'billing_zip', 'billing_country'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('blur', function() {
                if (document.getElementById('same_as_billing').checked) {
                    calculateShippingRates();
                }
            });
        });

        ['shipping_address', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('blur', function() {
                    if (!document.getElementById('same_as_billing').checked) {
                        calculateShippingRates();
                    }
                });
            }
        });

        // Handle form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('place-order-btn');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            
            // Submit form data
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                } else {
                    showNotification(data.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while processing your order. Please try again.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    </script>
</x-app-layout>