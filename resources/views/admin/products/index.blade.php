@extends('admin.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Danh sách sản phẩm</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('products.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus me-2"></i> Thêm sản phẩm mới
    </a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Giá khuyến mãi</th>
                <th>Số lượng</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Lượt xem</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->category->category_name ?? 'Không có' }}</td>
                    <td>
<<<<<<< HEAD
                        @if($product->image && file_exists(public_path('storage/' . $product->image)))
                            <img src="{{ asset('storage/' . $product->image) }}" width="70px" height="70px" alt="{{ $product->product_name }}">
                        @else
                            {{-- Nếu muốn ảnh mặc định: thay dòng dưới bằng ảnh như asset('images/default.png') --}}
=======
                        @if($product->image)
                           <img src="{{ asset('storage/' . $product->image) }}" width="100px" alt="..." />

                        @else
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
                            <span class="text-muted">Chưa có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                    <td>
                        @if($product->discount_price)
                            {{ number_format($product->discount_price, 0, ',', '.') }} đ
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        @if($product->status)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>{{ $product->created_at->format('d/m/Y') }}</td>
                    <td>{{ $product->views ?? 0 }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa sản phẩm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa sản phẩm">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
<<<<<<< HEAD
                    <td colspan="11" class="text-center">Chưa có sản phẩm nào.</td>
=======
                    <td colspan="10" class="text-center">Chưa có sản phẩm nào.</td>
>>>>>>> be89ce2cbccc5cd0cc791b738965a2f68a61ae19
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
