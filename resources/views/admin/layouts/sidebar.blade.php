<style>
    #sidebarMenu {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 56px; /* Chiều cao của header cố định */
        left: 0;
        overflow-y: auto;
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        z-index: 1030;
    }

    main {
        margin-left: 250px; /* Khoảng cách với sidebar */
    }

    .nav-link.active {
        background-color: #0d6efd;
        color: white !important;
        font-weight: bold;
        border-radius: 0.375rem;
    }

    .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>

<nav id="sidebarMenu">
    <div class="pt-3 px-3">
        <!-- Brand -->
        <div class="text-center mb-4">
            <a href="{{ url('/admin') }}" class="text-decoration-none fs-5 fw-bold text-primary">
                <i class="fas fa-store me-2"></i> Quản trị
            </a>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('/admin') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
<<<<<<< HEAD
                <a class="nav-link text-dark {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ url('/admin/products') }}">
                    <i class="fas fa-box me-2"></i> Sản phẩm
=======
                <a class="nav-link text-dark {{ request()->is('admin/products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                   <i class="fas fa-box me-2"></i> Sản phẩm
>>>>>>> origin/kiet
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/variants*') ? 'active' : '' }}" href="{{ url('/admin/variants') }}">
                    <i class="fas fa-th me-2"></i> Biến thể sản phẩm
                </a>
            </li>
            <li class="nav-item">
<<<<<<< HEAD
=======
              <a class="nav-link text-dark {{ request()->is('admin/colors*') ? 'active' : '' }}" href="{{ url('/admin/colors') }}">
                  <i class="fas fa-palette me-2"></i> Màu
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/rams*') ? 'active' : '' }}" href="{{ url('/admin/rams') }}">
                    <i class="fas fa-memory me-2"></i> RAM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/storages*') ? 'active' : '' }}" href="{{ url('/admin/storages') }}">
                    <i class="fas fa-hdd me-2"></i> Dung lượng
                </a>
            </li>            
            <li class="nav-item">
>>>>>>> origin/kiet
                <a class="nav-link text-dark {{ request()->is('admin/orders*') ? 'active' : '' }}" href="{{ url('/admin/orders') }}">
                    <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ url('/admin/users') }}">
                    <i class="fas fa-users me-2"></i> Người dùng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/promotions*') ? 'active' : '' }}" href="{{ url('/admin/promotions') }}">
                    <i class="fas fa-tags me-2"></i> Khuyến mãi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/comments*') ? 'active' : '' }}" href="{{ url('/admin/comments') }}">
                    <i class="fas fa-comments me-2"></i> Bình luận
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/interface*') ? 'active' : '' }}" href="{{ url('/admin/interface') }}">
                    <i class="fas fa-image me-2"></i> Giao diện
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/categories*') ? 'active' : '' }}" href="{{ url('/admin/categories') }}">
                    <i class="fas fa-list-alt me-2"></i> Danh mục
                </a>
            </li>
        </ul>
    </div>
<<<<<<< HEAD
</nav>
=======
</nav>
>>>>>>> origin/kiet
