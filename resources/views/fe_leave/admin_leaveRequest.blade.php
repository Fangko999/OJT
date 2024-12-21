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


                    <div class="container pt-5  mb-5">
                        <h2>Quản lý đơn xin nghỉ phép</h2>
                        <div class="row">
                            <div class="col-md-9">
                                <form method="GET" action="{{ route('leave_requests.index') }}">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <select name="status" class="form-control">
                                                <option value="all">Trạng thái đơn</option>
                                                <option value="0">Đang chờ</option> <!-- 0 -->
                                                <option value="1">Đã phê duyệt</option> <!-- 1 -->
                                                <option value="2">Đã từ chối</option> <!-- 2 -->
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                        </div>
                                    </div>
                                </form>

                                <!-- Danh sách đơn -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nhân viên</th>
                                            <th>Thời gian nghỉ</th>
                                            <th>Lý do</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaveRequests as $request)
                                        <tr>
                                            <td>{{ $request->user->name }}</td>
                                            <td>{{ $request->start_date }} - {{ $request->end_date }}</td>
                                            <td>{{ $request->reason }}</td>
                                            <td>
                                                @if ($request->status === 0)
                                                <span class="badge bg-warning">Đang chờ</span>
                                                @elseif ($request->status === 1)
                                                <span class="badge bg-success">Đã phê duyệt</span>
                                                @else
                                                <span class="badge bg-danger">Đã từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($request->status === 0)
                                                <form action="{{ route('leave_requests.updateStatus', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm">Phê duyệt</button>
                                                </form>
                                                <form action="{{ route('leave_requests.updateStatus', $request->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="submit" class="btn btn-danger btn-sm">Từ chối</button>
                                                </form>
                                                @else
                                                -
                                                @endif
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

</body>

</html>