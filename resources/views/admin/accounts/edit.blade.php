@extends('admin.layouts.app')

@section('content')
<h1>Edit Account</h1>

<form action="{{ route('accounts.update', $account->id) }}" method="POST"enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>

        <select name="role_id" class="form-control" >

        <select name="role_id" class="form-control" required>

            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $account->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" name="full_name" value="{{ $account->full_name }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar (URL)</label>
        <input type="file" name="avatar" class="form-control">
        @if($account->avatar)
        <img src="{{ asset('storage/' . $account->avatar) }}" alt="Ảnh " width="100px">
    @else
        <img src="{{ asset('storage/uploads/quantri/default.jpg') }}" alt="Ảnh mặc định" width="100px">
    @endif
    </div>

    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" value="{{ $account->date_of_birth }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" value="{{ $account->email }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ $account->phone }}" class="form-control" maxlength="10">
    </div>

    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-control" required>
            <option value="1" {{ $account->gender == 1 ? 'selected' : '' }}>Male</option>
            <option value="0" {{ $account->gender == 0 ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" class="form-control">{{ $account->address }}</textarea>
    </div>


    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
