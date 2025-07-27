@extends('admin.layouts.app')
@section('content')
<div class="container mt-4">
    <h3>Sửa bình luận #{{ $comment->id }}</h3>
    <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Sản phẩm</label>
            <select name="product_id" class="form-control" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $comment->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Người dùng</label>
            <select name="user_id" class="form-control">
                <option value="">-- Admin (trả lời) --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $comment->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Rating</label>
            <select name="rating" class="form-control">
                <option value="">Không đánh giá</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}" {{ $comment->rating == $i ? 'selected' : '' }}>{{ $i }} ⭐</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label>Nội dung</label>
            <textarea name="content" class="form-control" required>{{ $comment->content }}</textarea>
        </div>

        <button class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
