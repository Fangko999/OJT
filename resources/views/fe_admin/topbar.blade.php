<nav class="navbar navbar-expand navbar-dark bg-secondary topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Search -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="p-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-light" type="submit">Tìm</button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-white">{{ auth()->user()->name ?? 'Khách' }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('fe-access/img/undraw_profile.svg') }}" alt="User Profile">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if (auth()->user() && auth()->user()->role == 2)
                <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Thông tin cá nhân
                </a>
                @endif
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="return confirmLogout();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Đăng xuất
                </a>
            </div>
        </li>

    </ul>

    <script>
        function confirmLogout() {
            if (confirm("Bạn có muốn đăng xuất không?")) {
                window.location.href = "{{ route('logout') }}";
            }
            return false; // Ngăn chặn hành động mặc định nếu nhấn Cancel
        }
    </script>
</nav>

<style>
    .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    /* Cải thiện kiểu dáng cho navbar */
    .navbar {
        padding: 0.5rem 1rem; /* Thay đổi padding */
        background-color: #6c757d; /* Màu xám cụ thể */
    }

    /* Cải thiện kiểu dáng cho nút dropdown */
    .dropdown-menu {
        border-radius: 0.5rem; /* Bo góc cho dropdown */
    }

    /* Cải thiện kiểu dáng cho ảnh đại diện người dùng */
    .img-profile {
        width: 40px; /* Kích thước ảnh đại diện */
        height: 40px;
        object-fit: cover; /* Đảm bảo ảnh không bị méo */
    }
</style>
