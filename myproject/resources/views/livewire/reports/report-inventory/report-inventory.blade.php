<!DOCTYPE html>
<html>

<head>
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .receipt-container {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #e1e1e1;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: start;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .header {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .logo {
            max-height: 90px;
        }

        .company-info strong {
            font-weight: bold;
            justify-content: space-between;
        }

        .section-title-with-date {
            margin-top: 30px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
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

.company-info {
    margin-left: 20px;
    display: flex;
    gap: 20px; /* Espacio entre columnas */
    font-size: 14px;
    color: #2c3e50;
    line-height: 1.6;
}

.info-column {
    flex: 1; /* Cada columna ocupa el mismo espacio */
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #3498db;
            background-color: #f8f9fa;
            font-weight: bold;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .low-stock {
            background-color: #fff3cd;
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
            <h1>LOOK TRENDY</h1>
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
                @foreach($inventory as $item)
                    <tr class="{{ $item->Current_Stock <= $item->Minimum_Stock ? 'low-stock' : '' }}">
                        <td>{{ $item->product->Product_Name ?? 'N/A' }}</td>
                        <td>{{ $item->Current_Stock }}</td>
                        <td>{{ $item->Minimum_Stock }}</td>
                        <td>{{ $item->Current_Stock - $item->Minimum_Stock }}</td>
                        <td>
                            @if($item->Current_Stock <= $item->Minimum_Stock)
                                <span style="color: #e74c3c;">Bajo Stock</span>
                            @else
                                <span style="color: #27ae60;">Disponible</span>
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