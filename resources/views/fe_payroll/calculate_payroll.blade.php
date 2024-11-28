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
    <div id="wrapper">
        @include('fe_admin.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')

                <div class="container pt-5 mb-5">
                    <div class="row">
                        <div class="col-md-9">
                            <h2 class="font-weight-bold">Kết Quả Tính Lương</h2>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Nhân viên</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tên bậc lương</th>
                                    <td>{{ $nameSalary }}</td>
                                </tr>
                                <tr>
                                    <th>Hệ số lương</th>
                                    <td>{{ $salaryCoefficient }}</td>
                                </tr>
                                <tr>
                                    <th>Số ngày công hợp lệ</th>
                                    <td>{{ $validDays }}</td>
                                </tr>
                                <tr>
                                    <th>Số ngày công không hợp lệ</th>
                                    <td>{{ $invalidDays }}</td>
                                </tr>
                                <tr>
                                    <th>Lương nhận được</th>
                                    <td>{{ number_format($salaryReceived, 0) }} VND</td>
                                </tr>
                            </table>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('payroll.store') }}" class="btn btn-success"
                                    onclick="event.preventDefault(); 
                                         document.getElementById('payroll-form').submit();">
                                    Lưu
                                </a>
                                <a href="{{ route('payroll.form') }}" class="btn btn-danger mb-3">
                                    Quay lại
                                </a>
                            </div>
                            <form id="payroll-form" action="{{ route('payroll.store') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="hidden" name="salary_received" value="{{ $salaryReceived }}">
                                <input type="hidden" name="valid_days" value="{{ $validDays }}">
                                <input type="hidden" name="invalid_days" value="{{ $invalidDays }}">
                                <input type="hidden" name="name_salary" value="{{ $nameSalary }}">
                                <input type="hidden" name="salary_coefficient" value="{{ $salaryCoefficient }}">
                            </form>
                        </div>
                    </div>
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

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>

</body>

</html>