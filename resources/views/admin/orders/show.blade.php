@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i> Chi tiết đơn hàng #{{ $order->id }}</h4>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
                <li class="breadcrumb-item active">Chi tiết</li>
            </ol>
        </div>

        <div class="card-body">
            {{-- Thông báo --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">

                {{-- Thông tin người nhận --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Thông tin người nhận</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Họ tên:</strong> {{ $order->recipient_name }}</p>
                            <p><strong>Email:</strong> {{ $order->recipient_email ?? 'Không có' }}</p>
                            <p><strong>Điện thoại:</strong> {{ $order->recipient_phone }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->recipient_address }}</p>
                        </div>
                    </div>
                </div>

                {{-- Thông tin đơn hàng --}}
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Thông tin đơn hàng</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Ngày đặt:</strong> {{ $order->order_date?->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái:</strong>
                                <span class="badge bg-{{ $order->orderStatus->color ?? 'secondary' }}">
                                    {{ $order->orderStatus->status_name }}
                                </span>
                            </p>
                            <p><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->method_name ?? 'Không có' }}</p>
                            @if($order->voucher_code)
                                <p><strong>Mã giảm giá:</strong> {{ $order->voucher_code }}</p>
                            @endif
                            <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Cập nhật trạng thái --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0">Cập nhật trạng thái đơn</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label for="order_status_id" class="form-label">Trạng thái đơn</label>
                                        <select name="order_status_id" class="form-select">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->id }}" {{ $order->order_status_id == $status->id ? 'selected' : '' }}>
                                                    {{ $status->status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary w-100 mt-3 mt-md-0">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h6 class="mb-0">Sản phẩm đã đặt</h6>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-striped table-bordered align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên SP</th>
                                        <th>Phân loại</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalQty = 0;
                                        $total = 0;
                                    @endphp
                                    @foreach($order->orderDetails as $d)
                                        @php
                                            $v = $d->productVariant;
                                            $product = $v->product;
                                            $qty = $d->quantity;
                                            $price = $v->price;
                                            $subtotal = $price * $qty;
                                            $totalQty += $qty;
                                            $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td><img src="{{ asset('storage/' . $v->image) }}" width="60" class="rounded"></td>
                                            <td class="text-start">{{ $product->product_name }}</td>
                                            <td>{{ $v->ram->value ?? '-' }} / {{ $v->storage->value ?? '-' }} / {{ $v->color->value ?? '-' }}</td>
                                            <td>{{ number_format($price, 0, ',', '.') }}đ</td>
                                            <td>{{ $qty }}</td>
                                            <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tổng SL:</strong></td>
                                        <td><strong>{{ $totalQty }}</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Tổng sản phẩm:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($total, 0, ',', '.') }}đ</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Phí ship:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($order->shipping_fee ?? 30000, 0, ',', '.') }}đ</strong></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="5" class="text-end"><strong>Tổng đơn hàng:</strong></td>
                                        <td class="text-end"><strong>{{ number_format($total + ($order->shipping_fee ?? 30000), 0, ',', '.') }}đ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- row -->
        </div> <!-- card-body -->
    </div> <!-- card -->
</div>
@endsection
