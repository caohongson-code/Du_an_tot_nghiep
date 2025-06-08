@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa danh mục')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Chỉnh sửa danh mục</h2>

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

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="category_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old('category_name', $category->category_name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
        </div>


        <button type="submit" class="btn btn-primary">Cập nhật danh mục</button>
    </form>
</div>

@endsection
