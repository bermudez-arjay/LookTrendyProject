<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Compras - {{ now()->format('Y-m-d') }}</title>
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
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e1e1e1;
        }
        .logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin: 5px 0;
            text-transform: uppercase;
        }
        .report-subtitle {
            color: #7f8c8d;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .company-info {
            font-size: 10px;
            margin-bottom: 8px;
            color: #95a5a6;
        }
        .divider {
            border-top: 1px solid #e1e1e1;
            margin: 10px 0;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 4px 8px;
            margin: 15px 0 8px;
            font-weight: bold;
            font-size: 12px;
            border-left: 3px solid #3498db;
        }
        .summary-line {
            text-align: center;
            font-size: 11px;
            margin: 10px 0;
        }
        .summary-line strong {
            margin: 0 10px;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
            font-size: 11px;
            margin-bottom: 15px;
        }
        .filter-item {
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            display: inline-block;
            min-width: 80px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 10px 0;
        }
        .report-table th {
            padding: 8px 5px;
            background-color: #f8f9fa;
            text-align: center;
            border-bottom: 2px solid #3498db;
            font-weight: bold;
        }
        .report-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        .text-right {
            text-align: right;
            padding-right: 15px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            font-size: 9px;
            text-align: center;
            color: #95a5a6;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Encabezado con logo arriba del título -->
        <div class="header">
            <img src="./logotipo.png" alt="Logo" class="logo">
            <div class="report-title">REPORTE DE COMPRAS</div>
            <div class="report-subtitle">
                Generado el: {{ now()->format('d/m/Y H:i') }} | Usuario: 
                {{ auth()->user()->User_FirstName ?? 'Sistema' }} {{ auth()->user()->User_LastName ?? '' }}
            </div>
            <div class="company-info">
                {{ config('app.name') }} | {{ config('app.address') }} | {{ config('app.phone') }}
            </div>
        </div>

        <!-- Resumen ejecutivo -->
        <div class="summary-line">
            <strong>Total Compras:</strong> {{ $purchases->count() }} 
            <strong>Monto Total:</strong> C${{ number_format($totalAmount, 0) }} 
            <strong>Total IVA:</strong> C${{ number_format($totalVAT, 0) }} 
            <strong>Productos Comprados:</strong> {{ $totalQuantity }}
        </div>

        <div class="divider"></div>

        <!-- Filtros aplicados -->
        <div class="section-title">FILTROS APLICADOS</div>
        <div class="filters-grid">
            <div class="filter-item">
                <span class="filter-label">Fecha Inicio:</span> {{ $filters['start_date'] }}
            </div>
            <div class="filter-item">
                <span class="filter-label">Fecha Fin:</span> {{ $filters['end_date'] }}
            </div>
            <div class="filter-item">
                <span class="filter-label">Proveedor:</span> {{ $filters['supplier'] }}
            </div>
            <div class="filter-item">
                <span class="filter-label">Rango Montos:</span> {{ $filters['amount_range'] }}
            </div>
        </div>

        <div class="divider"></div>

        <!-- Detalle de compras -->
        <div class="section-title">DETALLE DE COMPRAS</div>
        <table class="report-table">
            <thead>
            <tr>
                <th style="width:13%"># Compra</th>
                <th style="width:13%">Fecha</th>
                <th style="width:25%">Proveedor</th>
                <th style="width:12%">Productos</th>
                <th style="width:12%">IVA Aplicado</th>
                <th style="width:12%">Subtotal</th>
                <th style="width:13%">Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($purchases as $purchase)
            <tr>
                <td>{{ str_pad($purchase->Purchase_ID, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                {{ $purchase->time?->Date ? \Carbon\Carbon::parse($purchase->time->Date)->format('d/m/Y') : 'N/A' }}
                </td>
                <td>{{ $purchase->supplier ? $purchase->supplier->Supplier_Name : 'N/A' }}</td>
                <td>{{ $purchase->purchaseDetails->sum('Quantity') }}</td>
                <td class="text-right">
                C$ {{ number_format($purchase->Total_Amount - ($purchase->Total_Amount / 1.15), 2) }}
                </td>
                <td class="text-right">
                C$ {{ number_format($purchase->Total_Amount / 1.15, 2) }}
                </td>
                <td class="text-right">
                C$ {{ number_format($purchase->Total_Amount, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">
                No se encontraron compras con los filtros aplicados
                </td>
            </tr>
            @endforelse
            </tbody>
            <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Totales:</td>
                <td>{{ $totalQuantity }}</td>
                <td class="text-right">C$ {{ number_format($totalVAT, 2) }}</td>
                <td class="text-right">C$ {{ number_format($totalAmount - $totalVAT, 2) }}</td>
                <td class="text-right">C$ {{ number_format($totalAmount, 2) }}</td>
            </tr>
            </tfoot>
        </table>

        <div class="footer">
            Documento generado por {{ config('app.name') }} | {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>