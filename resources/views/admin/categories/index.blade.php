@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Header --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">📁 Quản lý danh mục</h4>
            <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Tạo mới
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Tìm kiếm --}}
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <form method="GET" action="{{ route('categories.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo mã hoặc tên..." value="{{ request('keyword') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danh sách --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
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
                                <td class="text-start">{{ $category->category_name }}</td>
                                <td class="text-start">{{ $category->description ?? '-' }}</td>
                                <td>{{ $category->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted text-center">Không có danh mục nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $categories->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
