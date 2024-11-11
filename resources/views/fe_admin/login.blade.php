<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập</title>
    <style>
        body {
            background-image: url('{{ asset('fe-access/img/h.jpg') }}'); /* Đặt hình nền */
            background-size: cover; /* Đảm bảo hình nền phủ kín màn hình */
            background-position: center; /* Căn giữa hình nền */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif; /* Thêm font */
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9); /* Nền trắng với độ trong suốt */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .text-center {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
        }

        .btn:hover {
            background-color: #0056b3;
        }

        small.text-danger {
            display: block;
            margin-top: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="text-center">
            <h1 class="h4">Xin chào!</h1>
            <p>Vui lòng đăng nhập vào tài khoản của bạn.</p>
        </div>
        <form action="" method="post">
            @csrf
            <input type="email" name="email" class="form-control" placeholder="Email" required> <!-- Đã sửa thành Email -->
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
    </div>
</body>

</html>
