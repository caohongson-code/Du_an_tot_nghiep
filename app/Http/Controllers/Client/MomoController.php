<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MomoTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MomoController extends Controller
{
    /**
     * Gửi yêu cầu thanh toán MoMo (ATM).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function momo_payment(Request $request)
    {
        // Lấy tham số
        $requestId = $request->input('request_id');
        $amount = $request->input('total_momo');
        $orderIdRaw = $request->input('order_id');
        $orderId = $orderIdRaw . '-' . time();

        // Cấu hình MoMo
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = config('momo.partner_code', 'MOMOBKUN20180529');
        $accessKey = config('momo.access_key', 'klm05TvNBzhg7h7j');
        $secretKey = config('momo.secret_key', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa');
        $redirectUrl = route('momo.redirect');
        $ipnUrl = route('momo.ipn');
        $orderInfo = "Thanh toán qua ATM MoMo";
        $extraData = "";
        $requestType = "payWithATM";

        // Tạo chữ ký
        $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId'     => "MomoTestStore",
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl'      => $ipnUrl,
            'lang'        => 'vi',
            'extraData'   => $extraData,
            'requestType' => $requestType,
            'signature'   => $signature,
        ];

        // Gửi request
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        }

        return back()->with('error', '❌ Không thể tạo thanh toán MoMo: ' . ($jsonResult['message'] ?? 'Lỗi không xác định'));
    }

    /**
     * Gửi request POST tới endpoint MoMo
     *
     * @param string $url
     * @param string $data
     * @return string
     */
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ],
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * Xử lý IPN từ MoMo (callback server)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleMomoIpn(Request $request)
    {
        // Ghi log IPN nếu cần
        Log::info('MoMo IPN', $request->all());

        // Lưu giao dịch
        $this->storeMomoTransaction($request);

        // Nếu thanh toán thành công (resultCode == 0)
        if ($request->input('resultCode') == 0) {
            $fullOrderId = $request->input('orderId');
            $realOrderId = explode('-', $fullOrderId)[0];

            DB::table('orders')
                ->where('id', $realOrderId)
                ->update([
                    'order_status_id' => 1, // ✅ Chờ xác nhận
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
        }

        return response('IPN received', 200);
    }

    /**
     * Xử lý redirect từ MoMo sau thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleMomoRedirect(Request $request)
    {
        // Ghi log redirect nếu cần
        Log::info('MoMo Redirect', $request->all());

        // Lưu giao dịch
        $this->storeMomoTransaction($request);

        if ($request->input('resultCode') == 0) {
            $fullOrderId = $request->input('orderId');
            $realOrderId = explode('-', $fullOrderId)[0];

            DB::table('orders')
                ->where('id', $realOrderId)
                ->update([
                    'order_status_id' => 1,
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'order_date' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);

            return redirect('/home')->with('success', '✅ Thanh toán thành công! Đơn hàng đã được tạo.');
        }

        return redirect('/checkout')->with('error', '❌ Thanh toán thất bại hoặc bị huỷ.');
    }

    /**
     * Lưu transaction MoMo
     *
     * @param Request $request
     * @return void
     */
    private function storeMomoTransaction(Request $request)
    {
        MomoTransaction::create([
            'partner_code'  => $request->input('partnerCode'),
            'order_id'      => $request->input('orderId'),
            'request_id'    => $request->input('requestId'),
            'amount'        => $request->input('amount'),
            'order_info'    => $request->input('orderInfo'),
            'order_type'    => $request->input('orderType'),
            'trans_id'      => $request->input('transId'),
            'result_code'   => $request->input('resultCode'),
            'message'       => $request->input('message'),
            'pay_type'      => $request->input('payType'),
            'response_time' => Carbon::now('Asia/Ho_Chi_Minh'),
            'extra_data'    => $request->input('extraData'),
            'signature'     => $request->input('signature'),
        ]);
    }
}
