<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cảnh báo đăng nhập</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h2 style="color: #dc3545; text-align: center;">Thông báo đăng nhập mới!</h2>
        <p>Xin chào <strong>{{ $user->fullname }}</strong>,</p>
        <p>Hệ thống của chúng tôi vừa ghi nhận một lượt đăng nhập mới vào tài khoản của bạn. Dưới đây là chi tiết lượt đăng nhập:</p>
        
        <ul style="background-color: #e9ecef; padding: 15px 35px; border-radius: 4px;">
            <li><strong>Thời gian:</strong> {{ $time }}</li>
            <li><strong>IP Address:</strong> {{ $ip }}</li>
            <li><strong>Thiết bị/Trình duyệt:</strong> {{ $userAgent }}</li>
        </ul>

        <p>Nếu đây là bạn, bạn có thể bỏ qua email này.</p>
        <p style="color: #dc3545; font-weight: bold;">Nếu bạn không thực hiện đăng nhập này, vui lòng thay đổi mật khẩu ngay lập tức để bảo vệ tài khoản của bạn.</p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        <p style="font-size: 0.9em; color: #6c757d; text-align: center;">Trân trọng,<br>Đội ngũ Veloce Shoe Shop</p>
    </div>
</body>
</html>
