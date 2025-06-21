@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Danh sách bình luận</h3>
    <a href="{{ route('comments.create') }}" class="btn btn-primary mb-3">Thêm bình luận mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Nội dung</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->product->product_name ?? 'N/A' }}</td>
                    <td>{{ $comment->user->full_name ?? 'N/A' }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>
                        <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Xóa bình luận này?')" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $comments->links() }}
</div>
@endsection
 