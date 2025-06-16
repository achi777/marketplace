<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users
        $admin = User::firstOrCreate(
            ['email' => 'admin@marketplace.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_approved' => true,
                'phone' => '+1234567890',
                'address' => '123 Admin Street, Admin City, AC 12345',
            ]
        );

        $seller = User::firstOrCreate(
            ['email' => 'seller@marketplace.com'],
            [
                'name' => 'Demo Seller',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'is_approved' => true,
                'phone' => '+1234567891',
                'address' => '456 Seller Avenue, Seller City, SC 54321',
            ]
        );

        $buyer = User::firstOrCreate(
            ['email' => 'buyer@marketplace.com'],
            [
                'name' => 'Demo Buyer',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'is_approved' => true,
                'phone' => '+1234567892',
                'address' => '789 Buyer Boulevard, Buyer City, BC 98765',
            ]
        );

        // Create categories
        $electronics = Category::firstOrCreate(
            ['slug' => 'electronics'],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $smartphones = Category::firstOrCreate(
            ['slug' => 'smartphones'],
            [
                'name' => 'Smartphones',
                'description' => 'Mobile phones and accessories',
                'parent_id' => $electronics->id,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $laptops = Category::firstOrCreate(
            ['slug' => 'laptops'],
            [
                'name' => 'Laptops',
                'description' => 'Portable computers and accessories',
                'parent_id' => $electronics->id,
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $clothing = Category::firstOrCreate(
            ['slug' => 'clothing'],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Create product attributes
        $this->createProductAttributes($electronics, $smartphones, $laptops, $clothing);

        // Create products
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'short_description' => 'The latest iPhone with advanced features and powerful performance.',
                'description' => 'Experience the future with the iPhone 15 Pro. Featuring a titanium design, advanced camera system, and A17 Pro chip for unmatched performance.',
                'category_id' => $smartphones->id,
                'seller_id' => $seller->id,
                'status' => 'approved',
                'is_featured' => true,
                'images' => ['https://via.placeholder.com/400x400/007bff/ffffff?text=iPhone+15+Pro'],
                'weight' => 0.5,
                'dimensions' => '159.9 x 76.7 x 8.25 mm',
                'variations' => [
                    ['color' => 'Space Black', 'storage' => '128GB', 'price' => 999.00, 'compare_price' => 1099.00, 'stock' => 50],
                    ['color' => 'Space Black', 'storage' => '256GB', 'price' => 1199.00, 'compare_price' => 1299.00, 'stock' => 30],
                    ['color' => 'Blue', 'storage' => '128GB', 'price' => 999.00, 'compare_price' => 1099.00, 'stock' => 45],
                    ['color' => 'Blue', 'storage' => '256GB', 'price' => 1199.00, 'compare_price' => 1299.00, 'stock' => 25],
                ]
            ],
            [
                'name' => 'MacBook Pro 14"',
                'slug' => 'macbook-pro-14',
                'short_description' => 'Professional laptop with M3 chip for ultimate performance.',
                'description' => 'The MacBook Pro 14" with M3 chip delivers exceptional performance for professionals. Perfect for video editing, software development, and creative work.',
                'category_id' => $laptops->id,
                'seller_id' => $seller->id,
                'status' => 'approved',
                'is_featured' => true,
                'images' => ['https://via.placeholder.com/400x400/28a745/ffffff?text=MacBook+Pro'],
                'weight' => 1.6,
                'dimensions' => '31.26 x 22.12 x 1.55 cm',
                'variations' => [
                    ['memory' => '16GB', 'storage' => '512GB', 'price' => 1999.00, 'compare_price' => 2199.00, 'stock' => 15],
                    ['memory' => '16GB', 'storage' => '1TB', 'price' => 2399.00, 'compare_price' => 2599.00, 'stock' => 10],
                    ['memory' => '32GB', 'storage' => '1TB', 'price' => 2799.00, 'compare_price' => 2999.00, 'stock' => 8],
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'short_description' => 'Latest Samsung flagship with AI-powered features.',
                'description' => 'The Samsung Galaxy S24 features cutting-edge AI technology, advanced camera system, and premium design for the ultimate smartphone experience.',
                'category_id' => $smartphones->id,
                'seller_id' => $seller->id,
                'status' => 'approved',
                'is_featured' => true,
                'images' => ['https://via.placeholder.com/400x400/6f42c1/ffffff?text=Galaxy+S24'],
                'weight' => 0.45,
                'dimensions' => '147 x 70.6 x 7.6 mm',
                'variations' => [
                    ['color' => 'Phantom Black', 'storage' => '128GB', 'price' => 799.00, 'compare_price' => 899.00, 'stock' => 40],
                    ['color' => 'Phantom Black', 'storage' => '256GB', 'price' => 899.00, 'compare_price' => 999.00, 'stock' => 35],
                    ['color' => 'Cream', 'storage' => '128GB', 'price' => 799.00, 'compare_price' => 899.00, 'stock' => 30],
                ]
            ],
            [
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'short_description' => 'Comfortable cotton t-shirt perfect for everyday wear.',
                'description' => 'Made from 100% premium cotton, this classic t-shirt offers comfort and style. Perfect for casual wear or layering.',
                'category_id' => $clothing->id,
                'seller_id' => $seller->id,
                'status' => 'approved',
                'is_featured' => false,
                'images' => ['https://via.placeholder.com/400x400/dc3545/ffffff?text=T-Shirt'],
                'weight' => 0.2,
                'dimensions' => 'Various sizes available',
                'variations' => [
                    ['color' => 'White', 'size' => 'S', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 100],
                    ['color' => 'White', 'size' => 'M', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 150],
                    ['color' => 'White', 'size' => 'L', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 120],
                    ['color' => 'Black', 'size' => 'S', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 80],
                    ['color' => 'Black', 'size' => 'M', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 100],
                    ['color' => 'Black', 'size' => 'L', 'price' => 19.99, 'compare_price' => 24.99, 'stock' => 90],
                ]
            ],
        ];

        foreach ($products as $productData) {
            $variations = $productData['variations'];
            unset($productData['variations']);

            // Create product only if it doesn't exist
            $product = Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );

            // Create variations only if the product was just created
            if ($product->variations()->count() === 0) {
                foreach ($variations as $index => $variationData) {
                    $attributes = [];
                    
                    foreach ($variationData as $key => $value) {
                        if (!in_array($key, ['price', 'compare_price', 'stock'])) {
                            $attributes[$key] = $value;
                        }
                    }

                    ProductVariation::create([
                        'product_id' => $product->id,
                        'sku' => $product->sku . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                        'price' => $variationData['price'],
                        'compare_price' => $variationData['compare_price'] ?? null,
                        'stock_quantity' => $variationData['stock'],
                        'attributes' => $attributes,
                        'is_active' => true,
                    ]);
                }
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@marketplace.com / password');
        $this->command->info('Seller: seller@marketplace.com / password');
        $this->command->info('Buyer: buyer@marketplace.com / password');
    }

    private function createProductAttributes($electronics, $smartphones, $laptops, $clothing)
    {
        // Electronics attributes
        ProductAttribute::firstOrCreate(
            ['category_id' => $electronics->id, 'name' => 'Brand'],
            [
                'type' => 'select',
                'options' => ['Apple', 'Samsung', 'Sony', 'LG', 'Dell', 'HP'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $electronics->id, 'name' => 'Warranty'],
            [
                'type' => 'select',
                'options' => ['1 Year', '2 Years', '3 Years'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // Smartphone specific attributes
        ProductAttribute::firstOrCreate(
            ['category_id' => $smartphones->id, 'name' => 'Storage'],
            [
                'type' => 'select',
                'options' => ['64GB', '128GB', '256GB', '512GB', '1TB'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $smartphones->id, 'name' => 'Color'],
            [
                'type' => 'select',
                'options' => ['Space Black', 'Silver', 'Gold', 'Blue', 'Purple', 'Red'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // Laptop specific attributes
        ProductAttribute::firstOrCreate(
            ['category_id' => $laptops->id, 'name' => 'Screen Size'],
            [
                'type' => 'select',
                'options' => ['13"', '14"', '15"', '16"', '17"'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $laptops->id, 'name' => 'RAM'],
            [
                'type' => 'select',
                'options' => ['8GB', '16GB', '32GB', '64GB'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $laptops->id, 'name' => 'Storage Type'],
            [
                'type' => 'select',
                'options' => ['SSD', 'HDD', 'Hybrid'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        // Clothing specific attributes
        ProductAttribute::firstOrCreate(
            ['category_id' => $clothing->id, 'name' => 'Size'],
            [
                'type' => 'select',
                'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $clothing->id, 'name' => 'Color'],
            [
                'type' => 'select',
                'options' => ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Purple', 'Orange'],
                'is_required' => true,
                'is_filterable' => true,
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $clothing->id, 'name' => 'Material'],
            [
                'type' => 'select',
                'options' => ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Denim'],
                'is_required' => false,
                'is_filterable' => true,
                'sort_order' => 3,
                'is_active' => true,
            ]
        );

        ProductAttribute::firstOrCreate(
            ['category_id' => $clothing->id, 'name' => 'Care Instructions'],
            [
                'type' => 'textarea',
                'is_required' => false,
                'is_filterable' => false,
                'sort_order' => 4,
                'is_active' => true,
            ]
        );
    }
}