@extends('layouts.admin')

@section('title', 'إدارة الخزنات')

@section('content')
<div class="bg-white dark:bg-slate-800 shadow rounded-lg">
    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">قائمة الخزنات</h3>
            @can('create-safes')
            <a href="{{ route('admin.safes.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>إضافة خزنة جديدة</span>
            </a>
            @endcan
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.name') 
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.type')
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.status')
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.balance')
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.currency')
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">
                        @lang('admin.COMMON.actions')
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($safes as $safe)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ $safe->name }}
                        </div>
                        @if($safe->description)
                        <div class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ Str::limit($safe->description, 50) }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-900 dark:text-slate-100">
                            @switch($safe->type)
                                @case(1) محفظة إلكترونية @break
                                @case(2) حساب بنكي @break
                                @case(3) إنستا باي @break
                                @case(4) خزنة داخل الكاشير @break
                                @default غير محدد @break
                            @endswitch
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($safe->status)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            نشطة
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            غير نشطة
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                            {{ number_format($safe->balance, 2) }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-900 dark:text-slate-100">
                            @switch($safe->currency)
                                @case('EGP') جنيه مصري @break
                                @case('USD') دولار أمريكي @break
                                @case('EUR') يورو @break
                                @case('SAR') ريال سعودي @break
                                @default {{ $safe->currency }} @break
                            @endswitch
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col space-y-2">
                           
                            @can('edit-safes')
                            <a href="{{ route('admin.safes.edit', $safe->id) }}"
                               class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                تعديل
                            </a>
                            @endcan

                            @can('delete-safes')
                            <form action="{{ route('admin.safes.destroy', $safe->id) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الخزنة؟')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded-md transition-colors duration-200 flex items-center justify-center w-full">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    حذف
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="text-slate-500 dark:text-slate-400">
                            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">لا توجد خزنات</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">ابدأ بإنشاء خزنة جديدة.</p>
                            @can('create-safes')
                            <div class="mt-6">
                                <a href="{{ route('admin.safes.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    إضافة خزنة جديدة
                                </a>
                            </div>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($safes->hasPages())
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
        {{ $safes->links() }}
    </div>
    @endif
</div>

<style>
.btn {
    @apply px-3 py-1 text-sm font-medium rounded-md transition-colors duration-200;
}

.btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white;
}

.btn-danger {
    @apply bg-red-600 hover:bg-red-700 text-white;
}

.btn-secondary {
    @apply bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-600 dark:hover:bg-slate-500 dark:text-slate-200;
}
</style>
@endsection
