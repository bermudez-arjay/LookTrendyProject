<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
            background-color: #f5f5f5;
        }

        .receipt-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #e1e1e1;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logo {
            max-height: 80px;
            object-fit: contain;
        }
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: start;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .title {
            color: #E74C3C;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .company-info {
            margin-left: 20px;
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #2c3e50;
            line-height: 1.6;
        }

        .info-column {
            flex: 1;
        }

        .section-title-with-date {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin: 15px 0;
            font-weight: bold;
            color: #2c3e50;
            border-left: 4px solid #E74C3C;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title-with-date .date {
            margin-left: auto;
            font-weight: normal;
            font-size: 14px;
            color: #7f8c8d;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }

        th {
            text-align: center;
            padding: 12px 10px;
            background-color: #E74C3C;
            color: white;
            font-weight: bold;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        .low-stock {
            background-color: #FF9999;
        }

        .available {
            background-color: #FFCC99;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">

        <div class="top-row">
            <img src="./logotipo.png" alt="Logo de la empresa" class="logo">
            <h1 class="title">LOOK TRENDY</h1>
        </div>

        <div class="section-title-with-date">
            <span>DETALLE DE INVENTARIO</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Stock Actual</th>
                    <th>Stock Mínimo</th>
                    <th>Diferencia</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                    <tr class="{{ $item->Current_Stock <= $item->Minimum_Stock ? 'low-stock' : 'available' }}">
                        <td>{{ $item->product->Product_Name ?? 'N/A' }}</td>
                        <td>{{ $item->Current_Stock }}</td>
                        <td>{{ $item->Minimum_Stock }}</td>
                        <td>{{ $item->Current_Stock - $item->Minimum_Stock }}</td>
                        <td>
                            @if($item->Current_Stock <= $item->Minimum_Stock)
                                <strong>Bajo de Stock</strong>
                            @else
                                ALERTA
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Generado el {{ now()->format('d/m/Y H:i') }} | Sistema de Inventario
        </div>

    </div>
</body>

</html>