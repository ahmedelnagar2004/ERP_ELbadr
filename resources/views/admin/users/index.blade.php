@extends('layouts.admin')

@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')
@section('page-subtitle', 'عرض وإدارة المشرفين والمستخدمين')

@section('content')
<div class="page-header flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-900">قائمة المستخدمين</h2>
        <p class="text-sm text-gray-500 mt-1">إدارة المستخدمين وتعيين الأدوار</p>
    </div>
    @can('create-users')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">إضافة مستخدم</a>
    @endcan
    </div>

<div class="dashboard-container">
    <div class="dashboard-max-width">
        <div class="widget-card">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg shadow-md">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">الاسم الكامل</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">اسم المستخدم</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">البريد</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">الأدوار</th>
                            <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">الحالة</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider border-b">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->full_name }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->username }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                @foreach($user->roles as $role)
                                    <span class="inline-block bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 px-2 py-1 rounded text-xs mr-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                @if($user->status == 1)
                                    <span class="text-green-600 font-bold">نشط</span>
                                @else
                                    <span class="text-red-600 font-bold">غير نشط</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                <div class="flex gap-2 justify-center">
                                    @can('edit-users')
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">تعديل</a>
                                    @endcan
                                    @can('delete-users')
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('هل أنت متأكد من حذف المستخدم؟')">حذف</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">لا يوجد مستخدمين</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


