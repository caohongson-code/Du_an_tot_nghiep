@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Cập nhật danh mục</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('danhmuc.update',$danhmucs->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="category_name" class="form-label">Tên danh mục</label>
                <input type="text" name="category_name" id="category_name"
                       placeholder="Nhập tên danh mục"
                       class="form-control @error('category_name') is-invalid @enderror"
                       value="{{ old('category_name', $danhmucs->category_name) }}">
                @error('category_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $danhmucs->description) }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('danhmuc.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
