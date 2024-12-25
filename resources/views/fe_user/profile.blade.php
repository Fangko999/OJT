<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Danh sách người dùng</title>
    <!-- Font và CSS -->
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        .card-header {
            background-color: #6c757d;
            color: white;
        }

        .card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }

        .form-control {
            border-radius: 50px;
        }

        .modal-header.bg-primary {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_user.slidebar') <!-- Thanh bên -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Thanh trên -->
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Card Thông tin cá nhân -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5><i class="fas fa-user-circle"></i> Thông Tin Cá Nhân</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Họ và tên</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Giới tính</label>
                                        <input type="text" name="gender" id="gender" class="form-control" value="{{ $user->gender == 0 ? 'Nữ' : 'Nam' }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="date_of_birth">Ngày sinh</label>
                                        <input type="text" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number">Số điện thoại</label>
                                        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="department">Phòng ban</label>
                                        @php
                                            $departmentValue = 'Chưa xác định';
                                            if ($user->department) {
                                                if ($user->department->parent) {
                                                    $departmentValue = $user->department->name . ' - ' . $user->department->parent->name;
                                                } else {
                                                    $departmentValue = $user->department->name;
                                                }
                                            }
                                        @endphp
                                        <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $departmentValue) }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Chức vụ</label>
                                        <input type="text" name="role" id="role" class="form-control" value="{{ $user->role == 2 ? 'Nhân viên chính thức' : ($user->role == 3 ? 'Nhân viên tạm thời' : 'Admin') }}" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="salaryCoefficient">Bậc lương</label>
                                        <input type="text" name="salaryCoefficient" id="salary_coefficient" class="form-control" value="{{ old('salary_coefficient', isset($user->salaryLevel) ? $user->salaryLevel->level_name . ' - Lương tháng: ' . number_format($user->salaryLevel->monthly_salary, 0, ',', '.') . 'đ - Lương ngày: ' . number_format($user->salaryLevel->daily_salary, 0, ',', '.') . 'đ' : 'Chưa có') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('attendance') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-arrow-circle-left"></i> Quay lại
                                </a>
                                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#editUserModal">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </button>
                                <button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#changePasswordModal">
                                    <i class="fas fa-key"></i> Đổi mật khẩu
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal chỉnh sửa thông tin người dùng -->
                    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title" id="editUserModalLabel">Sửa Thông Tin Nhân Viên</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form id="editUserForm" action="{{ route('users.quickUpdate', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Họ và tên</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender">Giới tính:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="1" {{ $user->gender == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_male">Nam</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="0" {{ $user->gender == '0' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_female">Nữ</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="date_of_birth">Ngày sinh</label>
                                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number">Số điện thoại</label>
                                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                                        </div>
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            <a href="{{ route('attendance') }}" class="btn btn-outline-secondary">Hủy</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal đổi mật khẩu -->
                    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h5 class="modal-title" id="changePasswordModalLabel">Đổi Mật Khẩu</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form id="changePasswordForm" action="{{ route('users.changePassword', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="old_password">Mật khẩu cũ</label>
                                            <div class="input-group">
                                                <input type="password" name="old_password" id="old_password" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#old_password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">Mật khẩu mới</label>
                                            <div class="input-group">
                                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                                            <div class="input-group">
                                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#new_password_confirmation">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3">
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Hủy</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© 2024 Công ty ABC. Tất cả quyền lợi được bảo vệ.</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- JS, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.querySelector(button.getAttribute('data-target'));
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    button.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    </script>
</body>

</html>
