<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Navigation -->
                        <x-dropdown align="bottom" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <span>{{ __('Admin') }}</span>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('admin.categories.index')">
                                    <i class="bi bi-folder2-open mr-2"></i>{{ __('Categories') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.products.index')">
                                    <i class="bi bi-box-seam mr-2"></i>{{ __('Products') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.attributes.index')">
                                    <i class="bi bi-tags mr-2"></i>{{ __('Attributes') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.users.index')">
                                    <i class="bi bi-people mr-2"></i>{{ __('Users') }}
                                </x-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                                <x-dropdown-link :href="route('admin.kyc.index')">
                                    <i class="bi bi-file-earmark-check mr-2"></i>{{ __('KYC Review') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif
                    
                    @if(auth()->user()->role === 'seller')
                        <!-- Seller Navigation -->
                        <x-dropdown align="bottom" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <span>{{ __('Seller') }}</span>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('seller.dashboard')">
                                    <i class="bi bi-speedometer2 mr-2"></i>{{ __('Dashboard') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('seller.products.index')">
                                    <i class="bi bi-box-seam mr-2"></i>{{ __('My Products') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('seller.products.create')">
                                    <i class="bi bi-plus-circle mr-2"></i>{{ __('Add Product') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('seller.analytics')">
                                    <i class="bi bi-graph-up mr-2"></i>{{ __('Analytics') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif
                    
                    @if(auth()->user()->role === 'buyer')
                        <!-- Buyer Navigation -->
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            {{ __('My Orders') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                            {{ __('Cart') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->role === 'admin')
                <!-- Admin Mobile Navigation -->
                <div class="border-t border-gray-200 mt-3 pt-3">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin Panel</div>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        <i class="bi bi-folder2-open mr-2"></i>{{ __('Categories') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        <i class="bi bi-box-seam mr-2"></i>{{ __('Products') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.attributes.index')" :active="request()->routeIs('admin.attributes.*')">
                        <i class="bi bi-tags mr-2"></i>{{ __('Attributes') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <i class="bi bi-people mr-2"></i>{{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.kyc.index')" :active="request()->routeIs('admin.kyc.*')">
                        <i class="bi bi-file-earmark-check mr-2"></i>{{ __('KYC Review') }}
                    </x-responsive-nav-link>
                </div>
            @endif
            
            @if(auth()->user()->role === 'seller')
                <!-- Seller Mobile Navigation -->
                <div class="border-t border-gray-200 mt-3 pt-3">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Seller Panel</div>
                    <x-responsive-nav-link :href="route('seller.dashboard')" :active="request()->routeIs('seller.dashboard')">
                        <i class="bi bi-speedometer2 mr-2"></i>{{ __('Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('seller.products.index')" :active="request()->routeIs('seller.products.*')">
                        <i class="bi bi-box-seam mr-2"></i>{{ __('My Products') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('seller.products.create')" :active="request()->routeIs('seller.products.create')">
                        <i class="bi bi-plus-circle mr-2"></i>{{ __('Add Product') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('seller.analytics')" :active="request()->routeIs('seller.analytics')">
                        <i class="bi bi-graph-up mr-2"></i>{{ __('Analytics') }}
                    </x-responsive-nav-link>
                </div>
            @endif
            
            @if(auth()->user()->role === 'buyer')
                <!-- Buyer Mobile Navigation -->
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.*')">
                    {{ __('Cart') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
