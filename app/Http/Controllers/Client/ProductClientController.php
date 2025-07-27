<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
class ProductClientController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 1)->orderByDesc('created_at')->paginate(12);
        return view('client.home', compact('products'));
    }

public function show($id)
{
    $product = Product::with(['variants.images','variants.ram', 'variants.storage', 'variants.color'])->findOrFail($id);

    $relatedProducts = Product::where('category_id', $product->category_id)
                            ->where('id', '!=', $product->id)
                            ->where('status', 1)
                            ->latest()
                            ->take(4)
                            ->get();

    $reviews = Review::with(['account', 'variant.ram', 'variant.storage', 'variant.color'])
                    ->where('product_id', $product->id)
                    ->latest()
                    ->get();

    // ✅ Kiểm tra người dùng đã mua sản phẩm chưa
    $hasPurchased = false;
    $user = Auth::user();

    if ($user) {
        $variantIds = $product->variants->pluck('id');

        $hasPurchased = OrderDetail::whereIn('product_variant_id', $variantIds)
            ->whereHas('order', function ($query) use ($user) {
                $query->where('account_id', $user->id)
                      ->where('order_status_id', 5); // đã giao hàng
            })->exists();
    }

    return view('client.product.show', compact('product', 'relatedProducts', 'reviews', 'hasPurchased'));
}
    public function categoryPage($id = null)
    {
        $categories = \App\Models\Category::all();
        if ($id) {
            $products = \App\Models\Product::where('category_id', $id)->get();
            $selectedCategory = $id;
        } else {
            $products = \App\Models\Product::all();
            $selectedCategory = null;
        }
        return view('client.categories.index', compact('categories', 'products', 'selectedCategory'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = \App\Models\Product::where(function($query) use ($keyword) {
            $query->where('product_name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
        })->where('status', 1)->orderByDesc('created_at')->paginate(12);
        return view('client.home', compact('products', 'keyword'));
    }
    


}
