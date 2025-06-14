<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductClientController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->orderByDesc('created_at')->paginate(12);
        return view('client.home', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('client.product.show', compact('product'));
    }
}
