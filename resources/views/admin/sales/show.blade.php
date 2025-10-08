@extends('layouts.admin')


@push('styles')
<style>
    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    .invoice-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .status-completed {
        background: #10b981;
        color: white;
    }
    .status-pending {
        background: #f59e0b;
        color: white;
    }
    .invoice-details {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 1.5rem;
    }
    .info-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        border-right: 4px solid #3b82f6;
    }
    .info-label {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }
    .info-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 1rem;
    }
    .items-table {
        margin: 2rem 0;
    }
    .items-table th {
        background: #f1f5f9;
        padding: 1rem;
        text-align: right;
        font-weight: 600;
        color: #374151;
    }
    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .total-section {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1rem;
    }
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }
    .total-row.final {
        border-top: 2px solid #3b82f6;
        padding-top: 1rem;
        font-size: 1.125rem;
        font-weight: 700;
    }
    .print-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold mb-2">@lang('admin.COMMON.invoice')</h1>
                <p class="text-lg opacity-90">@lang('admin.COMMON.invoice_number') : {{ $sale->invoice_number }}</p>
            </div>
            <div class="text-right">
                
                <p class="mt-2 text-sm opacity-90">
                    @lang('admin.COMMON.created_at') : {{ $sale->created_at->format('Y/m/d') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">@lang('admin.COMMON.client')</div>
                <div class="info-value">{{ $sale->client->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">@lang('admin.COMMON.phone')</div>
                <div class="info-value">{{ $sale->client->phone }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">@lang('admin.COMMON.payment_type')</div>
                <div class="info-value">
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
            </div>
          
        </div>
    </div>

    <!-- Items Table -->
    <div class="invoice-details">
        <div class="items-table">
            <table class="w-full">
                <thead>
                    <tr>
                        <th>@lang('admin.COMMON.product')</th>
                        <th>@lang('admin.COMMON.quantity')</th>
                        <th>@lang('admin.COMMON.unit_price')</th>
                        <th>@lang('admin.COMMON.total_price')</th>

                        <th>@lang('admin.COMMON.safe')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                    <tr>
                        <td class="font-medium">{{ $item->name }}</td>
                        <td>{{ $item->pivot->quantity }}</td>
                        <td>{{ number_format($item->pivot->unit_price, 2) }} ر.س</td>
                        <td class="font-semibold">{{ number_format($item->pivot->total_price, 2) }} ر.س</td>
                        <td>{{ $sale->safe->name?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="total-section">
            <div class="total-row">
                <span>@lang('admin.COMMON.sub_total')</span>
                <span>{{ number_format($sale->total, 2) }} ر.س</span>
            </div>
            @if($sale->discount > 0)
            <div class="total-row">
                <span>الخصم:</span>
                <span class="text-red-600">-{{ number_format($sale->discount, 2) }} ر.س</span>
            </div>
            @endif
            @if($sale->shipping_cost > 0)
            <div class="total-row">
                <span>@lang('admin.COMMON.shipping_cost')</span>
                <span>{{ number_format($sale->shipping_cost, 2) }} ر.س</span>
            </div>
            @endif
            <div class="total-row final">
                <span>@lang('admin.COMMON.final_amount')</span>
                <span>{{ number_format($sale->net_amount, 2) }} ر.س</span>
            </div>
            @if($sale->remaining_amount > 0)
            <div class="total-row" style="color: #ef4444;">
                <span>@lang('admin.COMMON.remaining_amount')</span>
                <span>{{ number_format($sale->remaining_amount, 2) }} ر.س</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between items-center">
        <div class="flex gap-3">
            <a href="{{ route('admin.sales.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                @lang('admin.back') 
            </a>

        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.sales.print', $sale->id) }}" class="print-btn" target="_blank">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                @lang('admin.COMMON.print')
            </a>
        </div>
    </div>
</div>
@endsection
