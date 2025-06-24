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
            <input type="text" class="form-control" id="product_name" name="product_name"
                   value="{{ old('product_name', $product->product_name) }}" required>
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
                   value="{{ old('price', $product->price) }}" required>
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi</label>
            <input type="number" class="form-control" id="discount_price" name="discount_price"
                   value="{{ old('discount_price', $product->discount_price) }}">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                         style="max-height: 150px; object-fit: contain;">
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" id="quantity" name="quantity"
                   value="{{ old('quantity', $product->quantity) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select" id="status" name="status" required>
                <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <hr>
        <h4 class="mt-4">Biến thể sản phẩm</h4>

        @foreach ($product->variants as $index => $variant)
            <div class="card p-3 mb-3 border variant-group">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Màu sắc</label>
                        <select name="variants[{{ $index }}][color_id]" class="form-select" required>
                            <option value="">-- Chọn màu --</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}"
                                    {{ old("variants.$index.color_id", $variant->color_id) == $color->id ? 'selected' : '' }}>
                                    {{ $color->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">RAM</label>
                        <select name="variants[{{ $index }}][ram_id]" class="form-select" required>
                            <option value="">-- Chọn RAM --</option>
                            @foreach ($rams as $ram)
                                <option value="{{ $ram->id }}"
                                    {{ old("variants.$index.ram_id", $variant->ram_id) == $ram->id ? 'selected' : '' }}>
                                    {{ $ram->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dung lượng</label>
                        <select name="variants[{{ $index }}][storage_id]" class="form-select" required>
                            <option value="">-- Chọn dung lượng --</option>
                            @foreach ($storages as $storage)
                                <option value="{{ $storage->id }}"
                                    {{ old("variants.$index.storage_id", $variant->storage_id) == $storage->id ? 'selected' : '' }}>
                                    {{ $storage->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="variants[{{ $index }}][quantity]" class="form-control"
                               value="{{ old("variants.$index.quantity", $variant->quantity) }}" required>
                    </div>
                    <div class="col-md-3 mt-2">
                        <label class="form-label">Giá</label>
                        <input type="number" name="variants[{{ $index }}][price]" class="form-control"
                               value="{{ old("variants.$index.price", $variant->price) }}" required>
                    </div>
                  <div class="col-md-4 mt-2">
    <label class="form-label">Chọn ảnh mới (nếu muốn thay đổi)</label>
    <input type="file" name="color_images[{{ $variant->color_id }}]" class="form-control" accept="image/*">

    {{-- ID variant để xử lý update --}}
    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
    {{-- Ảnh cũ để giữ nếu không cập nhật ảnh --}}
    <input type="hidden" name="variants[{{ $index }}][old_image]" value="{{ $variant->image }}">

    {{-- Hiển thị ảnh hiện tại --}}
    @if($variant->image)
        <div class="mt-2">
            <p class="mb-1">Ảnh hiện tại:</p>
            <img src="{{ asset('storage/' . $variant->image) }}" alt="variant image" style="max-height: 80px; object-fit: contain;">
        </div>
    @endif
</div>

            </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-4">Cập nhật sản phẩm</button>
    </form>
</div>
@endsection
