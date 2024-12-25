<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tạo đơn xin nghỉ phép</title>
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        @include('fe_user.slidebar')
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')
                <div class="container pt-5 mb-5">
                    <h2 style="font-weight: bold"><i class="bi bi-house"></i> Tạo đơn xin nghỉ phép</h2>
                    <p>Số ngày nghỉ phép có lương còn lại: <strong>{{ $leaveBalance }}</strong></p>
                    <form action="{{ route('leave_requests.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Loại nghỉ</label>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="full_day">Nghỉ cả ngày</option>
                                <option value="multiple_days">Nghỉ nhiều ngày</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3" id="end_date_container" style="display: none;">
                            <label for="end_date" class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do nghỉ</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" maxlength="255"></textarea>
                        </div>
                        <div class="mb-3">
                            <p>Số ngày nghỉ có lương: <span id="paid_days" class="font-weight-bold text-success">0</span></p>
                            <p>Số ngày nghỉ không lương: <span id="unpaid_days" class="font-weight-bold text-danger">0</span></p>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi đơn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('fe-access/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('fe-access/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('fe-access/js/sb-admin-2.min.js')}}"></script>
    <script>
        document.getElementById('leave_type').addEventListener('change', function() {
            var endDateContainer = document.getElementById('end_date_container');
            if (this.value === 'multiple_days') {
                endDateContainer.style.display = 'block';
            } else {
                endDateContainer.style.display = 'none';
            }
            calculateLeaveDays();
        });

        document.getElementById('leave_type').addEventListener('change', calculateLeaveDays);
        document.getElementById('start_date').addEventListener('change', calculateLeaveDays);
        document.getElementById('end_date').addEventListener('change', calculateLeaveDays);

        function calculateLeaveDays() {
            var leaveType = document.getElementById('leave_type').value;
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value || startDate;
            var leaveBalance = {{ $leaveBalance }};
            var duration = 1;

            if (leaveType === 'multiple_days' && startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                duration = (end - start) / (1000 * 60 * 60 * 24) + 1;
            }

            var paidDays = Math.min(duration, leaveBalance);
            var unpaidDays = duration - paidDays;

            document.getElementById('paid_days').innerText = paidDays;
            document.getElementById('unpaid_days').innerText = unpaidDays;
        }

        calculateLeaveDays();
    </script>
</body>
</html>
