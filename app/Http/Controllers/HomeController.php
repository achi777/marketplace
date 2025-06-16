<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // For now, return empty collections to test the view
        $featuredProducts = collect([]);
        $categories = collect([]);
        
        return view('welcome', compact('featuredProducts', 'categories'));
    }
}