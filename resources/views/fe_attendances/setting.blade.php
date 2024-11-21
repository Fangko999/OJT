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
        .tree {
            padding-left: 20px;
        }
        .toggle-node {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .child-department {
            margin-left: 30px;
        }
        .toggle-btn {
            margin-right: 10px;
        }
        /* Form styling */
        .form-container {
            max-width: 600px; /* Adjusted width */
            margin: 0 auto;
            padding: 30px;
            background: #ffffff; /* Changed background color */
            border-radius: 10px; /* Adjusted border radius */
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2); /* Adjusted box shadow */
        }
        .form-container label {
            font-weight: 700; /* Adjusted font weight */
            margin-bottom: 10px; /* Adjusted margin */
            color: #343a40; /* Changed to dark gray */
        }
        .form-container input[type="time"] {
            width: 100%;
            padding: 12px; /* Adjusted padding */
            border: 1px solid #ced4da; /* Adjusted border color */
            border-radius: 5px; /* Adjusted border radius */
            margin-bottom: 20px; /* Adjusted margin */
        }
        .form-container button {
            width: 100%;
            padding: 12px; /* Adjusted padding */
            color: #fff;
            background-color: #4e73df; /* Changed to primary color */
            border: none;
            border-radius: 5px; /* Adjusted border radius */
            font-weight: bold;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #2e59d9; /* Changed to darker primary color */
        }
        .message {
            padding: 15px;
            background-color: #e9ecef; /* Adjusted background color */
            color: #343a40; /* Changed to dark gray */
            border-radius: 5px; /* Adjusted border radius */
            margin-bottom: 20px; /* Adjusted margin */
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
                    @if (session('message'))
                        <div class="message">{{ session('message') }}</div>
                    @endif
                
                    <div class="form-container">
                        <!-- Tiêu đề cho form -->
                        <h2 class="text-center mb-4">Cài đặt thời gian chấm công</h2>
                        <p class="text-center mb-4">Vui lòng nhập thời gian check-in và check-out hợp lệ.</p>
                
                        <form action="{{ route('setting.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="check_in_time">Thời gian Check In</label>
                                <input type="time" id="check_in_time" name="check_in_time" value="{{ $checkInTime }}" required>
                            </div>
                            <div class="form-group">
                                <label for="check_out_time">Thời gian Check Out</label>
                                <input type="time" id="check_out_time" name="check_out_time" value="{{ $checkOutTime }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </form>
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

    <!-- JavaScript files -->
    <script src="/fe-access/vendor/jquery/jquery.min.js"></script>
    <script src="/fe-access/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/fe-access/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/fe-access/js/sb-admin-2.min.js"></script>
</body>

</html>