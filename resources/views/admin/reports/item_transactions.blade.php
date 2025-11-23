@extends('layouts.admin')

@section('title', 'تقرير حركة صنف')
@section('page-title', 'تقرير حركة صنف')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.reports.item_transactions') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصنف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الحركة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستودع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->item->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type->value === 'add' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $transaction->type->value === 'add' ? 'إضافة' : 'صرف' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->warehouse->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaction->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
