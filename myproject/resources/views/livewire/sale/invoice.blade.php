<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Factura #{{ $invoice_number }}</title>
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
            margin: 10px auto;
            padding: 15px;
            background: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 5px;
        }

        .header {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            align-items: center;
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

        .header-info {
            flex: 1;
        }

        .header h1 {
            margin: 0 0 3px 0;
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
        }

        .header h2 {
            margin: 0;
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 400;
        }
        .info-section {
            margin-bottom: 12px;
            padding-bottom: 5px;
        }

        .section-title {
            font-weight: 600;
            font-size: 10px;
            margin-bottom: 5px;
            color: #2c3e50;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 5px;
            font-size: 9px;
        }

        .info-item {
            margin-bottom: 3px;
            padding: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
            border: 1px solid #f0f0f0;
        }

        th {
            background-color: #2c3e50;
            color: white;
            text-align: left;
            padding: 6px 5px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 6px 5px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .totals {
            margin-top: 10px;
            font-size: 10px;
        }

        .total-row {
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #95a5a6;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
        }
        .payment-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
            font-size: 9px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
        }

        .highlight {
            font-weight: 600;
            color: #2c3e50;
        }

        .totals-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
        }
        .total-amount {
            font-size: 12px;
            color: #e74c3c;
            font-weight: 600;
        }

        .iva-amount {
            color: #3498db;
        }

        .subtotal-amount {
            color: #2ecc71;
        }

        /* Responsive */
        @media print {
            body {
                background: none;
            }

            .invoice-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo-container">
                <img src="./logotipo.png" alt="Logo LookTrendy" class="logo">
            </div>
            <div class="header-info">
                <h1>FACTURA DE VENTA</h1>
                <h2>N° {{ $invoice_number }} | {{ $date }} | {{ now()->format('h:i A') }}</h2>
            </div>
        </div>
        <div class="info-section">
            <div class="section-title">Datos de la empresa</div>
            <div class="info-grid">
                <div class="info-item"><strong>LookTrendy</strong> - Moda y Accesorios</div>
                <div class="info-item"><strong>Teléfono:</strong> +505 8702 5001</div>
                <div class="info-item"><strong>RUC:</strong> 441-230572-0006S</div>
                <div class="info-item"><strong>Dirección:</strong> Managua, Nicaragua</div>
            </div>
        </div>
        <div class="info-section">
            <div class="section-title">Datos del cliente</div>
            <div class="info-grid">
                <div class="info-item"><strong>Nombre:</strong> {{ $sale->client->Client_FirstName }}
                    {{ $sale->client->Client_LastName }}</div>
                <div class="info-item"><strong>Identificación:</strong> {{ $sale->client->Client_Identity ?? 'N/A' }}
                </div>
                <div class="info-item"><strong>Atendido por:</strong> {{ $user->User_FirstName }}</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%">Producto</th>
                    <th style="width: 15%">Cantidad</th>
                    <th style="width: 15%" class="text-right">Precio Unitario</th>
                    <th style="width: 20%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleDetails as $detail)
                    <tr>
                        <td>{{ $detail->product->Product_Name }}</td>
                        <td>{{ $detail->Quantity }}</td>
                        <td class="text-right">C$ {{ number_format($detail->product->Unit_Price, 2) }}</td>
                        <td class="text-right">C$ {{ number_format($detail->Subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="totals">
            <div class="totals-grid">
                <div></div>
                <div style="background: #f9f9f9; padding: 8px; border-radius: 4px;">
                    <div class="flex-between">
                        <span>Subtotal:</span>
                        <span class="subtotal-amount">C$ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex-between">
                        <span>IVA 15%:</span>
                        <span class="iva-amount">C$ {{ number_format($vat, 2) }}</span>
                    </div>
                    <div class="flex-between total-row"
                        style="margin-top: 5px; padding-top: 5px; border-top: 1px solid #eee;">
                        <span>TOTAL:</span>
                        <span class="total-amount">C$ {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
<div class="payment-info">
    <div>
        <div class="highlight">Método de pago: <span style="color: #e74c3c">{{ $payment_type }}</span></div>
    </div>
    <div>
        <div class="flex-between">
            <span class="highlight">Efectivo recibido:</span>
            <span class="highlight">C$ {{ number_format($received_amount, 2) }}</span>
        </div>
        <div class="flex-between" style="margin-top: 3px;">
            <span class="highlight">Cambio:</span>
            <span class="highlight" style="color: #2ecc71">C$ {{ number_format($change_amount, 2) }}</span>
        </div>
                <div style="margin-top: 5px; font-size: 8px; color: #7f8c8d;">
            Transacción completada el {{ now()->format('d/m/Y h:i A') }}
        </div>
    </div>
</div>
        <div class="footer">
            <div>¡Gracias por su compra en LookTrendy!</div>
            <div style="margin-top: 3px;">Factura generada el {{ now()->format('d/m/Y H:i') }} | Sistema de ventas
                LookTrendy</div>
            <div style="margin-top: 3px; font-size: 7px; color: #bdc3c7;">Este documento es válido como factura legal
                según normativa nicaragüense</div>
        </div>
    </div>
</body>
</html>