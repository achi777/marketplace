<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark text-center">
                        <h3 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Payment Cancelled
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="bi bi-x-circle display-1 text-warning"></i>
                        </div>
                        
                        <h4 class="mb-3">Payment was cancelled</h4>
                        <p class="text-muted mb-4">
                            Your payment was cancelled and no charges were made to your account.
                            Your items are still in your cart if you'd like to try again.
                        </p>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Need help?</strong><br>
                            If you experienced any issues during checkout, please contact our support team.
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-amazon w-100">
                                    <i class="bi bi-cart"></i> Return to Cart
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-house"></i> Go to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>