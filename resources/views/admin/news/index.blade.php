@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Danh sách tin tức</h2>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ Thêm tin tức</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered shadow-sm bg-white">
        <thead class="table-light">
            <tr>
                <th style="width: 60px;">ID</th>
                <th>Tiêu đề</th>
                <th>Ảnh</th>
                <th>Ngày tạo</th>
                <th style="width: 160px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->title }}</td>
                <td>
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" width="100" height="60" style="object-fit: cover;">
                    @else
                        <span class="text-muted">Không ảnh</span>
                    @endif
                </td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xoá</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $news->links() }}
    </div>
</div>
@endsection
