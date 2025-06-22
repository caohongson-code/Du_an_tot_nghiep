<?php

namespace App\Http\Controllers\Client;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $buyNow = session('buy_now');

        if (!$buyNow) {
            return redirect()->route('home')->with('error', 'Không có sản phẩm nào để thanh toán.');
        }

        $product = Product::find($buyNow['product_id']);
        $variant = $buyNow['variant_id'] ? ProductVariant::find($buyNow['variant_id']) : null;

        $price = $variant ? $variant->price : $product->price;
        $quantity = $buyNow['quantity'];
        $subtotal = $price * $quantity;
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
            'buyNow', 'product', 'variant', 'vouchers',
            'subtotal', 'shippingFee', 'discount', 'total', 'selectedVoucherId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => ['nullable', 'exists:promotions,id'],
            'payment_method' => ['required', 'in:cod,bank,momo'],
        ], [
            'voucher_id.exists' => 'Mã voucher không hợp lệ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
        ]);

        $buyNow = session('buy_now');
        if (!$buyNow || !isset($buyNow['product_id'], $buyNow['quantity'])) {
            return redirect()->route('home')->with('error', 'Không có sản phẩm nào để thanh toán.');
        }

        $user = Auth()->user();
        if (!$user->phone || !$user->address) {
            return redirect()->back()->with('error', 'Vui lòng cập nhật số điện thoại và địa chỉ.');
        }

        $product = Product::find($buyNow['product_id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
        }

        $variant = null;
        if (!empty($buyNow['variant_id'])) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $buyNow['variant_id'])
                ->first();

            if (!$variant) {
                return redirect()->back()->with('error', 'Phiên bản sản phẩm không hợp lệ.');
            }
        }

        $quantity = $buyNow['quantity'];
        if (!is_numeric($quantity) || $quantity < 1) {
            return redirect()->back()->with('error', 'Số lượng sản phẩm không hợp lệ.');
        }

        $price = $variant ? $variant->price : $product->price;
        $subtotal = $price * $quantity;
        $shippingFee = 30000;
        $discount = 0;
        $voucher = null;

        if ($request->filled('voucher_id')) {
            $voucher = Promotion::where('id', $request->voucher_id)
                ->where('is_active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($voucher) {
                $discount = $voucher->discount_type === 'percent'
                    ? $subtotal * ($voucher->discount_value / 100)
                    : $voucher->discount_value;
            }
        }

        $total = $subtotal + $shippingFee - $discount;

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

        DB::table('order_details')->insert([
            'order_id'           => $orderId,
            'product_variant_id' => $variant ? $variant->id : null,
            'quantity'           => $quantity,
            'unit_price'         => $price,
            'total_price'        => $subtotal,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        session()->forget('buy_now');

        return redirect()->route('home')->with('success', '✅ Đặt hàng thành công!');
    }

    private function getPaymentMethodId($code)
    {
        return DB::table('payment_methods')->where('code', $code)->value('id') ?? 1;
    }
}