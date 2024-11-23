<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc nhở Check In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 15px;
        }
        .email-content {
            padding: 20px;
        }
        .email-footer {
            text-align: center;
            padding: 10px;
            font-size: 0.9em;
            color: #777;
            border-top: 1px solid #ddd;
        }
        a.button {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        a.button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Nhắc nhở Check In</h1>
        </div>
        <div class="email-content">
            <p>Chào <strong>[{{ $user->name }}]</strong>,</p>
            <p>Đây là email nhắc nhở bạn thực hiện check in. Vui lòng click vào liên kết bên dưới để hoàn thành check in:</p>
            <p><a href="http://localhost/EMS%202/login" class="button">Check In Ngay</a></p>
            <p>Cảm ơn bạn!</p>
            <p>Trân trọng,</p>
            <p><strong>Công ty AAA</strong></p>
        </div>
        <div class="email-footer">
            <p>© {{ date('Y') }} Công ty AAA. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
