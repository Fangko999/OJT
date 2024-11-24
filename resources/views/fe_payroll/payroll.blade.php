<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Danh sách bậc lương</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Include Cleave.js -->
    <script src="https://cdn.jsdelivr.net/npm/cleave.js"></script>
</head>

<body id="page-top">
<div class="container pt-5">
        <!-- Flash messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content -->
        <div class="row">

            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center">
                    <form action="{{ route('payrolls.index') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..."
                                value="{{ $search }}" style="max-width: 250px;">
                            <button type="submit" class="btn btn-primary ml-2">Tìm kiếm</button>
                        </div>
                    </form>
                    <a href="{{ route('payrolls.export') }}" class="btn btn-success mb-3">Xuất file</a>
                </div>

                <!-- Bảng payrolls -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            <th>Hệ số lương</th>
                            <th>Số ngày công hợp lệ</th>
                            <th>Số ngày công không hợp lệ</th>
                            <th>Lương nhận được</th>
                            <th>Ngày tính lương</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->user->name }}</td>
                                <td>{{ $payroll->salary_coefficient }}</td>
                                <td>{{ $payroll->valid_days }}</td>
                                <td>{{ $payroll->invalid_days }}</td>
                                <td>{{ number_format($payroll->salary_received, 0) }} VND</td>
                                <td>{{ $payroll->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $payrolls->appends(request()->input())->onEachSide(2)->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>
</body>

</html>
