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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar') <!-- Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar') <!-- Topbar -->
                <div class="container-fluid">
                    <div class="card-footer">
                        <a href="{{ route('users') }}" class="btn btn-danger mt-4">
                            <i class="fas fa-arrow-left"></i>
                            Quay lại
                        </a>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h2 class="h3 mb-0 text-gray-800">Thông tin chi tiết của người dùng</h2>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('password_success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('password_success') }}
                        </div>
                    @endif
                    @if (session('password_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('password_error') }}
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="card-body">
                            <!-- Hiển thị thông tin chi tiết -->
                            <div class="container-fluid">
                                <div class="form-group">
                                    <label for="name">Họ và Tên</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Giới tính</label>
                                    <input type="text" name="gender" id="gender" class="form-control" value="{{ old('gender', $user->gender == 1 ? 'Nam' : 'Nữ') }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày sinh</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth) }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required readonly>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Số điện thoại</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="department">Phòng ban</label>
                                    @php
                                        // Khởi tạo biến để chứa giá trị của phòng ban
                                        $departmentValue = 'Chưa xác định'; // Giá trị mặc định
                                        if ($user->department) {
                                            if ($user->department->parent) {
                                                $departmentValue = $user->department->name . ' - ' . $user->department->parent->name;
                                            } else {
                                                $departmentValue = $user->department->name;
                                            }
                                        }
                                    @endphp
                                    <input type="text" name="department" id="department" class="form-control" value="{{ old('department', $departmentValue) }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="position">Chức vụ</label>
                                    <input type="text" name="position" id="position" class="form-control" 
                                           value="{{ $user->role == 1 ? 'Admin' : ($user->role == 2 ? 'Nhân viên chính thức' : 'Nhân viên tạm thời') }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="salaryCoefficient">Bậc lương</label>
                                    <input type="text" name="salaryCoefficient" id="salary_coefficient" class="form-control" value="{{ old('salary_coefficient', isset($user->salaryLevel) ? $user->salaryLevel->level_name . ' - Lương tháng: ' . number_format($user->salaryLevel->monthly_salary, 0, ',', '.') . 'đ - Lương ngày: ' . number_format($user->salaryLevel->daily_salary, 0, ',', '.') . 'đ' : 'Chưa có') }}" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $user->status ? 'Hoạt động' : 'Vô hiệu hóa') }}" required readonly>
                                </div>

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateModal">
                                    Chỉnh sửa
                                </button>
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">
                                    Đổi mật khẩu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal cập nhật -->
                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">Cập nhật thông tin người dùng</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="{{ route('users.updatedetail', ['id' => $user->id]) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Họ và Tên:</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
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
                                        <label for="date_of_birth">Ngày sinh:</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $user->phone_number }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Chức vụ:</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Nhân viên chính thức</option>
                                            <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Nhân viên tạm thời</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="department_id">Chọn phòng ban</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            @foreach ($departments->where('status', 1) as $department)
                                                <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="salary_level_id">Chọn bậc lương</label>
                                        <select name="salary_level_id" id="salary_level_id" class="form-control">
                                            <option value="">Chưa có</option>
                                            @foreach ($salaries->where('is_active', 1) as $salary)
                                                <option value="{{ $salary->id }}" data-department="{{ $salary->department_id }}" {{ $user->salary_level_id == $salary->id ? 'selected' : '' }}>
                                                    {{ $salary->level_name }} - Lương tháng: {{ number_format($salary->monthly_salary, 0, ',', '.') }}đ - Lương ngày: {{ number_format($salary->daily_salary, 0, ',', '.') }}đ
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Trạng thái:</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="1" {{ $user->status ? 'selected' : '' }}>Hoạt động</option>
                                            <option value="0" {{ !$user->status ? 'selected' : '' }}>Vô hiệu hóa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-success" id="saveChangesButton">Lưu thay đổi</button>
                                </div>
                            </form>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Modal đổi mật khẩu -->
                <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="post" action="{{ route('users.changePassword', ['id' => $user->id]) }}" id="changePasswordForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="password">Mật khẩu mới:</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Xác nhận mật khẩu:</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                                </div>
                            </form>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
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
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>

    <!-- Custom Script for filtering salary options based on department selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('department_id');
            const salarySelect = document.getElementById('salary_level_id');
            const saveButton = document.getElementById('saveChangesButton');
            const dobInput = document.getElementById('date_of_birth');
            const allSalaries = [...salarySelect.options];

            function updateSalaryOptions() {
                const selectedDepartment = departmentSelect.value;
                salarySelect.innerHTML = '<option value="">Chưa có</option>';

                allSalaries.forEach(option => {
                    if (option.dataset.department === selectedDepartment || option.dataset.department === '') {
                        salarySelect.appendChild(option);
                    }
                });

                if (salarySelect.options.length === 1) {
                    salarySelect.insertAdjacentHTML('beforeend', '<option disabled>Không có bậc lương phù hợp</option>');
                }
            }

            function validateDOB() {
                const dob = new Date(dobInput.value);
                const today = new Date();
                const ageCheckDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

                if (dob > ageCheckDate) {
                    dobInput.setCustomValidity('Nhân viên phải trên 18 tuổi.');
                    dobInput.nextElementSibling.textContent = 'Nhân viên phải trên 18 tuổi.';
                } else {
                    dobInput.setCustomValidity('');
                    dobInput.nextElementSibling.textContent = '';
                }

                dobInput.classList.toggle('is-invalid', !dobInput.checkValidity());
                dobInput.classList.toggle('is-valid', dobInput.checkValidity());
            }

            function validateForm() {
                document.querySelectorAll('#updateModal .form-control').forEach(input => {
                    input.classList.toggle('is-invalid', !input.checkValidity());
                    input.classList.toggle('is-valid', input.checkValidity());
                    input.nextElementSibling.style.display = input.checkValidity() ? 'none' : 'block';
                });

                saveButton.disabled = ![...document.querySelectorAll('#updateModal .form-control')].every(field => field.checkValidity());
            }

            departmentSelect.addEventListener('change', updateSalaryOptions);
            dobInput.addEventListener('input', validateDOB);
            document.querySelectorAll('#updateModal .form-control').forEach(input => {
                input.addEventListener('input', validateForm);
            });

            document.querySelector('#updateModal form').addEventListener('submit', function(event) {
                if (![...document.querySelectorAll('#updateModal .form-control')].every(field => field.checkValidity())) {
                    event.preventDefault();
                    event.stopPropagation();
                    validateForm();
                }
            });

            updateSalaryOptions();
            validateForm();

            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const changePasswordForm = document.getElementById('changePasswordForm');

            function validatePassword() {
                if (passwordInput.value.length < 6) {
                    passwordInput.setCustomValidity('Mật khẩu phải có ít nhất 6 ký tự.');
                    passwordInput.nextElementSibling.textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';
                } else {
                    passwordInput.setCustomValidity('');
                    passwordInput.nextElementSibling.textContent = '';
                }

                passwordInput.classList.toggle('is-invalid', !passwordInput.checkValidity());
                passwordInput.classList.toggle('is-valid', passwordInput.checkValidity());
            }

            function validatePasswordConfirmation() {
                if (passwordInput.value !== passwordConfirmationInput.value) {
                    passwordConfirmationInput.setCustomValidity('Mật khẩu xác nhận không trùng khớp.');
                    passwordConfirmationInput.nextElementSibling.textContent = 'Mật khẩu xác nhận không trùng khớp.';
                } else {
                    passwordConfirmationInput.setCustomValidity('');
                    passwordConfirmationInput.nextElementSibling.textContent = '';
                }

                passwordConfirmationInput.classList.toggle('is-invalid', !passwordConfirmationInput.checkValidity());
                passwordConfirmationInput.classList.toggle('is-valid', passwordConfirmationInput.checkValidity());
            }

            passwordInput.addEventListener('input', validatePassword);
            passwordConfirmationInput.addEventListener('input', validatePasswordConfirmation);

            changePasswordForm.addEventListener('submit', function(event) {
                validatePassword();
                validatePasswordConfirmation();

                if (!changePasswordForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
            });
        });
    </script>

    @if ($errors->has('password') || $errors->has('password_confirmation'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#changePasswordModal').modal('show');
            });
        </script>
    @endif
</body>
</html>