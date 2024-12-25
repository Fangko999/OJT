<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chấm Công</title>

    <!-- Custom fonts for this template-->
    <link href="fe-access/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="fe-access/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            color: #495057;
        }

        .navbar,
        .footer {
            background-color: #6c757d;
        }

        .table-dark {
            background-color: #343a40;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f3f5;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .alert-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .alert-success .btn-close {
            color: #fff;
        }

        .sticky-footer {
            background-color: #6c757d;
        }

        .scroll-to-top {
            background-color: #343a40;
        }

        .card {
            background-color: #fff;
            border-radius: .375rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
        }

        .card-body {
            background-color: #f1f3f5;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 1rem;
        }

        .d-flex .btn {
            margin-right: 10px;
        }

        .alert {
            transition: all 0.5s ease-out;
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            border-radius: 0.375rem;
        }

        .alert-dismissible .btn-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            opacity: 0.7;
        }

        .alert-dismissible .btn-close:hover {
            opacity: 1;
        }

        .alert {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .fade.show {
            transform: scale(1.05);
        }

        /* Sidebar customization */
        .nav-group {
            margin-top: 1rem;
        }

        .nav-group .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-item .nav-link {
            padding: 0.75rem 1.25rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .nav-item .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Hover effect */
        .nav-item .nav-link:hover {
            background-color: #4e4e4e;
            transform: translateX(5px);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        /* Active link effect */
        .nav-item.active .nav-link {
            background-color: #6c757d;
            font-weight: bold;
            color: #fff;
            box-shadow: inset 4px 0 0 0 #007bff;
            transition: box-shadow 0.3s ease;
        }

        /* Focus effect */
        .nav-item .nav-link:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
            transition: box-shadow 0.3s ease;
        }

        /* Ripple effect */
        .nav-item .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-item .nav-link::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }

        .nav-item .nav-link:active::after {
            width: 200%;
            height: 200%;
        }

        /* Gradient background */
        .bg-gradient-dark {
            background: linear-gradient(180deg, #343a40 10%, #212529 100%);
            background-size: cover;
        }

        /* Text shadow */
        .sidebar-brand-text {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .sidebar-divider {
            margin: 1rem 0;
        }

        .sidebar.toggled .nav-item .nav-link span {
            display: none;
        }

        .sidebar.toggled .sidebar-brand-text {
            display: none;
        }

        .sidebar.toggled .sidebar-brand-icon {
            font-size: 1.5rem;
        }

        /* Button customization */
        .rounded-circle {
            border-radius: 50% !important;
        }

        .border-0 {
            border: 0 !important;
        }

        /* Loading button */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Pagination animation */
        .pagination .page-item .page-link {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .pagination .page-item .page-link:hover {
            background-color: #007bff;
            transform: scale(1.1);
        }

        /* Validation feedback */
        .is-invalid {
            border-color: #dc3545;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }

            75% {
                transform: translateX(-5px);
            }
        }

        /* Background blur */
        .bg-blur {
            backdrop-filter: blur(10px);
        }

        /* Typing effect */
        .typing-effect {
            overflow: hidden;
            white-space: nowrap;
            border-right: 0.15em solid #007bff;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        @keyframes blink-caret {
            from,
            to {
                border-color: transparent;
            }

            50% {
                border-color: #007bff;
            }
        }

        /* Text gradient */
        .text-gradient {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-user-tie"></i> <!-- Changed icon here -->
                </div>
                <div class="sidebar-brand-text mx-3">Quản lý nhân viên</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Grouped Navigation Items -->
            <div class="nav-group">



                <!-- Nav Item - Phòng ban -->
                <!-- Nav Item - Nhân viên -->
                <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('users') }}">
                        <i class="fas fa-users {{ request()->is('users*') ? 'text-primary' : '' }}"></i>
                        <span>Nhân viên</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('departments*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('departments') }}">
                        <i class="fas fa-sitemap {{ request()->is('departments*') ? 'text-primary' : '' }}"></i>
                        <span>Phòng ban</span>
                    </a>
                </li>



                <!-- Nav Item - Quản lý chấm công -->
                <li class="nav-item {{ request()->is('attendance/department-report*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('department.report') }}">
                        <i class="fas fa-file-alt {{ request()->is('department.report*') ? 'text-primary' : '' }}"></i>
                        <span>Báo cáo chấm công</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('admin/manage-attendances*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.manageAttendances') }}">
                        <i class="fas fa-clipboard-check {{ request()->is('admin.manageAttendances*') ? 'text-primary' : '' }}"></i>
                        <span>Xem giải trình</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->is('setting/edit*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('setting.edit') }}">
                        <i class="fas fa-cogs {{ request()->is('setting.edit*') ? 'text-primary' : '' }}"></i>
                        <span>Thời gian chấm công</span>
                    </a>
                </li>
                <!-- Nav Item - Quản lý bậc lương -->
                <li class="nav-item {{ request()->is('salaryLevels*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('salaryLevels') }}">
                        <i class="fas fa-money-check-alt {{ request()->is('salaryLevels*') ? 'text-primary' : '' }}"></i>
                        <span>Quản lý bậc lương</span></a>
                </li>

                <!-- Nav Item - Tính lương -->
                <li class="nav-item {{ request()->is('payroll/calculate*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('payroll.form') }}">
                        <i class="fas fa-money-check-alt {{ request()->is('payroll.form*') ? 'text-primary' : '' }}"></i>
                        <span>Tính lương</span></a>
                </li>


                <li class="nav-item {{ request()->is('payrolls*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('payrolls.index') }}">
                        <i class="fas fa-money-check-alt {{ request()->is('payrolls.index*') ? 'text-primary' : '' }}"></i>
                        <span>Xem bảng lương</span></a>
                </li>



                <li class="nav-item {{ request()->is('manage-leave_requests*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin_leave_requests.index') }}">
                        <i class="fas fa-chart-pie {{ request()->is('admin_leave_requests.index*') ? 'text-primary' : '' }}"></i>
                        <span>Đơn xin nghỉ phép</span></a>
                </li>

                <li class="nav-item {{ request()->is('chart*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('chart.view') }}">
                        <i class="fas fa-chart-pie {{ request()->is('chart.view*') ? 'text-primary' : '' }}"></i>
                        <span>Thống kê</span></a>
                </li>

            </div>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Content goes here -->
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="fe-access/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.getElementById('accordionSidebar').classList.toggle('toggled');
            });
        });
    </script>
</body>

</html>