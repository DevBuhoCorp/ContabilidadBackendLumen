<?php

namespace App\Exports;

use App\Models\Cuentacontable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class CuentacontableExport implements FromQuery, WithHeadings, WithEvents, WithMapping, WithColumnFormatting,ShouldAutoSize
{
    use Exportable;
    public function __construct(int $modelo)
    {
        $this->IDModelo = $modelo;
    }

    public function query()
    {
        return Cuentacontable::query()
                ->join('plancontable', 'IDCuenta', 'cuentacontable.ID')
//                ->select(['NumeroCuenta', 'Etiqueta'])
                ->where('plancontable.IDModelo', $this->IDModelo);
    }

    public function map($cuenta): array
    {
        return [
            $cuenta->NumeroCuenta,
            ( str_repeat('        ', substr_count($cuenta->NumeroCuenta, '.') )   ) . $cuenta->Etiqueta
        ];
    }

    public function headings(): array
    {
        return [
            'Número de Cuenta',
            'Etiqueta',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
//                $event->sheet->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $event->sheet->getDelegate()->setTitle('Cuentas Contables');

                $event->sheet->getDelegate()->getStyle('A1:B1')->applyFromArray([
                    'font' => [
                        'size' => 18,
                        'bold' => true,
                    ],
                    'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF8DB4E2',
                    ]
                ],
                ]);
            },
        ];
    }

}
