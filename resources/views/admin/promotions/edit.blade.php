@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Cập nhật khuyến mãi</h2>

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

        <input type="text" name="code" class="form-control mb-2" placeholder="Mã khuyến mãi"
            value="{{ old('code', $promotion->code) }}">

        <textarea name="description" class="form-control mb-2" placeholder="Mô tả">{{ old('description', $promotion->description) }}</textarea>

        <select name="discount_type" class="form-control mb-2">
            <option value="percentage" {{ old('discount_type', $promotion->discount_type) == 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
        </select>

        {{-- Giá trị giảm (hiển thị với định dạng số có dấu chấm) --}}
        <input type="text" name="discount_value" class="form-control mb-2" placeholder="Giá trị giảm"
            value="{{ old('discount_value', number_format($promotion->discount_value, 0, ',', '.')) }}">

        <input type="datetime-local" name="start_date" class="form-control mb-2"
            value="{{ old('start_date', date('Y-m-d\TH:i', strtotime($promotion->start_date))) }}">

        <input type="datetime-local" name="end_date" class="form-control mb-2"
            value="{{ old('end_date', date('Y-m-d\TH:i', strtotime($promotion->end_date))) }}">

        <input type="number" name="usage_limit" class="form-control mb-2" placeholder="Giới hạn lượt dùng"
            value="{{ old('usage_limit', $promotion->usage_limit) }}">

        <input type="hidden" name="is_active" value="0">
        <label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
            Kích hoạt
        </label>

        <button class="btn btn-primary mt-3">Cập nhật</button>
    </form>
</div>
@endsection
