<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Electronics Category
        $electronics = \App\Models\Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and gadgets',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Electronics Subcategories
        $smartphones = \App\Models\Category::create([
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'description' => 'Mobile phones and accessories',
            'parent_id' => $electronics->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $laptops = \App\Models\Category::create([
            'name' => 'Laptops',
            'slug' => 'laptops',
            'description' => 'Laptops and notebooks',
            'parent_id' => $electronics->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Fashion Category
        $fashion = \App\Models\Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Clothing and accessories',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $mensClothing = \App\Models\Category::create([
            'name' => "Men's Clothing",
            'slug' => 'mens-clothing',
            'description' => 'Clothing for men',
            'parent_id' => $fashion->id,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $womensClothing = \App\Models\Category::create([
            'name' => "Women's Clothing",
            'slug' => 'womens-clothing',
            'description' => 'Clothing for women',
            'parent_id' => $fashion->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Home & Garden Category
        $homeGarden = \App\Models\Category::create([
            'name' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Home improvement and garden supplies',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Sports Category
        $sports = \App\Models\Category::create([
            'name' => 'Sports',
            'slug' => 'sports',
            'description' => 'Sports equipment and fitness gear',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Create category attributes for smartphones
        \App\Models\CategoryAttribute::create([
            'category_id' => $smartphones->id,
            'name' => 'Brand',
            'type' => 'select',
            'options' => ['Apple', 'Samsung', 'Google', 'OnePlus', 'Xiaomi'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 1,
        ]);

        \App\Models\CategoryAttribute::create([
            'category_id' => $smartphones->id,
            'name' => 'Storage',
            'type' => 'select',
            'options' => ['64GB', '128GB', '256GB', '512GB', '1TB'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 2,
        ]);

        \App\Models\CategoryAttribute::create([
            'category_id' => $smartphones->id,
            'name' => 'Color',
            'type' => 'color',
            'options' => ['Black', 'White', 'Blue', 'Red', 'Gold'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 3,
        ]);

        // Create category attributes for laptops
        \App\Models\CategoryAttribute::create([
            'category_id' => $laptops->id,
            'name' => 'Brand',
            'type' => 'select',
            'options' => ['Apple', 'Dell', 'HP', 'Lenovo', 'ASUS'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 1,
        ]);

        \App\Models\CategoryAttribute::create([
            'category_id' => $laptops->id,
            'name' => 'RAM',
            'type' => 'select',
            'options' => ['8GB', '16GB', '32GB', '64GB'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 2,
        ]);

        \App\Models\CategoryAttribute::create([
            'category_id' => $laptops->id,
            'name' => 'Screen Size',
            'type' => 'select',
            'options' => ['13"', '14"', '15"', '16"', '17"'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 3,
        ]);

        // Create category attributes for clothing
        \App\Models\CategoryAttribute::create([
            'category_id' => $mensClothing->id,
            'name' => 'Size',
            'type' => 'select',
            'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 1,
        ]);

        \App\Models\CategoryAttribute::create([
            'category_id' => $womensClothing->id,
            'name' => 'Size',
            'type' => 'select',
            'options' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'is_required' => true,
            'is_filterable' => true,
            'sort_order' => 1,
        ]);
    }
}
