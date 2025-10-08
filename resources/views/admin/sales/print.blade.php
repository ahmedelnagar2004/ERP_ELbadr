@extends('layouts.admin')

@section('title', 'طباعة فاتورة المبيعات')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-area, .print-area * {
            visibility: visible;
        }
        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none !important;
        }
    }

    .print-area {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
        background: white;
        color: #333;
    }

    .invoice-header {
        text-align: center;
        border-bottom: 3px solid #333;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .invoice-title {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .invoice-number {
        font-size: 18px;
        color: #7f8c8d;
    }

    .invoice-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .info-column {
        flex: 1;
    }

    .info-title {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .info-item {
        margin-bottom: 5px;
        font-size: 14px;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .items-table th,
    .items-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: right;
    }

    .items-table th {
        background: #34495e;
        color: white;
        font-weight: bold;
    }

    .items-table tr:nth-child(even) {
        background: #f8f9fa;
    }

    .totals-section {
        width: 50%;
        margin-left: auto;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background: #f8f9fa;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 5px 0;
    }

    .total-row.final {
        border-top: 2px solid #333;
        padding-top: 15px;
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
    }

    .payment-info {
        margin-top: 30px;
        padding: 20px;
        background: #e8f4fd;
        border-radius: 8px;
        border-right: 4px solid #3498db;
    }

    .payment-title {
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .print-actions {
        margin-top: 20px;
        text-align: center;
        padding: 20px;
        background: #ecf0f1;
        border-radius: 8px;
    }

    .print-btn {
        background: #27ae60;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        margin: 0 10px;
    }

    .print-btn:hover {
        background: #229954;
    }

    .back-btn {
        background: #95a5a6;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none;
        display: inline-block;
    }

    .back-btn:hover {
        background: #7f8c8d;
    }

    @media (max-width: 768px) {
        .invoice-info {
            flex-direction: column;
            gap: 20px;
        }

        .totals-section {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="print-area">
    <!-- Print Header -->
    <div class="invoice-header">
        <div class="invoice-title">فاتورة مبيعات</div>
        <div class="invoice-number">رقم الفاتورة: {{ $sale->invoice_number }}</div>
    </div>

    <!-- Invoice Information -->
    <div class="invoice-info">
        <div class="info-column">
            <div class="info-title">معلومات العميل</div>
            <div class="info-item"><strong>الاسم:</strong> {{ $sale->client->name }}</div>
            <div class="info-item"><strong>الهاتف:</strong> {{ $sale->client->phone }}</div>
            @if($sale->client->address)
            <div class="info-item"><strong>العنوان:</strong> {{ $sale->client->address }}</div>
            @endif
        </div>
        <div class="info-column">
            <div class="info-title">معلومات الفاتورة</div>
            <div class="info-item"><strong>التاريخ:</strong> {{ $sale->created_at->format('Y/m/d') }}</div>
            <div class="info-item"><strong>المستخدم:</strong> {{ $sale->user->name }}</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">المنتج</th>
                <th style="width: 15%;">الكمية</th>
                <th style="width: 17%;">السعر</th>
                <th style="width: 18%;">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->pivot->quantity }}</td>
                <td>{{ number_format($item->pivot->unit_price, 2) }} ر.س</td>
                <td><strong>{{ number_format($item->pivot->total_price, 2) }} ر.س</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <div class="total-row">
            <span>المجموع الفرعي:</span>
            <span><strong>{{ number_format($sale->total, 2) }} ر.س</strong></span>
        </div>

        @if($sale->discount > 0)
        <div class="total-row">
            <span>الخصم:</span>
            <span style="color: #e74c3c;">-{{ number_format($sale->discount, 2) }} ر.س</span>
        </div>
        @endif

        @if($sale->shipping_cost > 0)
        <div class="total-row">
            <span>تكلفة الشحن:</span>
            <span><strong>{{ number_format($sale->shipping_cost, 2) }} ر.س</strong></span>
        </div>
        @endif

        <div class="total-row final">
            <span>الإجمالي النهائي:</span>
            <span><strong>{{ number_format($sale->net_amount, 2) }} ر.س</strong></span>
        </div>

        @if($sale->remaining_amount > 0)
        <div class="total-row" style="color: #e74c3c;">
            <span>المبلغ المتبقي:</span>
            <span><strong>{{ number_format($sale->remaining_amount, 2) }} ر.س</strong></span>
        </div>
        @endif
    </div>

    <!-- Payment Information -->
    <div class="payment-info">
        <div class="payment-title">معلومات الدفع</div>
        <div class="info-item">
            <strong>نوع الدفع:</strong>
            @switch($sale->payment_type)
                @case('cash')
                    نقدي
                    @break
                @case('card')
                    بطاقة ائتمان
                    @break
                @case('bank')
                    تحويل بنكي
                    @break
                @case('credit')
                    آجل
                    @break
                @default
                    غير محدد
            @endswitch
        </div>
        @if($sale->remaining_amount > 0)
        <div class="info-item" style="color: #e74c3c;">
            <strong>ملاحظة:</strong> هناك مبلغ متبقي قدره {{ number_format($sale->remaining_amount, 2) }} ر.س
        </div>
        @endif
    </div>

    <!-- Print Actions (No Print) -->
    <div class="print-actions no-print">
        <button onclick="window.print()" class="print-btn">
            <svg style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            طباعة الفاتورة
        </button>
        <a href="{{ route('admin.sales.show', $sale->id) }}" class="back-btn">
            <svg style="width: 16px; height: 16px; margin-left: 8px; vertical-align: middle;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            العودة للفاتورة
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto print when page loads (optional)
    // window.print();

    // Print button functionality
    document.querySelector('.print-btn')?.addEventListener('click', function() {
        window.print();
    });
});
</script>
@endpush
@endsection
