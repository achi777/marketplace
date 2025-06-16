<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Seller Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Welcome to Seller Dashboard</h1>
                    
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_products'] ?? 0 }}</h3>
                                    <p>Total Products</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['active_products'] ?? 0 }}</h3>
                                    <p>Active Products</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_orders'] ?? 0 }}</h3>
                                    <p>Total Orders</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h3>${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                                    <p>Revenue</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <h4>Recent Orders</h4>
                            @if($recentOrders && $recentOrders->count() > 0)
                                <ul class="list-group">
                                    @foreach($recentOrders as $order)
                                        <li class="list-group-item">
                                            Order #{{ $order['id'] }} - {{ $order['customer_name'] }} - ${{ $order['total_amount'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No orders yet</p>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Recent Products</h4>
                            @if($recentProducts && $recentProducts->count() > 0)
                                <ul class="list-group">
                                    @foreach($recentProducts as $product)
                                        <li class="list-group-item">
                                            {{ $product['name'] }} - {{ $product['status'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No products yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>