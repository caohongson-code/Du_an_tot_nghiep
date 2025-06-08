<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top px-3">
    <a class="navbar-brand fw-bold text-primary" href="{{ url('/admin') }}">
        <i class="fas fa-mobile-alt me-2"></i> PowPow Admin
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
        aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="adminNavbar">
        <ul class="navbar-nav align-items-center">
            <!-- Nút Thông báo -->
            <li class="nav-item me-3">
                <a class="nav-link position-relative text-dark" href="#">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        3
                    </span>
                </a>
            </li>

            <!-- Tài khoản -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="adminDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://i.pravatar.cc/30" class="rounded-circle me-1" width="30" height="30" alt="Avatar">
                    Admin
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                    <li><a class="dropdown-item" href="#">Hồ sơ</a></li>
                    <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#">Đăng xuất</a></li>
                </ul>
            </li>
        </ul>
    </div>
<<<<<<< HEAD
</header>
=======
</header>
>>>>>>> origin/kiet
