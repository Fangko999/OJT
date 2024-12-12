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
            height: 400px;
            width: 100%;
        }

        .dropdown-checkboxes {
            position: relative;
            display: inline-block;
        }

        .dropdown-checkboxes .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 200px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1;
            padding: 10px;
        }

        .dropdown-checkboxes.open .dropdown-menu {
            display: block;
        }

        .dropdown-checkboxes label {
            display: flex;
            align-items: center;
            padding: 5px 0;
        }

        .dropdown-checkboxes input {
            margin-right: 10px;
        }

        .dropdown-toggle {
            cursor: pointer;
            padding: 5px 10px;
            background-color: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-radius: 5px;
            display: inline-block;
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
                    <h1 class="h3 mb-2 text-gray-800">Biểu đồ tỷ lệ nhân sự giữa các phòng ban</h1>
                    <div class="mb-4">
                        <a href="{{ route('chart.view') }}" class="btn btn-danger">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                    <div class="mb-4">
                        <div class="dropdown-checkboxes">
                            <div class="dropdown-toggle" id="dropdownButton">Chọn phòng ban</div>
                            <div class="dropdown-menu" id="departmentDropdown">
                                @foreach($departments as $department)
                                    <label>
                                        <input type="checkbox" value="{{ $department->id }}" checked>
                                        {{ $department->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="employeeRatioChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        let employeeChart;

        document.addEventListener('DOMContentLoaded', () => {
            initializeEmployeeRatioChart();

            document.querySelector('#dropdownButton').addEventListener('click', function () {
                const dropdown = document.querySelector('.dropdown-checkboxes');
                dropdown.classList.toggle('open');
            });

            document.querySelectorAll('#departmentDropdown input').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateEmployeeRatioChart();
                });
            });
        });

        async function initializeEmployeeRatioChart() {
            const chartData = await fetchChartData();
            const ctx = document.getElementById('employeeRatioChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
            gradient.addColorStop(1, 'rgba(153, 102, 255, 0.6)');

            employeeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Số lượng nhân sự',
                            data: chartData.counts,
                            backgroundColor: gradient,
                            borderColor: '#4A5568',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.raw;
                                }
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            formatter: function (value) {
                                return value;
                            },
                            color: '#4A5568',
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Phòng ban'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượng nhân sự'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        async function updateEmployeeRatioChart() {
            const chartData = await fetchChartData();

            employeeChart.data.labels = chartData.labels;
            employeeChart.data.datasets[0].data = chartData.counts;
            employeeChart.update();
        }

        async function fetchChartData() {
            try {
                const selectedDepartments = Array.from(document.querySelectorAll('#departmentDropdown input:checked'))
                    .map(checkbox => checkbox.value)
                    .filter(value => value !== '');

                const response = await fetch(`http://localhost/EMS%202/api/user-count-by-department?departments=${selectedDepartments.join(',')}`);
                const data = await response.json();

                return {
                    labels: data.labels || [],
                    counts: data.counts || []
                };
            } catch (error) {
                console.error('Error fetching chart data:', error);
                return { labels: [], counts: [] };
            }
        }
    </script>
</body>

</html>