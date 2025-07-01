<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\PaymentMethod;
use App\Models\Cart;
use App\Models\ShippingZone;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        // Tạm thời bỏ middleware để kiểm tra giao diện
        // Khi có đăng nhập, thêm lại:
        // $this->middleware('can:manage-orders')->only(['index', 'show', 'update', 'edit']);
        // $this->middleware('auth')->only(['placeOrderFromCart', 'cancelOrder']);
        // $this->middleware('can:view,cart')->only('placeOrderFromCart');
        // $this->middleware('can:cancel,order')->only('cancelOrder');
    }

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
            $query->whereHas('account', function ($q) use ($request) {
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
        $totalAmountAll = $query->sum('total_amount');

        // Cache danh sách trạng thái
        $statuses = Cache::remember('order_statuses', 3600, function () {
            return OrderStatus::all();
        });

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
            'statusHistories.status',
            'statusHistories.updatedBy'
        ])->findOrFail($id);

        $statuses = Cache::remember('order_statuses', 3600, function () {
            return OrderStatus::all();
        });
        $paymentMethods = PaymentMethod::all();
        $shippingZones = ShippingZone::all();

        return view('admin.orders.show', compact('order', 'statuses', 'paymentMethods', 'shippingZones'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $statuses = Cache::remember('order_statuses', 3600, function () {
            return OrderStatus::all();
        });
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);

        $request->validate([
            'order_status_id' => 'required|exists:order_statuses,id',
            'description' => 'nullable|string',
        ]);

        // Quy tắc chuyển đổi trạng thái
        $validTransitions = [
            1 => [2, 5], // Chờ xác nhận -> Đã xác nhận, Đã hủy
            2 => [3, 5], // Đã xác nhận -> Đang giao hàng, Đã hủy
            3 => [4, 5], // Đang giao hàng -> Đã giao, Đã hủy
            4 => [6],    // Đã giao -> Hoàn trả/Hoàn tiền
            5 => [],     // Đã hủy -> none
            6 => [],     // Hoàn trả/Hoàn tiền -> none
        ];

        if (!in_array($request->order_status_id, $validTransitions[$order->order_status_id] ?? [])) {
            return redirect()->back()->with('error', 'Chuyển đổi trạng thái không hợp lệ!');
        }

        DB::beginTransaction();

        try {
            $oldStatus = $order->orderStatus->status_name;

            // Cập nhật trạng thái
            $order->order_status_id = $request->order_status_id;

            // Cập nhật phí vận chuyển
            if ($order->shipping_zone_id) {
                $shippingZone = ShippingZone::find($order->shipping_zone_id);
                $order->shipping_fee = $shippingZone?->shipping_fee ?? 30000;
            } elseif (is_null($order->shipping_fee)) {
                $order->shipping_fee = 30000;
            }

            // Tính lại tổng tiền
            $total = $order->orderDetails->sum(function ($detail) {
                return $detail->quantity * ($detail->unit_price ?? 0);
            }) ?? 0;

            $order->total_amount = $total + $order->shipping_fee;

            // Hoàn kho nếu hủy hoặc hoàn tiền
            if (in_array($request->order_status_id, [5, 6])) {
                foreach ($order->orderDetails as $detail) {
                    $variant = $detail->productVariant;
                    $variant->increment('stock', $detail->quantity);
                }
            }

            $order->save();

            // Ghi log vào order_status_history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'order_status_id' => $request->order_status_id,
                'description' => $request->description ?? "Cập nhật trạng thái từ {$oldStatus} sang {$order->orderStatus->status_name}",
                'updated_by' => null, // Tạm thời để null vì chưa có đăng nhập
            ]);

            // Gửi email thông báo (bỏ qua vì chưa có đăng nhập)
            // Notification::send($order->account, new OrderStatusUpdated($order));

            Log::info("Cập nhật đơn hàng #$id: shipping_fee = {$order->shipping_fee}, total_amount = {$order->total_amount}, status = {$order->orderStatus->status_name}");

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi cập nhật đơn hàng #$id: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function placeOrderFromCart(Request $request, $cartId)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'recipient_name' => 'required|string',
            'recipient_email' => 'required|email',
            'recipient_phone' => 'required|string',
            'recipient_address' => 'required|string',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
        ]);

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
            $shippingZone = ShippingZone::find($request->shipping_zone_id);
            $shippingFee = $shippingZone?->shipping_fee ?? 30000;

            // Tạo đơn hàng
            $order = Order::create([
                'account_id' => $cart->account_id,
                'cart_id' => $cart->id,
                'order_status_id' => 1, // Chờ xác nhận
                'payment_method_id' => $request->payment_method_id,
                'total_amount' => 0, // Tạm, sẽ cập nhật sau
                'shipping_zone_id' => $request->shipping_zone_id,
                'shipping_fee' => $shippingFee,
                'note' => $request->note,
                'recipient_name' => $request->recipient_name,
                'recipient_email' => $request->recipient_email,
                'recipient_phone' => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'order_date' => now(),
            ]);

            // Tạo chi tiết đơn hàng và tính tổng tiền
            $totalProductAmount = 0;

            foreach ($cart->details as $detail) {
                $unitPrice = $detail->productVariant->price ?? 0;
                $quantity = $detail->quantity;
                $totalPrice = $unitPrice * $quantity;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $detail->product_variant_id,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                ]);

                $totalProductAmount += $totalPrice;
            }

            // Cập nhật tổng tiền
            $order->update([
                'total_amount' => $totalProductAmount + $shippingFee
            ]);

            // Ghi log trạng thái ban đầu
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'order_status_id' => 1,
                'description' => 'Đơn hàng được tạo từ giỏ hàng #' . $cart->id,
                'updated_by' => null, // Tạm thời để null vì chưa có đăng nhập
            ]);

            // Đánh dấu giỏ hàng
            $cart->update(['status' => 'ordered']);

            DB::commit();

            // Chuyển hướng đến VNPay nếu thanh toán trực tuyến
            if ($request->payment_method_id == 1) { // Giả sử 1 là VNPay
                return redirect()->route('payment.vnpay', $order->id);
            }

            return redirect()->route('orders.show', $order->id)->with('success', 'Đặt hàng thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi đặt hàng từ giỏ hàng #$cartId: " . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi đặt hàng: ' . $e->getMessage());
        }
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);

        if ($order->order_status_id != 1) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng ở trạng thái này!');
        }

        DB::beginTransaction();

        try {
            $order->update(['order_status_id' => 5]);

            foreach ($order->orderDetails as $detail) {
                $variant = $detail->productVariant;
                $variant->increment('stock', $detail->quantity);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'order_status_id' => 5,
                'description' => 'Đơn hàng bị hủy bởi khách hàng.',
                'updated_by' => null, // Tạm thời để null vì chưa có đăng nhập
            ]);

            // Gửi email thông báo (bỏ qua vì chưa có đăng nhập)
            // Notification::send($order->account, new OrderStatusUpdated($order));

            DB::commit();

            return redirect()->route('orders.show', $order->id)->with('success', 'Đơn hàng đã được hủy!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi hủy đơn hàng #$id: " . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi hủy đơn hàng: ' . $e->getMessage());
        }
    }
}
