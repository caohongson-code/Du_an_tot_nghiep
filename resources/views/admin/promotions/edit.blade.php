@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Cập nhật khuyến mãi</h2>

    {{-- Hiển thị lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotions.update', $promotion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <input type="text" name="code" class="form-control" placeholder="Mã khuyến mãi"
                value="{{ old('code', $promotion->code) }}">
        </div>

        <div class="mb-3">
            <textarea name="description" class="form-control" placeholder="Mô tả">{{ old('description', $promotion->description) }}</textarea>
        </div>

        <div class="mb-3">
            <select name="discount_type" class="form-control">
                <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
            </select>
        </div>

        <div class="mb-3">
            <input type="text" name="discount_value" class="form-control" placeholder="Giá trị giảm"
                value="{{ old('discount_value', number_format($promotion->discount_value, 0, ',', '.')) }}">
        </div>

        <div class="mb-3">
            <input type="datetime-local" name="start_date" class="form-control"
                value="{{ old('start_date', date('Y-m-d\TH:i', strtotime($promotion->start_date))) }}">
        </div>

        <div class="mb-3">
            <input type="datetime-local" name="end_date" class="form-control"
                value="{{ old('end_date', date('Y-m-d\TH:i', strtotime($promotion->end_date))) }}">
        </div>

        <div class="mb-3">
            <input type="number" name="usage_limit" class="form-control" placeholder="Giới hạn lượt dùng"
                value="{{ old('usage_limit', $promotion->usage_limit) }}">
        </div>

        <input type="hidden" name="is_active" value="0">
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" class="form-check-input" value="1"
                {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
            <label class="form-check-label">Kích hoạt</label>
        </div>

        {{-- ✅ Chọn sản phẩm --}}
        <div class="mb-3">
            <label for="product_ids" class="form-label">Chọn sản phẩm được áp dụng:</label>
            <select name="product_ids[]" id="product_ids" class="form-control select2" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ in_array($product->id, old('product_ids', $selectedProductIds ?? [])) ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ✅ Chọn danh mục --}}
        <div class="mb-3">
            <label for="category_ids" class="form-label">Chọn danh mục được áp dụng:</label>
            <select name="category_ids[]" id="category_ids" class="form-control select2" multiple>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ in_array($category->id, old('category_ids', $selectedCategoryIds ?? [])) ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection

{{-- ✅ Thêm Select2 --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Chọn mục...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
