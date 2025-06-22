<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductVariant;

class CheckoutController extends Controller
{
    public function index()
{
    $buyNow = session('buy_now');

    if (!$buyNow) {
        return redirect()->route('home')->with('error', 'Không có sản phẩm nào để thanh toán.');
    }

    // Truy vấn sản phẩm và phiên bản (nếu có)
    $product = Product::find($buyNow['product_id']);
    $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;

    // Giá gốc sản phẩm
    $price = $variant ? $variant->price : $product->price;
    $quantity = $buyNow['quantity'];
    $subtotal = $price * $quantity;

    // Phí vận chuyển cố định
    $shippingFee = 30000;

    // Lấy danh sách voucher còn hạn & đang hoạt động
    $vouchers = Promotion::where('is_active', 1)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->get();

    // Nếu có session voucher_id đã chọn, dùng để hiển thị sẵn
    $selectedVoucherId = session('selected_voucher_id');

    // Nếu có voucher đã chọn thì tìm và tính chiết khấu
    $discount = 0;
    if ($selectedVoucherId) {
        $voucher = Promotion::find($selectedVoucherId);
        if ($voucher) {
            $discount = ($subtotal * $voucher->discount_percent) / 100;
        }
    }

    $total = $subtotal + $shippingFee - $discount;

    return view('client.checkout.index', compact(
        'buyNow',
        'product',
        'variant',
        'vouchers',
        'subtotal',
        'shippingFee',
        'discount',
        'total',
        'selectedVoucherId'
    ));
}
public function store(Request $request)
{
    $request->validate([
        'voucher_id' => 'nullable|exists:promotions,id',
        'payment_method' => 'required|in:cod,bank',
    ]);

    $buyNow = session('buy_now');
    if (!$buyNow) {
        return redirect()->route('home')->with('error', 'Không có sản phẩm nào để thanh toán.');
    }

    $user = auth()->user();
    if (!$user->phone || !$user->address) {
        return redirect()->back()->with('error', 'Vui lòng cập nhật số điện thoại và địa chỉ.');
    }

    // Lấy sản phẩm và phiên bản
    $product = Product::find($buyNow['product_id']);
    $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;
    $quantity = $buyNow['quantity'] ?? 1;

    if (!$product) {
        return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
    }

    $price = $variant ? $variant->price : $product->price;
    $subtotal = $price * $quantity;
    $shippingFee = 30000;
    $discount = 0;

    // Tính giảm giá nếu có voucher
    if ($request->voucher_id) {
        $voucher = Promotion::where('id', $request->voucher_id)
            ->where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        if ($voucher) {
            $discount = $subtotal * ($voucher->discount_percent / 100);
        }
    }

    $total = $subtotal + $shippingFee - $discount;

    // Tạo đơn hàng mới
    $order = new \App\Models\Order();
    $order->account_id = $user->id;
    $order->product_id = $product->id;
    $order->variant_id = $variant ? $variant->id : null;
    $order->quantity = $quantity;
    $order->subtotal = $subtotal;
    $order->shipping_fee = $shippingFee;
    $order->discount = $discount;
    $order->total_price = $total;
    $order->voucher_id = $request->voucher_id;
    $order->payment_method = $request->payment_method;
    $order->status = 'pending';
    $order->save();

    // Xoá session mua ngay
    session()->forget('buy_now');

    return redirect()->route('home')->with('success', '✅ Đặt hàng thành công!');
}


}
