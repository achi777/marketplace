<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function checkAdminAccess()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Access denied');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = User::with(['kycDocuments']);
        
        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        // Filter by KYC status
        if ($request->filled('kyc_status')) {
            // This would need to be implemented based on KYC logic
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get counts for statistics
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'pending_approval' => User::where('is_approved', false)->where('role', 'seller')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,seller,buyer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_approved' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_approved' => $request->boolean('is_approved'),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $this->checkAdminAccess();
        
        $user->load(['kycDocuments', 'products', 'orders']);
        
        // Get user statistics
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('status', '!=', 'cancelled')->sum('total_amount'),
            'total_products' => $user->products()->count(),
            'active_products' => $user->products()->where('status', 'approved')->count(),
        ];
        
        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $this->checkAdminAccess();
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,seller,buyer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'is_approved' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_approved' => $request->boolean('is_approved'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->checkAdminAccess();
        
        // Prevent deletion of the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Check if user has orders
        if ($user->orders()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete user with existing orders.');
        }
        
        // Check if seller has products
        if ($user->role === 'seller' && $user->products()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete seller with existing products.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function approve(User $user)
    {
        $this->checkAdminAccess();
        
        $user->update(['is_approved' => true]);
        
        return redirect()->back()
            ->with('success', 'User approved successfully.');
    }

    public function reject(User $user)
    {
        $this->checkAdminAccess();
        
        $user->update(['is_approved' => false]);
        
        return redirect()->back()
            ->with('success', 'User approval revoked.');
    }

    public function toggleStatus(User $user)
    {
        $this->checkAdminAccess();
        
        $user->update(['is_approved' => !$user->is_approved]);
        
        $status = $user->is_approved ? 'approved' : 'rejected';
        return redirect()->back()
            ->with('success', "User {$status} successfully.");
    }
}
