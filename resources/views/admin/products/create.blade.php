@extends('admin.layouts.app')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thêm sản phẩm mới</h2>

    <a href="{{ route('products.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="product_name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
        </div>

       <div class="mb-3">
      <label for="category_id" class="form-label">Danh mục</label>
       <select class="form-select" id="category_id" name="category_id" required>
         <option value="">-- Chọn danh mục --</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->category_name }}
               </option>
           @endforeach
        </select>
        </div>


        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi</label>
            <input type="number" class="form-control" id="discount_price" name="discount_price" value="{{ old('discount_price') }}" step="0.01">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select" id="status" name="status" required>
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
    </form>
</div>
@endsection
