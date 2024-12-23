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
            background-color: #e9ecef; /* Gray background */
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
            transition: background-color 0.3s ease, padding 0.3s ease, transform 0.3s ease;
            font-size: 1.1em;
            font-weight: bold; /* Bold text */
            color: #000; /* Black text */
        }
        .toggle-node:hover {
            background-color: #ced4da;
            transform: scale(1.02);
        }
        .toggle-node:focus, .toggle-node:active {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
        .toggle-node .department-name {
            margin-left: 10px;
            flex-grow: 1;
        }
        .child-department { 
            margin-left: 40px; 
            background-color: #f1f3f5; 
            padding: 10px; 
            border-radius: 5px;
            transition: background-color 0.3s ease, padding 0.3s ease, transform 0.3s ease;
            font-size: 1em;
            font-weight: bold; /* Bold text */
            color: #000; /* Black text */
        }
        .child-department:hover {
            transform: scale(1.02);
        }
        .toggle-btn { 
            margin-right: 10px; 
            color: #007bff; /* Blue color */
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .toggle-btn:hover {
            color: #0056b3; /* Darker blue on hover */
            transform: rotate(90deg);
        }
        .toggle-btn i {
            color: #007bff; /* Blue color for chevron icon */
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .toggle-btn:hover i {
            color: #0056b3; /* Darker blue on hover for chevron icon */
            transform: rotate(90deg);
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
        .btn-primary:focus, .btn-primary:active {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .statistics {
            margin-top: 20px;
            padding: 20px;
            background-color: #f8f9fc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .statistics h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }
        .statistics .stat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .statistics .stat span {
            font-weight: bold;
        }
        .department-name {
            font-weight: bold; /* Bold text */
            color: #000; /* Black text */
        }
        .ripple {
            position: relative;
            overflow: hidden;
        }
        .ripple::after {
            content: '';
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            background-image: radial-gradient(circle, #000 10%, transparent 10.01%);
            background-repeat: no-repeat;
            background-position: 50%;
            transform: scale(10, 10);
            opacity: 0;
            transition: transform 0.5s, opacity 1s;
        }
        .ripple:active::after {
            transform: scale(0, 0);
            opacity: 0.2;
            transition: 0s;
        }
        .loading-btn {
            position: relative;
            pointer-events: none;
        }
        .loading-btn::after {
            content: '';
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.6);
            background-image: url('/path/to/loader.gif');
            background-repeat: no-repeat;
            background-position: center;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .pagination a:hover {
            background-color: #007bff;
            color: #fff;
        }
        .filter-blur {
            filter: blur(5px);
            transition: filter 0.3s;
        }
        .filter-blur:hover {
            filter: blur(0);
        }
        .focus-animation {
            transition: box-shadow 0.3s;
        }
        .focus-animation:focus {
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
        .validation-feedback {
            color: #dc3545;
            font-size: 0.875em;
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

                            <form action="{{ route('departments.search') }}" method="GET" class="form-inline" id="search-form">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control" placeholder="Tìm kiếm phòng ban" required aria-label="Search" aria-describedby="search-button" id="search-input">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-search">X</button>
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
                                    <div class="toggle-node" data-id="{{ $department->id }}">
                                        @if($department->children && $department->children->count() > 0)
                                            <button class="btn btn-sm btn-outline-secondary toggle-btn">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        @else
                                            <span class="toggle-btn" style="visibility: hidden;">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        @endif
                                        <a href="{{ route('departments.show', $department->id) }}" class="department-name">
                                            {{ $department->name }} ({{ $department->children ? $department->children->count() : 0 }})
                                        </a>
                                    </div>
                                    <div class="sub-tree sub-departments-{{ $department->id }}" style="display: none; margin-left: 20px;">
                                        @foreach ($department->children ?? [] as $child)
                                            <div class="toggle-node" data-id="{{ $child->id }}">
                                                @if($child->children && $child->children->count() > 0)
                                                    <button class="btn btn-sm btn-outline-secondary toggle-btn">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                @else
                                                    <span class="toggle-btn" style="visibility: hidden;">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </span>
                                                @endif
                                                <a href="{{ route('departments.show', $child->id) }}" class="department-name">
                                                    {{ $child->name }} ({{ $child->children ? $child->children->count() : 0 }})
                                                </a>
                                            </div>
                                            <div class="sub-tree sub-departments-{{ $child->id }}" style="display: none; margin-left: 20px;">
                                                @foreach ($child->children ?? [] as $grandChild)
                                                    <div class="toggle-node" data-id="{{ $grandChild->id }}">
                                                        @if($grandChild->children && $grandChild->children->count() > 0)
                                                            <button class="btn btn-sm btn-outline-secondary toggle-btn">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </button>
                                                        @else
                                                            <span class="toggle-btn" style="visibility: hidden;">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </span>
                                                        @endif
                                                        <a href="{{ route('departments.show', $grandChild->id) }}" class="department-name">
                                                            {{ $grandChild->name }} ({{ $grandChild->children ? $grandChild->children->count() : 0 }})
                                                        </a>
                                                    </div>
                                                    <div class="sub-tree sub-departments-{{ $grandChild->id }}" style="display: none; margin-left: 20px;">
                                                        @foreach ($grandChild->children ?? [] as $greatGrandChild)
                                                            <div class="toggle-node" data-id="{{ $greatGrandChild->id }}">
                                                                @if($greatGrandChild->children && $greatGrandChild->children->count() > 0)
                                                                    <button class="btn btn-sm btn-outline-secondary toggle-btn">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </button>
                                                                @else
                                                                    <span class="toggle-btn" style="visibility: hidden;">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </span>
                                                                @endif
                                                                <a href="{{ route('departments.show', $greatGrandChild->id) }}" class="department-name">
                                                                    {{ $greatGrandChild->name }} ({{ $greatGrandChild->children ? $greatGrandChild->children->count() : 0 }})
                                                                </a>
                                                            </div>
                                                            <div class="sub-tree sub-departments-{{ $greatGrandChild->id }}" style="display: none; margin-left: 20px;">
                                                                @foreach ($greatGrandChild->children ?? [] as $greatGreatGrandChild)
                                                                    @include('fe_department.department_tree', ['department' => $greatGreatGrandChild])
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
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
            const searchInput = document.getElementById('search-input');
            const searchForm = document.getElementById('search-form');
            const clearSearchButton = document.getElementById('clear-search');

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

            clearSearchButton.addEventListener('click', function () {
                searchInput.value = '';
            });
        });
    </script>

    <script src="/fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="/fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/fe-access/js/sb-admin-2.min.js"></script>
</body>

</html>