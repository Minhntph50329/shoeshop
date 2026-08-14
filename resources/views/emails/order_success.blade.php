<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công – #{{ $order->code }}</title>
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
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0;
            color: #aaaacc;
            font-size: 13px;
        }
        /* Body */
        .body { padding: 28px 32px; }
        .greeting { font-size: 15px; font-weight: bold; margin-bottom: 6px; }
        .intro { color: #555; line-height: 1.6; margin-bottom: 24px; }
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
            margin-bottom: 12px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .product-info-cell {
            display: table-cell;
            vertical-align: middle;
            padding: 10px 12px;
        }
        .product-name-text {
            font-weight: bold;
            color: #222;
            font-size: 13px;
            margin-bottom: 3px;
        }
        .product-variant-text {
            color: #666;
            font-size: 12px;
            margin-bottom: 3px;
        }
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
        /* Total box */
        .total-box {
            background: #f9f9f9;
            border: 1px solid #e8e8e8;
            border-radius: 4px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 6px;
            color: #555;
        }
        .total-row:last-child {
            margin-bottom: 0;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 15px;
            font-weight: bold;
            color: #1a1a2e;
        }
        .discount-val { color: #27ae60; }
        /* Note */
        .note-box {
            background: #fffbe6;
            border-left: 3px solid #f5a623;
            padding: 10px 14px;
            font-size: 13px;
            color: #555;
            margin-bottom: 24px;
            border-radius: 0 4px 4px 0;
        }
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
        <h1>Đặt hàng thành công</h1>
        <p>Đơn hàng #{{ $order->code }} đã được xác nhận</p>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Xin chào {{ $order->fullname }},</p>
        <p class="intro">
            Cảm ơn bạn đã tin tưởng và lựa chọn sản phẩm của chúng tôi. Đơn hàng của bạn đã được đặt thành công và đang được xử lý. Chúng tôi sẽ liên hệ để xác nhận và giao hàng sớm nhất có thể.
        </p>

        {{-- Order info --}}
        <p class="section-label">Thông tin đơn hàng</p>
        <table class="info-table">
            <tr>
                <td class="lbl">Mã đơn hàng</td>
                <td class="val">#{{ $order->code }}</td>
            </tr>
            <tr>
                <td class="lbl">Ngày đặt</td>
                <td class="val">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="lbl">Phương thức thanh toán</td>
                <td class="val">{{ $order->payment->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="lbl">Hình thức giao hàng</td>
                <td class="val">{{ $order->shipping_type === 'express' ? 'Giao nhanh' : 'Giao tiêu chuẩn' }}</td>
            </tr>
            <tr>
                <td class="lbl">Địa chỉ nhận hàng</td>
                <td class="val">{{ $order->address }}</td>
            </tr>
            <tr>
                <td class="lbl">Số điện thoại</td>
                <td class="val">{{ $order->phone_number }}</td>
            </tr>
        </table>

        {{-- Products --}}
        <p class="section-label">Sản phẩm đã đặt</p>

        @foreach ($order->items as $item)
            @php
                $imgUrl = null;
                // Priority: variant image → product first image → product main image
                if ($item->productVariant && $item->productVariant->images->isNotEmpty()) {
                    $imgUrl = asset('storage/' . $item->productVariant->images->first()->url);
                } elseif ($item->product && $item->product->images->isNotEmpty()) {
                    $imgUrl = asset('storage/' . $item->product->images->first()->url);
                } elseif ($item->product && $item->product->image) {
                    $imgUrl = asset('storage/' . $item->product->image);
                }
                // Build variant label
                $variantLabel = $item->attributes_variant ?? $item->name_variant;
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
                    @if ($variantLabel)
                        <div class="product-variant-text">Phân loại: {{ $variantLabel }}</div>
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
            <div class="total-row">
                <span>Tạm tính</span>
                <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
            </div>
            @if ((float)$order->discount_amount > 0)
            <div class="total-row">
                <span>Giảm giá</span>
                <span class="discount-val">- {{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
            </div>
            @endif
            <div class="total-row">
                <span>Phí vận chuyển</span>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
            </div>
            <div class="total-row">
                <span>Tổng cộng</span>
                <span>{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
            </div>
        </div>

        @if ($order->note)
        <div class="note-box">
            <strong>Ghi chú:</strong> {{ $order->note }}
        </div>
        @endif

        <p style="color:#555; font-size:13px; margin:0;">
            Nếu bạn có câu hỏi nào về đơn hàng, vui lòng liên hệ với chúng tôi qua hotline hoặc phản hồi email này.
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
