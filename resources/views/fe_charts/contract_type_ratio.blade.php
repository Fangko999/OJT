<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract Type Ratio</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Contract Type Ratio by Department</h1>
    <form id="departmentForm">
        <label for="department">Select Department:</label>
        <select id="department" name="department">
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
        <button type="submit">View</button>
    </form>
    <canvas id="contractTypeChart"></canvas>

    <script>
        document.getElementById('departmentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const departmentId = document.getElementById('department').value;
            fetch(`/getContractTypeRatioByDepartment/${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('contractTypeChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: Object.keys(data),
                            datasets: [{
                                data: Object.values(data),
                                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Contract Type Ratio'
                                }
                            }
                        }
                    });
                });
        });
    </script>
</body>
</html>
