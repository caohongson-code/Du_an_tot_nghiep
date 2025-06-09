@extends('admin.layouts.app')

@section('title', 'Thêm biến thể sản phẩm')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Thêm biến thể sản phẩm</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('variants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="product_id" class="form-label">Sản phẩm</label>
            <select name="product_id" id="product_id" class="form-select" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ram_id" class="form-label">RAM</label>
            <select name="ram_id" id="ram_id" class="form-select" required>
                <option value="">-- Chọn RAM --</option>
                @foreach($rams as $ram)
                    <option value="{{ $ram->id }}" {{ old('ram_id') == $ram->id ? 'selected' : '' }}>
                        {{ $ram->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="storage_id" class="form-label">Storage</label>
            <select name="storage_id" id="storage_id" class="form-select" required>
                <option value="">-- Chọn Storage --</option>
                @foreach($storages as $storage)
                    <option value="{{ $storage->id }}" {{ old('storage_id') == $storage->id ? 'selected' : '' }}>
                        {{ $storage->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="color_id" class="form-label">Màu</label>
            <select name="color_id" id="color_id" class="form-select" required>
                <option value="">-- Chọn màu --</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                        {{ $color->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}" required min="0" step="0.01" />
        </div>

        <div class="mb-3">
            <label for="discount_price" class="form-label">Giá khuyến mãi (nếu có)</label>
            <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ old('discount_price') }}" min="0" step="0.01" />
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Số lượng</label>
            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') ?? 1 }}" required min="0" />
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh biến thể (tùy chọn)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*" />
        </div>

        <button type="submit" class="btn btn-primary">Thêm biến thể</button>
        <a href="{{ route('variants.index') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
</div>

@endsection
