@extends('layouts.admin')

@section('title', 'إدارة الوحدات')

@section('header')
<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        إدارة الوحدات
    </h2>
    <a href="{{ route('admin.units.create') }}" class="action-button action-blue">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        إضافة وحدة جديدة
    </a>
</div>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold text-gray-800">قائمة الوحدات</h3>
        <a href="{{ route('admin.units.create') }}" class="btn-primary">إضافة وحدة جديدة</a>
    </div>
    <div class="dashboard-max-width">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="widget-card">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg shadow-md">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">اسم الوحدة</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">عدد المنتجات</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">تاريخ الإنشاء</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($units as $unit)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $unit->name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($unit->status == 1)
                                    <span class="text-green-600 font-bold">نشط</span>
                                @else
                                    <span class="text-red-600 font-bold">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $unit->products_count }} منتج
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $unit->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('admin.units.show', $unit) }}" class="text-blue-600 hover:text-blue-900 font-semibold" style="color: white">عرض</a>
                                    <a href="{{ route('admin.units.edit', $unit) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">تعديل</a>
                                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('هل أنت متأكد من حذف الوحدة؟')">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">لا توجد وحدات</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
