@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Thêm bình luận mới</h4>
        </div>
        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.comments.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="product_id" class="form-label fw-semibold">Sản phẩm <span class="text-danger">*</span></label>
                    <select name="product_id" id="product_id" class="form-select" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="user_id" class="form-label fw-semibold">Người dùng</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">-- Không xác định --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label fw-semibold">Trả lời bình luận (nếu có)</label>
                    <select name="parent_id" id="parent_id" class="form-select">
                        <option value="">-- Không trả lời ai --</option>
                        @foreach($comments as $comment)
                            <option value="{{ $comment->id }}">{{ Str::limit($comment->content, 50) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label fw-semibold">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" class="form-control" rows="4" placeholder="Nhập nội dung bình luận..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane me-1"></i> Thêm bình luận
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
