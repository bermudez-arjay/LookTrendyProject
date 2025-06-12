<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Abonos - {{ now()->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 10px;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
        }
        .report-container {
            width: 100%;
            margin: 0 auto;
            padding: 15px;
        }
        .header {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e1e1e1;
        }
        .logo-container {
            width: 120px;
            margin-right: 15px;
        }
        .logo {
            max-width: 100%;
            max-height: 60px;
        }
        .header-content {
            flex: 1;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .report-subtitle {
            color: #7f8c8d;
            font-size: 11px;
        }
        .company-info {
            font-size: 10px;
            margin-bottom: 8px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 4px 8px;
            margin: 10px 0 5px;
            font-weight: bold;
            font-size: 11px;
            border-left: 3px solid #3498db;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            font-size: 10px;
            margin-bottom: 8px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 8px 0;
        }
        .report-table th {
            padding: 5px;
            background-color: #f8f9fa;
            text-align: left;
            border-bottom: 1px solid #3498db;
        }
        .report-table td {
            padding: 4px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .text-green {
            color: #27ae60;
        }
        .footer {
            font-size: 9px;
            text-align: center;
            color: #95a5a6;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ public_path('logotipo.png') }}" alt="Logo" class="logo">
            </div>
            <div class="header-content">
                <div class="report-title">REPORTE DE ABONOS</div>
                <div class="report-subtitle">
                    Generado el: {{ now()->format('d/m/Y H:i') }} | Usuario: {{ auth()->user()->name ?? 'Sistema' }}
                </div>
                <div class="company-info">
                    LookTrendy | Matagalpa, Nicaragua | +505 8702 5001
                </div>
            </div>
        </div>

        <div class="section-title">Filtros Aplicados</div>
        <div class="filters-grid">
            <div><strong>Fecha Inicial:</strong> {{ $filters['start_date'] ?? 'N/A' }}</div>
            <div><strong>Fecha Final:</strong> {{ $filters['end_date'] ?? 'N/A' }}</div>
            <div><strong>Crédito:</strong> {{ $filters['credit'] ?? 'Todos' }}</div>
            <div><strong>Tipo de pago:</strong> {{ $filters['payment_type'] ?? 'Todos' }}</div>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:10%"># Abono</th>
                    <th style="width:10%"># Crédito</th>
                    <th style="width:30%">Cliente</th>
                    <th style="width:15%">Fecha</th>
                    <th style="width:15%" class="text-right">Monto</th>
                    <th style="width:20%">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->Payment_ID }}</td>
                    <td>{{ $payment->Credit_ID }}</td>
                    <td>{{ $payment->credit->client->Client_FirstName ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->Payment_Date)->format('d/m/Y') }}</td>
                    <td class="text-right text-green">C$ {{ number_format($payment->Payment_Amount, 2) }}</td>
                    <td>{{ $payment->paymentType->Payment_Type_Name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-title">Resumen</div>
        <div style="font-size: 10px; padding: 5px; background-color: #f8f9fa;">
            <strong>Total abonado:</strong> C$ {{ number_format($total, 2) }}
        </div>

        <div class="footer">
            Documento generado por LookTrendy | {{ now()->format('d/m/Y H:i') }} | Página 1 de 1
        </div>
    </div>
</body>
</html>
