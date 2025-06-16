<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            \Log::info('ProductController index method called');
            
            // Get query parameters
            $categoryId = $request->get('category');
            $sort = $request->get('sort', 'featured');
            $search = $request->get('search');
            
            // Build base query using raw database queries
            $query = \DB::table('products')
                ->select([
                    'products.id',
                    'products.name',
                    'products.short_description',
                    'products.images',
                    'products.category_id',
                    'products.is_featured',
                    'categories.name as category_name'
                ])
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.status', 'approved')
                ->where('categories.is_active', true);
            
            // Apply category filter
            if ($categoryId) {
                $query->where('products.category_id', $categoryId);
            }
            
            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('products.name', 'like', '%' . $search . '%')
                      ->orWhere('products.short_description', 'like', '%' . $search . '%');
                });
            }
            
            // Apply sorting
            switch ($sort) {
                case 'price_low':
                    $query->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                          ->groupBy('products.id', 'products.name', 'products.short_description', 'products.images', 'products.category_id', 'products.is_featured', 'categories.name')
                          ->orderBy(\DB::raw('MIN(product_variations.price)'), 'asc');
                    break;
                case 'price_high':
                    $query->leftJoin('product_variations', 'products.id', '=', 'product_variations.product_id')
                          ->groupBy('products.id', 'products.name', 'products.short_description', 'products.images', 'products.category_id', 'products.is_featured', 'categories.name')
                          ->orderBy(\DB::raw('MIN(product_variations.price)'), 'desc');
                    break;
                case 'newest':
                    $query->orderBy('products.created_at', 'desc');
                    break;
                case 'name':
                    $query->orderBy('products.name', 'asc');
                    break;
                default: // featured
                    $query->orderBy('products.is_featured', 'desc')
                          ->orderBy('products.created_at', 'desc');
            }
            
            // Get paginated results
            $page = $request->get('page', 1);
            $perPage = 12;
            $offset = ($page - 1) * $perPage;
            
            $totalCount = $query->count();
            $rawProducts = $query->offset($offset)->limit($perPage)->get();
            
            // Process products to add prices and images
            $products = [];
            foreach ($rawProducts as $product) {
                // Get minimum price from variations
                $minPrice = \DB::table('product_variations')
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->min('price');
                
                // Parse images JSON
                $images = [];
                if ($product->images) {
                    $imagesArray = json_decode($product->images, true);
                    $images = is_array($imagesArray) ? $imagesArray : [];
                }
                
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'short_description' => $product->short_description,
                    'category_name' => $product->category_name,
                    'category_id' => $product->category_id,
                    'is_featured' => $product->is_featured,
                    'images' => $images,
                    'main_image' => count($images) > 0 ? $images[0] : null,
                    'min_price' => $minPrice
                ];
            }
            
            // Get categories for filter
            $categories = \DB::table('categories')
                ->select(['id', 'name', 'slug'])
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            
            // Simple pagination data
            $pagination = [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $totalCount,
                'last_page' => ceil($totalCount / $perPage),
                'has_more_pages' => $page < ceil($totalCount / $perPage),
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < ceil($totalCount / $perPage) ? $page + 1 : null
            ];
            
            return view('products.index', compact('products', 'categories', 'categoryId', 'sort', 'search', 'pagination'));
            
        } catch (\Exception $e) {
            \Log::error('Error in ProductController index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function show($productId)
    {
        try {
            // Get product with raw query
            $product = \DB::table('products')
                ->select(['id', 'name', 'sku', 'status', 'short_description', 'description', 'images', 'category_id', 'seller_id', 'created_at'])
                ->where('id', $productId)
                ->first();
            
            if (!$product || $product->status !== 'approved') {
                abort(404, 'Product not found.');
            }
            
            // Get category name
            $category = \DB::table('categories')
                ->select(['name', 'slug'])
                ->where('id', $product->category_id)
                ->first();
            
            // Get seller name
            $seller = \DB::table('users')
                ->select(['name'])
                ->where('id', $product->seller_id)
                ->first();
            
            // Get active variations
            $variations = \DB::table('product_variations')
                ->select(['id', 'sku', 'price', 'compare_price', 'stock_quantity', 'attributes', 'image'])
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->orderBy('price')
                ->get();
            
            // Parse images JSON
            $images = [];
            if ($product->images) {
                $imagesArray = json_decode($product->images, true);
                $images = is_array($imagesArray) ? $imagesArray : [];
            }
            
            // Get related products from same category
            $relatedProductsRaw = \DB::table('products')
                ->select(['id', 'name', 'images'])
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'approved')
                ->inRandomOrder()
                ->limit(4)
                ->get();
            
            $relatedProducts = [];
            foreach ($relatedProductsRaw as $relatedProduct) {
                // Get min price for related product
                $minPrice = \DB::table('product_variations')
                    ->where('product_id', $relatedProduct->id)
                    ->where('is_active', true)
                    ->min('price');
                
                // Parse images
                $relatedImages = [];
                if ($relatedProduct->images) {
                    $relatedImagesArray = json_decode($relatedProduct->images, true);
                    $relatedImages = is_array($relatedImagesArray) ? $relatedImagesArray : [];
                }
                
                $relatedProducts[] = [
                    'id' => $relatedProduct->id,
                    'name' => $relatedProduct->name,
                    'images' => $relatedImages,
                    'min_price' => $minPrice
                ];
            }
            
            // Convert to array format
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'short_description' => $product->short_description,
                'description' => $product->description,
                'images' => $images,
                'category_name' => $category ? $category->name : 'Unknown Category',
                'seller_name' => $seller ? $seller->name : 'Unknown Seller',
                'created_at' => $product->created_at
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
                    'image' => $variation->image
                ];
            }
            
            return view('products.show-simple', compact('productData', 'variationsData', 'relatedProducts', 'images'));
            
        } catch (\Exception $e) {
            \Log::error('Error in ProductController show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
    
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category');
        $sort = $request->get('sort', 'relevance');
        
        $products = Product::approved()
            ->with(['variations', 'seller', 'category']);
        
        // Apply search query
        if (!empty($query)) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('short_description', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhere('sku', 'like', '%' . $query . '%');
            });
        }
        
        // Apply category filter
        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $products->join('product_variations', 'products.id', '=', 'product_variations.product_id')
                        ->groupBy('products.id')
                        ->orderBy('product_variations.price', 'asc');
                break;
            case 'price_high':
                $products->join('product_variations', 'products.id', '=', 'product_variations.product_id')
                        ->groupBy('products.id')
                        ->orderBy('product_variations.price', 'desc');
                break;
            case 'newest':
                $products->latest();
                break;
            case 'name':
                $products->orderBy('name', 'asc');
                break;
            default:
                $products->orderBy('is_featured', 'desc')->latest();
        }
        
        $products = $products->paginate(12)->appends($request->query());
        $categories = Category::active()->orderBy('name')->get();
        
        return view('products.search', compact('products', 'categories', 'query', 'categoryId', 'sort'));
    }
}