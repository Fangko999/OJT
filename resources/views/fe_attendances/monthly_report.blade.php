<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Báo cáo chấm công</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc; /* Màu nền xám sáng */
            color: #495057;
        }

        .navbar, .footer {
            background-color: #6c757d; /* Màu xám cho navbar và footer */
        }

        .container {
            margin-top: 30px;
        }

        h1, h2, h3 {
            color: #343a40; /* Màu chữ đậm */
            font-weight: bold;
        }

        .form-row {
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 0.375rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .table {
            border-radius: 0.375rem;
            overflow: hidden;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 1rem;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f1f3f5;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .alert {
            background-color: #6c757d;
            color: white;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-danger {
            background-color: #dc3545;
        }

        .alert-dismissible .btn-close {
            color: white;
        }

        .alert-dismissible .btn-close:hover {
            color: #ccc;
        }

        .table td {
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .scroll-to-top {
            background-color: #343a40;
            color: white;
        }

        .scroll-to-top:hover {
            background-color: #0056b3;
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
                        <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="container">
                        <h1>Báo cáo chấm công</h1>

                        <form action="{{ route('attendance.monthlyReport') }}" method="GET">
    <div class="form-row align-items-center">
        <div class="col-auto">
            <label for="month">Tháng:</label>
        </div>
        <div class="col-auto">
            <select name="month" id="month" class="form-control">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ $m }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-auto ml-3">
            <label for="year">Năm:</label>
        </div>
        <div class="col-auto">
            <select name="year" id="year" class="form-control">
                @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
        </div>
        <div class="col-auto ml-3">
            <button type="submit" class="btn btn-primary">Lọc</button>
        </div>
    </div>
</form>



                        <h2>Tên nhân viên: {{ $employeeData['name'] }}</h2>

                        <table class="table table-bordered mt-3">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Thời gian Check In</th>
                                    <th>Thời gian Check Out</th>
                                    <th>Thời gian làm việc</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employeeData['attendance'] as $date => $attendance)
                                    <tr>
                                        <td>{{ $date }}</td>
                                        <td>{{ $attendance['checkIn'] ? $attendance['checkIn']->format('H:i:s') : 'Chưa check-in' }}</td>
                                        <td>{{ $attendance['checkOut'] ? $attendance['checkOut']->format('H:i:s') : 'Chưa check-out' }}</td>
                                        <td>{{ gmdate('H:i:s', $attendance['hours'] * 3600) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
