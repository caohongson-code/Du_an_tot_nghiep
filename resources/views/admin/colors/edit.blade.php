@extends('admin.layouts.app')
@section('content')
<div class="container mt-4">
    <h3>Danh sách bình luận</h3>
    <a href="{{ route('admin.comments.create') }}" class="btn btn-primary mb-3">Thêm mới</a>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th> 
                <th>Sản phẩm</th>
                <th>Người dùng</th>
                <th>Rating</th>
                <th>Nội dung</th>
                <th>Trả lời</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->product->name ?? 'N/A' }}</td>
                <td>{{ $comment->user->name ?? 'Admin' }}</td>
                <td>{{ $comment->rating ?? '-' }}</td>
                <td>{{ $comment->content }}</td>
                <td>
                    @foreach($comment->replies as $reply)
                        <div class="bg-light p-1 mb-1"><b>Admin:</b> {{ $reply->content }}</div>
                    @endforeach
                    <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST" class="mt-1">
                        @csrf
                        <input name="content" class="form-control mb-1" placeholder="Trả lời...">
                        <button class="btn btn-sm btn-success">Gửi</button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline-block">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Xóa?')" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $comments->links() }}
</div>
@endsection
