@extends('admin.layouts.auth')



@section('content')
<div class="w-full min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 login-form transition-all duration-300">
        <h2 class="login-title text-3xl font-bold text-center text-orange-500 mb-6">Đăng nhập Admin</h2>

        {{-- Thông báo lỗi --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('taikhoan.login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-400">
                        <i class="mdi mdi-email-outline text-lg"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="pl-10 pr-4 py-2 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                       >
                </div>
                @error('email')
                    <p class="text-sm text-red-500 mt-1 error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-400">
                        <i class="mdi mdi-lock-outline text-lg"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="pl-10 pr-10 py-2 w-full border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-400 focus:outline-none transition"
                          >
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 transition"
                          onclick="togglePassword()">
                        <i id="eye-icon" class="mdi mdi-eye-off-outline text-lg"></i>
                    </span>
                </div>
                @error('password')
                    <p class="text-sm text-red-500 mt-1 error-message">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-orange-500 text-white font-semibold py-2 rounded-xl hover:bg-orange-600 transition duration-200">
                Đăng nhập
            </button>
        </form>
    </div>
</div>

{{-- Script ẩn/hiện mật khẩu --}}
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");
        const isHidden = passwordInput.type === "password";

        passwordInput.type = isHidden ? "text" : "password";
        eyeIcon.classList.toggle("mdi-eye-outline", isHidden);
        eyeIcon.classList.toggle("mdi-eye-off-outline", !isHidden);
    }
</script>
@endsection
