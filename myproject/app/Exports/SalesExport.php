<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales;
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'Fecha',
            'Cliente',
            'Cédula',
            'Cantidad de Productos',
            'Total',
            'IVA'
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->Sale_ID,
            $sale->Sale_Date->format('d/m/Y'),
            $sale->client ? $sale->client->Client_FirstName . ' ' . $sale->client->Client_LastName : 'N/A',
            $sale->client ? $sale->client->Client_Identity : 'N/A',
            $sale->saleDetails->sum('Quantity'),
            $sale->Total_Amount,
            $sale->Sale_VAT
        ];
    }
}