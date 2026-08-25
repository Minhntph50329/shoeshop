<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khách hàng mới đăng ký</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f1f5f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);padding:32px 40px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.5px;">👤 Khách hàng mới!</h1>
                            <p style="margin:8px 0 0;color:rgba(255,255,255,0.85);font-size:13px;">Chào mừng: <strong>{{ $customer->fullname ?? 'Chưa cập nhật' }}</strong></p>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px 40px;">
                            <p style="margin:0 0 20px;color:#475569;font-size:14px;line-height:1.6;">
                                Chào Quản trị viên, một khách hàng mới vừa đăng ký tài khoản trên website.
                            </p>

                            {{-- Customer Info --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94a3b8;">Thông tin khách hàng</p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;">
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;">Họ tên</td>
                                                <td style="padding:6px 0;color:#1e293b;font-size:13px;font-weight:600;text-align:right;">{{ $customer->fullname ?? 'Chưa cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;border-top:1px solid #e2e8f0;">Email</td>
                                                <td style="padding:6px 0;color:#1e293b;font-size:13px;font-weight:600;text-align:right;border-top:1px solid #e2e8f0;">{{ $customer->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;border-top:1px solid #e2e8f0;">Ngày đăng ký</td>
                                                <td style="padding:6px 0;color:#1e293b;font-size:13px;font-weight:600;text-align:right;border-top:1px solid #e2e8f0;">{{ $customer->created_at->format('H:i d/m/Y') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/admin/customers') }}" style="display:inline-block;padding:14px 32px;background:linear-gradient(135deg,#10b981,#059669);color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:10px;box-shadow:0 4px 14px rgba(16,185,129,0.4);">
                                            Quản Lý Khách Hàng →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 40px 28px;border-top:1px solid #f1f5f9;text-align:center;">
                            <p style="margin:0;color:#94a3b8;font-size:11px;">Email này được gửi tự động từ hệ thống <strong>Veloce</strong>.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
