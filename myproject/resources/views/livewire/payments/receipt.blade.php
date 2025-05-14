<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recibo de Pago #{{ $payment->Payment_ID }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos mejorados para el recibo */
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #e1e1e1;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: #fff;
        }
        .header {
            display: flex;
            align-items: flex-end;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo-container {
            width: 250px;
            margin-right: 30px;
            margin-bottom: -75px;
        }
        .logo {
            max-width: 100%;
            max-height: 170px;
            object-fit: contain;
        }
        .header-content {
            flex: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .receipt-title {
            text-align: right;
        }
        .receipt-title h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }
        .receipt-title .receipt-number {
            color: #7f8c8d;
            font-size: 16px;
            margin-top: 5px;
        }
        .invoice-info {
            text-align: right;
        }
        .entity-section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #2c3e50;
            border-left: 4px solid #3498db;
            font-size: 16px;
        }
        /* Estilo de dos columnas para información de empresa */
        .company-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .company-info-item {
            margin-bottom: 8px;
        }
        /* Estilo de dos columnas para información de cliente */
        .client-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .client-info-item {
            margin-bottom: 8px;
        }
        .detail-label {
            font-weight: bold;
            color: #7f8c8d;
            display: block;
            margin-bottom: 3px;
            font-size: 14px;
        }
        .detail-value {
            font-size: 15px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .invoice-table th {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #3498db;
            font-weight: bold;
            background-color: #f8f9fa;
            color: #2c3e50;
        }
        .invoice-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .invoice-table tr:last-child td {
            border-bottom: none;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .amount-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .amount-row:last-child {
            border-bottom: none;
        }
        .payment-method {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .payment-method strong {
            color: #2c3e50;
        }
        .observations {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-style: italic;
        }
        .signature-area {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px dashed #95a5a6;
            width: 200px;
            text-align: center;
            padding-top: 10px;
            font-size: 12px;
            color: #95a5a6;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .text-right {
            text-align: right;
        }
        .text-red {
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Encabezado con logo y título alineados -->
        <div class="header">
            <div class="logo-container">
                <img src="./logotipo.png" alt="Logo de la empresa" class="logo">
            </div>
            <div class="header-content">
                <div class="receipt-title">
                    <h1>RECIBO DE PAGO</h1>
                    <div class="receipt-number">N° {{ str_pad($payment->Payment_ID, 6, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</div>
                </div>
                <div class="invoice-info">
                    <div>
                        <span class="detail-label">FECHA:</span>
                        <span class="detail-value">{{ $payment->Payment_Date->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la empresa en dos columnas -->
        <div class="entity-section">
            <div class="section-title">INFORMACIÓN DE LA EMPRESA</div>
            <div class="company-info-grid">
                <div class="company-info-item">
                    <span class="detail-label">Nombre:</span>
                    <span class="detail-value">LookTrendy</span>
                </div>
                <div class="company-info-item">
                    <span class="detail-label">Dirección:</span>
                    <span class="detail-value">Barrio primero de mayo del Parque una cuadra al oeste, Matagalpa, Nicaragua</span>
                </div>
                <div class="company-info-item">
                    <span class="detail-label">Teléfono:</span>
                    <span class="detail-value">+505 8702 5001</span>
                </div>
                <div class="company-info-item">
                    <span class="detail-label">RUC/Cédula:</span>
                    <span class="detail-value">441-230572-0006S</span>
                </div>
                <div class="company-info-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">looktrendy@gmail.com</span>
                </div>
                <div class="company-info-item">
                    <span class="detail-label">Web:</span>
                    <span class="detail-value">http://www.looktrendy.com</span>
                </div>
            </div>
        </div>

        <!-- Información del cliente en dos columnas -->
        <div class="entity-section">
            <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
            <div class="client-info-grid">
                    <div class="detail-item">
                    <span class="detail-label">Nombre:</span>
                    <span class="detail-value">{{ optional($payment->credit->client)->Client_FirstName }} {{ optional($payment->credit->client)->Client_LastName }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Identificación:</span>
                    <span class="detail-value">{{ optional($payment->credit->client)->Client_Identity }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Crédito N°:</span>
                    <span class="detail-value">{{ str_pad($payment->Credit_ID, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Fecha de pago:</span>
                    <span class="detail-value">{{ $payment->Payment_Date->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Resto del contenido se mantiene igual -->
        <!-- Detalles del pago -->
        <div class="section-title">DETALLES DEL PAGO</div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>CONCEPTO</th>
                    <th class="text-right">MONTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monto total del crédito</td>
                    <td class="text-right">${{ number_format($payment->credit->Total_Amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Total pagado anteriormente</td>
                    <td class="text-right">${{ number_format($payment->credit->payments->sum('Payment_Amount') - $payment->Payment_Amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Monto de este pago</td>
                    <td class="text-right">${{ number_format($payment->Payment_Amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>SALDO PENDIENTE</strong></td>
                    <td class="text-right text-red"><strong>${{ number_format($payment->remaining_balance, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Totales -->
        <div class="amount-details">
            <div class="amount-row total-row">
                <span>TOTAL RECIBIDO:</span>
                <span>${{ number_format($payment->Payment_Amount, 2) }}</span>
            </div>
        </div>

        <!-- Método de pago -->
       <div class="payment-method">
    <strong>MÉTODO DE PAGO:</strong> 
    <span class="detail-value">
        {{ $paymentMethodName ?? 'No especificado' }}
    </span>
</div>

        <!-- Observaciones -->
        <div class="observations">
            <strong>OBSERVACIONES:</strong> Pago realizado en {{$paymentMethodName}} en nuestras oficinas.
        </div>

        <!-- Pie de página -->
        <div class="footer">
            Documento generado el: {{ now()->format('d/m/Y H:i') }}<br>
            Este documento es válido como comprobante de pago.
        </div>
    </div>
</body>
</html>