<!DOCTYPE html>
<html lang="vi">

<head>
    <style>
        table.table tbody tr:hover {
            background-color: #f2f2f2; /* Màu khi hover */
            transition: background-color 0.3s ease-in-out;
        }
    
        table.table tbody tr.selected {
            background-color: #d1ecf1; /* Màu highlight khi được chọn */
        }
    
        .btn {
            transition: transform 0.2s, opacity 0.2s;
        }
    
        .btn:hover {
            transform: scale(1.05); /* Phóng to nhẹ khi hover */
            opacity: 0.9; /* Giảm độ mờ khi hover */
        }

        .btn:active {
            transform: scale(0.95); /* Thu nhỏ nhẹ khi active */
        }

        .btn:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Hiệu ứng focus */
        }

        .gradient-bg {
            background: linear-gradient(45deg, #6a11cb, #2575fc); /* Gradient background */
        }

        .parallax {
            background: url('path/to/image.jpg') no-repeat fixed; /* Parallax scrolling */
            background-size: cover;
        }

        .blur-bg {
            backdrop-filter: blur(10px); /* Background blur */
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Text shadow */
        }

        .typing-effect {
            overflow: hidden;
            border-right: .15em solid orange;
            white-space: nowrap;
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

        .text-gradient {
            background: linear-gradient(to right, #30cfd0 0%, #330867 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
            transition: transform 0.5s, opacity 1s;
        }

        .ripple-effect:active::after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            transition: 0s;
        }

        .loading-btn {
            position: relative;
            padding-right: 2.5rem;
        }

        .loading-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 1rem;
            width: 1rem;
            height: 1rem;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinner 0.6s linear infinite;
            transform: translateY(-50%);
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        .pagination-animation {
            display: flex;
            list-style: none;
        }

        .pagination-animation li {
            margin: 0 0.25rem;
            transition: transform 0.3s;
        }

        .pagination-animation li:hover {
            transform: scale(1.2);
        }

        .filter-effects {
            filter: grayscale(100%);
            transition: filter 0.3s;
        }

        .filter-effects:hover {
            filter: grayscale(0%);
        }

        .focus-animation {
            transition: box-shadow 0.3s;
        }

        .focus-animation:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }

        .validation-feedback {
            color: red;
            font-size: 0.875rem;
        }

        .search-input {
            width: 300px;
            margin-bottom: 1rem;
        }

        .bold-black {
            font-weight: bold;
            color: black;
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danh sách người dùng</title>

    <!-- Font và CSS -->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <div class="container-fluid">
                    <button onclick="window.location.href='{{ route('departments') }}';" class="btn btn-danger mt-4">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                    @if($department)
                    <h1 class="mt-4 bold-black">{{ $department->name }}</h1>
                
                    <div class="mb-3">
                        <span class="bold-black">Trạng thái phòng ban: </span>
                        <span class="badge {{ $department->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $department->status ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                        <!-- Form thay đổi trạng thái -->
                        <form action="{{ route('departments.updateStatus', $department->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PATCH')  <!-- Thêm method PATCH -->
                        
                            <input type="hidden" name="status" value="{{ $department->status ? 0 : 1 }}">
                            <button type="submit" class="btn btn-sm {{ $department->status ? 'btn-danger' : 'btn-success' }}">
                                {{ $department->status ? 'Tắt' : 'Bật' }}
                            </button>
                        </form>
                        <!-- Nút chỉnh sửa phòng ban -->
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-primary">Chỉnh sửa</a>
                    </div>

                    <!-- Search input -->
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Tìm kiếm theo tên nhân viên...">
                    </div>
                
                    <div class="card mt-4">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên nhân viên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Phòng</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeTable">
                                    @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>  
                                        <td>
                                            @if ($user->department)
                                                {{ $user->department->name }} 
                                            @else
                                                Chưa xác định
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có thành viên nào trong các phòng ban này.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                    @else
                        <div class="alert alert-danger mt-4">
                            Phòng ban không tồn tại hoặc đã bị xóa.
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa thành viên này không?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger">Xóa</button>
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

    <!-- JavaScript -->
    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('#employeeTable tr');
            rows.forEach(function(row) {
                var name = row.cells[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>