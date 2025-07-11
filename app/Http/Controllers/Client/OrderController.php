<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ReturnRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng của user
     */
    public function show(Request $request)
    {
        $accountId = Auth::id();
        $status = $request->query('status');

        $ordersQuery = Order::where('account_id', $accountId)
            ->when(!is_null($status), fn($q) => $q->where('order_status_id', $status))
            ->orderByDesc('created_at');

        $paginated = $ordersQuery->paginate(6)->withQueryString();
        $orderIds = $paginated->pluck('id');

        // Eager load với các ID đã phân trang
        $orders = Order::with([
                'orderDetails.productVariant.product',
                'orderStatus'
            ])
            ->whereIn('id', $orderIds)
            ->get()
            ->keyBy('id');

        $paginated->getCollection()->transform(fn($order) => $orders[$order->id]);

        $statuses = OrderStatus::all();

        $reviewedMap = Review::where('account_id', $accountId)
            ->get()
            ->mapWithKeys(fn($r) => [$r->order_id . '-' . $r->product_variant_id => true]);

        return view('client.user.orders', [
            'orders' => $paginated,
            'statuses' => $statuses,
            'reviewedMap' => $reviewedMap,
            'currentStatus' => $status
        ]);
    }

    /**
     * Ajax huỷ đơn hàng
     */
  /**
 * Ajax huỷ đơn hàng
 */
public function ajaxCancel($id)
{
    $userId = auth()->id();

    $order = Order::where('id', $id)
                  ->where('account_id', $userId)
                  ->first();

    if (!$order) {
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy đơn hàng.'
        ], 404);
    }

    // Nếu đã huỷ rồi
    if ($order->order_status_id == 7) {
        return response()->json([
            'success' => false,
            'already_cancelled' => true,
            'message' => 'Đơn hàng này đã huỷ trước đó.'
        ], 200);
    }

    // Không đúng trạng thái chờ xác nhận
    if ($order->order_status_id != 1) {
        return response()->json([
            'success' => false,
            'message' => 'Chỉ có thể huỷ đơn khi đang chờ xác nhận.'
        ], 400);
    }

    try {
        $order->order_status_id = 7;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Đã huỷ đơn hàng thành công.',
            'order_id' => $order->id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi máy chủ khi huỷ đơn hàng.'
        ], 500);
    }
}



    /**
     * Gửi yêu cầu trả hàng/hoàn tiền
     */
    public function requestReturnRefund(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('account_id', auth()->id())
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        if ($order->order_status_id != 5) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể trả hàng khi đơn đã giao.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('return_images', 'public');
            }
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'reason' => $request->input('reason'),
            'images' => json_encode($imagePaths),
        ]);

        $order->order_status_id = 6;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi yêu cầu trả hàng và hoàn tiền.'
        ]);
    }

    /**
     * Xác nhận đã nhận hàng
     */
    public function confirmReceived($id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order || $order->order_status_id != 4) {
            return redirect()->route('user.orders', ['status' => 4])
                ->with('error', 'Đơn hàng không hợp lệ hoặc chưa đến trạng thái giao hàng.');
        }

        DB::table('orders')->where('id', $id)->update([
            'order_status_id' => 5,
            'updated_at' => now(),
        ]);

        return redirect()->route('user.orders', ['status' => 5])
            ->with('success', '✅ Xác nhận đã nhận hàng thành công!');
    }
}
