<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MomoController extends Controller
{
    public function generateQR(Request $request)
    {
        $amount = (int) $request->query('amount', 0);

        // Số tài khoản và mã ngân hàng MB của bạn
        $accountNumber = '0932331573';
        $bankBin = '970422';

        // Tạo URL QR từ VietQR (dạng ảnh PNG động)
        $qrUrl = "https://img.vietqr.io/image/{$bankBin}-{$accountNumber}-compact2.jpg?amount={$amount}&addInfo=ThanhToanDonHang";

        return redirect()->away($qrUrl);
    }
}
