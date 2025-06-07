@extends('admin.layouts.app')
@section('title', 'Sửa dung lượng')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Sửa màu</h2>
    <form action="{{ route('storages.update', $storages->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="value" class="form-label">Tên dung lượng</label>
            <input type="text" name="value" class="form-control" value="{{ $storages->value }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
</div>
@endsection