<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LowStockExport implements FromCollection, WithHeadings, WithStyles, WithDrawings
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products->map(function ($item) {
            return [
                'Producto' => $item->product->Product_Name ?? 'N/A',
                'Stock Actual' => $item->Current_Stock,
                'Stock Mínimo' => $item->Minimum_Stock,
                'Diferencia' => $item->Current_Stock - $item->Minimum_Stock,
                'Estado' => $item->Current_Stock <= $item->Minimum_Stock ? 'CRÍTICO' : 'ALERTA'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Producto',
            'Stock Actual',
            'Stock Mínimo',
            'Diferencia',
            'Estado'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo para el encabezado
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E74C3C']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Estilo condicional para bajo stock
        $lastRow = $sheet->getHighestRow();
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('F' . $i)->getValue();
            $color = ($status == 'CRÍTICO') ? 'FF9999' : 'FFCC99';
            
            $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color]
                ]
            ]);
        }

        // Autoajustar columnas
        foreach(range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Agregar título
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'REPORTE DE PRODUCTOS BAJO STOCK - ' . now()->format('d/m/Y'));
        $sheet->getStyle('A1')->getFont()->setSize(14);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('./logotipo.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        
        return [$drawing];
    }
}