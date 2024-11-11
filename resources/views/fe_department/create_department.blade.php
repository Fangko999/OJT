<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thêm phòng ban</title>

    <!-- Font và CSS -->
    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">
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
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="m-0 font-weight-bold text-primary">Thêm phòng ban mới</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('departments.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Tên phòng ban</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                    
                                <div class="form-group">
                                    <label for="parent_id">Phòng ban cha (nếu có)</label>
                                    <select name="parent_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <div class="form-group">
                                    <label for="status">Trạng thái hoạt động</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1">Hoạt động</option>
                                        <option value="0">Không hoạt động</option>
                                    </select>
                                </div>
                    
                                <button type="submit" class="btn btn-success">Thêm phòng ban</button>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('fe-access/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>
</body>

</html>
