<!DOCTYPE html>
<html>
<head>
    <title>Đơn giải trình đã bị từ chối</title>
    <style>
        body {
            background-color: #f3f4f6; /* Màu nền nhạt hơn */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .email-content {
            background-color: #ffffff;
            margin: 40px auto;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }
        .email-header {
            background-color: #dc3545; /* Màu đỏ đậm */
            color: #ffffff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            text-align: left;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }
        .email-body ul {
            list-style-type: none;
            padding: 0;
        }
        .email-body ul li {
            margin: 8px 0;
            padding: 8px;
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
        }
        .email-body .note {
            margin-top: 20px;
            padding: 15px;
            background-color: #ffeeba;
            border-left: 4px solid #ffc107;
            font-weight: bold;
            color: #856404;
        }
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <!-- Header -->
        <div class="email-header">
            <h1>Đơn giải trình đã bị từ chối</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Xin chào <strong>{{ $name }}</strong>,</p>
            <p>Đơn giải trình của bạn đã bị từ chối với các thông tin sau:</p>
            <ul>
                <li><strong>Ngày giờ:</strong> {{ $attendance->time }}</li>
                <li><strong>Loại:</strong> {{ $attendance->type == 'in' ? 'Check In' : 'Check Out' }}</li>
                <li><strong>Lý do:</strong> {{ $reason }}</li>
            </ul>
            <p class="note">Lưu ý: Lương của bạn sẽ bị trừ 50% cho ngày này.</p>
            <p>Chúng tôi đánh giá cao sự hợp tác của bạn. Nếu có thắc mắc, vui lòng liên hệ với phòng nhân sự.</p>
            <p>Trân trọng,</p>
            <p><strong>Công ty AAA</strong></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>© {{ date('Y') }} Công ty AAA. Mọi quyền được bảo lưu.</p>
            <p><a href="#">Chính sách bảo mật</a> | <a href="#">Điều khoản sử dụng</a></p>
        </div>
    </div>
</body>
</html>
