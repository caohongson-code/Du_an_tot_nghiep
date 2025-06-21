@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">Cập nhật tài khoản</h1>

{{-- Hiển thị lỗi chung --}}
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Hiển thị lỗi validate --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('customers.update', $customers->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Họ tên --}}
    <div class="mb-3">
        <label for="full_name" class="form-label">Họ tên</label>
        <input type="text" name="full_name" value="{{ old('full_name', $customers->full_name) }}" class="form-control @error('full_name') is-invalid @enderror">
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Avatar --}}
    <div class="mb-3">
        <label class="form-label">Ảnh đại diện</label>
        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
        @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror

        @if($customers->avatar)
            <img src="{{ asset('storage/' . $customers->avatar) }}" alt="Ảnh đại diện" class="mt-2" width="100px">
        @else
            <img src="{{ asset('storage/uploads/quantri/default.jpg') }}" alt="Ảnh mặc định" class="mt-2" width="100px">
        @endif
    </div>

    {{-- Ngày sinh --}}
    <div class="mb-3">
        <label class="form-label">Ngày sinh</label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customers->date_of_birth) }}" class="form-control">
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $customers->email) }}" class="form-control @error('email') is-invalid @enderror" >
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Điện thoại --}}
    <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="phone" value="{{ old('phone', $customers->phone) }}" class="form-control @error('phone') is-invalid @enderror" maxlength="10">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Giới tính --}}
    <div class="mb-3">
        <label class="form-label">Giới tính</label>
        <select name="gender" class="form-control @error('gender') is-invalid @enderror" >
            <option value="">-- Chọn giới tính --</option>
            <option value="1" {{ old('gender', $customers->gender) == 1 ? 'selected' : '' }}>Nam</option>
            <option value="0" {{ old('gender', $customers->gender) == 0 ? 'selected' : '' }}>Nữ</option>
        </select>
        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Địa chỉ --}}
    <div class="mb-3">
        <label class="form-label">Địa chỉ</label>
        <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $customers->address) }}</textarea>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Mật khẩu cũ (nếu đổi) --}}
    <div class="mb-3">
        <label class="form-label">Mật khẩu cũ</label>
        <input type="password" name="old_password" class="form-control"   @error('old_password') is-invalid @enderror">
        @error('old_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Mật khẩu mới --}}
    <div class="mb-3">
        <label class="form-label">Mật khẩu mới</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Nhập lại mật khẩu --}}
    <div class="mb-3">
        <label class="form-label">Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
        @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
</form>
@endsection
