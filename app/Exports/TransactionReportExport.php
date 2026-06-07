<?php

namespace App\Exports;

use App\Models\RawMaterialTransaction;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransactionReportExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithEvents,
    WithColumnFormatting
{
    public function __construct(
        protected ?string $search,
        protected ?string $type,
        protected ?string $year,
        protected ?string $month,
    ) {}

    public function collection()
    {
        return RawMaterialTransaction::query()

            ->with('rawMaterial')

            ->search(
                $this->search
            )

            ->type(
                $this->type
            )

            ->year(
                $this->year
            )

            ->month(
                $this->month
            )

            ->latest()

            ->get()

            ->map(function ($transaction) {

                return [

                    'date' =>
                        $transaction
                            ->created_at
                            ->format('d-m-Y H:i'),

                    'material' =>
                        $transaction
                            ->rawMaterial
                            ?->name,

                    'type' =>
                        $transaction
                            ->type_label,

                    'quantity' =>
                        $transaction
                            ->quantity,

                    'unit' =>
                        $transaction
                            ->rawMaterial
                            ?->base_unit,

                    'before_stock' =>
                        $transaction
                            ->before_stock,

                    'after_stock' =>
                        $transaction
                            ->after_stock,

                    'purchase_value' =>
                        $transaction
                            ->total_price,

                ];

            });
    }

    public function headings(): array
    {
        return [

            'Date',
            'Material',
            'Activity Type',

            'Quantity',
            'Unit',

            'Before Stock',
            'After Stock',

            'Purchase Value',

        ];
    }

    public function columnFormats(): array
    {
        return [

            'D' =>
                NumberFormat::FORMAT_NUMBER_00,

            'F' =>
                NumberFormat::FORMAT_NUMBER_00,

            'G' =>
                NumberFormat::FORMAT_NUMBER_00,

            'H' =>
                '#,##0',

        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event->sheet;

                $sheet->freezePane(
                    'A2'
                );

                $sheet
                    ->getStyle('A1:I1')
                    ->getFont()
                    ->setBold(true);

                $sheet
                    ->getStyle('A:I')
                    ->getAlignment()
                    ->setHorizontal(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    );

                $sheet
                    ->getStyle('A:I')
                    ->getAlignment()
                    ->setVertical(
                        \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    );

                $sheet
                    ->getStyle('A:A')
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet
                    ->getStyle('C:I')
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet
                    ->getStyle('H:H')
                    ->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_RIGHT
                    );

            },

        ];
    }

}