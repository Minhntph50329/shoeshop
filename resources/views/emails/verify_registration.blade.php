<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đăng ký tài khoản</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px;">
        <h2 style="color: #0056b3; text-align: center;">Chào mừng bạn đến với Veloce Shoe Shop!</h2>
        <p>Xin chào <strong>{{ $user->fullname }}</strong>,</p>
        <p>Cảm ơn bạn đã đăng ký tài khoản tại hệ thống của chúng tôi. Để hoàn tất quá trình đăng ký và kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verifyUrl }}" style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Xác nhận tài khoản</a>
        </div>

        <p>Nếu nút bấm không hoạt động, bạn có thể copy và dán đường link sau vào trình duyệt:</p>
        <p style="word-break: break-all; background-color: #e9ecef; padding: 10px; border-radius: 4px;">
            <a href="{{ $verifyUrl }}">{{ $verifyUrl }}</a>
        </p>
        
        <p>Lưu ý: Nếu bạn không thực hiện đăng ký tài khoản này, vui lòng bỏ qua email này.</p>
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        <p style="font-size: 0.9em; color: #6c757d; text-align: center;">Trân trọng,<br>Đội ngũ Veloce Shoe Shop</p>
    </div>
</body>
</html>
