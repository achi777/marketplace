@extends('layouts.app')

@section('title', 'Profile - Marketplace')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .profile-hero {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        margin-top: -50px;
        position: relative;
        z-index: 10;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        margin: -60px auto 0;
        position: relative;
    }
    
    .profile-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .profile-card .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-danger-gradient {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-danger-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 107, 107, 0.4);
        color: white;
    }
    
    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
    }
    
    .stats-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.5rem;
    }
    
    .breadcrumb-custom {
        background: transparent;
        padding: 1rem 0;
    }
    
    .breadcrumb-custom .breadcrumb-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
    }
    
    .breadcrumb-custom .breadcrumb-item.active {
        color: white;
    }
    
    .page-title {
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('header')
    <div class="bg-transparent">
        <div class="container">
            <nav class="breadcrumb-custom">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house me-1"></i>Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
            <div class="text-center py-5">
                <h1 class="page-title display-4 fw-bold mb-3">
                    <i class="bi bi-person-circle me-3"></i>My Profile
                </h1>
                <p class="text-white-50 fs-5">Manage your account settings and preferences</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container pb-5">
        <!-- Profile Hero Card -->
        <div class="profile-hero p-5 mb-5">
            <div class="profile-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="text-center mt-4">
                <h2 class="fw-bold mb-2">{{ Auth::user()->name }}</h2>
                <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                <span class="badge bg-primary rounded-pill px-3 py-2">
                    <i class="bi bi-star-fill me-1"></i>{{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
            
            <!-- Quick Stats -->
            <div class="row mt-5 g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Member Since</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->created_at->format('M Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Account Status</h6>
                        <p class="text-success mb-0 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i>Verified
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="bi bi-activity"></i>
                        </div>
                        <h6 class="fw-bold text-dark">Last Login</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Forms -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-lines-fill me-2"></i>Profile Information
                        </h5>
                        <p class="mb-0 opacity-75">Update your account's profile information and email address.</p>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="profile-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-shield-lock me-2"></i>Change Password
                        </h5>
                        <p class="mb-0 opacity-75">Keep your account secure.</p>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="profile-card">
                    <div class="card-header bg-danger">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
                        </h5>
                        <p class="mb-0 text-white opacity-75">Permanently delete your account.</p>
                    </div>
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
