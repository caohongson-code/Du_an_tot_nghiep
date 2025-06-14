@extends('admin.layouts.app')
@section('title', 'Thêm màu')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thêm màu</h2>
    <form action="{{ route('colors.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="value" class="form-label">Tên màu</label>
            <input type="text" name="value" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>
@endsection
