<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->get();
        return view('client.products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::with(['variants.ram', 'variants.storage', 'variants.color'])
            ->where('slug', $slug)->firstOrFail();

        $rams = $product->variants->pluck('ram')->unique('id')->values();
        $storages = $product->variants->pluck('storage')->unique('id')->values();
        $colors = $product->variants->pluck('color')->unique('id')->values();

        return view('client.products.show', compact('product', 'rams', 'storages', 'colors'));
    }
}


