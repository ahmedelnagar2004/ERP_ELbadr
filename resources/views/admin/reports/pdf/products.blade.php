<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'dejavu sans', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .low-stock {
            background-color: #ffebee;
            color: #c62828;
        }
        .in-stock {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>تقرير المنتجات</h2>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>كود الصنف</th>
                <th>اسم الصنف</th>
                <th>التصنيف</th>
                <th>الوحدة</th>
                <th>المستودع</th>
                <th>الكمية الحالية</th>
                <th>الحد الأدنى</th>
                <th>السعر</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->item_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? '-' }}</td>
                    <td>{{ $product->unit?->name ?? '-' }}</td>
                    <td>{{ $product->warehouse?->name ?? '-' }}</td>
                    <td>{{ number_format($product->quantity, 2) }}</td>
                    <td>{{ number_format($product->minimum_stock, 2) }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td class="{{ $product->quantity <= $product->minimum_stock ? 'low-stock' : 'in-stock' }}">
                        {{ $product->quantity <= $product->minimum_stock ? 'مخزون منخفض' : 'متوفر' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">الإجمالي</th>
                <th>{{ number_format($products->sum('quantity'), 2) }}</th>
                <th>{{ number_format($products->sum('minimum_stock'), 2) }}</th>
                <th>{{ number_format($products->sum('price'), 2) }}</th>
                <th>{{ $products->where('quantity', '<=', 'minimum_stock')->count() }} منخفض</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
