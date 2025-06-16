<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\KycDocument;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is admin
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied');
        }
        
        \Log::info('Admin dashboard accessed by user: ' . auth()->user()->email);
        
        // Get statistics for dashboard
        try {
            $stats = [
                'total_users' => User::count(),
                'total_sellers' => User::where('role', 'seller')->count(),
                'total_buyers' => User::where('role', 'buyer')->count(),
                'pending_sellers' => 0, // Will be calculated from KYC documents
                
                'total_products' => Product::count(),
                'pending_products' => Product::where('status', 'pending')->count(),
                'approved_products' => Product::where('status', 'approved')->count(),
                
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'completed_orders' => Order::where('status', 'delivered')->count(),
                'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount') ?? 0,
                
                'pending_kyc' => KycDocument::pending()->count(),
                'approved_kyc_today' => KycDocument::approved()->whereDate('reviewed_at', today())->count(),
            ];
            
            // Calculate pending sellers from KYC documents
            $stats['pending_sellers'] = KycDocument::pending()
                ->whereHas('user', function($query) {
                    $query->where('role', 'seller');
                })
                ->distinct('user_id')
                ->count();
                
        } catch (\Exception $e) {
            // Fallback stats if database queries fail
            $stats = [
                'total_users' => 0,
                'total_sellers' => 0,
                'total_buyers' => 0,
                'pending_sellers' => 0,
                'total_products' => 0,
                'pending_products' => 0,
                'approved_products' => 0,
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_orders' => 0,
                'total_revenue' => 0,
                'pending_kyc' => 0,
                'approved_kyc_today' => 0,
            ];
        }

        try {
            $recentOrders = Order::with(['user', 'items'])
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $recentOrders = collect([]);
        }

        try {
            $pendingKyc = KycDocument::with('user')
                ->pending()
                ->latest()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $pendingKyc = collect([]);
        }

        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingKyc'));
    }
}
