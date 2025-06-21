@extends('admin.layouts.app')

@section('content')
<h1>Edit Role</h1>

<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="role_name" class="form-label">Role Name</label>
        <input type="text" name="role_name" class="form-control" value="{{ $role->role_name }}" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ $role->description }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
