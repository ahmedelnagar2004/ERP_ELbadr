@extends('layouts.admin')

@section('title', 'تقرير المنتجات')
@section('page-title', 'تقرير المنتجات')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.reports.products') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">التصنيف</label>
                <select name="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">المستودع</label>
                <select name="warehouse_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">الكل</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الكمية الدنيا</label>
                <input type="number" name="min_quantity" value="{{ request('min_quantity') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" step="0.01">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الكمية القصوى</label>
                <input type="number" name="max_quantity" value="{{ request('max_quantity') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" step="0.01">
            </div>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <div class="flex items-center">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="low_stock" class="mr-2 text-sm text-gray-700">المخزون المنخفض فقط</label>
            </div>
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
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">كود الصنف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الصنف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التصنيف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوحدة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستودع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية الحالية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحد الأدنى</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->item_code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->unit?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->warehouse?->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($product->quantity, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($product->minimum_stock, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->quantity <= $product->minimum_stock)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    مخزون منخفض
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    متوفر
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="5" class="px-6 py-3 text-right text-sm font-bold text-gray-900">الإجمالي</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($products->sum('quantity'), 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($products->sum('minimum_stock'), 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">{{ number_format($products->sum('price'), 2) }}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">
                        {{ $products->where('quantity', '<=', 'minimum_stock')->count() }} منخفض
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
