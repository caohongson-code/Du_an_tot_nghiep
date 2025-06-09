@extends('admin.layouts.app')
@section('title', 'Danh sách dung lượng')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Danh sách dung lượng</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('storages.create') }}" class="btn btn-success mb-3">Thêm dung lượng</a>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Giá trị</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($storages as $storage)
            <tr>
                <td>{{ $storage->id }}</td>
                <td>{{ $storage->value }}</td>
                <td>
                    <a href="{{ route('storages.edit', $storage->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                    <form action="{{ route('storages.destroy', $storage->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Xóa?');">
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