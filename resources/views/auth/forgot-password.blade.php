<x-guest-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .forgot-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .forgot-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        
        .forgot-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            transition: all 0.3s ease;
            background: white;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
        }
        
        .btn-outline {
            border: 2px solid #667eea;
            border-radius: 12px;
            color: #667eea;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: white;
        }
        
        .btn-outline:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .info-card {
            background: linear-gradient(135deg, #e8f5e8 0%, #d4f1d4 100%);
            border-radius: 16px;
            border: 1px solid #28a745;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-primary {
            color: #667eea !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        @media (max-width: 768px) {
            .forgot-container {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .forgot-header {
                padding: 1.5rem;
            }
        }
    </style>
    
    <div class="forgot-container">
        <!-- Header -->
        <div class="forgot-header">
            <div class="forgot-icon">
                <i class="bi bi-key"></i>
            </div>
            <h2 class="h3 fw-bold text-dark mb-2">Reset Password</h2>
            <p class="text-muted mb-0">We'll send you a password reset link</p>
        </div>

        <div class="p-4">
            <!-- Information Card -->
            <div class="info-card">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-info-circle-fill text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-success mb-2">How it works:</h6>
                        <p class="text-success mb-0 small">
                            Forgot your password? No problem. Just enter your email address and we'll send you a password reset link that will allow you to choose a new one.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-muted">
                        <i class="bi bi-envelope-fill me-2 text-primary"></i>Email Address
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus
                           placeholder="Enter your email address"
                           class="form-control">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Send Reset Link Button -->
                <button type="submit" class="btn btn-primary w-100 mb-4">
                    <i class="bi bi-send me-2"></i>
                    Send Password Reset Link
                </button>

                <!-- Divider -->
                <div class="position-relative my-4">
                    <hr class="border-top">
                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                        <span class="text-muted small">Remember your password?</span>
                    </div>
                </div>

                <!-- Back to Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-outline w-100">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back to Sign In
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
