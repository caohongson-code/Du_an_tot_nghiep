@extends('admin.layouts.app')
@section('title', 'Danh sách màu')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách màu</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('colors.create') }}" class="btn btn-success mb-3">Thêm màu</a>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Giá trị</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($colors as $color)
            <tr>
                <td>{{ $color->id }}</td>
                <td>{{ $color->value }}</td>
                <td>
                    <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection