<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Biểu đồ tỷ lệ nhân sự</title>
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .chart-container {
            position: relative;
            height: 100px;
            padding-bottom: 50%;
            /* Adjusted to make the chart responsive */
            width: 100%;
        }

        .no-data-message {
            text-align: center;
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }

        #genderRatioChart {
            max-width: 600px;
            /* Chiều rộng tối đa của biểu đồ */
            max-height: 400px;
            /* Chiều cao tối đa của biểu đồ */
            width: 100%;
            /* Chiều rộng tự động điều chỉnh */
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
                    <h1 class="h3 mb-4 text-gray-800">Biểu đồ tỷ lệ giới tính theo từng phòng ban</h1>
                    <div class="mb-4">
                        <a href="{{ route('chart.view') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <form id="departmentForm">
                                <div class="form-group">
                                    <label for="departmentSelect">Chọn phòng ban:</label>
                                    <select id="departmentSelect" class="form-control">
                                        <option value="">Chọn phòng ban</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" id="showChartButton" class="btn btn-primary">Hiển thị biểu đồ</button>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="chart-container mt-4">
                                        <canvas id="genderRatioChart"></canvas>
                                        <div id="noDataMessage" class="no-data-message" style="display: none;">Phòng ban này không có nhân viên nào!</div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="employeeStats" style="display: none; margin-top: 20px;">
                                    <p>Tổng số nhân viên: <span id="totalEmployees"></span></p>
                                    <p>Số lượng Nam: <span id="maleEmployees"></span></p>
                                    <p>Số lượng Nữ: <span id="femaleEmployees"></span></p>
                                </div>
                            </div>
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
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            var genderRatioChart;

            $('#showChartButton').click(function() {
                var departmentId = $('#departmentSelect').val();
                if (departmentId) {
                    $.get('http://localhost/EMS%202/api/gender-ratio-by-department/' + departmentId, function(data) {
                        var ctx = document.getElementById('genderRatioChart').getContext('2d');
                        if (genderRatioChart) {
                            genderRatioChart.destroy();
                        }
                        if (data.male === 0 && data.female === 0) {
                            $('#genderRatioChart').hide();
                            $('#noDataMessage').show();
                            $('#employeeStats').hide();
                        } else {
                            $('#genderRatioChart').show();
                            $('#noDataMessage').hide();
                            $('#employeeStats').show();
                            $('#totalEmployees').text(data.male + data.female);
                            $('#maleEmployees').text(data.male);
                            $('#femaleEmployees').text(data.female);
                            genderRatioChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['Nam', 'Nữ'],
                                    datasets: [{
                                        data: [data.male, data.female],
                                        backgroundColor: ['#36A2EB', '#FF6384']
                                    }]
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>