@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Sửa bình luận</h3>
    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" class="form-control" required>{{ old('content', $comment->content) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Cập nhật</button>
        <a href="{{ route('comments.index') }}" class="btn btn-secondary mt-2">Quay lại</a>
    </form>
</div>
@endsection
