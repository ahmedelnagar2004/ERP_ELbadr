<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'كود الصنف',
            'اسم الصنف',
            'الوصف',
            'التصنيف',
            'الوحدة',
            'المستودع',
            'الكمية الحالية',
            'الحد الأدنى',
            'السعر',
            'الحالة',
        ];
    }

    public function map($product): array
    {
        return [
            $product->item_code,
            $product->name,
            $product->description ?? '-',
            $product->category?->name ?? '-',
            $product->unit?->name ?? '-',
            $product->warehouse?->name ?? '-',
            number_format($product->quantity, 2),
            number_format($product->minimum_stock, 2),
            number_format($product->price, 2),
            $product->quantity <= $product->minimum_stock ? 'مخزون منخفض' : 'متوفر',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
