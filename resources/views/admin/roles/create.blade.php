@extends('admin.layouts.app')

@section('content')
<h1>Create New Role</h1>

<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="role_name" class="form-label">Role Name</label>
        <input type="text" name="role_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
