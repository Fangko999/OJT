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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <!-- Custom styles for this template-->
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        /* Smooth transitions for buttons */
        .btn {
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn:active {
            transform: scale(0.95);
        }

        /* Smooth transitions for alerts */
        .alert {
            transition: opacity 0.3s;
        }

        /* Smooth transitions for table rows */
        .table tbody tr {
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Gradient background for header */
        .thead-dark {
            background: linear-gradient(45deg, #343a40, #6c757d);
            color: white;
        }

        /* Parallax scrolling effect for the page */
        body {
            background: url('path/to/your/background.jpg') no-repeat center center fixed;
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-attachment: fixed;
        }

        /* Background blur for modal */
        .modal-content {
            backdrop-filter: blur(10px);
        }

        /* Text shadow for headings */
        h1, h2, h3, h4, h5, h6 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Typing effect for a specific element */
        .typing-effect {
            overflow: hidden;
            border-right: .15em solid orange;
            white-space: nowrap;
            margin: 0 auto;
            letter-spacing: .15em;
            animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: orange; }
        }

        /* Text gradient */
        .text-gradient {
            background: linear-gradient(to right, #30CFD0 0%, #330867 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Ripple effect for buttons */
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }

        .btn-ripple::after {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #fff 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform .5s, opacity 1s;
        }

        .btn-ripple:active::after {
            transform: scale(0, 0);
            opacity: .3;
            transition: 0s;
        }

        /* Loading button */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 1rem;
            height: 1rem;
            border: 2px solid transparent;
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 50%;
            left: 50%;
            margin-top: -0.5rem;
            margin-left: -0.5rem;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Pagination animation */
        .pagination a {
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        /* Filter effects for images */
        .img-filter {
            transition: filter 0.3s;
        }

        .img-filter:hover {
            filter: brightness(0.8) contrast(1.2);
        }

        /* Focus animation for inputs */
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Validation feedback */
        .is-invalid {
            border-color: #dc3545;
        }

        .is-invalid:focus {
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }

        .invalid-feedback {
            display: block;
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
                        <h1 class="h3 mb-0 text-gray-800">Danh sách nhân viên</h1>
                        <form action="{{ route('users') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                       placeholder="Nhập tên, email, phòng ban..." class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" id="clearSearch">X</button>
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>
                    
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
                    
                        @if (session('duplicate_emails'))
                        <div class="alert alert-warning">
                            <strong>Cảnh báo!</strong> Những email sau đã tồn tại và không được nhập lại:
                            <ul>
                                @foreach (session('duplicate_emails') as $email)
                                    <li>{{ $email }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    

                    <!-- Card chứa Import và Export -->
                    <div class="card mb-4"></div>
                        <div class="card-body">
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-3">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle w-100" type="button" id="optionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Tùy chọn
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="optionsDropdown">
                                            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                                @csrf
                                                <input type="file" name="import_file" class="form-control" id="importFile" style="display: none;" required>
                                                <button type="submit" class="dropdown-item" id="submitBtn" style="display: none;">
                                                    <i class="fas fa-file-import"></i> Nhập từ Excel
                                                </button>
                                            </form>
                                            <button class="dropdown-item" id="importDataBtn">
                                                <i class="fas fa-file-import"></i> Nhập dữ liệu
                                            </button>
                                            <a href="{{ route('export.template') }}" class="dropdown-item">
                                                <i class="fas fa-file-download"></i> Tải Mẫu Excel
                                            </a>
                                            <form action="{{ route('users.export') }}" method="GET">
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-file-export"></i> Xuất Dữ Liệu
                                                </button>
                                            </form>
                                            <a href="{{ route('users.create') }}" class="dropdown-item">
                                                <i class="fas fa-user-plus"></i> Thêm Nhân Viên
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Nút Xóa Người Dùng Đã Chọn -->
                                <div class="col-md-3 text-center">
                                    <button type="button" class="btn btn-danger w-100" onclick="confirmBulkDelete()">
                                        <i class="fas fa-trash"></i> Xóa Người Dùng Đã Chọn
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách người dùng -->
                    <div class="table-responsive">
                        <form id="deleteUsersForm" method="POST" action="{{ route('users.destroy') }}">
                            @csrf
                            <table class="table table-bordered w-100" id="employeeTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>
                                            STT
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="1">↕</button>
                                        </th>
                                        <th>
                                            Tên
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="2">↕</button>
                                        </th>
                                        <th>
                                            Email
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="3">↕</button>
                                        </th>
                                        <th>
                                            Số điện thoại
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="4">↕</button>
                                        </th>
                                        <th>
                                            Phòng ban
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="5">↕</button>
                                        </th>
                                        <th>
                                            Chức vụ
                                            <button type="button" class="btn btn-link p-0 sort-btn" data-column="6">↕</button>
                                        </th>
                                        <th>Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                    <tr>
                                        <td><input type="checkbox" name="user_ids[]" value="{{ $user->id }}"></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
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
                                            @if ($user->role == 1)
                                            Admin
                                            @elseif ($user->role == 2)
                                            Nhân viên chính thức
                                            @elseif ($user->role == 3)
                                            Nhân viên tạm thời
                                            @else
                                            Chưa xác định
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.detail', $user->id) }}" class="btn btn-primary">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Xin lỗi, không tìm thấy kết quả nào!!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                @if ($users->currentPage() > 1)
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url(1) }}" aria-label="First">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @endif

                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    @if ($i == 1 || $i == $users->lastPage() || ($i >= $users->currentPage() - 2 && $i <= $users->currentPage() + 2))
                                        <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @elseif ($i == 2 || $i == $users->lastPage() - 1)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endfor

                                @if ($users->currentPage() < $users->lastPage())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url($users->lastPage()) }}" aria-label="Last">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>© {{ date('Y') }} Your Company. All Rights Reserved.</span>
            </div>
        </div>
    </footer>
            </div>
            
        </div>
        
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script>
        document.getElementById('importDataBtn').addEventListener('click', function() {
            document.getElementById('importFile').click();
        });

        document.getElementById('importFile').addEventListener('change', function() {
            document.getElementById('importForm').submit();
        });

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
                alert('Vui lòng chọn ít nhất một người dùng để xóa.');
                return;
            }

            if (confirm('Bạn có chắc chắn muốn xóa những người dùng đã chọn?')) {
                form.submit();
            }
        }

        // Smooth scroll to top
        document.querySelector('.scroll-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        document.getElementById('clearSearch').addEventListener('click', function() {
            document.querySelector('input[name="search"]').value = '';
        });

        document.querySelectorAll('.sort-btn').forEach(button => {
            button.addEventListener('click', function() {
                const columnIndex = this.getAttribute('data-column');
                const currentOrder = this.getAttribute('data-order') || 'asc';
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                sortTable(columnIndex, newOrder);
                this.setAttribute('data-order', newOrder);
            });
        });

        function sortTable(columnIndex, order) {
            const table = document.getElementById('employeeTable');
            const rows = Array.from(table.rows).slice(1);
            const sortedRows = rows.sort((a, b) => {
                const aText = a.cells[columnIndex].innerText.trim();
                const bText = b.cells[columnIndex].innerText.trim();
                return order === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });
            const tbody = table.tBodies[0];
            tbody.innerHTML = '';
            sortedRows.forEach(row => tbody.appendChild(row));
        }
        
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>

    <!-- Ensure Bootstrap dropdown is initialized -->
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>

    
</body>

</html>
