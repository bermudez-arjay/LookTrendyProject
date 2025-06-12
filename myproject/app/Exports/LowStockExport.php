<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LowStockExport implements FromCollection, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell
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
               'Nombre Producto',
            'Stock Actual',
            'Stock Mínimo',
            'Diferencia',
            'Estado'
        ];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function styles(Worksheet $sheet)
    {
        // Logo y título de la empresa
        $sheet->mergeCells('B3:D4');
        $sheet->setCellValue('B3', 'TIENDA LOOK-TRENDY');
        $sheet->getStyle('B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 16
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E74C3C'] // Rojo original
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => 'FFFFFF']
                ]
            ]
        ]);

        // Título del reporte
        $sheet->mergeCells('A7:E7');
        $sheet->setCellValue('A7', 'REPORTE DE PRODUCTOS BAJO STOCK - ' . now()->format('d/m/Y'));
        $sheet->getStyle('A7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E74C3C'] // Rojo original
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Encabezados de la tabla
        $sheet->getStyle('A9:E9')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E74C3C'] // Rojo original
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Bordes para los datos
        $sheet->getStyle('A10:E' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Estilo condicional para bajo stock
        $lastRow = $sheet->getHighestRow();
        for ($i = 10; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('E' . $i)->getValue();
            $color = ($status == 'CRÍTICO') ? 'FF9999' : 'FFCC99'; // Colores originales
            
            $sheet->getStyle('A' . $i . ':E' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color]
                ]
            ]);
        }

        // Autoajustar columnas
        foreach(range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('./logotipo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);
        
        return [$drawing];
    }
}