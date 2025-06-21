<style>
    #sidebarMenu {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 56px;
        left: 0;
        overflow-y: auto;
        background-color: #f8f9fa;
        border-right: 1px solid #dee2e6;
        z-index: 1030;
    }

    main {
        margin-left: 250px;
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

    .submenu .nav-link {
        padding-left: 2.5rem;
        font-size: 0.95rem;
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


            <!-- Sản phẩm với menu con -->

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/variants*') ? 'active' : '' }}"
                    href="{{ url('/admin/variants') }}">
                    <i class="fas fa-th me-2"></i> Biến thể sản phẩm

                    <a class="nav-link text-dark d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#productSubMenu" role="button"
                        aria-expanded="{{ request()->is('admin/products*') || request()->is('admin/variants*') || request()->is('admin/colors*') || request()->is('admin/rams*') || request()->is('admin/storages*') ? 'true' : 'false' }}"
                        aria-controls="productSubMenu">
                        <span><i class="fas fa-box me-2"></i> Sản phẩm</span>
                        <i class="fas fa-chevron-down small"></i>


                    </a>

                    <div class="collapse {{ request()->is('admin/products*') || request()->is('admin/variants*') || request()->is('admin/colors*') || request()->is('admin/rams*') || request()->is('admin/storages*') ? 'show' : '' }}"
                        id="productSubMenu">
                        <ul class="nav flex-column ms-3 submenu">
                            <li class="nav-item">
                                <a class="nav-link text-dark {{ request()->is('admin/products*') ? 'active' : '' }}"
                                    href="{{ route('products.index') }}">Danh sách sản phẩm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark {{ request()->is('admin/variants*') ? 'active' : '' }}"
                                    href="{{ url('/admin/variants') }}">Biến thể</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark {{ request()->is('admin/colors*') ? 'active' : '' }}"
                                    href="{{ url('/admin/colors') }}">Màu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark {{ request()->is('admin/rams*') ? 'active' : '' }}"
                                    href="{{ url('/admin/rams') }}">RAM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark {{ request()->is('admin/storages*') ? 'active' : '' }}"
                                    href="{{ url('/admin/storages') }}">Dung lượng</a>
                            </li>
                        </ul>
                    </div>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/categories*') ? 'active' : '' }}"
                    href="{{ url('/admin/categories') }}">
                    <i class="fas fa-list-alt me-2"></i> Danh mục
                </a>
            </li>
            <li class="nav-item">


            <li class="nav-item">


                <a class="nav-link text-dark {{ request()->is('admin/rams*') ? 'active' : '' }}"
                    href="{{ url('/admin/rams') }}">
                    <i class="fas fa-memory me-2"></i> RAM
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/carts*') ? 'active' : '' }}"
                    href="{{ url('/admin/carts') }}">
                    <i class="fas fa-shopping-basket me-2"></i> Giỏ hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/storages*') ? 'active' : '' }}"
                    href="{{ url('/admin/storages') }}">
                    <i class="fas fa-hdd me-2"></i> Dung lượng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/promotions*') ? 'active' : '' }}"
                    href="{{ url('/admin/promotions') }}">
                    <i class="fas fa-tags me-2"></i> Khuyến mãi
                </a>
            </li>
            <li class="nav-item">


                <a class="nav-link text-dark {{ request()->is('admin/orders*') ? 'active' : '' }}"
                    href="{{ url('/admin/orders') }}">
                    <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/accounts*') ? 'active' : '' }}"
                    href="{{ url('/admin/accounts') }}">

                    <i class="fas fa-users me-2"></i> Quản trị
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/customers*') ? 'active' : '' }}"
                    href="{{ url('/admin/customers') }}">


                    <i class="fas fa-users me-2"></i> Người dùng
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/roles*') ? 'active' : '' }}"
                    href="{{ url('/admin/roles') }}">
                    <i class="fas fa-user-shield me-2"></i> Chức vụ
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/promotions*') ? 'active' : '' }}"
                    href="{{ url('/admin/promotions') }}">
                    <i class="fas fa-tags me-2"></i> Khuyến mãi
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/comments*') ? 'active' : '' }}"
                    href="{{ url('/admin/comments') }}">
                    <i class="fas fa-comments me-2"></i> Bình luận
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/interface*') ? 'active' : '' }}"
                    href="{{ url('/admin/interface') }}">
                    <i class="fas fa-image me-2"></i> Giao diện
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark {{ request()->is('admin/categories*') ? 'active' : '' }}"
                    href="{{ route('categories.index') }}">
                    <i class="fas fa-list-alt me-2"></i> Danh mục
                </a>
            </li>
        </ul>
    </div>
</nav>
