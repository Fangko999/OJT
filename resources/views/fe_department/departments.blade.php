<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Quản lý phòng ban</title>

    <link href="{{ asset('fe-access/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('fe-access/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        .input-group .form-control {
            border-radius: 0.25rem;
        }

        .input-group-append .btn {
            border-radius: 0 0.25rem 0.25rem 0;
        }

        .custom-btn {
            background-color: #d3d3d3;
            color: #000;
            border: none;
            transition: background-color 0.3s ease;
        }

        .custom-btn:hover {
            background-color: #c0c0c0;
        }

        .department-name {
            color: #000;
            text-decoration: none;
        }

        .department-name:hover {
            text-decoration: underline;
        }

        .parent-department {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .alert {
            margin-bottom: 20px;
        }

        .btn-primary {
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .tree {
            margin: 20px;
        }

        .department-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            margin-bottom: 10px;
            transition: background-color 0.3s;
            cursor: pointer;
            background-color: #f9f9f9;
        }

        .department-item:hover {
            background-color: #e9e9e9;
        }

        .child-departments {
            display: none; /* Ẩn phòng ban con theo mặc định */
            padding-left: 20px; /* Thêm khoảng cách cho phòng ban con */
        }

        .toggle-icon {
            display: inline-block;
            width: 20px;
            margin-right: 5px;
            transition: transform 0.3s; /* Hiệu ứng chuyển động cho mũi tên */
            cursor: pointer; /* Con trỏ chuột để cho biết đây là nút có thể nhấn */
        }

        .toggle-icon.collapsed {
            transform: rotate(90deg); /* Xoay mũi tên khi thu gọn */
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('fe_admin.slidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('fe_admin.topbar')

                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Danh sách phòng ban</h1>

                        <div class="d-flex">
                            <a href="{{ route('departments.create') }}" class="btn btn-success mr-2">Thêm phòng ban</a>

                            <form action="{{ route('departments.search') }}" method="GET" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control" placeholder="Tìm kiếm phòng ban" required aria-label="Search" aria-describedby="search-button">
                                    <div class="input-group-append">
                                        <button class="btn btn-light custom-btn" type="submit" id="search-button">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="tree">
                            @php
                                function renderDepartments($departments) {
                                    if ($departments->isEmpty()) return;

                                    echo '<div>'; // Thêm lề cho phòng ban con
                                    foreach ($departments as $department) {
                                        echo '<div class="department-item">';
                                        echo '<a href="' . route('departments.show', $department->id) . '" class="parent-department">'; // Chuyển đến trang chi tiết
                                        echo $department->name . ' (' . $department->children->count() . ')';
                                        echo '</a>';
                                        echo '<span class="toggle-icon" data-toggle="children-' . $department->id . '">&#x25B6;</span>'; // Mũi tên để mở/đóng
                                        echo '<div id="children-' . $department->id . '" class="child-departments">'; // Phòng ban con ẩn
                                        if ($department->children->isNotEmpty()) {
                                            renderDepartments($department->children);
                                        }
                                        echo '</div>'; // Kết thúc phòng ban con
                                        echo '</div>'; // Kết thúc phòng ban
                                    }
                                    echo '</div>';
                                }
                            @endphp

                            @if($departments->isEmpty())
                                <div class="alert alert-warning">Không tìm thấy phòng ban nào khớp với từ khóa.</div>
                            @else
                                @foreach ($departments as $department)
                                    <div class="department-item">
                                        <a href="{{ route('departments.show', $department->id) }}" class="parent-department">
                                            {{ $department->name }} ({{ $department->children->count() }})
                                        </a>
                                        <span class="toggle-icon" data-toggle="children-{{ $department->id }}">&#x25B6;</span> <!-- Mũi tên -->
                                        <div id="children-{{ $department->id }}" class="child-departments">
                                            @if($department->children->isNotEmpty())
                                                @php renderDepartments($department->children) @endphp
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @include('fe_admin.footer')
        </div>
    </div>

    <script src="{{ asset('fe-access/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('fe-access/js/sb-admin-2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.toggle-icon').click(function(event) {
                event.stopPropagation(); // Ngăn chặn sự kiện click tràn xuống tên phòng ban
                var toggleId = $(this).data('toggle');
                $('#' + toggleId).toggle(); // Ẩn/hiện phòng ban con
                $(this).toggleClass('collapsed'); // Thay đổi lớp để thêm hiệu ứng
            });
        });
    </script>
</body>

</html>
