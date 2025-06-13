<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura de Compra #{{ $invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h2>Factura de Compra</h2>
            <p>N° {{ $invoice_number }}</p>
        </div>
        
        <div class="details">
            <p><strong>Proveedor:</strong> {{ $purchase->supplier->Supplier_Name }}</p>
            <p><strong>Fecha:</strong> {{ $date }}</p>
            <p><strong>Método de Pago:</strong> {{ $payment_type }}</p>
            <p><strong>Atendido por:</strong> {{ $user->User_FirstName }} {{ $user->User_LastName }}</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>IVA (15%)</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchase->purchaseDetails as $detail)
                <tr>
                    <td>{{ $detail->product->Product_Name }}</td>
                    <td>{{ $detail->Quantity }}</td>
                    <td>C${{ number_format($detail->Unit_Price, 2) }}</td>
                    <td>C${{ number_format($detail->Subtotal, 2) }}</td>
                    <td>C${{ number_format($detail->VAT, 2) }}</td>
                    <td>C${{ number_format($detail->Total_With_VAT, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals" style="margin-top: 20px; text-align: right;">
            <p><strong>Subtotal:</strong> C${{ number_format($subtotal, 2) }}</p>
            <p><strong>IVA (15%):</strong> C${{ number_format($vat, 2) }}</p>
            <p><strong>Total:</strong> C${{ number_format($total, 2) }}</p>
            <p><strong>Monto Recibido:</strong> C${{ number_format($received_amount, 2) }}</p>
            <p><strong>Cambio:</strong> C${{ number_format($change_amount, 2) }}</p>
        </div>
        
        <div class="footer">
            <p>Gracias por su compra</p>
            <p>Sistema de Inventario - {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>