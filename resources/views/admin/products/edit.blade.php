@extends('admin.layouts.app')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Sửa sản phẩm: {{ $product->product_name }}</h2>

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

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product_name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}" required>
        </div>

       <div class="mb-3">
      <label for="category_id" class="form-label">Danh mục</label>
       <select class="form-select" id="category_id" name="category_id" required>
         <option value="">-- Chọn danh mục --</option>
          @foreach ($categories as $category)
          <option value="{{ $category->id }}" 
           {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
             {{ $category->category_name }}
             </option>
           @endforeach
        </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
           <input type="number" class="form-control" id="price" name="price"
             value="{{ old('price', number_format($product->price, 0, '', '')) }}" required>
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi</label>
             <input type="number" class="form-control" id="discount_price" name="discount_price"
            value="{{ old('discount_price', number_format($product->discount_price, 0, '', '')) }}">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">

            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" style="max-height: 150px; object-fit: contain;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select" id="status" name="status" required>
                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
    </form>
</div>
@endsection
