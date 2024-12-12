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

    <!-- Custom CSS -->
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Biểu đồ thống kê tỉ lệ chấm công</h1>
                    <a href="{{ route('chart.view') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>

                    <form id="filter-form" class="form-inline">
                        <div class="form-group mb-2 mr-2">
                            <label for="department" class="mr-2">Phòng ban:</label>
                            <select id="department" name="department" class="form-control form-control-sm">
                                <option value="">Tất cả</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2 mr-2">
                            <label for="user_name" class="mr-2">Tên nhân viên:</label>
                            <input type="text" id="user_name" name="user_name" class="form-control form-control-sm">
                        </div>
                        <div class="form-group mb-2 mr-2">
                            <label for="start_date" class="mr-2">Ngày bắt đầu:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control form-control-sm" value="{{ now()->subWeek()->toDateString() }}">
                        </div>
                        <div class="form-group mb-2 mr-2">
                            <label for="end_date" class="mr-2">Ngày kết thúc:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control form-control-sm" value="{{ now()->toDateString() }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm mb-2">Lọc</button>
                    </form>

                    <canvas id="attendanceChart" width="400" height="200"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        $(document).ready(function() {
            let attendanceChart;

            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                fetchAttendanceData();
            });

            function fetchAttendanceData() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();
                const department = $('#department').val();
                const userName = $('#user_name').val();

                $.ajax({
                    url: '{{ route("getAttendanceRatio") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        department: department,
                        user_name: userName
                    },
                    success: function(response) {
                        renderChart(response);
                    }
                });
            }

            function renderChart(data) {
                const ctx = document.getElementById('attendanceChart').getContext('2d');

                if (attendanceChart) {
                    attendanceChart.destroy();
                }

                attendanceChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.user_name),
                        datasets: [
                            {
                                label: 'Ngày hợp lệ',
                                data: data.map(item => Math.round(item.valid_days)),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Ngày không hợp lệ',
                                data: data.map(item => Math.round(item.invalid_days)),
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    font: {
                                        size: 16 // Increase font size for labels
                                    }
                                }
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'end',
                                formatter: function(value) {
                                    return value;
                                },
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });
            }

            fetchAttendanceData(); // Initial load
        });
    </script>
</body>

</html>
