<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Créditos - {{ now()->format('Y-m-d') }}</title>
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
        .text-red {
            color: #e74c3c;
        }
        .text-green {
            color: #27ae60;
        }
       .status-badge {
    padding: 0;
    font-size: 10px;
    font-weight: bold;
    display: inline-block;
    border-radius: 0;
    background-color: transparent !important;
    color: inherit !important;
}
        .summary-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            font-size: 10px;
            margin: 8px 0;
        }
        .summary-item {
            text-align: center;
            padding: 4px;
            background-color: #f8f9fa;
            border-radius: 3px;
        }
        .summary-value {
            font-weight: bold;
        }
        .footer {
            font-size: 9px;
            text-align: center;
            color: #95a5a6;
            margin-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Encabezado compacto -->
        <div class="header">
            <div class="logo-container">
                <img src="./logotipo.png" alt="Logo" class="logo">
            </div>
            <div class="header-content">
                <div class="report-title">REPORTE DE CRÉDITOS</div>
                <div class="report-subtitle">Generado el: {{ now()->format('d/m/Y H:i') }} | Usuario: {{ auth()->user()->name ?? 'Sistema' }}</div>
                <div class="company-info">
                    LookTrendy | Matagalpa, Nicaragua | +505 8702 5001
                </div>
            </div>
        </div>
        <div style="text-align: right; font-size: 11px; margin-bottom: 10px;">
    <strong>Total Créditos:</strong> {{ $credits->count() }} &nbsp;&nbsp;|&nbsp;&nbsp;
    <strong>Monto Total:</strong> C${{ number_format($credits->sum('Total_Amount'), 0) }} &nbsp;&nbsp;|&nbsp;&nbsp;
    <strong>Saldo Pendiente:</strong> <span style="color: #e74c3c;">C${{ number_format($credits->sum('remaining_balance'), 0) }}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
    <strong>Pagado:</strong> <span style="color: #27ae60;">C${{ number_format($credits->sum('Total_Amount') - $credits->sum('remaining_balance'), 0) }}</span>
</div>
        <!-- Tabla compacta -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:8%">#</th>
                    <th style="width:22%">Cliente</th>
                    <th style="width:10%">Inicio</th>
                    <th style="width:10%">Vence</th>
                    <th style="width:15%" class="text-right">Total</th>
                    <th style="width:15%" class="text-right">Saldo</th>
                    <th style="width:10%">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($credits as $credit)
                <tr>
                    <td>{{ str_pad($credit->Credit_ID, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ Str::limit($credit->client->Client_FirstName.' '.$credit->client->Client_LastName, 20) }}</td>
                    <td>{{ $credit->Start_Date->format('d/m/y') }}</td>
                    <td @if($credit->is_expired) class="text-red" @endif>
                        {{ $credit->Due_Date->format('d/m/y') }}
                    </td>
                    <td class="text-right">C${{ number_format($credit->Total_Amount, 0) }}</td>
                    <td class="text-right @if($credit->remaining_balance > 0) text-red @else text-green @endif">
                        C${{ number_format($credit->remaining_balance, 0) }}
                    </td>
                    <td>
                        <span class="status-badge 
                            @if($credit->computed_status == 'Cancelado') background-color:#e6f7ee;color:#27ae60; 
                            @elseif($credit->computed_status == 'Vencido') background-color:#feeceb;color:#e74c3c; 
                            @else background-color:#fef5e6;color:#f39c12; @endif">
                            {{ Str::limit($credit->computed_status, 3, '') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Documento generado por LookTrendy | {{ now()->format('d/m/Y H:i') }} | Página 1 de 1
        </div>
    </div>
</body>
</html>