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
        .no-data-message {
            text-align: center;
            color: red;
            font-size: 18px;
            margin-top: 20px;
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin: 0 auto;
        }

        .chartjs-render-monitor {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
                    <h1 class="h3 mb-4 text-gray-800">Biểu đồ độ tuổi theo từng phòng ban</h1>
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
                            <div class="chart-container mt-4">
                                <canvas id="ageRatioChart"></canvas>
                                <div id="noDataMessage" class="no-data-message" style="display: none;">Phòng ban này không có nhân viên nào!</div>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let ageRatioChart;

        document.getElementById('showChartButton').addEventListener('click', function () {
            const departmentId = document.getElementById('departmentSelect').value;
            fetchAgeRatio(departmentId);
        });

        function fetchAgeRatio(departmentId) {
            fetch(`http://localhost/EMS%202/api/age-ratio-by-department/${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('ageRatioChart').getContext('2d');
                    if (ageRatioChart) {
                        ageRatioChart.destroy();
                    }
                    const filteredData = Object.entries(data).filter(([key, value]) => value > 0);
                    if (filteredData.length === 0) {
                        document.getElementById('ageRatioChart').style.display = 'none';
                        document.getElementById('noDataMessage').style.display = 'block';
                    } else {
                        document.getElementById('ageRatioChart').style.display = 'block';
                        document.getElementById('noDataMessage').style.display = 'none';
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(75, 192, 192, 1)');
                        gradient.addColorStop(1, 'rgba(153, 102, 255, 1)');

                        ageRatioChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: filteredData.map(([key]) => key),
                                datasets: [{
                                    label: 'Số lượng nhân viên',
                                    data: filteredData.map(([_, value]) => value),
                                    backgroundColor: gradient,
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function (context) {
                                                return ` ${context.label}: ${context.raw}`;
                                            }
                                        }
                                    },
                                    datalabels: {
                                        color: '#444',
                                        anchor: 'end',
                                        align: 'top',
                                        formatter: (value) => value
                                    }
                                },
                                scales: {
                                    x: {
                                        ticks: {
                                            color: '#555',
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: '#555',
                                        }
                                    }
                                }
                            }
                        });
                    }
                });
        }

        // Initial load
        const initialDepartmentId = document.getElementById('departmentSelect').value;
        if (initialDepartmentId) {
            fetchAgeRatio(initialDepartmentId);
        }
    });
</script>

</body>

</html>
