@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chi tiết sản phẩm: {{ $product->product_name }}</h2>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" width="300px" alt="..." />
                    @else
                        <p class="text-muted">Chưa có ảnh sản phẩm</p>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>ID:</th>
                                <td>{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <th>Tên sản phẩm:</th>
                                <td>{{ $product->product_name }}</td>
                            </tr>
                            <tr>
                                <th>Giá:</th>
                                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            </tr>
                            <tr>
                                <th>Giá khuyến mãi:</th>
                                <td>
                                    @if($product->discount_price)
                                        {{ number_format($product->discount_price, 0, ',', '.') }} đ
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Số lượng:</th>
                                <td>{{ $product->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($product->status)
                                        <span class="badge bg-success">Hiển thị</span>
                                    @else
                                        <span class="badge bg-secondary">Ẩn</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo:</th>
                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Lượt xem:</th>
                                <td>{{ $product->views ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th>Mô tả:</th>
                                <td>{!! nl2br(e($product->description)) !!}</td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i> Sửa sản phẩm
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Xóa sản phẩm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
