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
        /* Căn giữa form */
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px; /* Hạn chế chiều rộng của form */
            margin: 0 auto; /* Căn giữa form */
        }

        /* Căn giữa tiêu đề và di chuyển nút "Quay Lại" */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-title {
            text-align: center;
            flex: 1;
            font-weight: bold;
            font-size: 1.5rem;
            color: #333333;
        }

        .back-button {
            margin-right: auto;
        }

        /* Các kiểu nút */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
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

                    <!-- Header với nút Quay Lại và tiêu đề căn giữa -->
                    <div class="header-container">
                        <a href="{{ route('salary') }}" class="btn btn-danger back-button">
                            <i class="fas fa-arrow-left"></i> Quay Lại
                        </a>
                        <h1 class="header-title">Thêm Bậc Lương</h1>
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

                        <!-- Nút Thêm ở phía dưới cùng của form -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
