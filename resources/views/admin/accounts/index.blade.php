@extends('admin.layouts.app')

@section('content')
<h1>Account List</h1>
<a href="{{ route('accounts.create') }}" class="btn btn-success">Add Account</a>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->id }}</td>
                <td>{{ $account->full_name }}</td>
                <td>{{ $account->email }}</td>
                <td>{{ $account->role->role_name }}</td>
                <td>
                    <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this account?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
