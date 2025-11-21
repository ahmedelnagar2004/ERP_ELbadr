<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
            'رقم الفاتورة',
            'التاريخ',
            'العميل',
            'الإجمالي',
            'المدفوع',
            'المتبقي',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->order_date,
            $sale->client->name,
            number_format($sale->net_amount, 2),
            number_format($sale->paid_amount, 2),
            number_format($sale->remaining_amount, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
