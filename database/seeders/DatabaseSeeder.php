<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users as specified in documentation
        
        // Create admin user if it doesn't exist
        if (!User::where('email', 'admin@marketplace.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@marketplace.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_approved' => true,
                'address' => '123 Admin Street, Admin City, AC 12345',
                'phone' => '+1-555-0001',
            ]);
        }

        // Create seller user if it doesn't exist
        if (!User::where('email', 'seller@marketplace.com')->exists()) {
            User::create([
                'name' => 'Demo Seller',
                'email' => 'seller@marketplace.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'seller',
                'is_approved' => true,
                'address' => '456 Seller Avenue, Seller City, SC 67890',
                'phone' => '+1-555-0002',
            ]);
        }

        // Create buyer user if it doesn't exist
        if (!User::where('email', 'buyer@marketplace.com')->exists()) {
            User::create([
                'name' => 'Demo Buyer',
                'email' => 'buyer@marketplace.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 'buyer',
                'is_approved' => true,
                'address' => '789 Buyer Boulevard, Buyer City, BC 54321',
                'phone' => '+1-555-0003',
            ]);
        }

        // Seed categories
        $this->call(CategorySeeder::class);
        
        // Seed products
        $this->call(ProductSeeder::class);
    }
}
