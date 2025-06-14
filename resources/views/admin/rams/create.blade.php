@extends('admin.layouts.app')
@section('title', 'Thêm ram')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Thêm ram</h2>
    <form action="{{ route('rams.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="value" class="form-label">Tên ram</label>
            <input type="text" name="value" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm</button>
    </form>
</div>
@endsection
