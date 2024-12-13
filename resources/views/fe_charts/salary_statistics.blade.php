<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Biểu đồ thống kê lương</title>

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
                    <h1 class="h3 mb-4 text-gray-800">Biểu đồ thống kê lương theo tháng</h1>
                    <div class="mb-4">
                        <a href="{{ route('chart.view') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <form id="monthForm">
                                <div class="form-group">
                                    <label for="monthSelect">Chọn tháng:</label>
                                    <input type="month" id="monthSelect" class="form-control" value="{{ now()->format('Y-m') }}">
                                </div>
                                <div class="form-group">
                                    <label for="departmentSelect">Chọn phòng ban:</label>
                                    <select id="departmentSelect" class="form-control">
                                        <option value="">Tất cả</option>
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
                                <canvas id="salaryStatisticsChart"></canvas>
                                <div id="noDataMessage" class="no-data-message" style="display: none;">Không có dữ liệu cho tháng này!</div>
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
        let salaryStatisticsChart;

        document.getElementById('showChartButton').addEventListener('click', function () {
            const month = document.getElementById('monthSelect').value;
            fetchSalaryStatistics(month);
        });

        function fetchSalaryStatistics(month) {
            const departmentId = document.getElementById('departmentSelect').value;
            fetch(`http://localhost/EMS%202/api/salary-statistics-by-month?month=${month}&department_id=${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('salaryStatisticsChart').getContext('2d');
                    if (salaryStatisticsChart) {
                        salaryStatisticsChart.destroy();
                    }
                    // Filter out employees with no salary
                    const filteredData = data.salaries.filter(salary => salary > 0);
                    const filteredLabels = data.labels.filter((label, index) => data.salaries[index] > 0);

                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(75, 192, 192, 1)');
                    gradient.addColorStop(1, 'rgba(153, 102, 255, 1)');

                    salaryStatisticsChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: filteredLabels,
                            datasets: [{
                                label: 'Lương nhận được',
                                data: filteredData,
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
                                            return ` ${context.label}: ${context.raw.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })}`;
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#444',
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: (value) => value.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' })
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
                                        callback: function(value) {
                                            return value.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                                        }
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                });
        }

        // Initial load
        const initialMonth = document.getElementById('monthSelect').value;
        if (initialMonth) {
            fetchSalaryStatistics(initialMonth);
        }
    });
</script>

</body>

</html>