<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h4 mb-1 text-dark fw-bold">User Management</h2>
                <p class="text-muted small mb-0">Manage users, roles, and account approvals</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-circle me-2"></i>Add User
            </a>
        </div>
    </x-slot>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .stats-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card.primary {
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .stats-card.success {
            --gradient: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
        }
        
        .stats-card.warning {
            --gradient: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
        }
        
        .stats-card.info {
            --gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
        }
        
        .stats-card.danger {
            --gradient: linear-gradient(135deg, #fd79a8 0%, #e84393 100%);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            background: var(--gradient);
            flex-shrink: 0;
        }
        
        .filter-card, .users-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .filter-card::before, .users-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .user-row {
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 1rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        
        .user-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
            background: white;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .role-badge {
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-badge {
            border-radius: 25px;
            padding: 0.25rem 0.75rem;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            margin: 0 2px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
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
        
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .user-row {
                padding: 1rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stats-card primary p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Total Users</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['total_users'] ?? 0) }}</h3>
                        </div>
                        <div class="stat-icon primary">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stats-card danger p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Admins</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['admin_users'] ?? 0) }}</h3>
                        </div>
                        <div class="stat-icon danger">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stats-card info p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Sellers</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['sellers'] ?? 0) }}</h3>
                        </div>
                        <div class="stat-icon info">
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stats-card success p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Buyers</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['buyers'] ?? 0) }}</h3>
                        </div>
                        <div class="stat-icon success">
                            <i class="bi bi-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-sm-6">
                <div class="stats-card warning p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.8rem; letter-spacing: 0.5px;">Pending</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ number_format($stats['pending_approval'] ?? 0) }}</h3>
                        </div>
                        <div class="stat-icon warning">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card mb-4">
            <div class="card-header bg-transparent border-0 p-4">
                <h5 class="mb-0 fw-bold d-flex align-items-center">
                    <i class="bi bi-funnel me-2 text-primary"></i>
                    Filter Users
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-search me-1"></i>Search
                            </label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search users..." 
                                   class="form-control">
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-person-badge me-1"></i>Role
                            </label>
                            <select name="role" class="form-select">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }}>Seller</option>
                                <option value="buyer" {{ request('role') === 'buyer' ? 'selected' : '' }}>Buyer</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label fw-semibold text-muted small">
                                <i class="bi bi-toggle-on me-1"></i>Status
                            </label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="users-card">
            <div class="card-header bg-transparent border-0 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-people me-2 text-primary"></i>
                        Users Directory
                    </h5>
                    <div class="text-muted small">
                        {{ $users->total() ?? 0 }} total users
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @forelse($users as $user)
                    <div class="user-row">
                        <div class="d-flex align-items-start">
                            <div class="user-avatar me-4">
                                <i class="bi bi-person"></i>
                            </div>
                            
                            <div class="flex-fill">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <h5 class="mb-0 fw-bold text-dark">{{ $user->name }}</h5>
                                        <span class="role-badge 
                                            @if($user->role === 'admin') bg-danger text-white
                                            @elseif($user->role === 'seller') bg-info text-white
                                            @else bg-success text-white @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                        <span class="status-badge {{ $user->is_approved ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                                            {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                        @if($user->role === 'seller')
                                            <span class="status-badge {{ $user->isKycVerified() ? 'bg-primary text-white' : 'bg-secondary text-white' }}">
                                                KYC: {{ $user->isKycVerified() ? 'Verified' : 'Pending' }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="action-btn btn btn-outline-primary btn-sm" 
                                           title="View Details" data-bs-toggle="tooltip">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="action-btn btn btn-outline-info btn-sm" 
                                           title="Edit User" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->role === 'seller')
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="action-btn btn btn-outline-warning btn-sm" 
                                                        onclick="return confirm('Are you sure you want to toggle approval status?')"
                                                        title="Toggle Approval" data-bs-toggle="tooltip">
                                                    <i class="bi bi-check-circle{{ $user->is_approved ? '-fill' : '' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="action-btn btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                                        title="Delete User" data-bs-toggle="tooltip">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row g-4 small text-muted mb-3">
                                    <div class="col-md-4">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-envelope me-2 text-primary"></i>
                                            <strong>{{ $user->email }}</strong>
                                        </span>
                                    </div>
                                    @if($user->phone)
                                        <div class="col-md-3">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-telephone me-2 text-primary"></i>
                                                {{ $user->phone }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-calendar me-2 text-primary"></i>
                                            Joined: <strong class="ms-1">{{ $user->created_at->format('M d, Y') }}</strong>
                                        </span>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-clock me-2 text-primary"></i>
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($user->role === 'seller' && $user->business_name)
                                    <div class="pt-3 border-top">
                                        <span class="badge bg-light text-dark fw-semibold">
                                            <i class="bi bi-building me-1"></i>
                                            Business: {{ $user->business_name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mx-auto mb-4" style="max-width: 400px;">
                            <div class="user-avatar bg-light text-muted mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                <i class="bi bi-people"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No users found</h4>
                            <p class="text-muted mb-4">Try adjusting your search criteria or add new users to get started.</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add First User
                            </a>
                        </div>
                    </div>
                @endforelse
                
                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-center">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</x-app-layout>