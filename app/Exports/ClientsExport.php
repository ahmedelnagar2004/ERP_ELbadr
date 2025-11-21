<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $clients;

    public function __construct($clients)
    {
        $this->clients = $clients;
    }

    public function collection()
    {
        return $this->clients;
    }

    public function headings(): array
    {
        return [
            'اسم العميل',
            'رقم الهاتف',
            'الرصيد الحالي',
            'عدد الفواتير',
            'إجمالي المشتريات',
        ];
    }

    public function map($client): array
    {
        return [
            $client->name,
            $client->phone,
            number_format($client->balance, 2),
            $client->sales_count,
            number_format($client->sales_sum_net_amount ?? 0, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
