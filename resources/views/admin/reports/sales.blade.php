@extends('layouts.admin')

@section('title', 'تقرير المبيعات')
@section('page-title', 'تقرير المبيعات')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.reports.sales') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العميل</label>
                <select name="client_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الصنف</label>
                <select name="item_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">بحث</button>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-sucess" style = " background:green;">
                <i class="fas fa-file-excel"></i>
                تصدير Excel
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 inline-flex items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                تصدير PDF
            </a>
            <button type="button" onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 inline-flex items-center gap-2">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </form>

    <style>
        @media print {
            form {
                display: none !important;
            }
            /* Hide sidebar and header if they don't have print:hidden class */
            aside, nav, header, footer {
                display: none !important;
            }
            /* Ensure content takes full width */
            main, .content {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            body {
                background: white !important;
            }
        }
    </style>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الفاتورة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المدفوع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المتبقي</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sales as $sale)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <a href="{{ route('admin.sales.show', $sale->id) }}" class="text-blue-600 hover:text-blue-900">
                                {{ $sale->invoice_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->order_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->client->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($sale->net_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($sale->paid_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($sale->remaining_amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">الإجمالي</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($sales->sum('net_amount'), 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($sales->sum('paid_amount'), 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($sales->sum('remaining_amount'), 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
