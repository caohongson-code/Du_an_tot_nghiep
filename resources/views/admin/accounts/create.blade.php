@extends('admin.layouts.app')

@section('content')
<h1>Create New Account</h1>

<form action="{{ route('accounts.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" class="form-control" required>
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar (URL)</label>
        <input type="file" name="avatar" class="form-control">
    </div>

    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" maxlength="10">
    </div>

    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-control" required>
            <option value="1">Nam</option>
            <option value="0">Nữ</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
