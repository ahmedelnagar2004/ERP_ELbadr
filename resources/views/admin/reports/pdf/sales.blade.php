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
    </style>
</head>
<body>
    <div class="header">
        <h2>تقرير المبيعات</h2>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>رقم الفاتورة</th>
                <th>التاريخ</th>
                <th>العميل</th>
                <th>الإجمالي</th>
                <th>المدفوع</th>
                <th>المتبقي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->order_date }}</td>
                    <td>{{ $sale->client->name }}</td>
                    <td>{{ number_format($sale->net_amount, 2) }}</td>
                    <td>{{ number_format($sale->paid_amount, 2) }}</td>
                    <td>{{ number_format($sale->remaining_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">الإجمالي</th>
                <th>{{ number_format($sales->sum('net_amount'), 2) }}</th>
                <th>{{ number_format($sales->sum('paid_amount'), 2) }}</th>
                <th>{{ number_format($sales->sum('remaining_amount'), 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
