@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý đơn hàng</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Đơn hàng</li>
    </ol>

    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label>Tìm kiếm</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Tên, email khách hàng..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label>Trạng thái đơn</label>
                <select name="order_status_id" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('order_status_id') == $status->id ? 'selected' : '' }}>
                            {{ $status->status_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Ngày đặt</label>
                <input type="date" name="order_date" class="form-control" value="{{ request('order_date') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Tìm
                </button>
            </div>
        </div>
    </form>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-table me-1"></i> Danh sách đơn hàng</span>
            <div>
                <span class="badge bg-success me-2">Tổng đơn: {{ $orders->count() }}</span>
                <span class="badge bg-info">Tổng tiền: {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}đ</span>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái đơn</th>
                        <th>Trạng thái giỏ</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-center">#{{ $order->id }}</td>
                        <td>
                            <strong>{{ $order->account->full_name ?? 'Không rõ' }}</strong><br>
                            <small class="text-muted">{{ $order->account->email ?? '---' }}</small>
                        </td>
                        <td class="text-center">
                            {{ $order->paymentMethod->method_name ?? 'Chưa chọn' }}
                        </td>
                        <td class="text-center">
                            <span class="badge @switch($order->orderStatus->status_name)
                                @case('Chưa xác nhận') bg-warning @break
                                @case('Đã xác nhận') bg-info @break
                                @case('Chưa thanh toán') bg-secondary @break
                                @case('Đã thanh toán') bg-primary @break
                                @case('Đang chuẩn bị giao hàng') bg-light @break
                                @case('Đang giao') bg-success @break
                                @case('Đã giao') bg-teal @break
                                @case('Đã nhận') bg-cyan @break
                                @case('Thành công') bg-success @break
                                @case('Hoàn hàng') bg-orange @break
                                @case('Hủy đơn') bg-danger @break
                                @default bg-secondary
                            @endswitch">
                                {{ $order->orderStatus->status_name }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if ($order->cart && $order->cart->statusModel)
                                <span class="badge @switch($order->cart->statusModel->name)
                                    @case('active') bg-success @break
                                    @case('ordered') bg-primary @break
                                    @case('cancelled') bg-danger @break
                                    @default bg-secondary
                                @endswitch">
                                    {{ $order->cart->statusModel->display_name ?? $order->cart->statusModel->name }}
                                </span>
                            @else
                                <span class="text-muted">Không có trạng thái giỏ</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : '---' }}
                        </td>
                        <td class="text-end text-nowrap">
                             {{ number_format($orders->sum('total_amount'), 0, ',', '.') }}đ
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="btn btn-sm btn-info mb-1">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            @if($order->order_status_id == 1) <!-- Chưa xác nhận -->
                                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="order_status_id" value="2"> <!-- Đã xác nhận -->
                                    <button type="submit" class="btn btn-sm btn-success mb-1"
                                            onclick="return confirm('Bạn có chắc chắn xác nhận đơn hàng này?')">
                                        <i class="fas fa-check"></i> Xác nhận
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Không có đơn hàng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection