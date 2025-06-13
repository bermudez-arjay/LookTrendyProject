<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura de Compra #{{ $invoice_number }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'Segoe UI', 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f9f9f9;
        }

        .invoice-container {
            max-width: 700px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            border-radius: 6px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
    .logo-container {
            width: 90px;
            margin-right: 15px;
        }

        .logo {
            max-width: 100%;
            max-height: 60px;
            object-fit: contain;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .header-info {
            font-size: 12px;
            text-align: right;
            color: #7f8c8d;
        }

        .section {
            margin-bottom: 15px;
        }

        .section-title {
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 5px;
            color: #2c3e50;
            text-transform: uppercase;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 3px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 5px;
            font-size: 11px;
        }

        .info-item {
            padding: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th {
            background-color: #2c3e50;
            color: #fff;
            text-align: left;
            padding: 6px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-top: 20px;
            font-size: 11px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
        }

        .totals-row.total {
            font-weight: bold;
            border-top: 1px solid #ddd;
            padding-top: 6px;
            margin-top: 6px;
            color: #e74c3c;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Encabezado -->
        <div class="header">
            <div class="header-left">
                <div class="logo-container">
                    <img src="{{ public_path('logotipo.png') }}" alt="Logo LookTrendy">
                </div>
                <div class="header-title">Factura de Compra</div>
            </div>
            <div class="header-info">
                <div>N° {{ $invoice_number }}</div>
                <div>{{ $date }} | {{ now()->format('h:i A') }}</div>
            </div>
        </div>

        <!-- Datos del proveedor -->
        <div class="section">
            <div class="section-title">Datos del proveedor</div>
            <div class="info-grid">
                <div class="info-item"><strong>Nombre:</strong> {{ $purchase->supplier->Supplier_Name }}</div>
                <div class="info-item"><strong>Teléfono:</strong> {{ $purchase->supplier->Supplier_Phone ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Información de compra -->
        <div class="section">
            <div class="section-title">Información de la compra</div>
            <div class="info-grid">
                <div class="info-item"><strong>Atendido por:</strong> {{ $user->name }}</div>
                <div class="info-item"><strong>Método de pago:</strong> {{ $payment_type }}</div>
            </div>
        </div>

        <!-- Detalle de productos -->
        <table>
            <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">IVA (15%)</th>
                <th class="text-right">Total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($purchase->purchaseDetails as $detail)
            @php
                $itemSubtotal = $detail->Unit_Price * $detail->Quantity;
                $itemIVA = $itemSubtotal * 0.15;
                $itemTotal = $itemSubtotal + $itemIVA;
            @endphp
            <tr>
                <td>{{ $detail->product->Product_Name }}</td>
                <td class="text-center">{{ $detail->Quantity }}</td>
                <td class="text-right">C$ {{ number_format($detail->Unit_Price, 2) }}</td>
                <td class="text-right">C$ {{ number_format($itemSubtotal, 2) }}</td>
                <td class="text-right">C$ {{ number_format($itemIVA, 2) }}</td>
                <td class="text-right">C$ {{ number_format($itemTotal, 2) }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span class="subtotal-amount">C$ {{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="totals-row total">
                <span>Total:</span>
                <span class="total-amount">C$ {{ number_format($total, 2) }}</span>
            </div>
            <div class="totals-row">
                <span>Monto pagado:</span>
                <span>C$ {{ number_format($paid_amount, 2) }}</span>
            </div>
            @if($change_amount > 0)
            <div class="totals-row">
                <span>Cambio:</span>
                <span style="color: #2ecc71">C$ {{ number_format($change_amount, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            Gracias por confiar en nosotros • Documento generado el {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>

</html>
