@extends('client.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Xin chào, {{ Auth::user()->name }}</h2>
    <ul>
        <li><a href="{{ route('user.profile') }}">👉 Thông tin cá nhân</a></li>
        <li><a href="{{ route('user.orders') }}">👉 Quản lý đơn hàng</a></li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                👉 Đăng xuất
            </a>
            <form id="logout-form" action="{{ route('taikhoan.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
@endsection
