<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website đang bảo trì</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 24px;
        }

        .card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px;
            padding: 56px 48px;
            max-width: 520px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(20px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.4);
        }

        .icon-wrap {
            width: 88px;
            height: 88px;
            border-radius: 24px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            box-shadow: 0 12px 32px rgba(99,102,241,0.35);
        }

        .icon-wrap svg {
            width: 42px;
            height: 42px;
            stroke: white;
        }

        h1 {
            font-size: 28px;
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .message {
            font-size: 15px;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99,102,241,0.12);
            border: 1px solid rgba(99,102,241,0.25);
            color: #a5b4fc;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 50px;
        }

        .dot {
            width: 8px;
            height: 8px;
            background: #818cf8;
            border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #475569;
        }
    </style>
</head>
<body>

    <div class="card">

        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
        </div>

        <h1>Website đang bảo trì</h1>

        <p class="message">{{ $message }}</p>

        <div class="badge">
            <span class="dot"></span>
            Đang tiến hành bảo trì hệ thống
        </div>

        <p class="footer">© {{ date('Y') }} Veloce. Chúng tôi sẽ sớm trở lại.</p>

    </div>

</body>
</html>
