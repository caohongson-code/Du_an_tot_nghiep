<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('taikhoan.showLoginForm')->with('error', 'Bạn cần đăng nhập để mua hàng.');
        }

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        session()->put('buy_now', [
            'product_id' => $product->id,
            'variant_id' => $variant?->id,
            'quantity'   => $request->quantity,
        ]);

        return redirect()->route('checkout'); // Route này bạn cần định nghĩa hoặc tạo riêng
    }
}
