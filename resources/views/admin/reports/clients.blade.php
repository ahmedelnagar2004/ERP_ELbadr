@extends('layouts.admin')

@section('title', 'تقرير العملاء')
@section('page-title', 'تقرير العملاء')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.reports.clients') }}" method="GET" class="mb-6 print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العميل</label>
                <select name="client_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
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

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرصيد الحالي</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الفواتير</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي المشتريات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clientsData as $client)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $client->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($client->balance, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->sales_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($client->sales_sum_net_amount ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .print\:hidden {
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
@endsection
