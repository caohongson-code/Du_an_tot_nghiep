<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\PaymentStatus;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
        $totalAmountAll = (clone $query)->sum('total_amount');
        $statuses = OrderStatus::all();

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
            'shippingZone',
            'paymentStatus'
        ])->findOrFail($id);

        $statuses = OrderStatus::all();
        $paymentMethods = PaymentMethod::all();
        $shippingZones = ShippingZone::all();
        $paymentStatus = PaymentStatus::all();

        return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods', 'shippingZones', 'paymentStatus'));
    }

    public function update(Request $request, $id)
    {
        // Load thêm productVariant để lấy giá nếu cần
        $order = Order::with(['orderStatus', 'orderDetails.productVariant'])->findOrFail($id);

        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        $newStatusId = (int) $request->order_status_id;
        $oldStatusId = $order->order_status_id;

        $FINAL_STATUS_IDS = [5, 6, 7]; // Đã giao, Trả hàng, Đã huỷ

        if (in_array($oldStatusId, $FINAL_STATUS_IDS)) {
            return back()->with('error', 'Đơn hàng đã hoàn tất hoặc bị huỷ. Không thể cập nhật nữa.');
        }

        $allowedNextStatus = match ($oldStatusId) {
            1 => [2, 7],
            2 => [3],
            3 => [4],
            4 => [5],
            5 => [6],
            default => [],
        };

        if (!in_array($newStatusId, $allowedNextStatus)) {
            return back()->with('error', 'Chuyển trạng thái không hợp lệ. Vui lòng tuân thủ quy trình.');
        }

        DB::beginTransaction();

        try {
            $order->order_status_id = $newStatusId;

            // Cập nhật phí ship nếu chưa có
            if ($order->shipping_zone_id) {
                $shippingZone = ShippingZone::find($order->shipping_zone_id);
                $order->shipping_fee = $shippingZone?->shipping_fee ?? 30000;
            } elseif (is_null($order->shipping_fee)) {
                $order->shipping_fee = 30000;
            }

            // ✅ Tính lại tổng tiền sản phẩm chính xác
            $totalProductAmount = $order->orderDetails->sum(function ($detail) {
                $price = $detail->price
                    ?? $detail->productVariant->sale_price
                    ?? $detail->productVariant->price
                    ?? 0;
                return $detail->quantity * $price;
            });

            $order->total_amount = $totalProductAmount + $order->shipping_fee;

            $order->save();

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
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

            $shippingZoneId = $cart->shipping_zone_id ?? null;
            $shippingFee = 30000;
            if ($shippingZoneId) {
                $shippingZone = ShippingZone::find($shippingZoneId);
                $shippingFee = $shippingZone?->shipping_fee ?? 30000;
            }

            $order = Order::create([
                'account_id' => $cart->account_id,
                'cart_id' => $cart->id,
                'order_status_id' => 1,
                'payment_method_id' => null,
                'total_amount' => 0,
                'shipping_zone_id' => $shippingZoneId,
                'shipping_fee' => $shippingFee,
                'note' => null,
                'recipient_name' => $cart->account->full_name ?? 'Tên người nhận',
                'recipient_phone' => $cart->account->phone ?? 'SĐT',
                'recipient_address' => $cart->account->address ?? 'Địa chỉ',
            ]);

            $totalProductAmount = 0;

            foreach ($cart->details as $detail) {
                $price = $detail->productVariant->sale_price ?? $detail->productVariant->price ?? 0;

                $quantity = $detail->quantity;
                $totalPrice = $price * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $detail->product_variant_id,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                ]);

                $totalProductAmount += $totalPrice;
            }

            $order->update([
                'total_amount' => $totalProductAmount + $shippingFee
            ]);

            $cart->update(['status' => 'ordered']);

            DB::commit();

            return redirect()->route('admin.orders.index')->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage());
        }
    }
}
