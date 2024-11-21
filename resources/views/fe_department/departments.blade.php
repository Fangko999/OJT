<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('fe-access/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{asset('fe-access/css/sb-admin-2.min.css')}}" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
            transition: background-color 0.3s ease;
        }
        .tree { 
            padding-left: 20px; 
            background-color: #e9ecef; 
            border-radius: 5px; 
            padding: 15px;
            transition: background-color 0.3s ease, padding 0.3s ease;
        }
        .toggle-node { 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            margin-bottom: 5px; 
            background-color: #dee2e6; 
            padding: 10px; 
            border-radius: 5px;
            transition: background-color 0.3s ease, padding 0.3s ease;
            font-size: 1.1em;
            font-weight: bold;
        }
        .toggle-node:hover {
            background-color: #ced4da;
        }
        .child-department { 
            margin-left: 40px; 
            background-color: #f1f3f5; 
            padding: 10px; 
            border-radius: 5px;
            transition: background-color 0.3s ease, padding 0.3s ease;
            font-size: 1em;
            font-weight: normal;
        }
        .toggle-btn { 
            margin-right: 10px; 
            color: #6c757d; 
            transition: color 0.3s ease;
        }
        .toggle-btn:hover {
            color: #495057;
        }
        .card {
            border: none;
            background-color: #e9ecef;
            transition: background-color 0.3s ease;
        }
        .card-header, .card-body {
            background-color: #f8f9fc;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
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
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Danh sách phòng ban</h1>
                        <div class="d-flex">
                            <a href="{{ route('departments.create') }}" class="btn btn-primary mr-2">Thêm phòng ban</a>

                            <form action="{{ route('departments.search') }}" method="GET" class="form-inline">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control" placeholder="Tìm kiếm phòng ban" required aria-label="Search" aria-describedby="search-button">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" id="search-button">Tìm kiếm</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="tree">
                            @if($departments->isEmpty())
                                <p>Không tìm thấy phòng ban nào khớp với từ khóa.</p>
                            @else
                            @foreach ($departments as $department)
                            @include('fe_department.department_tree', ['department' => $department])
                        @endforeach
                            @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-btn');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.parentElement.getAttribute('data-id');
                    const subTree = document.querySelector(`.sub-departments-${id}`);
                    const icon = this.querySelector('i');

                    // Toggle the display of the subtree
                    const isHidden = subTree.style.display === 'none';
                    subTree.style.display = isHidden ? 'block' : 'none';

                    // Toggle the icon class between up and down chevron
                    icon.classList.toggle('fa-chevron-down', !isHidden);
                    icon.classList.toggle('fa-chevron-up', isHidden);
                });
            });
        });
    </script>

    <script src="/fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="/fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/fe-access/js/sb-admin-2.min.js"></script>
</body>

</html>