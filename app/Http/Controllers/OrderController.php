<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
public function index(Request $request)
{
    $query = Order::with([
        'account',
        'paymentMethod',
        'orderStatus',
        'cart.statusModel',
        'shippingZone'
    ]);

    if ($request->search) {
        $query->whereHas('account', function($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->order_status_id) {
        $query->where('order_status_id', $request->order_status_id);
    }

    if ($request->order_date) {
        $query->whereDate('order_date', $request->order_date);
    }

    $orders = $query->orderBy('order_date', 'desc')->paginate(15);

    $totalAmountAll = $query->sum('total_amount'); // ✅ dùng chính $query để tính tổng theo bộ lọc hiện tại

    $statuses = OrderStatus::all();

    // ✅ THÊM BIẾN $totalAmountAll VÀO COMPACT
    return view('admin.orders.index', compact('orders', 'statuses', 'totalAmountAll'));
}

    public function show($id)
    {
        $order = Order::with([
            'orderDetails.productVariant.product',
            'orderDetails.productVariant.ram',
            'orderDetails.productVariant.storage',
            'orderDetails.productVariant.color',
            'orderStatus',
            'paymentMethod',
            'shippingZone'
        ])->findOrFail($id);

        $statuses = OrderStatus::all();
        $paymentMethods = PaymentMethod::all();
        $shippingZones = ShippingZone::all();

        return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods', 'shippingZones'));
    }

public function update(Request $request, $id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);

        // Validation
        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        DB::beginTransaction();

        try {
            // Lấy trạng thái cũ để log
            $oldStatus = $order->orderStatus->status_name;

            // Cập nhật trạng thái
            $order->order_status_id = $request->order_status_id;

            // Cập nhật phí vận chuyển (shipping_fee)
            if ($order->shipping_zone_id) {
            $shippingZone = ShippingZone::find($order->shipping_zone_id);
            $order->shipping_fee = $shippingZone?->shipping_fee ?? 30000;
            } elseif (is_null($order->shipping_fee)) {
            $order->shipping_fee = 30000;
            }


            // Debug
           Log::debug("Shipping fee sau tính toán: " . $order->shipping_fee);


            // Tính lại tổng tiền sản phẩm
            $total = $order->orderDetails->sum(function ($detail) {
                return $detail->quantity * ($detail->price ?? 0);
            }) ?? 0;

            // Cập nhật tổng tiền
            $order->total_amount = $total + $order->shipping_fee;

            // Lưu thay đổi
            $order->save();

            // Log sau khi save
            Log::info("Sau khi save - order #$id: shipping_fee = {$order->shipping_fee}, total_amount = {$order->total_amount}, status = {$order->orderStatus->status_name}");

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi cập nhật trạng thái đơn hàng #$id: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

  public function placeOrderFromCart($cartId)
{
    DB::beginTransaction();

    try {
        $cart = Cart::with(['details.productVariant', 'account', 'shippingZone'])->findOrFail($cartId);

        if ($cart->status !== 'active') {
            return redirect()->back()->with('error', 'Giỏ hàng không hợp lệ hoặc đã được đặt.');
        }

        if ($cart->details->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng không có sản phẩm.');
        }

        // Tính phí ship
        $shippingZoneId = $cart->shipping_zone_id ?? null;
        if ($shippingZoneId) {
            $shippingZone = ShippingZone::find($shippingZoneId);
            $shippingFee = $shippingZone?->shipping_fee ?? 30000;
        } else {
            $shippingFee = 30000;
        }

        // Tạo đơn hàng ban đầu (tạm thời gán total_amount = 0)
        $order = Order::create([
            'account_id' => $cart->account_id,
            'cart_id' => $cart->id,
            'order_status_id' => 1,
            'payment_method_id' => null,
            'total_amount' => 0, // Tạm, sẽ cập nhật lại sau
            'shipping_zone_id' => $shippingZoneId,
            'shipping_fee' => $shippingFee,
            'note' => null,
            'recipient_name' => $cart->account->full_name ?? 'Tên người nhận',
            'recipient_phone' => $cart->account->phone ?? 'SĐT',
            'recipient_address' => $cart->account->address ?? 'Địa chỉ',
        ]);

        // Tạo chi tiết đơn hàng và tính tổng tiền sản phẩm
        $totalProductAmount = 0;

        foreach ($cart->details as $detail) {
            $price = $detail->productVariant->price ?? 0;
            $quantity = $detail->quantity;
            $totalPrice = $price * $quantity;

            OrderDetail::create([
                'order_id' => $order->id,
                'product_variant_id' => $detail->product_variant_id,
                'price' => $price,
                'quantity' => $quantity,
                'total_price' => $totalPrice // ✅ Chỉ dùng nếu bảng order_details có cột này
            ]);

            $totalProductAmount += $totalPrice;
        }

        // Cập nhật lại tổng tiền đơn hàng
        $order->update([
            'total_amount' => $totalProductAmount + $shippingFee
        ]);

        // Đánh dấu giỏ hàng đã được đặt
        $cart->update(['status' => 'ordered']);

        DB::commit();

        return redirect()->route('admin.orders.index')->with('success', 'Đặt hàng thành công.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage());
    }
}

}