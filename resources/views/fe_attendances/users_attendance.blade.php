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
            /* Light gray background for the body */
            color: #495057;
            /* Darker text color for contrast */
        }

        .navbar,
        .footer {
            background-color: #6c757d;
            /* Gray navbar and footer */
        }

        .table-dark {
            background-color: #343a40;
            /* Darker gray for table header */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f1f3f5;
            /* Lighter gray for odd rows */
        }

        .table-bordered {
            border: 1px solid #dee2e6;
            /* Light gray borders */
        }

        .btn-primary {
            background-color: #007bff;
            /* Blue for primary buttons */
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker blue for hover effect */
        }

        .btn-danger {
            background-color: #dc3545;
            /* Red for danger buttons */
            border-color: #dc3545;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            /* Darker red for hover effect */
        }

        .alert-success {
            background-color: #28a745;
            /* Green alert background */
            border-color: #28a745;
        }

        .alert-success .btn-close {
            color: #fff;
        }

        .sticky-footer {
            background-color: #6c757d;
            /* Matching gray for footer */
        }

        .scroll-to-top {
            background-color: #343a40;
            /* Darker background for the scroll-to-top button */
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

        /* Thêm hiệu ứng chuyển động */
        .alert {
            transition: all 0.5s ease-out;
            font-size: 1.1rem;
            padding: 1rem 1.5rem;
            border-radius: 0.375rem;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }

        /* Hiệu ứng đóng thông báo */
        .alert-dismissible .btn-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            opacity: 0.7;
        }

        .alert-dismissible .btn-close:hover {
            opacity: 1;
        }

        /* Tạo bóng cho thông báo */
        .alert {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Thêm hiệu ứng phóng to khi hiển thị thông báo */
        .fade.show {
            transform: scale(1.05);
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_user.slidebar') <!-- Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Topbar -->

                <div class="container-fluid">
                    <!-- Hiển thị thông báo nếu có -->
                    @if(session('message'))
                        <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show"
                            role="alert" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                            <strong>{{ session('status') === 'success' ? 'Thành công!' : 'Lỗi!' }}</strong>
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif


                    <!-- Bảng Lịch sử Check In/Out -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-4">
                                <div class="d-flex justify-content-center gap-3">
                                    <form action="{{ route('attendance.checkin') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-sign-in-alt"></i> Check In
                                        </button>
                                    </form>

                                    <form action="{{ route('attendance.checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg">
                                            <i class="fas fa-sign-out-alt"></i> Check Out
                                        </button>
                                    </form>
                                </div>
                                <h4 class="text-center text-dark">Ngày {{ date('d/m/Y') }}</h4>
                            </div>

                            <!-- Bảng Lịch sử Check In/Out -->
                            <table class="table table-striped table-bordered text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Nhân viên</th>
                                        <th>Hoạt động</th>
                                        <th>Thời gian</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $attendance->user->name }}</td>
                                            <td>{{ $attendance->type === 'in' ? 'Check In' : 'Check Out' }}</td>

                                            <td>{{ \Carbon\Carbon::parse($attendance->time)->format('H:i d/m/Y') }}</td>

                                            <td>
                                                {{ $attendance->status ? 'Hợp lệ' : 'Không hợp lệ' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
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
</body>

</html>