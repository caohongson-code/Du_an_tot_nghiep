@extends('admin.layouts.app')

@section('title', 'Danh sách danh mục')

@section('content')

<div class="container py-4">
    <h2 class="mb-4">Danh sách danh mục</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus me-2"></i> Thêm danh mục mới
    </a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
             
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->category_name }}</td>
                    <td>{{ $category->description ?? '-' }}</td>
                    <td>{{ $category->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm me-1" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm me-1" title="Sửa danh mục">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa danh mục">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Chưa có danh mục nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
