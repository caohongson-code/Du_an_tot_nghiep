@extends('admin.layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Header --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">📦 Danh sách sản phẩm</h4>
            <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Tạo mới
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>{{ session('success') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tìm kiếm --}}
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc danh mục..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bảng danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Danh mục</th>
                            <th>Ảnh</th>
                            <th>Tên SP</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
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
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" width="80" class="img-thumbnail" alt="Ảnh sản phẩm">
                                    @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                                <td>
                                    @if($product->discount_price)
                                        {{ number_format($product->discount_price, 0, ',', '.') }} đ
                                    @else
                                        <span class="text-muted">-</span>
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
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm" title="Xem">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm" title="Sửa">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-muted text-center">Chưa có sản phẩm nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
