<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Danh sách người dùng</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        /* Styles cho modal */
        .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
        }
        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
        }
        .modal-body {
            padding: 20px;
        }
        /* Custom styles for better UI */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        .container-fluid {
            padding: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
            padding: 15px; /* Increase padding for better readability */
        }
        .table td:nth-child(5) { /* Target the "Trạng thái" column */
            font-size: 1.1rem; /* Increase font size */
        }
        .btn {
            margin: 5px;
            padding: 12px 25px; /* Improve button padding */
            font-size: 1rem; /* Increase font size for better readability */
        }
        .alert {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3); /* Add shadow for better visual */
        }
        .modal-header {
            border-bottom: none;
            background-color: #f8f9fc; /* Light background for header */
        }
        .modal-footer {
            border-top: none;
            background-color: #f8f9fc; /* Light background for footer */
        }
        .modal-title {
            font-weight: bold;
            font-size: 1.25rem; /* Increase font size */
        }
        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem; /* Increase close button size */
        }
        .btn-close:hover {
            color: #000;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_user.slidebar') <!-- Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Topbar -->

                <div class="container-fluid">
                    <!-- Hiển thị thông báo nếu có -->
                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Thành công!</strong> {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Lỗi!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Cảnh báo!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Lịch sử Check In/Out -->
                    <div class="d-flex justify-content-between mb-4">
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <form action="{{ route('attendance.checkin') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Check In
                                </button>
                            </form>
    
                            <form action="{{ route('attendance.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-sign-out-alt"></i> Check Out
                                </button>
                            </form>
                        </div>
                        <h4 class="text-center">Ngày {{ date('d/m/Y') }}</h4>
                    </div>

                    <table class="table table-striped table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>STT</th>
                                <th>Nhân viên</th>
                                <th>Hoạt động</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th> <!-- New Status Column -->
                                <th>Lý do giải trình</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ ucfirst($attendance->type) }}</td> <!-- Checkin/Checkout -->
                                <td>{{ $attendance->time->format('H:i d/m/Y') }}</td>
                                <td>
                                    @if ($attendance->status == 0)
                                        <span class="badge badge-danger">Không hợp lệ</span>
                                    @elseif ($attendance->status == 1)
                                        <span class="badge badge-success">Hợp lệ</span>
                                    @elseif ($attendance->status == 2)
                                        <span class="badge badge-warning">Đang chờ giải trình</span>
                                    @elseif ($attendance->status == 3)
                                        <span class="badge badge-danger">Đã từ chối</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance->status == 0)
                                        <!-- Button to trigger modal -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#justificationModal-{{ $attendance->id }}">
                                            Giải trình
                                        </button>
                                        
                                        <!-- Modal for justification -->
                                        <div class="modal fade" id="justificationModal-{{ $attendance->id }}" tabindex="-1" aria-labelledby="justificationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="justificationModalLabel">Giải trình cho nhân viên {{ $attendance->user->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('attendance.addJustification', $attendance->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="justificationTextarea">Lý do giải trình</label>
                                                                <textarea name="justification" id="justificationTextarea" class="form-control" placeholder="Nhập lý do giải trình" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" class="btn btn-primary">Gửi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($attendance->status == 2)
                                        <!-- Hiển thị lý do đã được chấp nhận -->
                                        <p>{{ $attendance->justification }}</p>
                                    @elseif ($attendance->status == 3)
                                        <!-- Hiển thị trạng thái đã từ chối -->
                                        <p>Đã từ chối</p>
                                    @else
                                        --
                                    @endif
                                </td>
                                
                              </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $attendances->links('pagination::bootstrap-4') }}

                </div>
            </div>
        
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>

    <!-- Script to auto-show modal after submission -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.href.indexOf("justification-submitted=true") > -1) {
                var modalId = "{{ session('attendanceId') }}"; // Attendance ID saved after submission
                var modal = new bootstrap.Modal(document.getElementById("justificationModal-" + modalId));
                modal.show();
            }
        });
    </script>
</body>

</html>
