@extends('admin.layouts.app')

@section('content')
<h1>Create New Account</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Role
    <div class="mb-3">
        <label for="role_id" class="form-label">Role</label>
        <select name="role_id" class="form-control" >
            <option value="">-- Select Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ $role->role_name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div> --}}

    {{-- Full Name --}}
    <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
        @error('full_name')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Avatar --}}
    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar (Hình ảnh)</label>
        <input type="file" name="avatar" class="form-control">
        @error('avatar')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Date of Birth --}}
    <div class="mb-3">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
        @error('date_of_birth')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        @error('email')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Phone --}}
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" maxlength="10">
        @error('phone')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Gender --}}
    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-control" >
            <option value="">-- Select Gender --</option>
            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Nam</option>
            <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Nữ</option>
        </select>
        @error('gender')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- Address --}}
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" class="form-control">{{ old('address') }}</textarea>
        @error('address')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>

    {{-- mật khẩu  --}}
    <div class="mb-3">
        <label for="password" class="form-label" >Mật khẩu</label>
        <input type="password" name="password" class="form-control" value="{{old('password')}}" >
        @error('password')
            <p class="text-danger">{{ $message }}</p>
        @enderror</p>
    </div>
     {{-- Confirm Password --}}
     <div class="mb-3">
        <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control">
        @error('password_confirmation')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
