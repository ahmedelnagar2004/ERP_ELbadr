@extends('layouts.admin')

@section('title', 'الخزنة - إنشاء جديد')

@section('content')
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                @lang('admin.COMMON.create_new_safe')
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.safes.store') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">

                            @lang('admin.COMMON.name') <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                               placeholder="@lang('admin.COMMON.enter_safe_name')">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.description')
                        </label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                  placeholder="أدخل وصف الخزنة (اختياري)">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.type')  <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="1" {{ old('type', 1) == 1 ? 'selected' : '' }}>محفظة إلكترونية</option>
                            <option value="2" {{ old('type', 1) == 2 ? 'selected' : '' }}>حساب بنكي</option>
                            <option value="3" {{ old('type', 1) == 3 ? 'selected' : '' }}>إنستا باي</option>
                            <option value="5" {{ old('type', 1) == 5 ? 'selected' : '' }}>شبكه</option>
                            <option value="4" {{ old('type', 1) == 4 ? 'selected' : '' }}>خزنة داخل الكاشير</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Initial Balance -->
                    <div>
                        <label for="balance" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.initial_balance') <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 dark:text-slate-300 sm:text-sm">
                                <span id="currency-symbol">ج.م</span>
                            </span>
                            </div>
                            <input type="number" name="balance" id="balance"
                                   value="{{ old('balance', 0) }}" step="0.01" min="0" required
                                   class="pl-16 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="0.00">
                        </div>
                        @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.currency') <span class="text-red-500">*</span>
                        </label>
                        <select name="currency" id="currency" required
                                class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="EGP" {{ old('currency', 'EGP') == 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                            <option value="USD" {{ old('currency', 'EGP') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                            <option value="EUR" {{ old('currency', 'EGP') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                            <option value="SAR" {{ old('currency', 'EGP') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                        </select>
                        @error('currency')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <!-- Status -->
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" value="1"
                                       {{ old('status', 1) ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                            نشطة
                        </span>
                            </label>
                            @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id ?? null }}">

                        <!-- Account Number (Optional) -->
                        <div class="mt-4">
                            <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                @lang('admin.COMMON.account_number') (إن وجد)
                            </label>
                            <input type="text" name="account_number" id="account_number"
                                   value="{{ old('account_number') }}"
                                   class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                   placeholder="أدخل رقم الحساب إن وجد">
                            @error('account_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.safes.index') }}" class="btn btn-secondary">
                            @lang('admin.COMMON.cancel')
                        </a>
                        <button type="submit" class="btn btn-primary">
                          @lang('admin.COMMON.create')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            /* جعل الحقول بيضاء والنص داخلها أسود */
            input[type="text"],
            input[type="number"],
            input[type="password"],
            input[type="email"],
            textarea,
            select {
                background-color: #ffffff !important;
                color: #000000 !important;
                border-color: #d1d5db !important;
            }

            /* حتى في الوضع الليلي */
            .dark input[type="text"],
            .dark input[type="number"],
            .dark input[type="password"],
            .dark input[type="email"],
            .dark textarea,
            .dark select {
                background-color: #ffffff !important;
                color: #000000 !important;
            }

            /* لون النص داخل placeholder */
            input::placeholder,
            textarea::placeholder {
                color: #6b7280;
            }

            /* تأثير عند التركيز */
            input:focus,
            textarea:focus,
            select:focus {
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 1px #3b82f6;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const currencySelect = document.getElementById('currency');
                const currencySymbolSpan = document.getElementById('currency-symbol');

                if (currencySelect && currencySymbolSpan) {
                    function updateCurrencySymbol() {
                        const selectedCurrency = currencySelect.value;
                        switch(selectedCurrency) {
                            case 'EGP':
                                currencySymbolSpan.textContent = 'ج.م';
                                break;
                            case 'USD':
                                currencySymbolSpan.textContent = '$';
                                break;
                            case 'EUR':
                                currencySymbolSpan.textContent = '€';
                                break;
                            case 'SAR':
                                currencySymbolSpan.textContent = 'ر.س';
                                break;
                            default:
                                currencySymbolSpan.textContent = 'ج.م';
                        }
                    }

                    updateCurrencySymbol();
                    currencySelect.addEventListener('change', updateCurrencySymbol);
                }
            });
        </script>
    @endpush

@endsection
