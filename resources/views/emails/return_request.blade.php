<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận yêu cầu trả hàng – #{{ $refund->order->code }}</title>
    <style>
        body {
            margin: 0; padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #333333;
            background-color: #f4f4f4;
        }
        .wrap {
            max-width: 600px;
            margin: 24px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            overflow: hidden;
        }
        /* Header */
        .header {
            background-color: #1a1a2e;
            padding: 24px 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
        }
        .header p {
            margin: 6px 0 0;
            color: #aaaacc;
            font-size: 13px;
        }
        /* Body */
        .body { padding: 28px 32px; }
        .greeting { font-size: 15px; font-weight: bold; margin-bottom: 6px; }
        .intro { color: #555; line-height: 1.6; margin-bottom: 20px; }
        /* Section title */
        .section-label {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #888;
            margin: 0 0 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eeeeee;
        }
        /* Apology */
        .apology {
            background: #fff8f0;
            border-left: 3px solid #e67e22;
            border-radius: 0 4px 4px 0;
            padding: 12px 16px;
            font-size: 13px;
            color: #555;
            margin-bottom: 24px;
            line-height: 1.6;
        }
        /* Info table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 13px;
        }
        .info-table td {
            padding: 7px 0;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table .lbl { color: #888; width: 45%; }
        .info-table .val { font-weight: bold; color: #222; }
        /* Product row */
        .product-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #eeeeee;
            border-radius: 4px;
            overflow: hidden;
        }
        .product-img-cell {
            display: table-cell;
            width: 72px;
            min-width: 72px;
            vertical-align: middle;
            padding: 8px;
            background: #f9f9f9;
        }
        .product-img-cell img {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 4px;
            display: block;
        }
        .product-img-placeholder {
            width: 56px;
            height: 56px;
            background: #e8e8e8;
            border-radius: 4px;
            text-align: center;
            line-height: 56px;
            font-size: 22px;
        }
        .product-info-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 10px 12px;
        }
        .product-name-text { font-weight: bold; color: #222; font-size: 13px; margin-bottom: 3px; }
        .product-variant-text { color: #666; font-size: 12px; margin-bottom: 3px; }
        .product-price-text { color: #555; font-size: 12px; }
        .product-price-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 10px 12px;
            text-align: right;
            white-space: nowrap;
            font-weight: bold;
            color: #222;
            font-size: 13px;
            min-width: 100px;
        }
        /* Total */
        .total-box {
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
            color: #222;
        }
        /* Steps */
        .step-table { width: 100%; border-collapse: collapse; font-size: 13px; color: #555; margin-bottom: 24px; }
        .step-table td { padding: 6px 0; vertical-align: top; }
        .step-num-cell { width: 30px; }
        .step-num {
            display: inline-block;
            width: 22px;
            height: 22px;
            line-height: 22px;
            text-align: center;
            background: #1a1a2e;
            color: #fff;
            border-radius: 50%;
            font-size: 11px;
            font-weight: bold;
        }
        .step-content strong { display: block; color: #333; margin-bottom: 2px; }
        /* Footer */
        .footer {
            background: #f4f4f4;
            border-top: 1px solid #e0e0e0;
            padding: 18px 32px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="wrap">
    {{-- Header --}}
    <div class="header">
        <h1>Tiếp nhận yêu cầu trả hàng</h1>
        <p>Đơn hàng #{{ $refund->order->code }}</p>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Xin chào {{ $refund->user->fullname ?? 'Khách hàng' }},</p>

        <div class="apology">
            Chúng tôi thành thật xin lỗi vì sản phẩm chưa đáp ứng được mong đợi của bạn. Yêu cầu trả hàng của bạn đã được tiếp nhận và đang được xử lý. Chúng tôi cam kết giải quyết nhanh chóng và đảm bảo quyền lợi cho bạn.
        </div>

        {{-- Refund request info --}}
        <p class="section-label">Thông tin yêu cầu</p>
        <table class="info-table">
            <tr>
                <td class="lbl">Mã đơn hàng</td>
                <td class="val">#{{ $refund->order->code }}</td>
            </tr>
            <tr>
                <td class="lbl">Ngày yêu cầu</td>
                <td class="val">{{ $refund->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="lbl">Ngân hàng hoàn tiền</td>
                <td class="val">{{ $refund->bank_name }}</td>
            </tr>
            <tr>
                <td class="lbl">Chủ tài khoản</td>
                <td class="val">{{ $refund->user_bank_name }}</td>
            </tr>
            <tr>
                <td class="lbl">Số tài khoản</td>
                <td class="val">{{ $refund->bank_account }}</td>
            </tr>
            <tr>
                <td class="lbl">Lý do trả hàng</td>
                <td class="val">{{ $refund->reason }}</td>
            </tr>
        </table>

        {{-- Products --}}
        <p class="section-label">Sản phẩm yêu cầu trả</p>

        @foreach ($refund->items as $item)
            @php
                $imgUrl = null;
                if ($item->productVariant && $item->productVariant->images->isNotEmpty()) {
                    $imgUrl = asset('storage/' . $item->productVariant->images->first()->url);
                } elseif ($item->product && $item->product->images->isNotEmpty()) {
                    $imgUrl = asset('storage/' . $item->product->images->first()->url);
                } elseif ($item->product && $item->product->image) {
                    $imgUrl = asset('storage/' . $item->product->image);
                }
            @endphp
            <div class="product-row">
                <div class="product-img-cell">
                    @if ($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $item->name }}">
                    @else
                        <div class="product-img-placeholder">👟</div>
                    @endif
                </div>
                <div class="product-info-cell">
                    <div class="product-name-text">{{ $item->name }}</div>
                    @if ($item->name_variant)
                        <div class="product-variant-text">Phân loại: {{ $item->name_variant }}</div>
                    @endif
                    <div class="product-price-text">{{ number_format($item->effective_price, 0, ',', '.') }}đ × {{ $item->quantity }}</div>
                </div>
                <div class="product-price-cell">
                    {{ number_format($item->line_total, 0, ',', '.') }}đ
                </div>
            </div>
        @endforeach

        {{-- Total --}}
        <div class="total-box">
            <span>Tổng tiền yêu cầu hoàn</span>
            <span>{{ number_format($refund->total_amount, 0, ',', '.') }}đ</span>
        </div>

        {{-- Next steps --}}
        <p class="section-label">Các bước tiếp theo</p>
        <table class="step-table">
            <tr>
                <td class="step-num-cell"><div class="step-num">1</div></td>
                <td class="step-content">
                    <strong>Xem xét yêu cầu</strong>
                    Đội ngũ của chúng tôi đang xem xét yêu cầu của bạn (1–2 ngày làm việc).
                </td>
            </tr>
            <tr>
                <td class="step-num-cell"><div class="step-num">2</div></td>
                <td class="step-content">
                    <strong>Gửi lại sản phẩm</strong>
                    Sau khi được phê duyệt, bạn sẽ nhận thông báo hướng dẫn gửi sản phẩm về cho chúng tôi.
                </td>
            </tr>
            <tr>
                <td class="step-num-cell"><div class="step-num">3</div></td>
                <td class="step-content">
                    <strong>Hoàn tiền</strong>
                    Sau khi nhận được sản phẩm, chúng tôi sẽ hoàn tiền vào tài khoản của bạn trong 3–5 ngày làm việc.
                </td>
            </tr>
        </table>

        <p style="color:#555; font-size:13px; margin:0;">
            Nếu bạn cần hỗ trợ thêm, vui lòng liên hệ qua hotline hoặc phản hồi email này.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p style="margin:0 0 4px;">Đây là email tự động, vui lòng không trả lời trực tiếp email này.</p>
        <p style="margin:0;">© {{ date('Y') }} Veloce Shoe Shop. All rights reserved.</p>
    </div>
</div>
</body>
</html>
