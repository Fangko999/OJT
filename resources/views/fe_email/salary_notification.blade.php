<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo lương</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .email-header {
            background-color: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .email-header img {
            max-width: 80px;
            margin-bottom: 10px;
        }

        .email-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .email-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .email-table th,
        .email-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .email-table th {
            background-color: #4CAF50;
            color: #fff;
            text-transform: uppercase;
        }

        .email-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .email-footer {
            text-align: center;
            padding: 15px;
            background-color: #f7f7f7;
            font-size: 14px;
            color: #777;
        }

        .email-footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h2>Thông báo lương ngày {{ $day }}</h2>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Xin chào <strong>{{ $user }}</strong>,</p>
            <p>Bạn đã nhận được lương tháng này, sau đây là thông tin chi tiết:</p>

            <table class="email-table">
                <thead>
                    <tr>
                        <th>Nhân viên</th>
                        <th>Tên bậc lương</th>
                        <th>Số ngày công hợp lệ</th>
                        <th>Số ngày công không hợp lệ</th>
                        <th>Lương nhận được</th>
                        <th>Ngày tính lương</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user }}</td>
                        <td>{{ $name_salary }}</td>
                        <td>{{ $valid_days }}</td>
                        <td>{{ $invalid_days }}</td>
                        <td>{{ number_format($salary_received, 0, ',', '.') }} VND</td>
                        <td>{{ $day }}</td>
                    </tr>
                </tbody>
            </table>

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
