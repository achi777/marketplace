<x-guest-layout>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .register-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .register-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        
        .register-icon {
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
        
        .strength-meter {
            height: 4px;
            border-radius: 2px;
            background: #e9ecef;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        
        .strength-bar {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-fair { background: #fd7e14; width: 50%; }
        .strength-good { background: #20c997; width: 75%; }
        .strength-strong { background: #198754; width: 100%; }
        
        @media (max-width: 768px) {
            .register-container {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .register-header {
                padding: 1.5rem;
            }
        }
    </style>
    
    <div class="register-container">
        <!-- Header -->
        <div class="register-header">
            <div class="register-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <h2 class="h3 fw-bold text-dark mb-2">Create Account</h2>
            <p class="text-muted mb-0">Join our marketplace community today</p>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-semibold text-muted">
                        <i class="bi bi-person-fill me-2 text-primary"></i>Full Name
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Enter your full name"
                           class="form-control">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

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
                               autocomplete="new-password"
                               placeholder="Create a strong password"
                               class="form-control pe-5"
                               oninput="checkPasswordStrength()">
                        <button type="button" 
                                onclick="togglePassword('password', 'password-icon')"
                                class="password-toggle">
                            <i id="password-icon" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="strength-meter">
                        <div id="strength-bar" class="strength-bar"></div>
                    </div>
                    <small id="strength-text" class="text-muted">Password strength will appear here</small>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold text-muted">
                        <i class="bi bi-shield-check me-2 text-primary"></i>Confirm Password
                    </label>
                    <div class="position-relative">
                        <input id="password_confirmation" 
                               type="password" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Confirm your password"
                               class="form-control pe-5"
                               oninput="checkPasswordMatch()">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation', 'confirm-icon')"
                                class="password-toggle">
                            <i id="confirm-icon" class="bi bi-eye"></i>
                        </button>
                    </div>
                    <small id="match-text" class="text-muted">Passwords must match</small>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-primary w-100 mb-4">
                    <i class="bi bi-person-plus me-2"></i>
                    Create Account
                </button>

                <!-- Divider -->
                <div class="position-relative my-4">
                    <hr class="border-top">
                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                        <span class="text-muted small">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-outline w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In Instead
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'bi bi-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'bi bi-eye';
            }
        }
        
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            let strength = 0;
            let text = '';
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'strength-bar';
            
            switch(strength) {
                case 0:
                case 1:
                    strengthBar.classList.add('strength-weak');
                    text = 'Weak password';
                    break;
                case 2:
                case 3:
                    strengthBar.classList.add('strength-fair');
                    text = 'Fair password';
                    break;
                case 4:
                    strengthBar.classList.add('strength-good');
                    text = 'Good password';
                    break;
                case 5:
                    strengthBar.classList.add('strength-strong');
                    text = 'Strong password';
                    break;
            }
            
            strengthText.textContent = password.length > 0 ? text : 'Password strength will appear here';
            checkPasswordMatch();
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('match-text');
            
            if (confirmPassword.length === 0) {
                matchText.textContent = 'Passwords must match';
                matchText.className = 'text-muted';
            } else if (password === confirmPassword) {
                matchText.textContent = 'Passwords match!';
                matchText.className = 'text-success';
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.className = 'text-danger';
            }
        }
    </script>
</x-guest-layout>
