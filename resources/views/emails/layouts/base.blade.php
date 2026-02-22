<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Sarabun', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #334155; line-height: 1.6; }
        .email-wrapper { max-width: 650px; margin: 0 auto; background: #ffffff; }
        .email-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); padding: 40px 30px; text-align: center; position: relative; }
        .email-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.05"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="10" cy="50" r="0.5" fill="%23ffffff" opacity="0.03"/><circle cx="90" cy="30" r="0.5" fill="%23ffffff" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); opacity: 0.1; }
        .email-header h1 { color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; position: relative; }
        .email-header p { color: #cbd5e1; font-size: 14px; margin-top: 4px; position: relative; }
        .email-body { padding: 40px 35px; }
        .email-footer { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 30px 35px; text-align: center; border-top: 1px solid #e2e8f0; }
        .email-footer p { color: #64748b; font-size: 13px; line-height: 1.8; margin-bottom: 8px; }
        .email-footer a { color: #3b82f6; text-decoration: none; font-weight: 500; }
        .email-footer a:hover { text-decoration: underline; }
        .greeting { font-size: 20px; font-weight: 600; color: #0f172a; margin-bottom: 20px; }
        .text { font-size: 15px; color: #475569; margin-bottom: 20px; line-height: 1.7; }
        .btn { display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #ffffff !important; text-decoration: none; border-radius: 8px; font-size: 15px; font-weight: 600; margin: 20px 0; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .btn:hover { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4); }
        .info-box { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 24px 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 500; }
        .info-value { color: #0f172a; font-weight: 600; text-align: right; }
        .highlight-box { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bae6fd; border-radius: 12px; padding: 20px 24px; margin: 20px 0; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1); }
        .highlight-box.warning { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-color: #fde68a; }
        .highlight-box.danger { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-color: #fecaca; }
        .highlight-box.info { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-color: #bae6fd; }
        .highlight-box.success { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-color: #bbf7d0; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); }
        table.items th { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 14px 16px; font-size: 13px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; font-weight: 600; border-bottom: 2px solid #e2e8f0; }
        table.items td { padding: 16px; font-size: 14px; color: #475569; border-bottom: 1px solid #f1f5f9; }
        table.items tr:last-child td { border-bottom: none; }
        table.items tr:hover { background: #f8fafc; }
        .total-row { font-weight: 700; color: #0f172a; font-size: 16px; }
        .badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; letter-spacing: 0.5px; }
        .badge-success { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); color: #92400e; border: 1px solid #fde68a; }
        .badge-danger { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); color: #991b1b; border: 1px solid #fecaca; }
        .badge-info { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); color: #1e40af; border: 1px solid #bae6fd; }
        .divider { border: none; border-top: 2px solid #f1f5f9; margin: 32px 0; }
        .status-indicator { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; }
        .status-indicator.success { background: #f0fdf4; color: #166534; }
        .status-indicator.warning { background: #fffbeb; color: #92400e; }
        .status-indicator.info { background: #f0f9ff; color: #1e40af; }
    </style>
</head>
<body>
    <div style="padding: 24px 0; background-color: #f8fafc;">
        <div class="email-wrapper" style="border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
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
