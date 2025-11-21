<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemTransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'الصنف',
            'نوع الحركة',
            'الكمية',
            'المستودع',
            'المستخدم',
            'الوصف',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at->format('Y-m-d H:i'),
            $transaction->item->name,
            $transaction->type->value === 'add' ? 'إضافة' : 'صرف',
            $transaction->quantity,
            $transaction->warehouse->name,
            $transaction->user->name ?? '-',
            $transaction->description,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
