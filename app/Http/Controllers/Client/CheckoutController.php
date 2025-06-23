<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartDetail;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('taikhoan.showLoginForm')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $buyNow = session('buy_now');
        $selectedItems = $request->input('selected_items', []);

        $cartItems = [];
        $subtotal = 0;

        // Xử lý trường hợp mua ngay
        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;
            $price = $variant ? $variant->price : $product->price;
            $quantity = $buyNow['quantity'];
            $subtotal = $price * $quantity;

            $cartItems[] = [
                'product' => $product,
                'variant' => $variant,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal
            ];
        }
        // Xử lý trường hợp chọn sản phẩm từ giỏ hàng
        elseif (!empty($selectedItems)) {
            $cart = Cart::where('account_id', $user->id)->where('cart_status_id', 1)->first();
            if (!$cart) {
                return redirect()->route('cart.show')->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            $cartDetails = $cart->details()->whereIn('id', $selectedItems)->with(['product', 'variant'])->get();
            if ($cartDetails->isEmpty()) {
                return redirect()->route('cart.show')->with('error', 'Không có sản phẩm nào để thanh toán.');
            }

            foreach ($cartDetails as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                $subtotal += $price * $item->quantity;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product'        => $item->product,
                    'variant'        => $item->variant,
                    'quantity'       => $item->quantity,
                    'price'          => $price,
                    'subtotal'       => $price * $item->quantity
                ];
            }
        }
        // Không có sản phẩm nào
        else {
            return redirect()->route('cart.show')->with('error', 'Không có sản phẩm nào để thanh toán.');
        }

        // Tính voucher & phí ship
        $shippingFee = 30000;
        $vouchers = Promotion::where('is_active', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        $selectedVoucherId = session('selected_voucher_id');
        $discount = 0;

        if ($selectedVoucherId) {
            $voucher = Promotion::find($selectedVoucherId);
            if ($voucher) {
                $discount = ($subtotal * $voucher->discount_percent) / 100;
            }
        }

        $total = $subtotal + $shippingFee - $discount;

        return view('client.checkout.index', compact(
            'buyNow', 'cartItems', 'vouchers',
            'subtotal', 'shippingFee', 'discount', 'total', 'selectedVoucherId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo'],
        ]);

        $user = Auth::user();
        if (!$user || !$user->phone || !$user->address) {
            return redirect()->back()->with('error', 'Vui lòng cập nhật đầy đủ số điện thoại và địa chỉ nhận hàng.');
        }

        $buyNow = session('buy_now');
        $selectedItems = $request->input('selected_items', []);

        $cartItems = [];
        $subtotal = 0;

        // Xử lý mua ngay
        if ($buyNow) {
            $product = Product::find($buyNow['product_id']);
            $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;
            $price = $variant ? $variant->price : $product->price;
            $quantity = $buyNow['quantity'];
            $subtotal = $price * $quantity;

            $cartItems[] = [
                'product' => $product,
                'variant' => $variant,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal
            ];
        }
        // Xử lý từ giỏ hàng
        elseif (!empty($selectedItems)) {
            $cart = Cart::where('account_id', $user->id)->where('cart_status_id', 1)->first();
            if (!$cart) {
                return redirect()->route('cart.show')->with('error', 'Giỏ hàng của bạn đang trống.');
            }

            $cartDetails = $cart->details()->whereIn('id', $selectedItems)->with(['product', 'variant'])->get();
            if ($cartDetails->isEmpty()) {
                return redirect()->route('cart.show')->with('error', 'Không có sản phẩm nào để thanh toán.');
            }

            foreach ($cartDetails as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                $subtotal += $price * $item->quantity;

                $cartItems[] = [
                    'cart_detail_id' => $item->id,
                    'product'        => $item->product,
                    'variant'        => $item->variant,
                    'quantity'       => $item->quantity,
                    'price'          => $price,
                    'subtotal'       => $price * $item->quantity
                ];
            }
        }
        // Không có sản phẩm nào
        else {
            return redirect()->route('cart.show')->with('error', 'Không có sản phẩm nào để thanh toán.');
        }

        // Tính voucher & tổng tiền
        $shippingFee = 30000;
        $discount = 0;
        $voucher = null;

        if ($request->filled('voucher_id')) {
            $voucher = Promotion::active()->find($request->voucher_id);
            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $total = $subtotal + $shippingFee - $discount;

        // Lưu đơn hàng
        $orderId = DB::table('orders')->insertGetId([
            'account_id'        => $user->id,
            'payment_method_id' => $this->getPaymentMethodId($request->payment_method),
            'shipping_zone_id'  => 1,
            'order_status_id'   => 1,
            'voucher_id'        => $voucher?->id,
            'voucher_code'      => $voucher?->code,
            'shipping_fee'      => $shippingFee,
            'recipient_name'    => $user->full_name,
            'recipient_phone'   => $user->phone,
            'recipient_email'   => $user->email,
            'recipient_address' => $user->address,
            'total_amount'      => $total,
            'order_date'        => now(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Chi tiết đơn hàng
        foreach ($cartItems as $item) {
            DB::table('order_details')->insert([
                'order_id'           => $orderId,
                'product_variant_id' => $item['variant'] ? $item['variant']->id : null,
                'quantity'           => $item['quantity'],
                'unit_price'         => $item['price'],
                'total_price'        => $item['subtotal'],
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        // Nếu từ giỏ hàng thì xóa sản phẩm đã chọn
        if (!empty($selectedItems)) {
            CartDetail::whereIn('id', $selectedItems)->delete();
        }

        session()->forget('buy_now');

        return redirect()->route('home')->with('success', '✅ Đặt hàng thành công!');
    }

    private function getPaymentMethodId($code)
    {
        return DB::table('payment_methods')->where('code', $code)->value('id') ?? 1;
    }
}
