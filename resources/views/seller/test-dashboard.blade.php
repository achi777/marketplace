<!DOCTYPE html>
<html>
<head>
    <title>Test Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Seller Dashboard</h1>
        <p>This is a test page to check if the issue is with the view or controller.</p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Test Card</h5>
                        <p>If you see this, the basic view rendering works.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        
        <h3>Debug Info</h3>
        <p><strong>User:</strong> {{ auth()->user()->name ?? 'Not authenticated' }}</p>
        <p><strong>Role:</strong> {{ auth()->user()->role ?? 'No role' }}</p>
        <p><strong>Current URL:</strong> {{ request()->url() }}</p>
        
        @if(isset($stats))
            <h4>Stats Data:</h4>
            <pre>{{ json_encode($stats, JSON_PRETTY_PRINT) }}</pre>
        @endif
        
        @if(isset($recentOrders))
            <h4>Recent Orders Count:</h4>
            <p>{{ $recentOrders->count() }}</p>
        @endif
        
        @if(isset($recentProducts))
            <h4>Recent Products Count:</h4>
            <p>{{ $recentProducts->count() }}</p>
        @endif
    </div>
</body>
</html>