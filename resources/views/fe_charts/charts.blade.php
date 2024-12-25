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
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #f8f9fc 25%, #e2e6ea 100%);
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-primary, .btn-secondary {
            background-color: #4e73df;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover, .btn-secondary:hover {
            background-color: #2e59d9;
            color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transform: translateY(-3px);
        }

        .btn-primary:focus, .btn-secondary:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        .btn-primary:active, .btn-secondary:active {
            transform: translateY(1px);
        }

        .btn-primary::after, .btn-secondary::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        .btn-primary:active::after, .btn-secondary:active::after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0;
        }

        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.02);
            opacity: 0.95;
        }

        h1 {
            color: #4e73df;
            font-weight: bold;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container-fluid {
            max-width: 900px;
            margin: 30px auto;
        }

        .text-center {
            margin-bottom: 20px;
        }

        footer {
            background-color: #f8f9fc;
            padding: 20px 0;
            backdrop-filter: blur(10px);
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column fade-in">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <!-- Biểu đồ tỷ lệ nhân viên giữa các phòng ban -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4">Biểu đồ thống kê</h1>
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
                                <a href="{{ route('salary.statistics') }}" class="btn btn-primary">
                                    <i class="fas fa-chart-bar"></i> Thống kê lương theo tháng
                                </a>
                                <a href="{{ route('seniority.ratio') }}" class="btn btn-primary">
                                    <i class="fas fa-user-clock"></i> Thống kê thâm niên theo phòng ban
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
                        <span>&copy; {{ date('Y') }} Your Company. All Rights Reserved.</span>
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

            $('.btn-primary').on('click', function () {
                $(this).addClass('loading');
                setTimeout(() => {
                    $(this).removeClass('loading');
                }, 2000);
            });
        });
    </script>
</body>

</html>