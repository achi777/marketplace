<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = \App\Models\User::where('role', 'seller')->first();
        $categories = \App\Models\Category::whereNotNull('parent_id')->get();
        
        foreach ($categories as $category) {
            // Create 3-5 products per category
            for ($i = 1; $i <= rand(3, 5); $i++) {
                $product = \App\Models\Product::create([
                    'seller_id' => $seller->id,
                    'category_id' => $category->id,
                    'name' => $this->getProductName($category->name, $i),
                    'short_description' => $this->getShortDescription($category->name),
                    'description' => $this->getDescription($category->name),
                    'images' => [
                        'https://via.placeholder.com/400x400/007bff/ffffff?text=' . urlencode($category->name),
                        'https://via.placeholder.com/400x400/28a745/ffffff?text=' . urlencode($category->name . ' 2'),
                        'https://via.placeholder.com/400x400/dc3545/ffffff?text=' . urlencode($category->name . ' 3'),
                    ],
                    'status' => 'approved',
                    'is_featured' => rand(0, 1),
                    'weight' => rand(100, 5000) / 100, // 1-50 kg
                    'dimensions' => rand(10, 50) . 'x' . rand(10, 50) . 'x' . rand(5, 20) . ' cm',
                ]);

                // Create variations for each product
                $this->createVariations($product, $category);
            }
        }
    }

    private function getProductName($categoryName, $index): string
    {
        $names = [
            'Smartphones' => ['iPhone 15 Pro Max', 'Samsung Galaxy S24 Ultra', 'Google Pixel 8 Pro', 'OnePlus 12', 'Xiaomi 14 Ultra'],
            'Laptops' => ['MacBook Pro 16"', 'Dell XPS 15', 'HP Spectre x360', 'Lenovo ThinkPad X1', 'ASUS ZenBook Pro'],
            "Men's Clothing" => ['Premium Cotton T-Shirt', 'Classic Denim Jeans', 'Wool Blazer', 'Casual Polo Shirt', 'Leather Jacket'],
            "Women's Clothing" => ['Elegant Summer Dress', 'Designer Handbag', 'Silk Blouse', 'High-Waist Jeans', 'Cashmere Sweater'],
        ];

        $categoryNames = $names[$categoryName] ?? ['Premium Product', 'Quality Item', 'Top Brand Product', 'Best Seller', 'Featured Item'];
        return $categoryNames[($index - 1) % count($categoryNames)];
    }

    private function getShortDescription($categoryName): string
    {
        $descriptions = [
            'Smartphones' => 'Latest flagship smartphone with cutting-edge technology and premium features.',
            'Laptops' => 'High-performance laptop perfect for work, gaming, and creative tasks.',
            "Men's Clothing" => 'Stylish and comfortable clothing made from premium materials.',
            "Women's Clothing" => 'Fashionable and elegant clothing designed for the modern woman.',
        ];

        return $descriptions[$categoryName] ?? 'Premium quality product with excellent features and design.';
    }

    private function getDescription($categoryName): string
    {
        return "This is a detailed description of our premium " . strtolower($categoryName) . " product. 
        
        Features:
        • High-quality materials and construction
        • Modern design and aesthetics
        • Excellent value for money
        • Fast shipping and reliable customer service
        • 1-year warranty included
        
        Perfect for everyday use and special occasions. Our commitment to quality ensures you get the best product at competitive prices.";
    }

    private function createVariations($product, $category)
    {
        $attributes = $category->attributes;
        
        if ($attributes->isEmpty()) {
            // Create a basic variation if no attributes
            \App\Models\ProductVariation::create([
                'product_id' => $product->id,
                'price' => rand(50, 2000),
                'compare_price' => rand(2050, 2500),
                'stock_quantity' => rand(5, 100),
                'low_stock_threshold' => 5,
                'attributes' => [],
                'is_active' => true,
            ]);
            return;
        }

        // Generate variations based on attributes
        $combinations = $this->generateAttributeCombinations($attributes);
        
        foreach (array_slice($combinations, 0, rand(3, 8)) as $combination) {
            $basePrice = rand(50, 2000);
            \App\Models\ProductVariation::create([
                'product_id' => $product->id,
                'price' => $basePrice,
                'compare_price' => $basePrice + rand(50, 500),
                'stock_quantity' => rand(0, 50),
                'low_stock_threshold' => 5,
                'attributes' => $combination,
                'is_active' => true,
            ]);
        }
    }

    private function generateAttributeCombinations($attributes)
    {
        $combinations = [[]];
        
        foreach ($attributes as $attribute) {
            if (!$attribute->options || empty($attribute->options)) continue;
            
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach (array_slice($attribute->options, 0, 3) as $option) { // Limit to 3 options per attribute
                    $newCombinations[] = array_merge($combination, [
                        strtolower($attribute->name) => $option
                    ]);
                }
            }
            $combinations = $newCombinations;
        }
        
        return $combinations;
    }
}
