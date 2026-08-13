<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hoàn tiền thành công</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1e293b;
        }
        .summary-box {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .summary-title {
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 12px;
            letter-spacing: 0.05em;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            font-weight: 700;
            color: #4f46e5;
        }
        .table-title {
            font-weight: 700;
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th {
            background-color: #f8fafc;
            text-align: left;
            padding: 10px 12px;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 12px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hoàn Tiền Thành Công</h1>
        </div>
        <div class="content">
            <p class="greeting">Xin chào {{ $refund->user->fullname ?? 'Khách hàng' }},</p>
            <p>Yêu cầu trả hàng / hoàn tiền cho đơn hàng <strong>#{{ $refund->order->code }}</strong> của bạn đã được xử lý thành công.</p>
            <p>Chúng tôi đã thực hiện hoàn tiền đến tài khoản ngân hàng của bạn. Dưới đây là thông tin chi tiết:</p>

            <div class="summary-box">
                <div class="summary-title">Thông tin giao dịch hoàn tiền</div>
                <div class="summary-row">
                    <span>Ngân hàng:</span>
                    <strong>{{ $refund->bank_name }}</strong>
                </div>
                <div class="summary-row">
                    <span>Chủ tài khoản:</span>
                    <strong>{{ $refund->user_bank_name }}</strong>
                </div>
                <div class="summary-row">
                    <span>Số tài khoản:</span>
                    <strong>{{ $refund->bank_account }}</strong>
                </div>
                <div class="summary-row">
                    <span>Số tiền hoàn:</span>
                    <strong>{{ number_format($refund->total_amount, 0, ',', '.') }}đ</strong>
                </div>
            </div>

            <div class="table-title">Sản phẩm được hoàn trả</div>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refund->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->name }}</strong>
                            @if($item->name_variant)
                                <br><small style="color: #64748b;">{{ $item->name_variant }}</small>
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->effective_price, 0, ',', '.') }}đ</td>
                        <td>{{ number_format($item->line_total, 0, ',', '.') }}đ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($refund->aadmin_reason)
                <p><strong>Ghi chú từ cửa hàng:</strong> {{ $refund->aadmin_reason }}</p>
            @endif

            <p style="margin-top: 32px;">Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua số hotline hoặc phản hồi email này.</p>
            <p>Cảm ơn bạn đã đồng hành cùng Veloce!</p>
        </div>
        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>
            <p>© {{ date('Y') }} Veloce Shoe Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
