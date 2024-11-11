<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-building"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Quản lý nhân viên</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Phòng ban -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('departments') }}">
            <i class="fas fa-building"></i>
            <span>Phòng ban</span>
        </a>
    </li>
    <hr class="sidebar-divider">

    <!-- Nav Item - Nhân viên -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('users') }}">
            <i class="fas fa-users"></i>
            <span>Nhân viên</span>
        </a>
    </li>

    <!-- Nav Item - Quản lý chấm công -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('department.report') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Quản lý chấm công</span>
        </a>
    </li>
    
    <!-- Nav Item - Quản lý bậc lương -->
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('salary') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span >Quản lý bậc lương </span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
