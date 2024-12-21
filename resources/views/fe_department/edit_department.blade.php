<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chỉnh sửa phòng ban</title>

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
                    <h1 class="mt-4">Chỉnh sửa phòng ban</h1>

                    <form action="{{ route('departments.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên phòng ban</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $department->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Phòng ban cha</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">Không có</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $department->parent_id == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ $department->status ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ !$department->status ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </form>
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
    <script src="{{asset('fe-access/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('fe-access/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('fe-access/js/sb-admin-2.min.js')}}"></script>
</body>
</html>
