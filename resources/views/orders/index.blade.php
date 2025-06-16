@extends('layouts.app')

@section('title', 'My Orders - Marketplace')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="h3 mb-1 fw-bold text-dark">
                <i class="bi bi-bag me-2 text-primary"></i>{{ __('My Orders') }}
            </h2>
            <p class="text-muted small mb-0">View and track your order history</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="container py-4">
        @if(count($orders) > 0)
            <div class="row">
                <div class="col-12">
                    @foreach($orders as $order)
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-0 py-3">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h6 class="mb-0 fw-bold">Order #{{ $order['id'] }}</h6>
                                        <small class="text-muted">{{ date('M d, Y', strtotime($order['created_at'])) }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info', 
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusColor = $statusColors[$order['status']] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} px-3 py-2">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted small">{{ $order['items_count'] }} item(s)</div>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="fw-bold text-success fs-5">${{ number_format($order['total_amount'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            Last updated: {{ date('M d, Y H:i', strtotime($order['updated_at'])) }}
                                        </small>
                                    </div>
                                    <div>
                                        <a href="/orders/{{ $order['id'] }}" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                        @if($order['status'] === 'shipped')
                                            <a href="/orders/{{ $order['id'] }}/tracking" class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-truck me-1"></i>Track Order
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-bag display-1 text-muted"></i>
                </div>
                <h3 class="fw-bold text-dark mb-3">No Orders Yet</h3>
                <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="/" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        @endif
    </div>
@endsection