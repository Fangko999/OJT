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
        @include('fe_user.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')

                <div class="container pt-5 mb-5">
                    <h2 style="font-weight: bold"><i class="bi bi-house"></i> Chi tiết</h2>
                    <div class="row">
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#leaveRequestModal">
                            Tạo đơn xin nghỉ
                        </button>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Loại nghỉ</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Lý do</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaveRequests as $leave)
                                <tr>
                                    <td>{{ $leave->leave_type }}</td>
                                    <td>{{ $leave->start_date }}</td>
                                    <td>{{ $leave->end_date }}</td>
                                    <td>{{ $leave->reason }}</td>
                                    <td>{{ $leave->status == 0 ? 'Đang chờ duyệt' : 'Đã duyệt' }}</td>
                                    <td>
                                        <a href="{{ route('leave_requests.edit', $leave->id) }}" class="btn btn-warning">Sửa</a>
                                        <form action="{{ route('leave_requests.destroy', $leave->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            {{ $leaveRequests->onEachSide(2)->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Close content-wrapper -->
    </div> <!-- Close wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="leaveRequestModal" tabindex="-1" aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('leave.request') }}" method="POST" id="leaveForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveRequestModalLabel">Gửi đơn xin nghỉ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Options -->
                        <div class="mb-3">
                            <label for="leave_type" class="form-label">Loại nghỉ</label>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="full_day">Nghỉ cả ngày</option>
                                <option value="multiple_days">Nghỉ nhiều ngày</option>
                            </select>
                        </div>

                        <!-- Chọn ngày -->
                        <div class="mb-3" id="datePickerContainer">
                            <label for="start_date" class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>

                            <label for="end_date" class="form-label mt-2" id="end_date_label" style="display: none;">
                                Đến ngày
                            </label>
                            <input type="date" class="form-control" id="end_date" name="end_date" style="display: none;">
                        </div>

                        <!-- Lý do nghỉ -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do nghỉ</label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" maxlength="255"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Gửi đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
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

            if (leaveType === 'multiple_days') {
                endDateLabel.style.display = 'block';
                endDateInput.style.display = 'block';
                endDateInput.required = true;
            } else {
                endDateLabel.style.display = 'none';
                endDateInput.style.display = 'none';
                endDateInput.required = false;
            }
        });
    </script>
</body>
</html>