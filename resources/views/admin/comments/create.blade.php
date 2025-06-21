@extends('admin.layouts.app')

@section('content')
<h2>Thêm bình luận mới</h2>

<form method="POST" action="{{ route('comments.store') }}">
    @csrf

    <div class="form-group">
        <label>Sản phẩm:</label>
        <select name="product_id" class="form-control" required>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Người bình luận:</label>
        <select name="user_id" class="form-control" required>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->full_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Nội dung:</label>
        <textarea name="content" class="form-control" required></textarea>
    </div>

    <div class="form-group">
        <label>Phản hồi cho bình luận (nếu có):</label>
        <input type="number" name="parent_id" class="form-control" placeholder="ID bình luận gốc (nếu phản hồi)">
    </div>

    <button type="submit" class="btn btn-primary">Gửi</button>
</form>
@endsection
