<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('fonts/THSarabunNew.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('fonts/THSarabunNew Bold.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url('{{ public_path('fonts/THSarabunNew Italic.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url('{{ public_path('fonts/THSarabunNew BoldItalic.ttf') }}') format('truetype');
        }
        body { font-family: 'THSarabunNew', sans-serif; font-size: 16px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0d9488; padding-bottom: 15px; }
        .header h1 { font-size: 20px; margin: 0 0 5px; color: #0d9488; }
        .header p { margin: 0; font-size: 12px; color: #666; }
        .summary-grid { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-grid td { text-align: center; padding: 10px; border: 1px solid #e5e7eb; background: #f9fafb; width: 25%; }
        .summary-grid .value { font-size: 18px; font-weight: bold; color: #0d9488; }
        .summary-grid .label { font-size: 11px; color: #666; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
        th { background: #0d9488; color: white; padding: 8px 5px; text-align: left; font-size: 12px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 20px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-info { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PGMF Shop — @yield('title')</h1>
        <p>@yield('subtitle')</p>
    </div>

    @yield('content')

    <div class="footer">
        สร้างโดย PGMF Shop Admin — {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
