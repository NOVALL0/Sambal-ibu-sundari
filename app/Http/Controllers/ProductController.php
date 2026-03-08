<?php
// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display products for web view (halaman products)
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->has('variant') && $request->variant != '') {
            $query->where('variant', $request->variant);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(9);

        return view('products', compact('products'));
    }

    /**
     * Untuk halaman home - ambil featured products
     */
    public function featured()
    {
        
        $featuredProducts = Product::where('is_active', true)
                            ->inRandomOrder()
                            ->limit(3)
                            ->get();

        return view('home', compact('featuredProducts'));
    }
}