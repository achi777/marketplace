<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .auth-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            
            .auth-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            }
            
            @media (max-width: 640px) {
                .auth-card {
                    margin: 1rem;
                    border-radius: 1rem;
                }
                
                .auth-container {
                    padding: 1rem 0;
                }
            }
            
            .form-input {
                transition: all 0.3s ease;
                border: 2px solid #e5e7eb;
            }
            
            .form-input:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                transform: translateY(-2px);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-container flex flex-col justify-center items-center px-4 py-8">
            <!-- Logo and Welcome Section -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-4 mx-auto">
                        <i class="bi bi-shop text-2xl sm:text-3xl text-indigo-600"></i>
                    </div>
                </a>
                <h1 class="text-white text-2xl sm:text-3xl font-bold mb-2">{{ config('app.name', 'Marketplace') }}</h1>
                <p class="text-white/80 text-sm sm:text-base">Welcome to our marketplace platform</p>
            </div>

            <!-- Auth Card -->
            <div class="w-full max-w-sm sm:max-w-md auth-card rounded-2xl p-6 sm:p-8">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-8 text-white/60 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Marketplace') }}. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
