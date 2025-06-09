@extends('admin.layouts.app')

@section('title', 'Thêm danh mục mới')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Thêm danh mục mới</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="category_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old('category_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" rows="4" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="status" id="status" class="form-check-input" {{ old('status', true) ? 'checked' : '' }}>
            <label for="status" class="form-check-label">Hiển thị</label>
        </div>

        <button type="submit" class="btn btn-primary">Lưu danh mục</button>
    </form>
</div>

@endsection
