<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khôi phục mật khẩu - Veloce</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 20px;
        }
        .email-content {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }
        .email-header {
            background-color: #4f46e5;
            padding: 40px 40px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.025em;
        }
        .email-body {
            padding: 40px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 24px;
            margin-top: 0;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 24px;
        }
        .btn-container {
            text-align: center;
            margin: 40px 0;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.025em;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
            transition: all 0.2s;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }
        .footer-text {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 16px 0;
            line-height: 1.5;
        }
        .social-links {
            margin-bottom: 16px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #94a3b8;
            text-decoration: none;
        }
        .copyright {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-content">
            <div class="email-header">
                <h1>Veloce Store</h1>
            </div>
            
            <div class="email-body">
                <h2 class="greeting">Xin chào,</h2>
                <p class="text">
                    Chúng tôi nhận được yêu cầu khôi phục mật khẩu cho tài khoản Veloce của bạn. 
                    Nhấn vào nút bên dưới để thiết lập mật khẩu mới:
                </p>
                
                <div class="btn-container">
                    <a href="{{ $resetUrl }}" class="btn">Đặt lại mật khẩu</a>
                </div>
                
                <p class="text" style="font-size: 14px;">
                    Nếu bạn không yêu cầu đổi mật khẩu, vui lòng bỏ qua email này. Tài khoản của bạn vẫn an toàn.
                </p>
                <p class="text" style="font-size: 14px; margin-bottom: 0;">
                    Liên kết khôi phục này sẽ hết hạn trong vòng 60 phút.
                </p>
            </div>
            
            <div class="email-footer">
                <p class="footer-text">
                    Email này được gửi tự động từ hệ thống của Veloce Store.<br>
                    Vui lòng không trả lời trực tiếp email này.
                </p>
                <div class="social-links">
                    <a href="#">Facebook</a>
                    <a href="#">Instagram</a>
                    <a href="#">Website</a>
                </div>
                <p class="copyright">
                    &copy; {{ date('Y') }} Veloce Store. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
