@extends('client.layouts.app')

@section('content')
<div class="container py-5">
    <h3>Xác nhận mua ngay</h3>

    @if (session('buy_now'))
        <ul>
            <li><strong>Sản phẩm ID:</strong> {{ session('buy_now.product_id') }}</li>
            <li><strong>Phiên bản ID:</strong> {{ session('buy_now.variant_id') ?? 'Không chọn' }}</li>
            <li><strong>Số lượng:</strong> {{ session('buy_now.quantity') }}</li>
        </ul>
        <form action="#" method="POST">
            @csrf
            <button class="btn btn-success">Xác nhận đặt hàng</button>
        </form>
    @else
        <p>Không có sản phẩm để thanh toán.</p>
    @endif
</div>
@endsection
