@extends('admin.layouts.app')

@section('title', 'Danh sách giỏ hàng')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách giỏ hàng</h2>

    <form action="{{ route('carts.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Tìm theo tên hoặc email...">
        <button class="btn btn-primary"><i class="fas fa-search me-1"></i> Tìm</button>
    </div>
</form>


    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Số sản phẩm</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($carts as $cart)
                <tr>
                    <td>{{ $cart->id }}</td>
                    <td>{{ $cart->account->full_name ?? 'Không có' }}</td>
                    <td>{{ $cart->details->count() }}</td>
                    <td>{{ $cart->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('carts.show', $cart->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye me-1"></i> Chi tiết
                        </a>
                        <form action="{{ route('carts.destroy', $cart->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá giỏ hàng này?')">
                          @csrf
                          @method('DELETE')
                           <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                         </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Chưa có giỏ hàng nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
