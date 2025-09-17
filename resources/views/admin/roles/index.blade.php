@extends('layouts.admin')

@section('title', 'إدارة الأدوار')

@section('header')
<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        إدارة الأدوار
    </h2>
    @can('create-roles')
    <a href="{{ route('admin.roles.create') }}" class="action-button action-blue">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        إضافة دور جديد
    </a>
    @endcan
</div>
@endsection

@section('content')
<a href="{{ route('admin.roles.create') }}" class="btn-primary">إضافة دور جديد</a></a>
<div class="dashboard-container">
    <div class="dashboard-max-width">
        <div class="widget-card">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg shadow-md">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                اسم الدور
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                الصلاحيات
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $role->permissions->count() }} صلاحية
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2 justify-end">
                                    @can('view-roles')
                                    <a href="{{ route('admin.roles.show', $role) }}" 
                                       class="text-blue-600 hover:text-blue-900">عرض</a>
                                    @endcan
                                    
                                    @can('edit-roles')
                                    <a href="{{ route('admin.roles.edit', $role) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                    @endcan
                                    
                                    @can('delete-roles')
                                    <form action="{{ route('admin.roles.destroy', $role) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('هل أنت متأكد؟')">
                                            حذف
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                لا توجد أدوار مسجلة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection