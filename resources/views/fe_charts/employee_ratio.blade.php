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
            background: linear-gradient(135deg, #f8f9fc 0%, #e2e6ea 100%);
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
            max-height: 300px; /* Set a max height for the dropdown */
            overflow-y: auto; /* Enable vertical scrolling */
            transition: opacity 0.3s ease;
            opacity: 0;
        }

        .dropdown-checkboxes.open .dropdown-menu {
            display: block;
            opacity: 1;
        }

        .dropdown-checkboxes label {
            display: flex;
            align-items: center;
            padding: 5px 0;
        }

        .dropdown-checkboxes label:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
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

        .dropdown-toggle:hover, .dropdown-toggle:focus {
            background-color: #e2e6ea;
            transition: background-color 0.3s ease;
        }

        .dropdown-toggle:active {
            transform: scale(0.98);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            position: relative;
            overflow: hidden;
        }

        .btn-danger::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.5);
            transition: width 0.3s ease, height 0.3s ease, top 0.3s ease, left 0.3s ease;
            border-radius: 50%;
            z-index: 1;
            opacity: 0;
        }

        .btn-danger:active::after {
            width: 0;
            height: 0;
            top: 50%;
            left: 50%;
            opacity: 1;
        }

        .btn-danger span {
            position: relative;
            z-index: 2;
        }

        .btn-danger.loading::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            z-index: 3;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .text-gradient {
            background: linear-gradient(90deg, #ff7e5f, #feb47b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .typing-effect::after {
            content: '|';
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .pagination-animation {
            display: flex;
            list-style: none;
        }

        .pagination-animation li {
            margin: 0 5px;
            transition: transform 0.3s ease;
        }

        .pagination-animation li:hover {
            transform: scale(1.2);
        }

        .filter-effects {
            filter: grayscale(100%);
            transition: filter 0.3s ease;
        }

        .filter-effects:hover {
            filter: grayscale(0%);
        }

        .focus-animation:focus {
            animation: focusPulse 1s infinite;
        }

        @keyframes focusPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5); }
            50% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
        }

        .validation-feedback {
            color: #dc3545;
            font-size: 0.875em;
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
                                <label>
                                    <input type="checkbox" id="selectAllDepartments">
                                    Tất cả
                                </label>
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
            // Select all departments by default
            document.querySelectorAll('#departmentDropdown input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = true;
            });
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

            document.querySelector('#selectAllDepartments').addEventListener('change', function () {
                const isChecked = this.checked;
                document.querySelectorAll('#departmentDropdown input[type="checkbox"]').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                updateEmployeeRatioChart();
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

                if (selectedDepartments.length === 0) {
                    return { labels: [], counts: [] };
                }

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