@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'عرض بيانات العميل')
@section('page-subtitle', 'تفاصيل العميل المختار')

@section('content')
<style>
    .detail-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .detail-header { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .detail-body { padding:16px; }
    .row { display:flex; gap:12px; padding:8px 0; border-bottom:1px dashed #e2e8f0; }
    .row:last-child { border-bottom:0; }
    .label { min-width:140px; color:#475569; font-weight:600; }
    .value { color:#111827; }
</style>

<div class="detail-card">
    <div class="detail-header">
        <h3 class="text-lg font-bold text-gray-900">{{ $client->name }}</h3>
        <div class="flex gap-2">
            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn-primary">تعديل</a>
            <a href="{{ route('admin.clients.index') }}" class="btn-secondary">رجوع</a>
        </div>
    </div>
    <div class="detail-body">
        <div class="row">
            <div class="label">البريد الإلكتروني</div>
            <div class="value">{{ $client->email }}</div>
        </div>
        <div class="row">
            <div class="label">رقم الهاتف</div>
            <div class="value">{{ $client->phone }}</div>
        </div>
        <div class="row">
            <div class="label">العنوان</div>
            <div class="value">{{ $client->address }}</div>
        </div>
        <div class="row">
            <div class="label">الرصيد الحالي</div>
            <div class="value">
                @if($client->balance > 0)
                    <span class="text-green-600 font-bold">{{ number_format($client->balance, 2) }}</span>
                @elseif($client->balance < 0)
                    <span class="text-red-600 font-bold">{{ number_format(abs($client->balance), 2) }}</span>
                @else
                    <span class="text-gray-600 font-bold">0.00</span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="label">تاريخ الإنشاء</div>
            <div class="value">{{ optional($client->created_at)->format('Y-m-d H:i') }}</div>
        </div>
        <div class="row">
            <div class="label">آخر تحديث</div>
            <div class="value">{{ optional($client->updated_at)->format('Y-m-d H:i') }}</div>
        </div>
    </div>
</div>

<!-- Pay Remaining Section -->
<div class="detail-card mt-6">
    <div class="detail-header">
        <h3 class="text-lg font-bold text-gray-900">سداد متبقي للعميل</h3>
    </div>
    <div class="detail-body">
        <a href="{{ route('admin.payremaining.create', ['client_id' => $client->id]) }}" class="btn-primary">سداد متبقي</a>
        <a href="{{ route('admin.payremaining.index') }}" class="btn-secondary">رجوع</a>
    </div>
</div>


<!-- Transactions Section -->
@if($client->transactions->count() > 0)
<div class="detail-card mt-6">
    <div class="detail-header">
        <h3 class="text-lg font-bold text-gray-900">حركات حساب العميل</h3>
        <div class="text-sm text-gray-600">
            آخر 50 حركة
        </div>
    </div>
    <div class="detail-body">
        <div class="overflow-x-auto">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">التاريخ</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المرجع</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">الوصف</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المبلغ</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">المبلغ المتبقي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($client->transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ optional($transaction->created_at)->format('Y-m-d') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ optional($transaction->created_at)->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->reference_type && $transaction->reference_id)
                                    @switch($transaction->reference_type)
                                        @case('App\\Models\\Sale')
                                            <a href="{{ route('admin.sales.show', $transaction->reference_id) }}" 
                                               class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                                                فاتورة #{{ $transaction->reference_id }}
                                            </a>
                                            @break
                                        @default
                                            <span class="text-sm text-gray-600">{{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}</span>
                                    @endswitch
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $transaction->description }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->type->value === 'credit')
                                    <span class="text-red-600 font-semibold">{{ number_format($transaction->amount, 2) }}</span>
                                @else
                                    <span class="text-green-600 font-semibold">{{ number_format($transaction->amount, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($transaction->reference_type === 'App\\Models\\Sale' && $transaction->reference_id)
                                    @php
                                        $sale = \App\Models\Sale::find($transaction->reference_id);
                                    @endphp
                                    @if($sale && $sale->remaining_amount > 0)
                                        <span class="text-red-600 font-bold">{{ number_format($sale->remaining_amount, 2) }}</span>
                                        <div class="text-xs text-gray-500">متبقي</div>
                                    @else
                                        <span class="text-green-600 font-semibold">مسددة</span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="detail-card mt-6">
    <div class="detail-header">
        <h3 class="text-lg font-bold text-gray-900">حركات حساب العميل</h3>
    </div>
    <div class="detail-body">
        <div class="text-center py-8 text-gray-500">
            لا توجد حركات حسابية لهذا العميل
        </div>
    </div>
</div>
@endif
@endsection

