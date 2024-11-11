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

    <!-- Custom Styles -->
    <style>
        /* Cải thiện giao diện card */
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Nút Quay lại màu đỏ */
        .btn-secondary {
            background-color: #dc3545;
            /* Màu đỏ */
            border-color: #dc3545;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #c82333;
            /* Màu đỏ đậm khi hover */
            border-color: #bd2130;
        }

        /* Nền màu xám cho phần chữ "Thông tin bậc lương" */
        .card-header {
            background-color: #6c757d;
            /* Màu xám */
            font-size: 1.25rem;
            font-weight: bold;
            color: white;
            /* Màu chữ trắng */
        }

        /* Cải thiện phần container và card header */
        .container-fluid {
            padding-top: 20px;
        }

        /* Cải thiện alert */
        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #e2e3e5;
            /* Màu nền xám nhạt cho alert */
            color: #6c757d;
            /* Màu chữ xám */
        }

        /* Chỉnh sửa nút sửa trong modal */
        .modal-header .btn-close {
            background-color: #f8f9fc;
        }

        /* Cải thiện form trong modal */
        .form-group label {
            font-weight: 600;
            color: #6c757d;
            /* Màu xám cho label */
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #6c757d;
            /* Màu xám cho nút chính */
            border-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        /* Cải thiện giao diện cho các phần tử thông tin bậc lương */
        .font-weight-bold {
            font-weight: 700;
        }

        .badge-success {
            background-color: #28a745;
            /* Màu xanh cho trạng thái hoạt động */
        }

        .badge-danger {
            background-color: #dc3545;
            /* Màu đỏ cho trạng thái vô hiệu hóa */
        }

        .modal-dialog {
            max-width: 600px;
        }
    </style>

    <style>
        /* Định dạng tiêu đề trong card-body */
        .card-body .card-title,
        .card-body .card-text {
            font-weight: bold;
            /* In đậm */
            font-size: 1.2rem;
            /* Làm chữ to hơn */
        }

        /* Nếu bạn muốn tăng kích thước chữ cho các phần tử đặc biệt */
        .card-body .card-title {
            font-size: 1.5rem;
            /* Làm chữ tiêu đề lớn hơn */
        }

        .card-body .card-text {
            font-size: 1.2rem;
            /* Làm chữ cho các đoạn văn lớn hơn */
        }
    </style>

</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')

                <div class="d-flex justify-content-between">
                    <a href="{{ route('salary') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                </div>

                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4">
                        <h1 class="h3 mb-0 text-gray-800 mr-3">Chi tiết bậc lương</h1>
                        <!-- Nút chỉnh sửa mở modal -->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editModal">Chỉnh sửa</button>
                    </div>



                    <!-- Card thông tin bậc lương -->
                    <div class="card shadow-lg rounded">
                        <div class="card-body">
                            <h5 class="card-title">Tên bậc lương: <span
                                    class="font-weight-bold">{{ $salaryLevel->name }}</span></h5>
                            <p class="card-text">
                                <strong>Lương theo tháng:</strong>
                                <span
                                    class="font-weight-bold">{{ number_format($salaryLevel->monthly_salary, 0, '.', ',') }}
                                    VND</span>
                            </p>
                            <p class="card-text">
                                <strong>Lương theo ngày:</strong>
                                <span
                                    class="font-weight-bold">{{ number_format($salaryLevel->daily_salary, 0, '.', ',') }}
                                    VND</span>
                            </p>
                            <p class="card-text">
                                <strong>Trạng thái:</strong>
                                <span class="badge badge-{{ $salaryLevel->status == 1 ? 'success' : 'danger' }}">
                                    {{ $salaryLevel->status == 1 ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </p>
                            <p class="card-text">
                                <strong>Thời gian tạo:</strong>
                                <span class="font-weight-bold">{{ $salaryLevel->created_at->format('d/m/Y') }}</span>
                            </p>
                            <p class="card-text">
                                <strong>Thời gian cập nhật:</strong>
                                <span class="font-weight-bold">{{ $salaryLevel->updated_at->format('d/m/Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Modal Chỉnh sửa -->
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa bậc lương</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('salary.update', $salaryLevel->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="name">Tên bậc lương</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ $salaryLevel->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="monthly_salary">Lương theo tháng</label>
                                            <input type="text" class="form-control" id="monthly_salary"
                                                name="monthly_salary"
                                                value="{{ number_format($salaryLevel->monthly_salary, 0, '.', ',') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="daily_salary">Lương theo ngày</label>
                                            <input type="text" class="form-control" id="daily_salary"
                                                name="daily_salary"
                                                value="{{ number_format($salaryLevel->daily_salary, 0, '.', ',') }}"
                                                required>
                                        </div>
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select name="status" class="form-control" required>
                                                <option value="1" {{ $salaryLevel->status == 1 ? 'selected' : '' }}>Hoạt
                                                    động</option>
                                                <option value="0" {{ $salaryLevel->status == 0 ? 'selected' : '' }}>Không
                                                    hoạt động</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </form>
                                </div>
                            </div>
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
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>

    <!-- Custom JavaScript for Cleave.js -->
    <script>
        new Cleave('#monthly_salary', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });

        new Cleave('#daily_salary', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    </script>
</body>

</html>