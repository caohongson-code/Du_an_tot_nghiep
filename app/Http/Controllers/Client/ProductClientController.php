<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductClientController extends Controller
{
    /**
     * Trang chủ - danh sách sản phẩm mới nhất
     */
    public function index()
    {
        $products = Product::where('status', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('client.home', compact('products'));
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with([
                'variants.images',
                'variants.ram',
                'variants.storage',
                'variants.color'
            ])
            ->findOrFail($id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->latest()
            ->take(4)
            ->get();

        return view('client.product.show', compact('product', 'relatedProducts'));
    }

    /**
     * Trang tất cả sản phẩm
     */
    public function allProducts()
    {
        $products = Product::where('status', 1)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('client.products.index', compact('products'));
    }
}
