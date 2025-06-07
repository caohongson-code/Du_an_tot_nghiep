@extends('admin.layouts.app')
@section('title', 'Sửa màu')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Sửa màu</h2>
    <form action="{{ route('colors.update', $colors->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="value" class="form-label">Tên màu</label>
            <input type="text" name="value" class="form-control" value="{{ $colors->value }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection