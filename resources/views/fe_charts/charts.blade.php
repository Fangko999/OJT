<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Biểu đồ thống kê</title>

    <!-- Font và CSS -->
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Custom CSS -->
    <style>
        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #4e73df; /* Màu xanh dương đậm */
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #2e59d9; /* Màu xanh dương sáng */
            color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            transform: scale(1.05);
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4e73df;
            font-weight: bold;
        }

        .container-fluid {
            max-width: 800px;
            margin: auto;
        }

        .text-center {
            margin-bottom: 20px;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <!-- Biểu đồ tỷ lệ nhân viên giữa các phòng ban -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Biểu đồ thống kê</h1>
                    <div class="card shadow mb-4">
                        <div class="card-body text-center">
                            <div class="button-container">
                                <a href="{{ route('employee.ratio') }}" class="btn btn-primary">
                                    <i class="fas fa-users"></i> Thống kê nhân sự giữa các phòng ban
                                </a>
                                <a href="{{ route('gender.ratio') }}" class="btn btn-primary">
                                    <i class="fas fa-venus-mars"></i> Thống kê giới tính theo phòng ban
                                </a>
                                <a href="{{ route('attendance.ratio.view') }}" class="btn btn-primary">
                                    <i class="fas fa-check-circle"></i> Tỉ lệ chấm công của nhân viên
                                </a>
                                <a href="{{ route('age.ratio') }}" class="btn btn-primary">
                                    <i class="fas fa-birthday-cake"></i> Thống kê độ tuổi của nhân viên theo phòng ban
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
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

    <!-- JavaScript -->
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            $('.card').hide().fadeIn(1000);
            $('.btn-primary').hover(
                function () {
                    $(this).addClass('animate__animated animate__pulse');
                },
                function () {
                    $(this).removeClass('animate__animated animate__pulse');
                }
            );
        });
    </script>
</body>

</html>
