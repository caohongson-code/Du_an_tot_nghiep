@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Thêm khuyến mãi</h2>

    {{-- Hiển thị lỗi validate --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('promotions.store') }}" method="POST">
        @csrf

        <input type="text" name="code" class="form-control mb-2" placeholder="Mã khuyến mãi" value="{{ old('code') }}">

        <textarea name="description" class="form-control mb-2" placeholder="Mô tả">{{ old('description') }}</textarea>

        <select name="discount_type" class="form-control mb-2">
            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
        </select>

        <input type="number" step="0.01" name="discount_value" class="form-control mb-2" placeholder="Giá trị giảm" value="{{ old('discount_value') }}">

        <input type="datetime-local" name="start_date" class="form-control mb-2" value="{{ old('start_date') }}">
        <input type="datetime-local" name="end_date" class="form-control mb-2" value="{{ old('end_date') }}">

        <input type="number" name="usage_limit" class="form-control mb-2" placeholder="Giới hạn lượt dùng (tuỳ chọn)" value="{{ old('usage_limit') }}">

        {{-- Gửi giá trị 0 khi không check --}}
        <input type="hidden" name="is_active" value="0">
        <label><input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Kích hoạt</label>

        <button class="btn btn-success mt-3">Tạo mới</button>
    </form>
</div>
@endsection
