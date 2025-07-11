{{-- resources/views/client/user/dashboard.blade.php --}}
@extends('client.layouts.app')

@section('content')
@push('styles')
<style>
    .tab-pane {
        display: block !important;
        opacity: 1 !important;
    }
</style>
@endpush

<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-dark text-white fw-bold">
                    Xin chào, {{ Auth::user()->name }}
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">👉 Thông tin cá nhân</a>
                    <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">👉 Quản lý đơn hàng</a>
                    <a href="#" class="list-group-item list-group-item-action text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        👉 Đăng xuất
                    </a>
                    <form id="logout-form" action="{{ route('taikhoan.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="col-md-9">
            <div class="card shadow-sm rounded-3">
                <div class="card-body">
                    @yield('dashboard-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- 👇 Thêm dòng này để các @push('scripts') hoạt động --}}
@stack('scripts')
