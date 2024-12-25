<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm Nhân Viên</title>

    <!-- Font và CSS -->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <style>
        /* Hover effects */
        .btn:hover {
            background-color: #0056b3;
            color: white;
        }

        /* Focus effects */
        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Transition effects */
        .form-control,
        .btn {
            transition: all 0.3s ease-in-out;
        }

        /* Validation feedback */
        .was-validated .form-control:invalid {
            border-color: #dc3545;
        }

        .was-validated .form-control:valid {
            border-color: #28a745;
        }

        /* Button loading effect */
        .btn-loading {
            position: relative;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinner-border .75s linear infinite;
            transform: translate(-50%, -50%);
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
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
                    <button onclick="window.history.back();" class="btn btn-danger mt-4">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="card mt-4">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Thêm Nhân Viên Mới</h4>

                            <form id="createUserForm" action="{{ route('users.store') }}" method="POST" novalidate>
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Họ và Tên:</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Họ và tên nhân viên" value="{{ old('name') }}" required>
                                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                        <div class="invalid-feedback">Vui lòng nhập tên nhân viên.</div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="gender">Giới tính:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male" value="1" {{ old('gender', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_male">Nam</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female" value="0" {{ old('gender') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_female">Nữ</label>
                                        </div>
                                        @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
                                        <div class="invalid-feedback">Vui lòng chọn giới tính.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="date_of_birth">Ngày sinh:</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                                        @error('date_of_birth') <small class="text-danger">{{ $message }}</small> @enderror
                                        <div class="invalid-feedback">Vui lòng chọn ngày sinh.</div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="phone_number">Số điện thoại:</label>
                                        <input type="tel" name="phone_number" id="phone_number" class="form-control"
                                            placeholder="Nhập số điện thoại" value="{{ old('phone_number') }}" pattern="^(\+84|84|0)[0-9]{9}$" required>
                                        <input type="hidden" name="formatted_phone_number" id="formatted_phone_number">
                                        @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                                        <div class="invalid-feedback">Số điện thoại phải bắt đầu bằng 0, 84, hoặc +84 và có 10 hoặc 11 chữ số.</div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Nhập địa chỉ email" value="{{ old('email') }}" required>
                                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        <small id="email-exists-error" class="text-danger"></small>
                                        <div class="invalid-feedback">Vui lòng nhập địa chỉ email hợp lệ.</div>
                                    </div>

                                    <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="department_id">Phòng ban:</label>
                                        <select name="department_id" id="department_id" class="form-control" required>
                                            <option value="">Chọn phòng ban</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Vui lòng chọn phòng ban.</div>
                                    </div>

                                    <div class="form-group col-md-6">
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
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="password">Mật khẩu:</label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Nhập mật khẩu (Tối thiểu 6 ký tự)..." required minlength="6">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <div class="invalid-feedback">Vui lòng nhập mật khẩu (Tối thiểu 6 ký tự).</div>
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="password_confirmation">Xác nhận mật khẩu:</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control" placeholder="Xác nhận mật khẩu" required>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                                        <div class="invalid-feedback">Vui lòng xác nhận mật khẩu.</div>
                                        <div class="invalid-feedback" id="password-confirmation-error">Mật khẩu xác nhận không khớp.</div>
                                    </div>
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

            // Add loading effect to submit button
            var submitButton = document.querySelector('button[type="submit"]');
            submitButton.classList.add('btn-loading');
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

        // Immediate validation feedback
        document.querySelectorAll('.form-control').forEach(function(input) {
            input.addEventListener('input', function() {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            });
        });

        // Check if email already exists and validate email format
        document.getElementById('email').addEventListener('input', function() {
            var emailInput = this;
            var email = emailInput.value;
            var errorMessage = document.getElementById('email-exists-error');
            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (email && emailPattern.test(email)) {
                fetch('{{ route('users.checkEmail') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        emailInput.classList.add('is-invalid');
                        errorMessage.textContent = 'Email đã tồn tại.';
                    } else {
                        emailInput.classList.remove('is-invalid');
                        errorMessage.textContent = '';
                    }
                });
            } else {
                emailInput.classList.add('is-invalid');
                errorMessage.textContent = 'Email không hợp lệ.';
            }
        });

        // Validate date of birth
        document.getElementById('date_of_birth').addEventListener('input', function() {
            var dobInput = this;
            var dob = new Date(dobInput.value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (age < 18) {
                dobInput.classList.add('is-invalid');
                dobInput.nextElementSibling.textContent = 'Nhân viên phải trên 18 tuổi.';
            } else {
                dobInput.classList.remove('is-invalid');
                dobInput.classList.add('is-valid');
                dobInput.nextElementSibling.textContent = '';
            }
        });

        // Validate password confirmation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            var passwordInput = document.getElementById('password');
            var passwordConfirmationInput = this;
            var errorMessage = document.getElementById('password-confirmation-error');
            if (passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationInput.classList.add('is-invalid');
                errorMessage.style.display = 'block';
            } else {
                passwordConfirmationInput.classList.remove('is-invalid');
                passwordConfirmationInput.classList.add('is-valid');
                errorMessage.style.display = 'none';
            }
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.previousElementSibling;
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        document.getElementById('role').addEventListener('change', function() {
            var role = this.value;
            var departmentField = document.getElementById('department_id');
            var salaryField = document.getElementById('salary_level_id');

            if (role == 1) { // Admin
                departmentField.disabled = true;
                salaryField.disabled = true;
            } else {
                departmentField.disabled = false;
                salaryField.disabled = false;
            }
        });

        // Trigger change event on page load to set initial state
        document.getElementById('role').dispatchEvent(new Event('change'));
    </script>
</body>

</html>