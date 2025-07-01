<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function redirectToVNPay(Request $request, Order $order)
    {
        // Kiểm tra trạng thái đơn hàng
        if ($order->order_status_id != 1) {
            return redirect()->back()->with('error', 'Đơn hàng không ở trạng thái cho phép thanh toán!');
        }

        $vnpayUrl = config('services.vnpay.url');
        $tmnCode = config('services.vnpay.tmn_code');
        $hashSecret = config('services.vnpay.hash_secret');
        $returnUrl = config('services.vnpay.return_url');

        $vnpayParams = [
            'vnp_Version' => '2.1.0',
            'vnp_Command' => 'pay',
            'vnp_TmnCode' => $tmnCode,
            'vnp_Amount' => $order->total_amount * 100, // VNPay yêu cầu số tiền * 100
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $request->ip(),
            'vnp_Locale' => 'vn',
            'vnp_OrderInfo' => 'Thanh toán đơn hàng #' . $order->id,
            'vnp_OrderType' => '250000', // Loại đơn hàng: Thương mại điện tử
            'vnp_ReturnUrl' => $returnUrl,
            'vnp_TxnRef' => $order->id . '_' . time(),
        ];

        // Tạo chữ ký bảo mật
        ksort($vnpayParams);
        $queryString = http_build_query($vnpayParams);
        $secureHash = hash_hmac('sha512', $queryString, $hashSecret);
        $vnpayParams['vnp_SecureHash'] = $secureHash;

        // Chuyển hướng đến VNPay
        $redirectUrl = $vnpayUrl . '?' . http_build_query($vnpayParams);
        return redirect($redirectUrl);
    }

    public function handleVNPayReturn(Request $request)
    {
        $vnpayData = $request->all();
        $orderId = explode('_', $vnpayData['vnp_TxnRef'])[0];
        $order = Order::findOrFail($orderId);

        // Xác minh chữ ký
        $secureHash = $vnpayData['vnp_SecureHash'];
        unset($vnpayData['vnp_SecureHash']);
        ksort($vnpayData);
        $hashData = http_build_query($vnpayData);
        $calculatedHash = hash_hmac('sha512', $hashData, config('services.vnpay.hash_secret'));

        if ($secureHash === $calculatedHash && $vnpayData['vnp_ResponseCode'] == '00') {
            // Thanh toán thành công
            $order->update(['order_status_id' => 2]); // Đã xác nhận

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'order_status_id' => 2,
                'description' => 'Thanh toán thành công qua VNPay. Mã giao dịch: ' . $vnpayData['vnp_TransactionNo'],
                'updated_by' => $order->account_id,
            ]);

            return redirect()->route('order.show', $order->id)->with('success', 'Thanh toán thành công!');
        } else {
            // Thanh toán thất bại
            $order->update(['order_status_id' => 5]); // Đã hủy

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'order_status_id' => 5,
                'description' => 'Thanh toán thất bại qua VNPay. Lý do: ' . ($vnpayData['vnp_ResponseCode'] ?? 'Lỗi không xác định'),
                'updated_by' => $order->account_id,
            ]);

            // Hoàn lại kho
            foreach ($order->details as $detail) {
                $variant = $detail->productVariant;
                $variant->increment('stock', $detail->quantity);
            }

            return redirect()->route('order.show', $order->id)->with('error', 'Thanh toán thất bại!');
        }
    }
}
