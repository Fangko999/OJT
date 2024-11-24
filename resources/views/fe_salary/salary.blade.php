<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin - Danh sách bậc lương</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fe-access/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fe-access/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        /* Cải thiện tiêu đề */
        .card-header {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
            padding: 15px;
            border-radius: 5px;
        }

        /* Căn chỉnh bảng */
        .table {
            margin-top: 20px;
            border-collapse: collapse;
        }

        .table thead {
            background-color: #4e73df;
            color: white;
        }

        .table thead th {
            background-color: #4e73df;
            color: white;
            font-weight: bold;
        }

        .table tbody tr:hover {
            background-color: #f2f2f2;
        }

        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            padding: 10px;
        }

        /* Cải thiện nút */
        .btn {
            font-size: 14px;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-danger {
            background-color: #e74a3b;
            border-color: #e74a3b;
        }

        .btn:hover {
            opacity: 0.8;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        /* Modal */
        .modal-header {
            background-color: #4e73df;
            color: white;
        }

        /* Form tìm kiếm */
        .input-group {
            display: flex;
            width: 100%;
        }

        .input-group .form-control {
            border-right: 0;
        }

        .input-group .btn {
            border-left: 0;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')

                <div class="card-header">
                    <h2 class="mb-0">Quản lý bậc lương</h2>
                </div>
            </div>

            <!-- Content: Salary Levels Table -->
            <div class="col-md-9 mx-auto mt-4">
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Button Thêm Bậc Lương -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <form action="{{ route('salaryLevels') }}" method="GET" class="d-flex w-75">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bậc lương..."
                                value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </form>

                    <div>
                        <a href="{{ route('salaryLevels.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm bậc lương
                        </a>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">
                            <i class="fas fa-trash-alt"></i> Xóa mục đã chọn
                        </button>
                    </div>
                </div>

                <!-- Form xóa mềm nhiều mục -->
                <form id="deleteForm" action="{{ route('salaryLevels.softDeleteMultiple') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Bảng Salary Levels -->
                    <table class="table table-striped table-bordered table-hover text-center">
                        <thead>
                            <tr>
                                <th style="width: 50px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="select-all" />
                                        <label class="form-check-label" for="select-all"></label>
                                    </div>
                                </th>
                                <th>STT</th>
                                <th>Tên Bậc Lương</th>
                                <th>Hệ Số Lương</th>
                                <th>Lương theo tháng</th>
                                <th>Chỉnh sửa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryLevels as $salaryLevel)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $salaryLevel->id }}" class="item-checkbox"></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $salaryLevel->level_name }}</td>
                                <td>{{ $salaryLevel->salary_coefficient }}</td>
                                <td>{{ number_format($salaryLevel->monthly_salary, 0, ',', '.') }} VND</td>
                                <td>
                                    <a href="{{ route('salaryLevels.edit', $salaryLevel->id) }}" class="btn btn-success">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                            <div class="d-flex justify-content-center mt-4">
                {{ $salaryLevels->onEachSide(2)->appends(request()->input())->links() }}
            </div>
            </div>



            <!-- Modal xác nhận xóa -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa các bậc lương đã chọn không?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteButton">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add footer here -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© {{ date('Y') }} Your Company. All Rights Reserved.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>

    <script>
        // Script to select/deselect all checkboxes
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Script to handle delete confirmation
        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            document.getElementById('deleteForm').submit();
        });
    </script>
</body>

</html>
