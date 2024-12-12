<ul class="navbar-nav bg-gradient-secondary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index">
        <div class="sidebar-brand-icon rotate-n-15">
        </div>
        <div class="sidebar-brand-text mx-3">Chấm công nhân viên</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('attendance') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('attendance') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Chấm công</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('attendance.monthlyReport') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('attendance.monthlyReport') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Báo cáo chấm công</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('leave_requests.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('leave_requests.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Đơn xin nghỉ phép</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<script>
    $(document).ready(function() {
        // Khi một mục menu được nhấp vào
        $('.nav-link').on('click', function() {
            // Bỏ active khỏi tất cả các mục
            $('.nav-item').removeClass('active');
            // Thêm active cho mục đang được nhấp
            $(this).parent('.nav-item').addClass('active');
        });
    });
</script>

<style>
    /* Đặt màu xám làm màu chủ đạo cho sidebar */
    .sidebar {
        background-color: #6c757d; /* Màu xám cho sidebar */
    }

    .sidebar .nav-link {
        color: #fff; /* Màu chữ trắng cho các mục menu */
    }

    .sidebar .nav-link:hover {
        background-color: #495057; /* Màu nền xám tối khi hover */
    }

    .sidebar .sidebar-brand {
        background-color: #343a40; /* Nền xám tối cho brand */
    }

    .sidebar .sidebar-brand-text {
        color: #fff; /* Màu chữ trắng cho brand */
    }

    /* Chỉ mục hiện tại (active) sẽ có màu xanh */
    .sidebar .nav-item.active .nav-link {
        background-color: #007bff; /* Màu xanh cho mục được chọn */
        color: #fff;
    }

    .sidebar .nav-item .fas {
        color: #fff; /* Màu biểu tượng trắng */
    }

    .sidebar-divider {
        border-top: 1px solid #ced4da; /* Màu xám nhạt cho đường phân cách */
    }
</style>
