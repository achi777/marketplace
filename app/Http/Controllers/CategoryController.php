<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            \Log::info('CategoryController index method called');
            
            // Get main categories (parent categories)
            $mainCategories = \DB::table('categories')
                ->select(['id', 'name', 'slug', 'description', 'image'])
                ->where('parent_id', null)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            
            // Get all categories for navigation
            $allCategories = \DB::table('categories')
                ->select(['id', 'name', 'slug', 'parent_id'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            
            // Build category tree for navigation
            $categoryTree = [];
            foreach ($allCategories as $category) {
                if ($category->parent_id === null) {
                    $categoryTree[$category->id] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'children' => []
                    ];
                }
            }
            
            // Add children to parent categories
            foreach ($allCategories as $category) {
                if ($category->parent_id !== null && isset($categoryTree[$category->parent_id])) {
                    $categoryTree[$category->parent_id]['children'][] = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug
                    ];
                }
            }
            
            // Get product counts for each main category
            $categoriesWithCounts = [];
            foreach ($mainCategories as $category) {
                $productCount = \DB::table('products')
                    ->where('category_id', $category->id)
                    ->where('status', 'approved')
                    ->count();
                
                $categoriesWithCounts[] = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image,
                    'product_count' => $productCount
                ];
            }
            
            return view('categories.index-modern', compact('categoriesWithCounts', 'categoryTree'));
            
        } catch (\Exception $e) {
            \Log::error('Error in CategoryController index: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }

    public function show($categorySlug, Request $request)
    {
        try {
            \Log::info('CategoryController show method called for category: ' . $categorySlug);
            
            // Get category by slug
            $category = \DB::table('categories')
                ->select(['id', 'name', 'slug', 'description', 'parent_id'])
                ->where('slug', $categorySlug)
                ->where('is_active', true)
                ->first();
            
            if (!$category) {
                abort(404, 'Category not found');
            }
            
            // Get child categories
            $childCategories = \DB::table('categories')
                ->select(['id', 'name', 'slug', 'description', 'image'])
                ->where('parent_id', $category->id)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            
            // Get child categories with product counts
            $childCategoriesWithCounts = [];
            foreach ($childCategories as $child) {
                $productCount = \DB::table('products')
                    ->where('category_id', $child->id)
                    ->where('status', 'approved')
                    ->count();
                
                $childCategoriesWithCounts[] = [
                    'id' => $child->id,
                    'name' => $child->name,
                    'slug' => $child->slug,
                    'description' => $child->description,
                    'image' => $child->image,
                    'product_count' => $productCount
                ];
            }
            
            // Get products from this category (and child categories if no direct products)
            $directProductCount = \DB::table('products')
                ->where('category_id', $category->id)
                ->where('status', 'approved')
                ->count();
            
            // If no direct products but has children, get products from all children
            $categoryIds = [$category->id];
            if ($directProductCount === 0 && count($childCategories) > 0) {
                foreach ($childCategories as $child) {
                    $categoryIds[] = $child->id;
                }
            }
            
            $products = [];
            $rawProducts = \DB::table('products')
                ->select('id', 'name', 'status', 'images', 'category_id')
                ->whereIn('category_id', $categoryIds)
                ->where('status', 'approved')
                ->orderBy('is_featured', 'desc')
                ->latest()
                ->get();
            
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
                
                // Get category name for this product
                $productCategory = \DB::table('categories')
                    ->select('name')
                    ->where('id', $product->category_id)
                    ->first();
                
                $products[] = [
                    'id' => $product->id,
                    'name' => $product->name ?? 'No name',
                    'status' => $product->status ?? 'unknown',
                    'image' => $images,
                    'min_price' => $minPrice,
                    'category_name' => $productCategory ? $productCategory->name : 'Unknown'
                ];
            }
            
            $productCount = count($products);
            
            // Get breadcrumb path
            $breadcrumbs = [];
            $currentCategory = $category;
            while ($currentCategory) {
                array_unshift($breadcrumbs, [
                    'name' => $currentCategory->name,
                    'slug' => $currentCategory->slug,
                    'id' => $currentCategory->id
                ]);
                
                if ($currentCategory->parent_id) {
                    $currentCategory = \DB::table('categories')
                        ->select(['id', 'name', 'slug', 'parent_id'])
                        ->where('id', $currentCategory->parent_id)
                        ->first();
                } else {
                    $currentCategory = null;
                }
            }
            
            \Log::info('Child categories found: ' . count($childCategoriesWithCounts));
            \Log::info('Products found: ' . $productCount);
            
            return view('categories.show-multilevel', compact('category', 'childCategoriesWithCounts', 'products', 'productCount', 'breadcrumbs'));
            
        } catch (\Exception $e) {
            \Log::error('CategoryController error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}