<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $buyNow = session('buy_now');

        if (!$buyNow) {
            return redirect()->route('home')->with('error', 'Không có sản phẩm nào để thanh toán.');
        }

        return view('client.checkout.index', compact('buyNow'));
    }
}
