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
                <div class="container-fluid">
                    <h2 class="font-weight-bold">Chỉnh sửa bậc lương</h2>
                    <a href="{{ route('salaryLevels') }}" class="btn btn-danger mb-3">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                    <form action="{{ route('salaryLevels.update', $salaryLevel->id) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="form-label">Tên Bậc Lương</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $salaryLevel->level_name) }}" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập tên bậc lương.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="salary_coefficient" class="form-label">Hệ Số Bậc Lương</label>
                            <input type="number" class="form-control" id="salary_coefficient" name="salary_coefficient"
                                value="{{ old('salary_coefficient', $salaryLevel->salary_coefficient) }}" step="any" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập hệ số bậc lương.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="monthly_salary" class="form-label">Lương theo tháng (VND)</label>
                            <input type="text" class="form-control" id="monthly_salary" name="monthly_salary"
                                value="{{ old('monthly_salary', $salaryLevel->monthly_salary) }}" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập lương tháng.
                            </div>
                            @if ($errors->has('monthly_salary'))
                                <div class="text-danger">
                                    {{ $errors->first('monthly_salary') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="daily_salary" class="form-label">Lương theo ngày (VND)</label>
                            <input type="text" class="form-control" id="daily_salary" name="daily_salary"
                                value="{{ old('daily_salary', $salaryLevel->daily_salary) }}" required>
                            <div class="invalid-feedback">
                                Vui lòng nhập lương ngày.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </form>
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
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>

     <!-- Initialize Cleave.js -->
     <script>
            const cleaveMonthly = new Cleave('#monthly_salary', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalScale: 2,
                noImmediatePrefix: true
            });

            const monthlyInput = document.getElementById('monthly_salary');

            document.querySelector('form').addEventListener('submit', function(event) {
                const cleanedSalary = monthlyInput.value.replace(/[^0-9.]/g, '');
                monthlyInput.value = cleanedSalary;
            });

            const cleaveDaily = new Cleave('#daily_salary', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalScale: 2,
                noImmediatePrefix: true
            });

            const dailyInput = document.getElementById('daily_salary');

            document.querySelector('form').addEventListener('submit', function(event) {
                const cleanedDailySalary = dailyInput.value.replace(/[^0-9.]/g, '');
                dailyInput.value = cleanedDailySalary;
            });
        </script>
</body>

</html>
