@extends('layouts.admin')

@section('title', 'عرض الخزنة - ' . $safe->name)

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-6 border-b border-slate-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-slate-800">{{ $safe->name }}</h3>
                <p class="text-sm text-slate-900 mt-1">
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
                   class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition-colors duration-200">
                    رجوع للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- معلومات أساسية -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="text-sm font-medium text-blue-900">نوع الخزنة</div>
                        <div class="text-lg font-bold text-blue-800">
                            @switch($safe->type)
                                @case(1) محفظة إلكترونية @break
                                @case(2) حساب بنكي @break
                                @case(3) إنستا باي @break
                                @case(4) خزنة داخل الكاشير @break

                                @case(6) خزنه داخل الكاشير @break
                                @default غير محدد @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="text-sm font-medium text-green-900">الرصيد الحالي</div>
                        <div class="text-lg font-bold text-green-800">
                            {{ number_format($safe->balance, 2) }}
                            <span class="text-sm text-green-700">
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

            <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="text-sm font-medium text-purple-900">العملة</div>
                        <div class="text-lg font-bold text-purple-800">
                            @switch($safe->currency)
                                @case('EGP') جنيه مصري @break
                                @case('USD') دولار أمريكي @break
                                @case('EUR') يورو @break
                                @case('SAR') ريال سعودي @break
                                @default {{ $safe->currency }} @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mr-3">
                        <div class="text-sm font-medium text-amber-900">تاريخ الإنشاء</div>
                        <div class="text-lg font-bold text-amber-800">
                            {{ $safe->created_at->format('Y/m/d') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل إضافية -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 border border-slate-200">
                <h4 class="text-lg font-semibold text-slate-800 mb-4">معلومات الخزنة</h4>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-slate-900">اسم الخزنة</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $safe->name }}</dd>
                    </div>
                    @if($safe->description)
                    <div>
                        <dt class="text-sm font-medium text-slate-900">الوصف</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $safe->description }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-slate-900">العملة</dt>
                        <dd class="mt-1 text-sm text-slate-900">
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
                        <dt class="text-sm font-medium text-slate-900">رقم الحساب</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $safe->account_number }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-slate-900">تاريخ الإنشاء</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $safe->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @if($safe->updated_at != $safe->created_at)
                    <div>
                        <dt class="text-sm font-medium text-slate-900">آخر تحديث</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $safe->updated_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <div class="bg-slate-50 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-slate-800">المعاملات الأخيرة</h4>
                    <span class="text-sm text-slate-900 bg-slate-200 px-3 py-1 rounded-full font-medium">
                        {{ $transactions->total() }} معاملة
                    </span>
                </div>

                <!-- بحث برقم الفاتورة -->
                @if($transactions->count() > 0)
                <div class="mb-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="invoiceSearch"
                            placeholder="🔍 ابحث برقم الفاتورة أو رقم المرتجع..." 
                            class="w-full pr-10 pl-4 py-3 bg-white border border-slate-300 text-slate-900 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        >
                    </div>
                </div>
                @endif

                @if($transactions->count() > 0)
                <div class="space-y-4">
                    @foreach($transactions as $transaction)
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <!-- العملية الرئيسية -->
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 {{ $transaction->type == 'deposit' ? 'bg-gradient-to-br from-green-400 to-green-600' : 'bg-gradient-to-br from-red-400 to-red-600' }} rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($transaction->type == 'deposit')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20V4m0 0l-4 4m4-4l4 4"></path>
                                                @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0 0l-4-4m4 4l4-4"></path>
                                                @endif
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mr-4 flex-1">
                                        <div class="flex items-center gap-2">
                                          
                                            @if($transaction->reference_type)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                    @if($transaction->reference_type == 'App\Models\Sale')
                                                        📄 فاتورة
                                                    @elseif($transaction->reference_type == 'App\Models\ReturnModel')
                                                        🔄 مرتجع
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-900 mt-1.5 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $transaction->created_at->format('Y/m/d H:i A') }}
                                        </div>
                                        @if($transaction->description)
                                        <div class="text-xs text-slate-900 mt-2 bg-slate-50 p-2 rounded-lg">
                                            💬 {{ $transaction->description }}
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-left mr-4">
                                    <div class="text-xl font-bold {{ $transaction->type == 'deposit' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transaction->type == 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                                    </div>
                                    <div class="text-xs font-medium text-slate-900 mt-1">
                                        {{ $safe->currency == 'EGP' ? 'ج.م' : ($safe->currency == 'USD' ? '$' : ($safe->currency == 'EUR' ? '€' : 'ر.س')) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل الفاتورة/المرتجع -->
                        @if($transaction->reference)
                        <div class="border-t {{ $transaction->reference_type == 'App\Models\Sale' ? 'border-green-200 bg-gradient-to-r from-green-50 to-emerald-50' : 'border-red-200 bg-gradient-to-r from-red-50 to-pink-50' }} px-4 py-3">
                            @if($transaction->reference_type == 'App\Models\Sale')
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-green-900 mb-2">
                                        📋 فاتورة مبيعات #{{ $transaction->reference->invoice_number ?? $transaction->reference->id }}
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-xs text-green-800 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <strong>العميل:</strong> {{ $transaction->reference->client->name ?? 'غير محدد' }}
                                        </div>
                                        <div class="text-xs text-green-800 flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <strong>الإجمالي:</strong> {{ number_format($transaction->reference->total, 2) }} {{ $safe->currency == 'EGP' ? 'ج.م' : $safe->currency }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left mr-4">
                                    <div class="text-sm font-bold text-green-900">
                                        💰 {{ number_format($transaction->reference->paid_amount, 2) }}
                                    </div>
                                    <div class="text-xs text-green-700">مدفوع</div>
                                    @if($transaction->reference->remaining_amount > 0)
                                    <div class="text-xs text-orange-700 mt-1">
                                        متبقي: <strong>{{ number_format($transaction->reference->remaining_amount, 2) }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @elseif($transaction->reference_type == 'App\Models\ReturnModel')
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="text-sm font-bold text-red-900 mb-2">
                                        🔁 مرتجع #{{ $transaction->reference->return_number ?? $transaction->reference->id }}
                                    </div>
                                    <div class="text-xs text-red-800 flex items-start gap-1.5">
                                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <strong>السبب:</strong> {{ $transaction->reference->reason ?? 'غير محدد' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-left mr-4">
                                    <div class="text-sm font-bold text-red-900">
                                        💸 {{ number_format($transaction->reference->return_amount, 2) }}
                                    </div>
                                    <div class="text-xs text-red-700">قيمة المرتجع</div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- معلومات المستخدم -->
                        @if($transaction->user)
                        <div class="border-t border-slate-200 bg-slate-50 px-4 py-2">
                            <div class="text-xs text-slate-900 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <strong>المستخدم:</strong> {{ $transaction->user->name }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- روابط التصفح -->
                <div class="mt-6">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">لا توجد معاملات</h3>
                    <p class="mt-1 text-sm text-slate-900">لم تتم أي معاملات لهذه الخزنة بعد.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- الرصيد الحالي -->
            <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-blue-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                                </svg>
                            </div>
                            <p class="text-slate-900 text-sm font-semibold">💰 الرصيد الحالي</p>
                        </div>
                        <div class="text-3xl font-bold text-blue-600">
                            {{ number_format($safe->balance, 2) }}
                        </div>
                        <div class="text-sm text-slate-900 mt-1 font-medium">
                            {{ $safe->currency == 'EGP' ? 'جنيه مصري' : ($safe->currency == 'USD' ? 'دولار أمريكي' : ($safe->currency == 'EUR' ? 'يورو' : 'ريال سعودي')) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- إجمالي الإيداعات -->
            <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-green-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 20V4m0 0l-4 4m4-4l4 4"></path>
                                </svg>
                            </div>
                            <p class="text-slate-900 text-sm font-semibold">⬆️ إجمالي الإيداعات</p>
                        </div>
                        <div class="text-3xl font-bold text-green-600">
                            {{ $safe->transactions()->where('type', 'deposit')->count() }}
                        </div>
                        <div class="text-sm text-slate-900 mt-1 font-medium">
                            عملية إيداع
                        </div>
                    </div>
                </div>
            </div>

            <!-- إجمالي السحوبات -->
            <div class="bg-white rounded-xl p-6 shadow-lg border-2 border-red-200 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m0 0l-4-4m4 4l4-4"></path>
                                </svg>
                            </div>
                            <p class="text-slate-900 text-sm font-semibold">⬇️ إجمالي السحوبات</p>
                        </div>
                        <div class="text-3xl font-bold text-red-600">
                            {{ $safe->transactions()->where('type', 'withdrawal')->count() }}
                        </div>
                        <div class="text-sm text-slate-900 mt-1 font-medium">
                            عملية سحب
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('invoiceSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const transactions = document.querySelectorAll('.space-y-4 > div');
            let visibleCount = 0;
            
            transactions.forEach(transaction => {
                const invoiceElement = transaction.querySelector('[class*="فاتورة"], [class*="مرتجع"]');
                let shouldShow = false;
                
                if (searchTerm === '') {
                    shouldShow = true;
                } else {
                    // البحث في نص الفاتورة أو المرتجع
                    const text = transaction.textContent.toLowerCase();
                    
                    // البحث عن رقم الفاتورة
                    const invoiceMatch = text.includes('فاتورة') && text.includes(searchTerm);
                    const returnMatch = text.includes('مرتجع') && text.includes(searchTerm);
                    const numberMatch = text.includes('#' + searchTerm) || text.includes('inv-' + searchTerm);
                    
                    shouldShow = invoiceMatch || returnMatch || numberMatch || text.includes(searchTerm);
                }
                
                if (shouldShow) {
                    transaction.style.display = '';
                    visibleCount++;
                } else {
                    transaction.style.display = 'none';
                }
            });
            
            // عرض رسالة إذا لم يتم العثور على نتائج
            const noResultsDiv = document.getElementById('noSearchResults');
            if (visibleCount === 0 && searchTerm !== '') {
                if (!noResultsDiv) {
                    const container = document.querySelector('.space-y-4');
                    const div = document.createElement('div');
                    div.id = 'noSearchResults';
                    div.className = 'text-center py-8';
                    div.innerHTML = `
                        <svg class="mx-auto h-12 w-12 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">لم يتم العثور على نتائج</h3>
                        <p class="mt-1 text-sm text-slate-900">جرب البحث برقم فاتورة آخر</p>
                    `;
                    container.appendChild(div);
                }
            } else if (noResultsDiv) {
                noResultsDiv.remove();
            }
        });
    }
});
</script>
@endpush

@endsection
