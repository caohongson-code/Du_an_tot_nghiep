@extends('admin.layouts.app')

@section('title', 'Quản lý nội bộ')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-3">
        {{-- Header --}}
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">👤 Quản lý nhân sự nội bộ</h4>
            <a href="{{ route('accounts.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Tạo mới
            </a>
        </div>

        {{-- Body --}}
        <div class="card-body">

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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

            {{-- Tìm kiếm --}}
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <form method="GET" action="{{ route('accounts.index') }}">
                        <div class="input-group input-group-sm">
                            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc chức vụ..." value="{{ request('keyword') }}">
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
                            <th>Ảnh</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Chức vụ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($listQT as $nv)
                        <tr>
                            <td>{{ $nv->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $nv->avatar) }}" alt="avatar" class="img-thumbnail" width="80">
                            </td>
                            <td>{{ $nv->full_name }}</td>
                            <td>{{ $nv->email }}</td>
                            <td>{{ $nv->role->role_name ?? 'Không rõ' }}</td>
                            <td>
                                @if ($admin && $admin->role_id == 1)
                                    <a href="{{ route('accounts.edit', $nv->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('accounts.destroy', $nv->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Xoá tài khoản này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Xoá
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted">Không có quyền</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center">Không có dữ liệu.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $listQT->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
