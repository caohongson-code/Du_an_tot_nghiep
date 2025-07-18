@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Chỉnh sửa tin tức</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title', $news->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea name="content" id="content" rows="6" class="form-control" required>{{ old('content', $news->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label><br>
            @if($news->image)
                <img src="{{ asset('storage/' . $news->image) }}" width="150" class="mb-3 rounded shadow-sm" style="object-fit: cover;">
            @else
                <p class="text-muted">Không có ảnh</p>
            @endif
            <input type="file" name="image" class="form-control mt-2">
        </div>

        <button class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#content')).catch(error => console.error(error));
</script>
@endsection
