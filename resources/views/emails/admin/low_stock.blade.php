<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cảnh báo số lượng tồn kho thấp</title>
</head>
<body style="margin:0;padding:0;background-color:#f1f5f9;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f1f5f9;padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#f97316 0%,#ea580c 100%);padding:32px 40px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800;letter-spacing:-0.5px;">⚠️ Sản phẩm sắp hết hàng!</h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px 40px;">
                            <p style="margin:0 0 20px;color:#475569;font-size:14px;line-height:1.6;">
                                Chào Quản trị viên, sản phẩm dưới đây đang có số lượng tồn kho rất thấp (dưới mức quy định). Vui lòng kiểm tra và nhập thêm hàng.
                            </p>

                            {{-- Product Info --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#fff7ed;border-radius:12px;border:1px solid #ffedd5;margin-bottom:24px;border-left:4px solid #f97316;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#ea580c;">Thông tin sản phẩm</p>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:12px;">
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;">Tên sản phẩm</td>
                                                <td style="padding:6px 0;color:#1e293b;font-size:13px;font-weight:600;text-align:right;">{{ $product->name }}</td>
                                            </tr>
                                            @if($variant)
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;border-top:1px solid #ffedd5;">Phân loại</td>
                                                <td style="padding:6px 0;color:#1e293b;font-size:13px;font-weight:600;text-align:right;border-top:1px solid #ffedd5;">{{ collect($variant->attributes)->map(function($val, $key) { return $key . ': ' . $val; })->join(', ') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;border-top:1px solid #ffedd5;">Số lượng còn lại</td>
                                                <td style="padding:6px 0;color:#ea580c;font-size:14px;font-weight:800;text-align:right;border-top:1px solid #ffedd5;">{{ $variant->stock }}</td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td style="padding:6px 0;color:#64748b;font-size:13px;border-top:1px solid #ffedd5;">Số lượng còn lại</td>
                                                <td style="padding:6px 0;color:#ea580c;font-size:14px;font-weight:800;text-align:right;border-top:1px solid #ffedd5;">{{ $product->stock }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/admin/products/' . $product->id . '/edit') }}" style="display:inline-block;padding:14px 32px;background:linear-gradient(135deg,#f97316,#ea580c);color:#ffffff;font-size:14px;font-weight:700;text-decoration:none;border-radius:10px;box-shadow:0 4px 14px rgba(249,115,22,0.4);">
                                            Kiểm Tra Sản Phẩm →
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