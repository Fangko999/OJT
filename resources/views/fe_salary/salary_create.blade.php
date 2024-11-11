<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý bậc lương</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Thêm CSS tùy chỉnh -->
    <style>
        /* Toàn bộ giao diện */
        body {
            background-color: #f4f4f4; /* Màu nền sáng cho toàn bộ */
            color: #333; /* Màu chữ tối */
        }

        /* Sidebar */
        .sidebar {
            background-color: #333; /* Sidebar màu xám đậm */
        }

        .sidebar .nav-link {
            color: #ccc; /* Liên kết màu xám nhạt */
        }

        .sidebar .nav-link:hover {
            background-color: #444; /* Màu nền khi hover */
            color: #fff;
        }

        .sidebar .nav-link.active {
            background-color: #007bff; /* Màu xanh cho liên kết active */
            color: #fff;
        }

        /* Header */
        .bg-white {
            background-color: #fff; /* Màu nền trắng cho header */
        }

        .h3, h1, .form-label {
            color: #333333; /* Màu chữ tối cho tiêu đề */
        }

        /* Nút */
        .btn-primary {
            background-color: #007bff; /* Nút màu xanh */
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Màu xanh khi hover */
            border-color: #004085;
        }

        .btn-secondary {
            background-color: #6c757d; /* Nút màu xám */
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268; /* Nút màu khi hover */
            border-color: #545b62;
        }

        /* Footer */
        .sticky-footer {
            background-color: #333; /* Footer xám đậm */
            color: #fff;
        }

        footer {
            background-color: #333; /* Màu xám đậm cho footer */
            color: #fff;
            padding: 20px;
        }

        .copyright {
            text-align: center;
        }

        /* Form */
        .form-control {
            background-color: #fff; /* Nền trắng cho input */
            border: 1px solid #ccc; /* Đường viền xám */
        }

        .form-control:focus {
            border-color: #007bff; /* Đổi màu viền khi focus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Cấu trúc form */
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }
        
        /* Giao diện tinh giản */
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Thêm Bậc Lương</h1>
                    </div>

                    
                    <form action="{{ route('salary.store') }}" method="POST" class="form-container">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên Bậc Lương:</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   placeholder="Nhập tên bậc lương" required>
                        </div>

                        <div class="form-group">
                            <label for="monthly_salary">Lương Theo Tháng:</label>
                            <input type="text" name="monthly_salary" id="monthly_salary"
                                   class="form-control money-input" placeholder="Nhập lương tháng" required>
                        </div>

                        <div class="form-group">
                            <label for="daily_salary">Lương Theo Ngày:</label>
                            <input type="text" name="daily_salary" id="daily_salary"
                                   class="form-control money-input" placeholder="Nhập lương ngày" required>
                        </div>

                        <!-- Nút Quay Lại và Thêm ở cùng một hàng -->
                        <div class="btn-container">
                            <a href="{{ route('salary') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© {{ date('Y') }} Your Company. All Rights Reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js"></script>

    <!-- Initialize Cleave.js -->
    <script>
    const cleaveMonthly = new Cleave('#monthly_salary', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalScale: 0,
        suffix: ' ₫'
    });

    const cleaveDaily = new Cleave('#daily_salary', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalScale: 0,
        suffix: ' ₫'
    });

    const monthlyInput = document.getElementById('monthly_salary');
    const dailyInput = document.getElementById('daily_salary');

    // Trước khi gửi form, loại bỏ dấu phân cách ngàn và ký tự ₫
    const form = document.querySelector('form');
    form.addEventListener('submit', function (event) {
        // Loại bỏ dấu phẩy và ₫ khi gửi dữ liệu
        monthlyInput.value = monthlyInput.value.replace(/,/g, '').replace(' ₫', '');
        dailyInput.value = dailyInput.value.replace(/,/g, '').replace(' ₫', '');
    });
    </script>
</body>
</html>
