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
        <h2>تقرير العملاء</h2>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>اسم العميل</th>
                <th>رقم الهاتف</th>
                <th>الرصيد الحالي</th>
                <th>عدد الفواتير</th>
                <th>إجمالي المشتريات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientsData as $client)
                <tr>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ number_format($client->balance, 2) }}</td>
                    <td>{{ $client->sales_count }}</td>
                    <td>{{ number_format($client->sales_sum_net_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
