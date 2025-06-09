@extends('admin.layouts.app')

@section('title', 'Danh sách biến thể sản phẩm')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Danh sách biến thể sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('variants.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus me-2"></i> Thêm biến thể mới
    </a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>RAM</th>
                <th>Storage</th>
                <th>Màu</th>
                <th>Ảnh</th>
                <th>Giá</th>
                <th>Giá KM</th>
                <th>Số lượng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
@forelse($variants as $variant)
    <tr>
        <td>{{ $variant->id }}</td>
        <td>{{ $variant->product->product_name ?? 'Không có' }}</td>
        <td>{{ $variant->ram->value ?? '---' }}</td>
        <td>{{ $variant->storage->value ?? '---' }}</td>
        <td>{{ $variant->color->value ?? '---' }}</td>
        <td>
            @if($variant->image)
                <img src="{{ asset('storage/' . $variant->image) }}" width="70px" height="70px" alt="Ảnh biến thể" />
            @else
                <span class="text-muted">Chưa có ảnh</span>
            @endif
        </td>
<td>
    <strong style="font-size: 15px;">
        {{ number_format($variant->price, 0, ',', '.') }} đ
    </strong>
</td>
<td>
    @if($variant->discount_price)
        <strong style="color: red; font-size: 15px;">
            {{ number_format($variant->discount_price, 0, ',', '.') }} đ
        </strong>
    @else
        <span class="text-muted fst-italic">Không có</span>
    @endif
</td>

        <td>{{ $variant->quantity }}</td>
        <td>
            <a href="{{ route('variants.edit', $variant->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa biến thể">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" title="Xóa biến thể">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">Chưa có biến thể nào.</td>
    </tr>
@endforelse

        </tbody>
    </table>
</div>

@endsection
