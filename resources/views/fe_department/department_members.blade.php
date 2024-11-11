<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quản lý phòng ban</title>

    <!-- Font và CSS -->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        /* Các style đã có */
        .btn-inline {
            margin-left: 10px; /* Điều chỉnh khoảng cách giữa tên và nút */
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Thanh bên -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->

                <div class="container-fluid">
                    <button onclick="window.history.back();" class="btn mt-4" 
                    style="background-color: #dc3545; color: #ffffff; border: none; padding: 0.375rem 0.75rem;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                    @if($department)
                    <h1 class="mt-4" style="color: #000000; font-weight: bold;">
                        {{ $department->name }}
                        <div class="d-inline-block">
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning btn-sm btn-inline">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <button type="button" class="btn btn-danger btn-sm btn-inline" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </h1>
                  
                    <div class="mb-3">
                        <span>Trạng thái phòng ban: </span>
                        <span class="badge" style="background-color: {{ $department->status ? '#28a745' : '#dc3545' }}; color: white;">
                            {{ $department->status ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>

                        <!-- Form thay đổi trạng thái -->
                        <form action="{{ route('departments.updateStatus', $department->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PATCH')  <!-- Thêm method PATCH -->
                        
                            <input type="hidden" name="status" value="{{ $department->status ? 0 : 1 }}">
                            <button type="submit" class="btn btn-toggle {{ $department->status ? 'btn-danger' : 'btn-success' }}">
                                <i class="fas {{ $department->status ? 'fa-times' : 'fa-check' }}"></i> 
                                {{ $department->status ? 'Tắt' : 'Bật' }}
                            </button>
                        </form>
                    </div>
                
                    <div class="card mt-4">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Chức vụ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone_number }}</td>
                                        <td>{{ $user->position }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có thành viên nào trong các phòng ban này.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                
                            </table>
                            
                        </div>
                    </div>

                    <!-- Nút sửa phòng ban -->
                    <div class="mb-3">
                        <!-- Nút xóa phòng ban -->
                    </div>
                    @else
                        <div class="alert alert-danger mt-4">
                            Phòng ban không tồn tại hoặc đã bị xóa.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal xác nhận xóa -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa phòng ban này và tất cả các phòng ban con của nó không?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
</body>
</html>
