<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quản lý chấm công</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
        }

        .container-fluid {
            padding: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #4e73df;
            color: white;
            padding: 15px;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #858796;
        }

        .table th,
        .table td {
            padding: 12px;
            vertical-align: middle;
            border-top: 1px solid #e3e6f0;
        }

        .table thead th {
            background-color: #f8f9fc;
            color: #4e73df;
            border-bottom: 2px solid #e3e6f0;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-secondary {
            background-color: #858796;
            border-color: #858796;
        }

        .btn-secondary:hover {
            background-color: #6c757d;
            border-color: #5a6268;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')



                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Xem giải trình</h4>
                        </div>
                        <div class="card-body">
                            @if(session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            <table class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>STT</th>
                                        <th>Nhân viên</th>
                                        <th>Thời gian</th>
                                        <th>Hoạt động</th>
                                        <th>Lý do giải trình</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($invalidAttendances->isEmpty())
                                    <tr>
                                        <td colspan="6">Hiện không có lý do giải trình nào</td>
                                    </tr>
                                    @else
                                    @foreach ($invalidAttendances as $attendance)
                                    @if ($attendance->status == 2)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ $attendance->time->format('H:i d/m/Y') }}</td>
                                        <td>{{ ucfirst($attendance->type) }}</td>
                                        <td>{{ $attendance->justification }}</td>
                                        <td>
                                            <form action="{{ route('admin.approveAttendance', $attendance->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Chấp nhận lý do</button>
                                            </form>
                                            <form action="{{ route('admin.rejectAttendance', $attendance->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">Từ chối lý do</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <!-- Hiển thị phân trang -->
                            {{ $invalidAttendances->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© {{ date('Y') }} Your Company. All Rights Reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JavaScript -->
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
</body>

</html>