@extends('layouts.admin')

@section('title', 'عرض الخزنة - ' . $safe->name)

@section('content')
<div class="bg-white dark:bg-slate-800 shadow rounded-lg">
    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">{{ $safe->name }}</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    تفاصيل الخزنة والمعاملات
                </p>
            </div>
            <div class="flex space-x-3">
                @can('edit-safes')
                <a href="{{ route('admin.safes.edit', $safe->id) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>تعديل</span>
                </a>
                @endcan
                <a href="{{ route('admin.safes.index') }}"
                   class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-600 dark:hover:bg-slate-500 dark:text-slate-200 rounded-lg transition-colors duration-200">
                    رجوع للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- معلومات أساسية -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-4">
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">نوع الخزنة</div>
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                            @switch($safe->type)
                                @case(1) محفظة إلكترونية @break
                                @case(2) حساب بنكي @break
                                @case(3) إنستا باي @break
                                @case(4) خزنة داخل الكاشير @break
                                @default غير محدد @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-4">
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">الرصيد الحالي</div>
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                            {{ number_format($safe->balance, 2) }}
                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                @switch($safe->currency)
                                    @case('EGP') ج.م @break
                                    @case('USD') $ @break
                                    @case('EUR') € @break
                                    @case('SAR') ر.س @break
                                    @default @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $safe->status ? 'bg-green-500' : 'bg-red-500' }} rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-4">
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">الحالة</div>
                        <div class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $safe->status ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ $safe->status ? 'نشطة' : 'غير نشطة' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل إضافية -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">معلومات الخزنة</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">اسم الخزنة</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $safe->name }}</dd>
                    </div>
                    @if($safe->description)
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">الوصف</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $safe->description }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">العملة</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">
                            @switch($safe->currency)
                                @case('EGP') جنيه مصري @break
                                @case('USD') دولار أمريكي @break
                                @case('EUR') يورو @break
                                @case('SAR') ريال سعودي @break
                                @default {{ $safe->currency }} @break
                            @endswitch
                        </dd>
                    </div>
                    @if($safe->account_number)
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">رقم الحساب</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $safe->account_number }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">تاريخ الإنشاء</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $safe->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @if($safe->updated_at != $safe->created_at)
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">آخر تحديث</dt>
                        <dd class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $safe->updated_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-6">
                <h4 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">المعاملات الأخيرة</h4>
                @if($safe->transactions->count() > 0)
                <div class="space-y-3">
                    @foreach($safe->transactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-slate-800 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 {{ $transaction->type == 'deposit' ? 'bg-green-500' : 'bg-red-500' }} rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $transaction->type == 'deposit' ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mr-3">
                                <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                    {{ $transaction->type == 'deposit' ? 'إيداع' : 'سحب' }}
                                </div>
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ $transaction->created_at->format('Y/m/d') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ $transaction->type == 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                            </div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                {{ $safe->currency == 'EGP' ? 'ج.م' : ($safe->currency == 'USD' ? '$' : ($safe->currency == 'EUR' ? '€' : 'ر.س')) }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        عرض جميع المعاملات
                    </a>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900 dark:text-slate-100">لا توجد معاملات</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">لم تتم أي معاملات لهذه الخزنة بعد.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-lg font-semibold">إحصائيات الخزنة</h4>
                    <p class="text-blue-100 mt-1">نظرة عامة على أداء الخزنة</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ number_format($safe->balance, 2) }}</div>
                    <div class="text-blue-100">{{ $safe->currency == 'EGP' ? 'ج.م' : ($safe->currency == 'USD' ? '$' : ($safe->currency == 'EUR' ? '€' : 'ر.س')) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
