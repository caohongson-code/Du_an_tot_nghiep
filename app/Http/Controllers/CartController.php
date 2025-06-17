<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
 public function index(Request $request)
{
    $query = Cart::with(['account', 'details']);

    if ($search = $request->input('search')) {
        $query->whereHas('account', function ($q) use ($search) {
            $q->where('full_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    $carts = $query->get();
    return view('admin.carts.index', compact('carts', 'search'));
}


    public function show($id)
    {
        $cart = Cart::with(['account', 'details.product', 'details.variant'])->findOrFail($id);
        return view('admin.carts.show', compact('cart'));
    }
    public function destroy($id)
{
    $cart = Cart::findOrFail($id);
    $cart->details()->delete(); // Xoá chi tiết giỏ trước
    $cart->delete(); // Rồi xoá giỏ
    return redirect()->route('carts.index')->with('success', 'Đã xoá giỏ hàng');
}

}