@extends('admin.layouts.app')

@section('title', 'Quản lý chức vụ')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">

        {{-- Header --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">🏷️ Danh sách chức vụ</h4>
            <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Thêm chức vụ
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body table-responsive">

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Bảng dữ liệu --}}
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th width="60px">ID</th>
                        <th class="text-start">Tên chức vụ</th>
                        <th class="text-start">Mô tả</th>
                        <th width="180px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td class="text-start">{{ $role->role_name }}</td>
                            <td class="text-start">{{ $role->description }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center">Không có chức vụ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Phân trang --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $roles->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
