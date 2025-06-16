<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    private function checkSellerAccess()
    {
        if (!auth()->check() || auth()->user()->role !== 'seller') {
            abort(403, 'Access denied');
        }
        
        if (!auth()->user()->is_approved) {
            return redirect()->route('seller.dashboard')
                ->with('error', 'Your seller account is pending approval. You cannot manage products yet.');
        }
    }

    public function index(Request $request)
    {
        \Log::info('Seller ProductController index method called');
        try {
            $accessCheck = $this->checkSellerAccess();
            if ($accessCheck) return $accessCheck;
            
            $sellerId = auth()->id();
            
            // Build query using raw database queries to avoid Eloquent issues
            $query = \DB::table('products')->where('seller_id', $sellerId);
            
            // Apply filters
            if ($request->filled('search')) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', $searchTerm)
                      ->orWhere('sku', 'like', $searchTerm)
                      ->orWhere('short_description', 'like', $searchTerm);
                });
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            
            // Get products with raw query
            $rawProducts = $query->select(['id', 'name', 'sku', 'status', 'images', 'category_id', 'created_at'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            \Log::info('Products found: ' . $rawProducts->count());
            
            // Convert to array format and add minimum prices
            $products = [];
            foreach ($rawProducts as $product) {
                // Get minimum price from variations
                $minPrice = \DB::table('product_variations')
                    ->where('product_id', $product->id)
                    ->min('price');
                
                // Parse images JSON
                $images = null;
                if ($product->images) {
                    $imagesArray = json_decode($product->images, true);
                    $images = is_array($imagesArray) && count($imagesArray) > 0 ? $imagesArray[0] : null;
                }
                
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name ?? 'No name',
                    'sku' => $product->sku ?? 'N/A',
                    'status' => $product->status ?? 'unknown',
                    'category_id' => $product->category_id,
                    'image' => $images,
                    'min_price' => $minPrice,
                    'created_at' => $product->created_at
                ];
            }
            
            // Get categories for filter dropdown
            $categories = \DB::table('categories')
                ->select(['id', 'name'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            
            return view('seller.products.beautiful-index', compact('products', 'categories'));
            
        } catch (\Exception $e) {
            \Log::error('Error in Seller ProductController index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function create()
    {
        $accessCheck = $this->checkSellerAccess();
        if ($accessCheck) return $accessCheck;
        
        $categories = Category::active()->orderBy('name')->get();
        
        return view('seller.products.create', compact('categories'));
    }

    public function getCategoryAttributes(Category $category)
    {
        $accessCheck = $this->checkSellerAccess();
        if ($accessCheck) return $accessCheck;
        
        $attributes = $category->attributes()->where('is_active', true)->orderBy('sort_order')->get();
        
        return response()->json($attributes->map(function($attr) {
            return [
                'id' => $attr->id,
                'name' => $attr->name,
                'type' => $attr->type,
                'options' => $attr->options,
                'is_required' => $attr->is_required,
                'is_active' => $attr->is_active,
            ];
        }));
    }

    public function store(Request $request)
    {
        $accessCheck = $this->checkSellerAccess();
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
        $data['seller_id'] = auth()->id();
        $data['status'] = 'pending'; // Always pending for seller-created products
        
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
        
        // Handle product attribute values
        $category = Category::find($data['category_id']);
        if ($category) {
            $categoryAttributes = $category->attributes()->active()->get();
            
            foreach ($categoryAttributes as $attribute) {
                $attributeKey = 'attribute_' . $attribute->id;
                if ($request->has($attributeKey)) {
                    $value = $request->get($attributeKey);
                    
                    // Handle different attribute types
                    if (in_array($attribute->type, ['multiselect', 'checkbox']) && is_array($value)) {
                        $value = json_encode($value);
                    } elseif ($attribute->type === 'boolean') {
                        $value = $value ? '1' : '0';
                    }
                    
                    // Only create if value is not empty
                    if (!empty($value) || $value === '0') {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'product_attribute_id' => $attribute->id,
                            'value' => $value,
                        ]);
                    }
                }
            }
        }
        
        // Create variations
        foreach ($request->variations as $variationData) {
            $variationData['product_id'] = $product->id;
            
            // Process attributes - handle checkbox arrays and clean empty values
            if (isset($variationData['attributes'])) {
                $processedAttributes = [];
                foreach ($variationData['attributes'] as $key => $value) {
                    if (is_array($value)) {
                        // For checkboxes, join array values
                        if (!empty($value)) {
                            $processedAttributes[$key] = implode(', ', $value);
                        }
                    } elseif ($value !== null && $value !== '') {
                        $processedAttributes[$key] = $value;
                    }
                }
                $variationData['attributes'] = !empty($processedAttributes) ? $processedAttributes : null;
            }
            
            ProductVariation::create($variationData);
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully and submitted for review.');
    }

    public function show($productId)
    {
        try {
            $accessCheck = $this->checkSellerAccess();
            if ($accessCheck) return $accessCheck;
            
            // Get product with raw query
            $product = \DB::table('products')
                ->select(['id', 'name', 'sku', 'status', 'short_description', 'description', 'images', 'category_id', 'seller_id', 'created_at', 'rejection_reason'])
                ->where('id', $productId)
                ->first();
            
            if (!$product) {
                abort(404, 'Product not found.');
            }
            
            // Check if product belongs to current seller
            if ($product->seller_id !== auth()->id()) {
                abort(403, 'You can only view your own products.');
            }
            
            // Get category name
            $category = \DB::table('categories')
                ->select(['name'])
                ->where('id', $product->category_id)
                ->first();
            
            // Get variations with raw query
            $variations = \DB::table('product_variations')
                ->select(['id', 'sku', 'price', 'compare_price', 'stock_quantity', 'attributes', 'is_active', 'image'])
                ->where('product_id', $productId)
                ->get();
            
            // Parse images JSON
            $images = [];
            if ($product->images) {
                $imagesArray = json_decode($product->images, true);
                $images = is_array($imagesArray) ? $imagesArray : [];
            }
            
            // Convert to array format
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'status' => $product->status,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'images' => $images,
                'category_name' => $category ? $category->name : 'Unknown Category',
                'created_at' => $product->created_at,
                'rejection_reason' => $product->rejection_reason
            ];
            
            // Convert variations to array
            $variationsData = [];
            foreach ($variations as $variation) {
                $attributes = [];
                if ($variation->attributes) {
                    $attributes = json_decode($variation->attributes, true) ?: [];
                }
                
                $variationsData[] = [
                    'id' => $variation->id,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'compare_price' => $variation->compare_price,
                    'stock_quantity' => $variation->stock_quantity,
                    'attributes' => $attributes,
                    'is_active' => $variation->is_active,
                    'image' => $variation->image
                ];
            }
            
            return view('seller.products.beautiful-show', compact('productData', 'variationsData'));
            
        } catch (\Exception $e) {
            \Log::error('Error in Seller ProductController show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function edit($productId)
    {
        try {
            $accessCheck = $this->checkSellerAccess();
            if ($accessCheck) return $accessCheck;
            
            // Get product with raw query
            $product = \DB::table('products')
                ->select(['id', 'name', 'sku', 'status', 'short_description', 'description', 'images', 'category_id', 'seller_id', 'slug', 'weight', 'dimensions', 'created_at', 'rejection_reason'])
                ->where('id', $productId)
                ->first();
            
            if (!$product) {
                abort(404, 'Product not found.');
            }
            
            // Check if product belongs to current seller
            if ($product->seller_id !== auth()->id()) {
                abort(403, 'You can only edit your own products.');
            }
            
            // Check if product can be edited
            if ($product->status === 'approved') {
                return redirect()->route('seller.products.show', $productId)
                    ->with('error', 'Approved products cannot be edited. Contact admin for changes.');
            }
            
            // Get variations with raw query
            $variations = \DB::table('product_variations')
                ->select(['id', 'sku', 'price', 'compare_price', 'stock_quantity', 'low_stock_threshold', 'attributes', 'is_active', 'image'])
                ->where('product_id', $productId)
                ->get();
            
            // Get categories
            $categories = \DB::table('categories')
                ->select(['id', 'name', 'slug'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            
            // Parse images JSON
            $images = [];
            if ($product->images) {
                $imagesArray = json_decode($product->images, true);
                $images = is_array($imagesArray) ? $imagesArray : [];
            }
            
            // Convert to array format
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'status' => $product->status,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'images' => $images,
                'category_id' => $product->category_id,
                'seller_id' => $product->seller_id,
                'slug' => $product->slug,
                'weight' => $product->weight,
                'dimensions' => $product->dimensions,
                'created_at' => $product->created_at,
                'rejection_reason' => $product->rejection_reason
            ];
            
            // Convert variations to array
            $variationsData = [];
            foreach ($variations as $variation) {
                $attributes = [];
                if ($variation->attributes) {
                    $attributes = json_decode($variation->attributes, true) ?: [];
                }
                
                $variationsData[] = [
                    'id' => $variation->id,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'compare_price' => $variation->compare_price,
                    'stock_quantity' => $variation->stock_quantity,
                    'low_stock_threshold' => $variation->low_stock_threshold,
                    'attributes' => $attributes,
                    'is_active' => $variation->is_active,
                    'image' => $variation->image
                ];
            }
            
            return view('seller.products.edit', compact('productData', 'variationsData', 'categories'));
            
        } catch (\Exception $e) {
            \Log::error('Error in Seller ProductController edit: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        $accessCheck = $this->checkSellerAccess();
        if ($accessCheck) return $accessCheck;
        
        // Check if product belongs to current seller
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'You can only edit your own products.');
        }
        
        // Check if product can be edited
        if ($product->status === 'approved') {
            return redirect()->route('seller.products.show', $product)
                ->with('error', 'Approved products cannot be edited. Contact admin for changes.');
        }
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Reset status to pending when edited
        $data['status'] = 'pending';
        $data['rejection_reason'] = null;
        
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

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully and resubmitted for review.');
    }

    public function destroy(Product $product)
    {
        $this->checkSellerAccess();
        
        // Check if product belongs to current seller
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'You can only delete your own products.');
        }
        
        // Check if product has orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('seller.products.index')
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

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function variations($productId)
    {
        try {
            $accessCheck = $this->checkSellerAccess();
            if ($accessCheck) return $accessCheck;
            
            // Get product with raw query
            $product = \DB::table('products')
                ->select(['id', 'name', 'sku', 'status', 'category_id', 'seller_id'])
                ->where('id', $productId)
                ->first();
            
            if (!$product) {
                abort(404, 'Product not found.');
            }
            
            // Check if product belongs to current seller
            if ($product->seller_id !== auth()->id()) {
                abort(403, 'You can only manage your own product variations.');
            }
            
            // Get variations with raw query
            $variations = \DB::table('product_variations')
                ->select(['id', 'sku', 'price', 'compare_price', 'stock_quantity', 'low_stock_threshold', 'attributes', 'is_active', 'image', 'created_at'])
                ->where('product_id', $productId)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Get category attributes
            $categoryAttributes = \DB::table('product_attributes')
                ->select(['id', 'name', 'type', 'options', 'is_required'])
                ->where('category_id', $product->category_id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
            
            // Convert to arrays
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'status' => $product->status,
                'category_id' => $product->category_id,
                'seller_id' => $product->seller_id
            ];
            
            $variationsData = [];
            foreach ($variations as $variation) {
                $attributes = [];
                if ($variation->attributes) {
                    $attributes = json_decode($variation->attributes, true) ?: [];
                }
                
                $variationsData[] = [
                    'id' => $variation->id,
                    'sku' => $variation->sku,
                    'price' => $variation->price,
                    'compare_price' => $variation->compare_price,
                    'stock_quantity' => $variation->stock_quantity,
                    'low_stock_threshold' => $variation->low_stock_threshold,
                    'attributes' => $attributes,
                    'is_active' => $variation->is_active,
                    'image' => $variation->image,
                    'created_at' => $variation->created_at
                ];
            }
            
            $attributesData = [];
            foreach ($categoryAttributes as $attr) {
                $options = [];
                if ($attr->options) {
                    $options = json_decode($attr->options, true) ?: [];
                }
                
                $attributesData[] = [
                    'id' => $attr->id,
                    'name' => $attr->name,
                    'type' => $attr->type,
                    'options' => $options,
                    'is_required' => $attr->is_required
                ];
            }
            
            return view('seller.products.beautiful-variations', compact('productData', 'variationsData', 'attributesData'));
            
        } catch (\Exception $e) {
            \Log::error('Error in Seller ProductController variations: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function storeVariation(Request $request, Product $product)
    {
        $accessCheck = $this->checkSellerAccess();
        if ($accessCheck) return $accessCheck;
        
        // Check if product belongs to current seller
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'You can only manage your own product variations.');
        }
        
        $request->validate([
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'attributes' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except(['image']);
        $data['product_id'] = $product->id;
        $data['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('products/variations', $imageName, 'public');
            $data['image'] = $imagePath;
        }
        
        // Process attributes - handle checkbox arrays
        if ($request->has('attributes')) {
            $processedAttributes = [];
            foreach ($request->attributes as $key => $value) {
                if (is_array($value)) {
                    // For checkboxes, join array values
                    $processedAttributes[$key] = implode(', ', $value);
                } else {
                    $processedAttributes[$key] = $value;
                }
            }
            $data['attributes'] = $processedAttributes;
        }
        
        ProductVariation::create($data);

        return redirect()->route('seller.products.variations', $product)
            ->with('success', 'Variation added successfully.');
    }

    public function analytics()
    {
        $this->checkSellerAccess();
        
        $sellerId = auth()->id();
        
        $stats = [
            'total_products' => Product::where('seller_id', $sellerId)->count(),
            'pending_products' => Product::where('seller_id', $sellerId)->where('status', 'pending')->count(),
            'approved_products' => Product::where('seller_id', $sellerId)->where('status', 'approved')->count(),
            'rejected_products' => Product::where('seller_id', $sellerId)->where('status', 'rejected')->count(),
            'total_stock' => ProductVariation::whereHas('product', function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })->sum('stock_quantity'),
            'low_stock_items' => ProductVariation::whereHas('product', function($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count(),
        ];
        
        return view('seller.products.analytics', compact('stats'));
    }
}