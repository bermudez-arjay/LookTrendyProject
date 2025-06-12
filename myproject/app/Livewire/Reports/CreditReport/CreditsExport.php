<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CreditsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $credits;

    public function __construct($credits)
    {
        $this->credits = $credits;
    }

    public function collection()
    {
        return $this->credits;
    }

    public function headings(): array
    {
        return [
            'ID Crédito',
            'Cliente',
            'Fecha Inicio',
            'Fecha Vencimiento',
            'Monto Total',
            'Saldo Pendiente',
            'Estado',
           
        ];
    }

    public function map($credit): array
    {
        return [
            $credit->Credit_ID,
            $credit->client->Name ?? 'N/A',
            $credit->Start_Date->format('d/m/Y'),
            $credit->Due_Date->format('d/m/Y'),
            $credit->Total_Amount,
            $credit->remaining_balance,
            $credit->computed_status,
           
        ];
    }
}