@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Chi tiết đơn hàng #{{ $order->id }}</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </div>

    <!-- Thông báo -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Thông tin người nhận -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thông tin người nhận</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $order->recipient_name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->recipient_email ?? 'Không có' }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->recipient_phone }}</p>
                    <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->recipient_address }}</p>
                </div>
            </div>
        </div>

        <!-- Thông tin đơn hàng -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
                    <p class="mb-1"><strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $order->orderStatus->color ?? 'secondary' }} me-2">
                            {{ $order->orderStatus->status_name }}
                        </span>
                    </p>
                    <p class="mb-1"><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->method_name ?? 'Không có' }}</p>
                    @if($order->voucher_code)
                        <p class="mb-1"><strong>Mã giảm giá:</strong> {{ $order->voucher_code }}</p>
                    @endif
                    <p class="mb-0"><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                </div>
            </div>
        </div>

        <!-- Cập nhật đơn hàng -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Cập nhật đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="order_status_id" class="form-label">Trạng thái đơn</label>
                                <select name="order_status_id" id="order_status_id" class="form-select">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                            {{ $status->status_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sản phẩm đã đặt -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 80px;" class="text-center">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Phân loại</th>
                                <th class="text-end" style="width: 120px;">Giá</th>
                                <th class="text-center" style="width: 100px;">Số lượng</th>
                                <th class="text-end" style="width: 150px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalQuantity = 0;
                                $totalSubtotal = 0;
                            @endphp
                            @foreach($order->orderDetails as $detail)
                                @php
                                    $variant = $detail->productVariant;
                                    $product = $variant->product;
                                    $quantity = $detail->quantity ?? 1;
                                    $price = $variant->price ?? 9900000;
                                    $subtotal = $price * $quantity;
                                    $totalQuantity += $quantity;
                                    $totalSubtotal += $subtotal;
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">
                                        <img src="{{ asset('storage/' . $variant->image) }}" alt="Ảnh" width="60" class="img-fluid rounded">
                                    </td>
                                    <td class="align-middle">{{ $product->product_name }}</td>
                                    <td class="align-middle">{{ $variant->ram->value ?? '' }} / {{ $variant->storage->value ?? '' }} / {{ $variant->color->value ?? '' }}</td>
                                    <td class="text-end align-middle">{{ number_format($price, 0, ',', '.') }}đ</td>
                                    <td class="text-center align-middle">
                                        <input type="text" value="{{ $quantity }}" class="form-control text-center bg-light border-0" readonly>
                                    </td>
                                    <td class="text-end align-middle">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tổng số lượng:</strong></td>
                                <td class="text-center"><strong>{{ $totalQuantity }}</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Tổng tiền sản phẩm:</strong></td>
                                <td></td>
                                <td class="text-end"><strong>{{ number_format($totalSubtotal, 0, ',', '.') }}đ</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Phí ship:</strong></td>
                                <td></td>
                                <td class="text-end"><strong>{{ number_format($order->shipping_fee ?? 30000, 0, ',', '.') }}đ</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="4" class="text-end"><strong>Tổng tiền đơn hàng:</strong></td>
                                <td></td>
                                <td class="text-end"><strong>{{ number_format($totalSubtotal + ($order->shipping_fee ?? 30000), 0, ',', '.') }}đ</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection