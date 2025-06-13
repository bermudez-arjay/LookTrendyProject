<?php

namespace App\Exports;

use App\Models\Inventory;
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

class InventoryExport implements FromCollection, WithHeadings, WithStyles, WithDrawings, WithCustomStartCell
{
    public function collection()
    {
        return Inventory::with(['product' => function ($query) {
                $query->where('Removed', 0);
            }])
            ->whereHas('product', function ($query) {
                $query->where('Removed', 0);
            })
            ->orderBy('Current_Stock', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'Nombre Producto' => $item->product->Product_Name ?? 'N/A',
                    'Stock' => $item->Current_Stock,
                    'Stock Mini' => $item->Minimum_Stock,
                    'Diferencia' => $item->Current_Stock - $item->Minimum_Stock,
                    'Estado' => $item->Current_Stock <= $item->Minimum_Stock ? 'Bajo Stock' : 'Disponible',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nombre Producto',
            'Stock',
            'Stock Minimo',
            'Diferencia',
            'Estado',
        ];
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('B3:D4');
        $sheet->setCellValue('B3', 'TIENDA LOOK-TRENDY');
        $sheet->getStyle('B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);


        $sheet->mergeCells('A7:E7');
        $sheet->setCellValue('A7', 'REPORTE DE INVENTARIO - ' . now()->format('d/m/Y'));
        $sheet->getStyle('A7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A9:E9')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
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

        $sheet->getStyle('A8:E' . $sheet->getHighestRow())->applyFromArray([
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

        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('logotipo.png')); 
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(10);

        return [$drawing];
    }
}
