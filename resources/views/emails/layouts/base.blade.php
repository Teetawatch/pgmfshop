<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f7; color: #333; line-height: 1.6; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background: #fff; }
        .email-header { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); padding: 30px; text-align: center; }
        .email-header h1 { color: #fff; font-size: 22px; font-weight: 700; letter-spacing: 0.5px; }
        .email-header p { color: #a0aec0; font-size: 13px; margin-top: 4px; }
        .email-body { padding: 32px 30px; }
        .email-footer { background: #f8f9fa; padding: 24px 30px; text-align: center; border-top: 1px solid #e9ecef; }
        .email-footer p { color: #868e96; font-size: 12px; line-height: 1.8; }
        .email-footer a { color: #868e96; text-decoration: underline; }
        .greeting { font-size: 18px; font-weight: 600; color: #1a1a2e; margin-bottom: 16px; }
        .text { font-size: 14px; color: #4a5568; margin-bottom: 16px; }
        .btn { display: inline-block; padding: 12px 32px; background: #1a1a2e; color: #fff !important; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; margin: 16px 0; }
        .btn:hover { background: #2d3748; }
        .info-box { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #718096; font-weight: 500; }
        .info-value { color: #2d3748; font-weight: 600; text-align: right; }
        .highlight-box { background: #f0fff4; border: 1px solid #c6f6d5; border-radius: 8px; padding: 16px 20px; margin: 16px 0; }
        .highlight-box.warning { background: #fffbeb; border-color: #fde68a; }
        .highlight-box.danger { background: #fef2f2; border-color: #fecaca; }
        .highlight-box.info { background: #eff6ff; border-color: #bfdbfe; }
        table.items { width: 100%; border-collapse: collapse; margin: 16px 0; }
        table.items th { background: #f7fafc; padding: 10px 12px; font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 2px solid #e2e8f0; }
        table.items td { padding: 12px; font-size: 14px; color: #4a5568; border-bottom: 1px solid #edf2f7; }
        table.items tr:last-child td { border-bottom: none; }
        .total-row { font-weight: 700; color: #1a1a2e; font-size: 16px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #f0fff4; color: #22543d; }
        .badge-warning { background: #fffbeb; color: #92400e; }
        .badge-danger { background: #fef2f2; color: #991b1b; }
        .badge-info { background: #eff6ff; color: #1e40af; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
    </style>
</head>
<body>
    <div style="padding: 20px 0; background-color: #f4f4f7;">
        <div class="email-wrapper" style="border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
            <div class="email-header">
                <h1>{{ config('app.name', 'PGMF Shop') }}</h1>
                <p>@yield('header-subtitle', 'ร้านค้าออนไลน์ครบวงจร')</p>
            </div>
            <div class="email-body">
                @yield('content')
            </div>
            <div class="email-footer">
                <p>
                    &copy; {{ date('Y') }} {{ config('app.name', 'PGMF Shop') }}. สงวนลิขสิทธิ์.<br>
                    หากมีข้อสงสัย กรุณาติดต่อ <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
