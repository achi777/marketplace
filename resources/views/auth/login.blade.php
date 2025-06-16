<x-guest-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        
        .login-icon {
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
        
        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid #667eea;
            border-radius: 6px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .demo-card {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 16px;
            border: 1px solid #2196f3;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            background: #f8f9fa;
            color: #667eea;
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
            .login-container {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .login-header {
                padding: 1.5rem;
            }
        }
    </style>
    
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
            <h2 class="h3 fw-bold text-dark mb-2">Welcome Back!</h2>
            <p class="text-muted mb-0">Sign in to your account to continue</p>
        </div>

        <div class="p-4">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
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
                           autocomplete="username"
                           placeholder="Enter your email address"
                           class="form-control">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold text-muted">
                        <i class="bi bi-lock-fill me-2 text-primary"></i>Password
                    </label>
                    <div class="position-relative">
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="Enter your password"
                               class="form-control pe-5">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="password-toggle">
                            <i id="password-icon" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me and Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="form-check-input">
                        <label class="form-check-label text-muted" for="remember_me">
                            Remember me
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-primary text-decoration-none fw-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary w-100 mb-4">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>

                <!-- Divider -->
                <div class="position-relative my-4">
                    <hr class="border-top">
                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                        <span class="text-muted small">Don't have an account?</span>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="text-center mb-4">
                    <a href="{{ route('register') }}" class="btn btn-outline w-100">
                        <i class="bi bi-person-plus me-2"></i>
                        Create New Account
                    </a>
                </div>

                <!-- Demo Accounts Info -->
                <div class="demo-card">
                    <h6 class="fw-bold text-primary mb-3 d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>Demo Accounts
                    </h6>
                    <div class="row g-2 small">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold text-primary">Admin:</span>
                                <span class="text-muted">admin@marketplace.com</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold text-primary">Seller:</span>
                                <span class="text-muted">seller@marketplace.com</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold text-primary">Buyer:</span>
                                <span class="text-muted">buyer@marketplace.com</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold text-primary">Password:</span>
                                <span class="text-muted">password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'bi bi-eye';
            }
        }
    </script>
</x-guest-layout>
