@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thêm tin tức</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Nội dung</label>
            <textarea name="content" id="content" rows="6" class="form-control" required>{{ old('content') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="btn btn-primary">Thêm mới</button>
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
