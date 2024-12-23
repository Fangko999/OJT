<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm Người Dùng</title>

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
                    <button onclick="window.history.back();" class="btn btn-danger mt-4">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Thêm Nhân Viên Mới</h4>

                            <form id="createUserForm" action="{{ route('users.store') }}" method="POST" novalidate>
                                @csrf
                                <div class="form-group">
                                    <label for="name">Họ và Tên:</label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                           placeholder="Họ và tên nhân viên" value="{{ old('name') }}" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng nhập tên người dùng.</div>
                                </div>

                                <div class="form-group">
                                    <label for="gender">Giới tính:</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>Nam</option>
                                        <option value="0" {{ old('gender') == 0 ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                    @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng chọn giới tính.</div>
                                </div>

                                <div class="form-group">
                                    <label for="date_of_birth">Ngày sinh:</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng chọn ngày sinh.</div>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Số điện thoại:</label>
                                    <input type="tel" name="phone_number" id="phone_number" class="form-control" 
                                           placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}" pattern="^(\+84|84|0)[0-9]{9}$" required>
                                    <input type="hidden" name="formatted_phone_number" id="formatted_phone_number">
                                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ.</div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                           placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng nhập địa chỉ email hợp lệ.</div>
                                </div>

                                <div class="form-group">
                                    <label for="role">Chức vụ:</label>
                                    <select name="role" id="role" class="form-control" required>
                                        <option value="">Chọn chức vụ</option>
                                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Nhân viên chính thức</option>
                                        <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Nhân viên tạm thời</option>
                                    </select>
                                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng chọn chức vụ.</div>
                                </div>

                                <div class="form-group">
                                    <label for="department_id">Phòng ban:</label>
                                    <select name="department_id" id="department_id" class="form-control" required>
                                        <option value="">-- Chọn phòng ban --</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Vui lòng chọn phòng ban.</div>
                                </div>

                                <div class="form-group">
                                    <label for="salary_level_id">Bậc lương:</label>
                                    <select name="salary_level_id" id="salary_level_id" class="form-control" required>
                                        <option value="">Chọn bậc lương</option>
                                        @foreach($salaries as $salary)
                                            <option value="{{ $salary->id }}" {{ old('salary_level_id') == $salary->id ? 'selected' : '' }}>
                                                {{ $salary->level_name }} - Lương tháng: {{ number_format($salary->monthly_salary, 0, ',', '.') }}đ - Lương ngày: {{ number_format($salary->daily_salary, 0, ',', '.') }}đ
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('salary_level_id') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng chọn bậc lương.</div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Mật khẩu:</label>
                                    <input type="password" name="password" id="password" class="form-control" 
                                           placeholder="Nhập mật khẩu" required minlength="6">
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng nhập mật khẩu (tối thiểu 6 ký tự).</div>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Xác nhận mật khẩu:</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="form-control" placeholder="Xác nhận mật khẩu" required>
                                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                                    <div class="invalid-feedback">Vui lòng xác nhận mật khẩu.</div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">Thêm Nhân Viên</button>
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
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="fe-access/js/sb-admin-2.min.js"></script>
    <script>
        document.getElementById('createUserForm').addEventListener('submit', function() {
            var phoneNumber = document.getElementById('phone_number').value;
            var formattedPhoneNumber = phoneNumber.replace(/^(\+84|84|0)/, '0');
            document.getElementById('formatted_phone_number').value = formattedPhoneNumber;
        });

        // Client-side validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>
</html>