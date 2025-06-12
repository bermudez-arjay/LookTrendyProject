<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura #{{ $invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 5px;
            color: #333;
        }
        .invoice-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 5px;
        }
        .header {
            display: flex;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            align-items: flex-end;
        }
        .logo-container {
            width: 80px;
            margin-right: 10px;
        }
        .logo {
            max-width: 100%;
            max-height: 50px;
        }
        .header-info {
            flex: 1;
        }
        .header h1 {
            margin: 0;
            font-size: 12px;
            color: #2c3e50;
        }
        .header h2 {
            margin: 0;
            font-size: 10px;
            color: #7f8c8d;
        }
        .info-section {
            margin-bottom: 8px;
        }
        .section-title {
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 3px;
            font-size: 8px;
        }
        .info-item {
            margin-bottom: 1px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
            font-size: 8px;
        }
        th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 3px;
            border-bottom: 1px solid #ddd;
        }
        td {
            padding: 3px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            margin-top: 5px;
            font-size: 9px;
        }
        .total-row {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 8px;
            text-align: center;
            font-size: 7px;
            color: #95a5a6;
        }
        .signature-line {
            border-top: 1px dashed #ccc;
            width: 80px;
            display: inline-block;
            margin-top: 15px;
            text-align: center;
            font-size: 7px;
            color: #95a5a6;
        }
        .payment-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px;
            margin-top: 5px;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
       
        <div class="header">
            <div class="logo-container">
                <img src="./logotipo.png" alt="Logo" class="logo">
            </div>
            <div class="header-info">
                <h1>FACTURA DE VENTA</h1>
                <h2>N° {{ $invoice_number }} | {{ $date }}</h2>
            </div>
        </div>

       
            <div class="section-title">EMPRESA</div>
            <div class="info-grid">
                <div class="info-item">LookTrendy</div>
                <div class="info-item">Tel: +505 8702 5001</div>
                <div class="info-item">RUC: 441-230572-0006S</div>
            </div>
        </div>
        <div class="info-section">
            <div class="section-title">CLIENTE</div>
            <div class="info-grid">
                <div class="info-item">{{ $sale->client->Client_FirstName }} {{ $sale->client->Client_LastName }}</div>
                <div class="info-item">ID: {{ $sale->client->Client_Identity ?? 'N/A' }}</div>
                <div class="info-item">Atendido: {{ $user->User_FirstName }}</div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>PRODUCTO</th>
                    <th>CANT</th>
                    <th class="text-right">PRECIO</th>
                    <th class="text-right">TOTAL</th>
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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3px;">
                <div></div>
                <div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Subtotal:</span>
                        <span>C$ {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>IVA 15%:</span>
                        <span>C$ {{ number_format($vat, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: bold;">
                        <span>TOTAL:</span>
                        <span>C$ {{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
<div class="payment-info">
    <div>
        <div class="highlight">Método: {{ $payment_type }}</div>
    </div>
    <div>
        <div style="display: flex; justify-content: space-between;">
            <span class="highlight">Recibido:</span>
            <span class="highlight">C$ {{ number_format($received_amount, 2) }}</span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span class="highlight">Cambio:</span>
            <span class="highlight">C$ {{ number_format($change_amount, 2) }}</span>
        </div>
    </div>
</div>
        <div style="text-align: center; margin-top: 10px;">
            <span class="signature-line">Cliente</span>
            &nbsp;
            <span class="signature-line">Vendedor</span>
        </div>

        
        <div class="footer">
            {{ now()->format('d/m/Y H:i') }} | LookTrendy
        </div>
    </div>
</body>
</html>