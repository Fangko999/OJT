<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Danh sách nhân viên</title>



    <!-- Custom fonts for this template-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        .table-responsive {
            margin: 20px 0;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .thead-dark th {
            background-color: #343a40;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }

        .table th {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .btn {
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-warning:hover {
            background-color: #ffcc00;
            color: black;
        }

        .modal-content {
            border-radius: 8px;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>

    <style>
        .btn-custom {
            padding: 0.375rem 0.625rem;
            /* Giảm padding */
            font-size: 0.8rem;
            /* Giảm kích thước chữ */
            margin: 5px;
            /* Căn lề cho nút */
            width: calc(45% + 5px);
            /* Tăng chiều rộng thêm 5px */
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .action-buttons>div {
            flex: 1;
            margin: 5px;
            min-width: 85px;
            /* Điều chỉnh lại kích thước tối thiểu của nút */
        }
    </style>

</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Topbar -->

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 font-weight-bold" style="color: black;">Danh sách nhân viên</h1>
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                    </div>

                    <!-- Card chứa Import và Export -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3 align-items-center action-buttons">
                                <!-- Nút Thêm Nhân Viên -->
                                <div class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-success btn-custom dropdown-toggle" type="button" id="addEmployeeBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="toggleDropdown()">
                                            <i class="fas fa-user-plus"></i> Thêm nhân viên
                                        </button>
                                        <div id="additionalButtons" class="dropdown-menu" style="display: none;">
                                            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                                @csrf
                                                <div class="input-group mb-2">
                                                    <input type="file" name="import_file" class="form-control" id="importFile" style="display: none;" required>
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-primary btn-custom" id="submitBtn" style="display: none;">
                                                            <i class="fas fa-file-import"></i> Nhập từ Excel
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                            <button class="btn btn-primary btn-custom w-75 mb-2" id="importDataBtn">
                                                <i class="fas fa-file-import"></i> Nhập Từ Excel
                                            </button>
                                            <input type="file" name="import_file" class="form-control" id="importFile" style="display: none;" required>
                                            <a href="{{ route('export.template') }}" class="btn btn-secondary btn-custom w-75 mb-2">Tải Mẫu Excel</a>
                                            <a href="{{ route('users.create') }}" class="btn btn-success btn-custom w-75">Thêm thủ công</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nút Xuất Dữ Liệu -->
                                <div class="text-center">
                                    <form action="{{ route('users.export') }}" method="GET">
                                        <button type="submit" class="btn btn-info btn-custom" style="padding: 5px 10px; font-size: 14px;">
                                            <i class="fas fa-file-export"></i> Xuất File
                                        </button>
                                    </form>
                                </div>


                                <!-- Nút Xóa Người Dùng Đã Chọn -->
                                <div class="text-center">
                                    <button type="button" class="btn btn-danger btn-custom" onclick="confirmBulkDelete()" style="padding: 5px 10px; font-size: 14px;">
                                        <i class="fas fa-trash"></i> Xóa nhân viên
                                    </button>
                                </div>

                            </div>

                            <!-- Ô tìm kiếm -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                <form action="{{ route('users') }}" method="GET" class="mb-3">
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ $search ?? '' }}"
                                            placeholder="Nhập để tìm kiếm..." class="form-control">
                                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                    </div>
                                </form>


                            </div>


                            <!-- Danh sách người dùng -->
                            <div class="table-responsive">
                                <form id="deleteUsersForm" method="POST" action="{{ route('users.destroy') }}">
                                    @csrf
                                    <table class="table table-bordered w-100">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th>STT</th>
                                                <th>Tên</th>
                                                <th>Email</th>
                                                <th>Số điện thoại</th>
                                                <th>Chức vụ</th>
                                                <th>Phòng ban</th>
                                                <th>Hành động</th> <!-- Thêm cột Hành động -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_number }}</td>
                                                <td>{{ $user->position }}</td>
                                                <td>
                                                    @if ($user->department)
                                                    {{ $user->department->name }}
                                                    @if ($user->department->parent_id)
                                                    - {{ $user->department->parent->name ?? 'Chưa xác định' }}
                                                    @endif
                                                    @else
                                                    Chưa xác định
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Nút sửa thông tin người dùng -->
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                                                        <i class="fas fa-user-edit"></i> Sửa
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal sửa người dùng cho từng người dùng -->
                                            <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Sửa Thông Tin Nhân Viên</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="editUserForm{{ $user->id }}" method="POST" action="{{ route('users.update', $user->id) }}">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>Tên</label>
                                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Số điện thoại</label>
                                                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Chức vụ</label>
                                                                    <input type="text" name="position" class="form-control" value="{{ old('position', $user->position) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Phòng ban</label>
                                                                    <select name="department_id" class="form-control" required>
                                                                        @foreach($departments as $department)
                                                                        <option value="{{ $department->id }}"
                                                                            {{ $department->id == $user->department_id ? 'selected' : '' }}>
                                                                            {{ $department->name }}
                                                                        </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Không có dữ liệu.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </form>
                            </div>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            @include('fe_admin.footer')

            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <script>
                function confirmDelete(userId) {
                    if (confirm('Are you sure you want to delete this user?')) {
                        document.getElementById('delete-form-' + userId).submit();
                    }
                }
            </script>

            </script>
            <script>
                // Thêm sự kiện cho nút để kích hoạt ô chọn file
                document.getElementById('importDataBtn').addEventListener('click', function() {
                    document.getElementById('importFile').click();
                });

                // Sự kiện cho ô input file, chỉ gửi form khi chọn file
                document.getElementById('importFile').addEventListener('change', function() {
                    if (this.files.length > 0) { // Kiểm tra xem có file được chọn
                        document.getElementById('importForm').submit();
                    }
                });
            </script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


            <script>
                document.getElementById('select-all').addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });

                function confirmBulkDelete() {
                    const form = document.getElementById('deleteUsersForm');
                    const selectedCheckboxes = form.querySelectorAll('input[name="user_ids[]"]:checked');

                    if (selectedCheckboxes.length === 0) {
                        swal("Cảnh báo!", "Vui lòng chọn ít nhất một nhân viên để xóa", "warning");
                        return;
                    }

                    swal({
                        title: "Xác nhận xóa",
                        text: "Bạn có muốn xóa những nhân viên đã chọn?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Có, xóa!",
                        cancelButtonText: "Không, quay lại!",
                        closeOnConfirm: false
                    }, function() {
                        form.submit(); // Gửi form
                    });
                }
            </script>


            <!-- Bootstrap core JavaScript-->
            <script src="{{asset('fe-access/vendor/jquery/jquery.min.js')}}"></script>
            <script src="{{asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

            <!-- Core plugin JavaScript-->
            <script src="{{asset('fe-access/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

            <!-- Custom scripts for all pages-->
            <script src="{{asset('fe-access/js/sb-admin-2.min.js')}}"></script>

            <script>
                function toggleDropdown() {
                    var dropdownMenu = document.getElementById('additionalButtons');
                    dropdownMenu.style.display = (dropdownMenu.style.display === "none" || dropdownMenu.style.display === "") ? "block" : "none";
                }
            </script>

            <style>
                .dropdown-menu {
                    position: absolute;
                    background-color: #fff;
                    z-index: 1000;
                    padding: 10px;
                    border-radius: 0.25rem;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    min-width: 200px;
                    /* Bạn có thể điều chỉnh chiều rộng tối thiểu nếu cần */
                }
            </style>
</body>

</html>