@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <h2>Thêm danh mục</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('danhmuc.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="category_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                @error('category_name')
    <small class="text-danger">{{ $message }}</small>
@enderror
                <input type="text" name="category_name" id="category_name" class="form-control" value="{{ old('category_name') }}" >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description')
    <small class="text-danger">{{ $message }}</small>
@enderror
            </div>

            <button type="submit" class="btn btn-success">Thêm</button>
            <a href="{{ route('danhmuc.index') }}" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
@endsection
