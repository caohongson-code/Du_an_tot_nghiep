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
    $product = Product::with('variants.ram', 'variants.storage', 'variants.color')->findOrFail($id);
    $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->take(4)->get();
    
    $comments = $product->comments()->with('user')->latest()->get(); // thêm dòng này

    return view('client.product.show', compact('product', 'relatedProducts', 'comments'));
}




}
