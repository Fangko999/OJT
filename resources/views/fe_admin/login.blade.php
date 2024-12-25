<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

        body {
            background-image: url('{{ asset('fe-access/img/h.jpg') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
            animation: fadeIn 1s ease-in-out;
            background-attachment: fixed; /* Parallax Scrolling */
            backdrop-filter: blur(5px); /* Background Blur */
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.5s ease-in-out;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)); /* Gradient Background */
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Text Shadow */
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); }
            to { transform: translateY(0); }
        }

        .text-center {
            text-align: center;
            margin-bottom: 20px;
        }

        .text-center h1 {
            animation: typing 2s steps(22), blink 0.5s step-end infinite alternate; /* Typing Effect */
            white-space: nowrap;
            overflow: hidden;
            border-right: 3px solid;
        }

        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink {
            from { border-color: transparent; }
            to { border-color: black; }
        }

        .form-control {
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #007bff;
            outline: none;
            animation: focusAnimation 0.3s ease-in-out; /* Focus Animation */
        }

        @keyframes focusAnimation {
            from { box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); }
            to { box-shadow: 0 0 10px rgba(0, 123, 255, 0.8); }
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
            transition: background-color 0.3s, transform 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.5s;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        .btn:active::after {
            transform: translate(-50%, -50%) scale(1);
            transition: 0s;
        }

        .btn.loading {
            pointer-events: none;
            opacity: 0.6;
        }

        .btn.loading::after {
            content: '...';
            animation: loading 1s infinite;
        }

        @keyframes loading {
            0% { content: '...'; }
            33% { content: '..'; }
            66% { content: '.'; }
            100% { content: '...'; }
        }

        small.text-danger {
            display: block;
            margin-top: 5px;
            font-size: 12px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            animation: fadeIn 0.5s ease-in-out; /* Validation Feedback */
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="text-center">
            <h1 class="h4">Xin chào!</h1>
            <p>Vui lòng đăng nhập vào tài khoản của bạn.</p>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="" method="post">
            @csrf
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
            <div style="position: relative;">
                <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                <span class="password-toggle" onclick="togglePassword()">👁️</span>
            </div>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
            <button type="submit" class="btn">Đăng nhập</button>
        </form>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.querySelector('input[name="password"]');
            const passwordToggle = document.querySelector('.password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                passwordToggle.textContent = '👁️';
            }
        }

        document.querySelector('form').addEventListener('submit', function() {
            document.querySelector('.btn').classList.add('loading'); /* Loading Button */
        });
    </script>
</body>

</html>
