@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Danh sách bình luận</h3>

    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div> 
    @endif

    <a href="{{ route('admin.comments.create') }}" class="btn btn-success mb-3">
        <i class="fas fa-plus"></i> Thêm bình luận mới
    </a>    

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Trả lời</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr>
                <td>{{ $comment->user->full_name ?? 'Admin' }}</td>
<td>{{ $comment->product->product_name }}</td>

                <td>{{ $comment->content }}</td>
                <td>
                    @foreach($comment->replies as $reply)
                        <div>
                            <b>{{ $reply->user->full_name ?? 'Admin' }}:</b> {{ $reply->content }}
                        </div>
                    @endforeach

                    <form action="{{ route('admin.comments.reply', $comment->id) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="text" name="content" class="form-control mb-1" placeholder="Trả lời...">
                        <button class="btn btn-sm btn-success">Gửi</button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline-block">
                        @csrf 
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa bình luận này?')">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
