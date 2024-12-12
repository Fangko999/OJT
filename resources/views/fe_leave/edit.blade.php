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

                <div class="container pt-5 mb-5">
        <h2 style="font-weight: bold"><i class="bi bi-house"></i> Chỉnh sửa đơn nghỉ phép</h2>
        <div class="row">
            <div class="col-md-9">
                <form action="{{ route('leave_requests.update', $leaveRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="leave_type" class="form-label">Loại nghỉ</label>
                        <select class="form-select" id="leave_type" name="leave_type" required>
                            <option value="full_day" {{ $leaveRequest->leave_type == 'full_day' ? 'selected' : '' }}>Nghỉ cả
                                ngày</option>
                            <option value="multiple_days"
                                {{ $leaveRequest->leave_type == 'multiple_days' ? 'selected' : '' }}>Nghỉ nhiều ngày
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ $leaveRequest->start_date }}" required>
                    </div>

                    <div class="mb-3">
                        <label id="end_date_label" for="end_date" class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ $leaveRequest->end_date }}">
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Lý do nghỉ</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ $leaveRequest->reason }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('leave_requests.index') }}" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
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

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>

    <script>
    document.getElementById('leave_type').addEventListener('change', function() {
        const leaveType = this.value;
        const endDateLabel = document.getElementById('end_date_label');
        const endDateInput = document.getElementById('end_date');

        // Kiểm tra loại nghỉ
        if (leaveType === 'multiple_days') {
            endDateLabel.style.display = 'block'; // Hiện label "Đến ngày"
            endDateInput.style.display = 'block'; // Hiện trường "end_date"
            endDateInput.required = true; // Đặt bắt buộc nhập cho trường này
        } else {
            endDateLabel.style.display = 'none'; // Ẩn label "Đến ngày"
            endDateInput.style.display = 'none'; // Ẩn trường "end_date"
            endDateInput.required = false; // Bỏ bắt buộc nhập cho trường này
        }
    });
    // Chạy kiểm tra ngay khi tải trang để đảm bảo trạng thái chính xác
    document.getElementById('leave_type').dispatchEvent(new Event('change'));
</script>

</body>

</html>