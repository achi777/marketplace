<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">Create New User</h2>
                <p class="text-muted small mb-0">Add a new user account to the marketplace</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .form-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .form-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 0.9rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }
        
        .form-control::placeholder {
            color: #a0a6b1;
            font-weight: 400;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        .form-check-input {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
            border: 2px solid #e9ecef;
            border-radius: 6px;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            font-weight: 500;
            color: #2d3436;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .required-asterisk {
            color: #dc3545;
            font-weight: bold;
        }
        
        .create-user-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            margin: 0 auto 1rem;
        }
        
        .password-strength {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }
            
            .container {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-card p-4">
                    <!-- Create User Header -->
                    <div class="text-center mb-4">
                        <div class="create-user-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Create New User</h4>
                        <p class="text-muted mb-0">Add a new account to the marketplace</p>
                    </div>

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-person-circle"></i>
                                Basic Information
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        Full Name <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Enter full name">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Email Address <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Enter email address">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-shield-lock"></i>
                                Security Settings
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        Password <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="password" name="password" id="password" required
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Enter secure password">
                                    <div class="password-strength">
                                        <small><i class="bi bi-info-circle me-1"></i>Use at least 8 characters with a mix of letters, numbers, and symbols</small>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Confirm Password <span class="required-asterisk">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required
                                           class="form-control"
                                           placeholder="Confirm password">
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="bi bi-gear"></i>
                                Account Settings
                            </h5>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="role" class="form-label">
                                        User Role <span class="required-asterisk">*</span>
                                    </label>
                                    <select name="role" id="role" required
                                            class="form-select @error('role') is-invalid @enderror">
                                        <option value="">Choose user role</option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin - Full system access</option>
                                        <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>Seller - Can sell products</option>
                                        <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Buyer - Can purchase products</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        Phone Number
                                    </label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="address" class="form-label">
                                    Address
                                </label>
                                <textarea name="address" id="address" rows="3"
                                          class="form-control @error('address') is-invalid @enderror"
                                          placeholder="Enter address (optional)">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="is_approved" id="is_approved" value="1" 
                                           {{ old('is_approved', true) ? 'checked' : '' }}
                                           class="form-check-input">
                                    <label for="is_approved" class="form-check-label">
                                        <strong>Pre-approve User Account</strong>
                                        <br><small class="text-muted">Allows sellers to start selling immediately without manual approval</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-3 pt-4 border-top">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Passwords do not match';
                    this.parentNode.appendChild(feedback);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback && feedback.textContent === 'Passwords do not match') {
                    feedback.remove();
                }
            }
        });

        // Role selection helper
        document.getElementById('role').addEventListener('change', function() {
            const approvalCheckbox = document.getElementById('is_approved');
            const approvalLabel = document.querySelector('label[for="is_approved"]');
            
            if (this.value === 'seller') {
                approvalLabel.innerHTML = '<strong>Pre-approve Seller Account</strong><br><small class="text-muted">Allows this seller to start selling immediately without manual approval</small>';
            } else {
                approvalLabel.innerHTML = '<strong>Pre-approve User Account</strong><br><small class="text-muted">Allows sellers to start selling immediately without manual approval</small>';
            }
        });
    </script>
</x-app-layout>