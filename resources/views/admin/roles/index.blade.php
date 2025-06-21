@extends('admin.layouts.app')

@section('content')
<h1>Role List</h1>
<a href="{{ route('roles.create') }}" class="btn btn-success mb-2">Add Role</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->id }}</td>
            <td>{{ $role->role_name }}</td>
            <td>{{ $role->description }}</td>
            <td>
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this role?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
