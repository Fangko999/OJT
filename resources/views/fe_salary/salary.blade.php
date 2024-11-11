<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Danh sách bậc lương</title>

    <!-- Custom fonts and styles-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        /* Cập nhật màu sắc chủ đạo */
        body {
            background-color: #f4f6f9; /* Màu nền xám sáng */
        }

        .input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .input-group input {
            border: none;
            padding: 15px;
            font-size: 16px;
            border-radius: 50px; /* Bo góc input */
        }

        .input-group input:focus {
            outline: none;
            box-shadow: none;
        }

        .input-group .btn {
            background-color: #6c757d; /* Màu xám cho nút */
            color: white;
            border: none;
            border-radius: 0;
        }

        .input-group .btn:hover {
            background-color: #5a6368; /* Đổi màu khi hover */
        }

        .action-btns a, .action-btns button {
            margin-right: 5px;
        }

        .action-btns .btn {
            border-radius: 5px;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table thead {
            background-color: #343a40; /* Nền xám tối cho tiêu đề bảng */
            color: white;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f8f9fc; /* Nền sáng cho các dòng lẻ */
        }

        .table tbody tr:nth-child(even) {
            background-color: #e9ecef; /* Nền sáng cho các dòng chẵn */
        }

        .btn-primary {
            background-color: #4e73df; /* Nút chính màu xanh dương */
            border: none;
        }

        .btn-primary:hover {
            background-color: #2e59d9; /* Đổi màu khi hover */
        }

        .btn-danger {
            background-color: #dc3545; /* Màu đỏ cho nút xóa */
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333; /* Đổi màu khi hover */
        }

        .alert-success {
            background-color: #28a745; /* Màu xanh cho thông báo thành công */
            color: white;
        }

        .alert {
            margin-bottom: 20px;
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
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Danh sách bậc lương</h1>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('salary.create') }}" class="btn btn-primary mr-2">Thêm bậc lương</a>
                    
                            <form method="GET" action="{{ route('salary') }}" class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="search_salary" class="sr-only">Nhập tên cấp bậc:</label>
                                    <input type="text" class="form-control mr-2" id="search_salary" name="search_salary" value="{{ request()->input('search_salary') }}" placeholder="Nhập tên bậc lương...">
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bậc lương</th>
                                    <th>Lương theo tháng</th>
                                    <th>Lương theo ngày</th>
                                    <th>Hành động </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($salarylevels as $salaryLevel)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $salaryLevel->name }}</td>
                                        <td>{{ number_format($salaryLevel->monthly_salary, 0, ',', '.') }} VND</td>
                                        <td>{{ number_format($salaryLevel->daily_salary, 0, ',', '.') }} VND</td>
                                        <td class="action-btns">
                                            <!-- Nút Xem -->
                                            <a href="{{ route('salary.show', $salaryLevel->id) }}" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>

                                            <!-- Nút Xóa -->
                                            <form action="{{ route('salary.destroy', $salaryLevel->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, ''); // Chỉ giữ lại số
            input.value = new Intl.NumberFormat('vi-VN').format(value); // Định dạng theo VN
        }
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>
</body>

</html>
