<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends Controller
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
        
        $query = Product::with(['seller', 'category', 'variations']);
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('seller')) {
            $query->where('seller_id', $request->seller);
        }
        
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }
        
        $products = $query->withCount('variations')
                         ->latest()
                         ->paginate(15);
        
        // Get filter options
        $categories = Category::orderBy('name')->get();
        $sellers = User::where('role', 'seller')->orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'categories', 'sellers'));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        $categories = Category::active()->orderBy('name')->get();
        $sellers = User::where('role', 'seller')->where('is_approved', true)->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'sellers'));
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,approved,rejected',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            // Variations
            'variations' => 'required|array|min:1',
            'variations.*.price' => 'required|numeric|min:0',
            'variations.*.compare_price' => 'nullable|numeric|min:0',
            'variations.*.stock_quantity' => 'required|integer|min:0',
            'variations.*.low_stock_threshold' => 'nullable|integer|min:0',
            'variations.*.attributes' => 'nullable|array',
            'variations.*.is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Handle image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        // Create product
        $product = Product::create($data);
        
        // Create variations
        foreach ($request->variations as $variationData) {
            $variationData['product_id'] = $product->id;
            
            // Handle attributes JSON
            if (isset($variationData['attributes']) && is_string($variationData['attributes'])) {
                $variationData['attributes'] = json_decode($variationData['attributes'], true);
            }
            
            ProductVariation::create($variationData);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $this->checkAdminAccess();
        
        $product->load(['seller', 'category', 'variations', 'orderItems.order']);
        
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $this->checkAdminAccess();
        
        $product->load('variations');
        $categories = Category::active()->orderBy('name')->get();
        $sellers = User::where('role', 'seller')->where('is_approved', true)->orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'sellers'));
    }

    public function update(Request $request, Product $product)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string|max:1000',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->images) {
                foreach ($product->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = $path;
            }
            $data['images'] = $images;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->checkAdminAccess();
        
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product with existing orders.');
        }
        
        // Delete images
        if ($product->images) {
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }
        
        // Delete variations
        $product->variations()->delete();
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function approve(Product $product)
    {
        $this->checkAdminAccess();
        
        $product->update([
            'status' => 'approved',
            'rejection_reason' => null
        ]);
        
        return redirect()->back()
            ->with('success', 'Product approved successfully.');
    }

    public function reject(Request $request, Product $product)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);
        
        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);
        
        return redirect()->back()
            ->with('success', 'Product rejected successfully.');
    }

    public function toggleFeatured(Product $product)
    {
        $this->checkAdminAccess();
        
        $product->update(['is_featured' => !$product->is_featured]);
        
        $status = $product->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()
            ->with('success', "Product {$status} successfully.");
    }

    public function bulkAction(Request $request)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'action' => 'required|in:approve,reject,delete,feature,unfeature',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'rejection_reason' => 'required_if:action,reject|string|max:1000'
        ]);
        
        $products = Product::whereIn('id', $request->product_ids);
        
        switch ($request->action) {
            case 'approve':
                $products->update(['status' => 'approved', 'rejection_reason' => null]);
                $message = 'Products approved successfully.';
                break;
            case 'reject':
                $products->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);
                $message = 'Products rejected successfully.';
                break;
            case 'feature':
                $products->update(['is_featured' => true]);
                $message = 'Products featured successfully.';
                break;
            case 'unfeature':
                $products->update(['is_featured' => false]);
                $message = 'Products unfeatured successfully.';
                break;
            case 'delete':
                // Check for orders
                $productsWithOrders = $products->whereHas('orderItems')->count();
                if ($productsWithOrders > 0) {
                    return redirect()->back()
                        ->with('error', 'Cannot delete products with existing orders.');
                }
                
                // Delete images and variations
                foreach ($products->get() as $product) {
                    if ($product->images) {
                        foreach ($product->images as $image) {
                            if (Storage::disk('public')->exists($image)) {
                                Storage::disk('public')->delete($image);
                            }
                        }
                    }
                    $product->variations()->delete();
                }
                
                $products->delete();
                $message = 'Products deleted successfully.';
                break;
        }
        
        return redirect()->back()->with('success', $message);
    }
}