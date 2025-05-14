<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recibo de Pago #{{ $payment->Payment_ID }}</title>
    <style>
        /* Estilos mejorados para el recibo */
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 25px;
            border: 1px solid #e1e1e1;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .logo-container {
            width: 150px;
            height: auto;
        }
        .logo {
            max-width: 100%;
            max-height: 80px;
        }
        .receipt-title {
            text-align: right;
        }
        .receipt-title h1 {
            color: #2c3e50;
            margin: 0;
            font-size: 24px;
        }
        .receipt-title .receipt-number {
            color: #7f8c8d;
            font-size: 16px;
            margin-top: 5px;
        }
        .client-info, .payment-details {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            color: #2c3e50;
            border-left: 4px solid #3498db;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .detail-item {
            margin-bottom: 10px;
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
        .total-row {
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #3498db;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
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
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Encabezado con logo -->
        <div class="header">
            <div class="logo-container">
                
                <img src="/logotipo.png" alt="Logo">
            </div>
            <div class="receipt-title">
                <h1>RECIBO DE PAGO</h1>
                <div class="receipt-number">N° {{ str_pad($payment->Payment_ID, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- Información del cliente -->
        <div class="client-info">
            <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
            <div class="details-grid">
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

        <!-- Detalles del pago -->
        <div class="payment-details">
            <div class="section-title">DETALLES DEL PAGO</div>
            
            <div class="amount-details">
                <div class="amount-row">
                    <span>Monto total del crédito:</span>
                    <span>${{ number_format($payment->credit->Total_Amount, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span>Total pagado anteriormente:</span>
                    <span>${{ number_format($payment->credit->payments->sum('Payment_Amount') - $payment->Payment_Amount, 2) }}</span>
                </div>
                <div class="amount-row">
                    <span>Monto de este pago:</span>
                    <span>${{ number_format($payment->Payment_Amount, 2) }}</span>
                </div>
                <div class="amount-row total-row">
                    <span>SALDO PENDIENTE:</span>
                    <span>${{ number_format($payment->remaining_balance, 2) }}</span>
                </div>
            </div>
        </div>

        

        <!-- Pie de página -->
        <div class="footer">
            <p>{{ config('app.name') }} - {{ now()->format('d/m/Y H:i') }}</p>
            <p>Este documento es un comprobante de pago válido</p>
        </div>
    </div>
</body>
</html>