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
        <h2>تقرير حركة صنف</h2>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>الصنف</th>
                <th>نوع الحركة</th>
                <th>الكمية</th>
                <th>المستودع</th>
                <th>المستخدم</th>
                <th>الوصف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $transaction->item->name }}</td>
                    <td>{{ $transaction->type->value === 'add' ? 'إضافة' : 'صرف' }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->warehouse->name }}</td>
                    <td>{{ $transaction->user->name ?? '-' }}</td>
                    <td>{{ $transaction->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
