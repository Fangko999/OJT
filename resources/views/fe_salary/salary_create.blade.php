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
                    <h2 style="font-weight: bold">Thêm Bậc Lương</h2>
                    <a href="{{ route('salaryLevels') }}" class="btn btn-danger mb-3">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>

                    <form action="{{ route('salaryLevels.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Tên Bậc Lương</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="salary_coefficient" class="col-sm-2 col-form-label">Hệ Số Bậc Lương</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="salary_coefficient" name="salary_coefficient" step="any" value="{{ old('salary_coefficient') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="monthly_salary" class="col-sm-2 col-form-label">Lương theo tháng (VND)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="monthlySalary" name="monthly_salary" oninput="formatMoney(this)" value="{{ old('monthly_salary') }}" required>
                                @if ($errors->has('monthly_salary'))
                                    <span class="text-danger">{{ $errors->first('monthly_salary') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="daily_salary" class="col-sm-2 col-form-label">Lương theo ngày (VND)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="dailySalary" name="daily_salary" oninput="formatMoney(this)" value="{{ old('daily_salary') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Thêm Bậc Lương</button>
                            </div>
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

        <!-- Initialize Cleave.js -->
        <script>
            const cleaveMonthly = new Cleave('#monthlySalary', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalScale: 2,
                noImmediatePrefix: true
            });

            const monthlyInput = document.getElementById('monthlySalary');

            document.querySelector('form').addEventListener('submit', function(event) {
                const cleanedSalary = monthlyInput.value.replace(/[^0-9.]/g, '');
                monthlyInput.value = cleanedSalary;
            });

            const cleaveDaily = new Cleave('#dailySalary', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalScale: 2,
                noImmediatePrefix: true
            });

            const dailyInput = document.getElementById('dailySalary');

            document.querySelector('form').addEventListener('submit', function(event) {
                const cleanedDailySalary = dailyInput.value.replace(/[^0-9.]/g, '');
                dailyInput.value = cleanedDailySalary;
            });
        </script>

</body>

</html>