@extends('layouts.admin')

@section('title', 'التقارير')
@section('page-title', 'التقارير')
@section('page-subtitle', 'عرض وإدارة التقارير')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">قائمة التقارير</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- تقرير المبيعات -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
            <h4 class="text-xl font-bold mb-2">تقرير المبيعات</h4>
            <p class="text-blue-100 mb-4">عرض إحصائيات المبيعات الشهرية والسنوية</p>
            <a href="{{ route('admin.reports.sales') }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded font-medium hover:bg-blue-50">
                عرض التقرير
            </a>
        </div>

        <!-- تقرير حركة صنف -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
            <h4 class="text-xl font-bold mb-2">تقرير حركة صنف</h4>
            <p class="text-green-100 mb-4">حالة المخزون وحركة الأصناف</p>
            <a href="{{ route('admin.reports.item_transactions') }}" class="inline-block bg-white text-green-600 px-4 py-2 rounded font-medium hover:bg-green-50">
                عرض التقرير
            </a>
        </div>

        <!-- تقرير المنتجات -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300">
            <h4 class="text-xl font-bold mb-2 text-white">تقرير المنتجات</h4>
            <p class="text-white mb-4">بيانات المنتجات والمخزون</p>
            <a href="{{ route('admin.reports.products') }}" class="inline-block bg-white text-red-600 px-4 py-2 rounded font-medium hover:bg-red-50 transition-colors duration-200">
                عرض التقرير
            </a>
        </div>

        <!-- تقرير العملاء -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
            <h4 class="text-xl font-bold mb-2">تقرير العملاء</h4>
            <p class="text-purple-100 mb-4">إحصائيات العملاء والطلبات</p>
            <a href="{{ route('admin.reports.clients') }}" class="inline-block bg-white text-purple-600 px-4 py-2 rounded font-medium hover:bg-purple-50">
                عرض التقرير
            </a>
        </div>

    </div>
    <div class="mt-8">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">التقارير السريعة</h4>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-600">سيتم إضافة المزيد من التقارير قريباً...</p>
        </div>
    </div>
</div>
@endsection