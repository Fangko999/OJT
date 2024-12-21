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
    <style>
        #seniorityChart {
            height: 400px !important;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
        }

        .card-header {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            color: #fff;
        }

        .card-header h6 {
            margin: 0;
            font-weight: bold;
        }

        .scroll-to-top {
            background: #4e73df;
        }

        .scroll-to-top:hover {
            background: #2e59d9;
        }

        .btn-primary {
            background: #1cc88a;
            border: none;
        }

        .btn-primary:hover {
            background: #17a673;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Biểu đồ thống kê thâm niên làm việc</h1>
                    <div class="mb-4">
                        <a href="{{ route('chart.view') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="departmentSelect">Chọn phòng ban:</label>
                                <select id="departmentSelect" class="form-control">
                                    <option value="">Tất cả</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="noDataMessage" class="alert alert-warning" style="display: none;">
                                Không có nhân viên nào trong phòng ban này.
                            </div>
                            <canvas id="seniorityChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        $(document).ready(function() {
            let seniorityChart;

            function fetchSeniorityData(departmentId = '') {
                const url = departmentId ? `http://localhost/EMS%202/api/seniority-ratio-by-department/${departmentId}` : `http://localhost/EMS%202/api/seniority-ratio-by-department`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const ctx = document.getElementById('seniorityChart').getContext('2d');
                        if (seniorityChart) {
                            seniorityChart.destroy();
                        }
                        if (Object.keys(data).length === 0) {
                            $('#noDataMessage').show();
                            $('#seniorityChart').hide();
                        } else {
                            $('#noDataMessage').hide();
                            $('#seniorityChart').show();
                            seniorityChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: Object.keys(data),
                                    datasets: [{
                                        data: Object.values(data),
                                        backgroundColor: ['#4e73df'],
                                        label: 'Số lượng nhân viên'
                                    }]
                                },
                                options: {
                                    indexAxis: 'y',
                                    plugins: {
                                        datalabels: {
                                            color: '#fff',
                                            display: true,
                                            formatter: (value) => value,
                                        }
                                    },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    },
                                    plugins: {
                                        datalabels: {
                                            anchor: 'end',
                                            align: 'end',
                                            formatter: function(value, context) {
                                                return value;
                                            },
                                            color: '#000',
                                            font: {
                                                weight: 'bold'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    });
            }

            $('#departmentSelect').change(function() {
                const departmentId = $(this).val();
                fetchSeniorityData(departmentId);
            });

            fetchSeniorityData();
        });
    </script>

</body>

</html>
