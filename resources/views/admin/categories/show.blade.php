@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Chi tiết danh mục</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách
    </a>

    <div class="card">
        <div class="card-body">
            <h4>Tên danh mục:</h4>
            <p>{{ $category->category_name }}</p>

            <h4>Mô tả:</h4>
            <p>{{ $category->description ?? 'Không có mô tả' }}</p>

            <h4>Ngày tạo:</h4>
            <p>{{ $category->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>

@endsection
