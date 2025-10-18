@extends('layouts.admin')

@section('title', 'الخزنة - إنشاء جديد')

@section('content')
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">@lang('admin.COMMON.create_new_safe')</h2>
                    <p class="text-blue-100 text-sm">إنشاء خزنة جديدة في النظام</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.safes.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Basic Information Section -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-lg ml-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">المعلومات الأساسية</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                @lang('admin.COMMON.name') <span class="text-red-500 mr-1">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="@lang('admin.COMMON.enter_safe_name')">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                @lang('admin.COMMON.description')
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="أدخل وصف الخزنة (اختياري)">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Financial Information Section -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-lg ml-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">المعلومات المالية</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label for="type" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                @lang('admin.COMMON.type') <span class="text-red-500 mr-1">*</span>
                            </label>
                            <select name="type" id="type" required
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="1" {{ old('type', 1) == 1 ? 'selected' : '' }}>💳 محفظة إلكترونية</option>
                                <option value="2" {{ old('type', 1) == 2 ? 'selected' : '' }}>🏦 حساب بنكي</option>
                                <option value="3" {{ old('type', 1) == 3 ? 'selected' : '' }}>📱 إنستا باي</option>
                                <option value="4" {{ old('type', 1) == 4 ? 'selected' : '' }}>🌐 شبكه</option>
                                <option value="5" {{ old('type', 1) == 5 ? 'selected' : '' }}>⏰ أجل</option>
                                <option value="6" {{ old('type', 1) == 6 ? 'selected' : '' }}>💰 خزنة داخل الكاشير</option>
                            </select>
                            @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div>
                            <label for="currency" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                @lang('admin.COMMON.currency') <span class="text-red-500 mr-1">*</span>
                            </label>
                            <select name="currency" id="currency" required
                                    class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="EGP" {{ old('currency', 'EGP') == 'EGP' ? 'selected' : '' }}>🇪🇬 جنيه مصري (EGP)</option>
                                <option value="USD" {{ old('currency', 'EGP') == 'USD' ? 'selected' : '' }}>🇺🇸 دولار أمريكي (USD)</option>
                                <option value="EUR" {{ old('currency', 'EGP') == 'EUR' ? 'selected' : '' }}>🇪🇺 يورو (EUR)</option>
                                <option value="SAR" {{ old('currency', 'EGP') == 'SAR' ? 'selected' : '' }}>🇸🇦 ريال سعودي (SAR)</option>
                            </select>
                            @error('currency')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Initial Balance -->
                        <div class="md:col-span-2">
                            <label for="balance" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                @lang('admin.COMMON.initial_balance') <span class="text-red-500 mr-1">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 dark:text-slate-400 sm:text-sm bg-slate-100 dark:bg-slate-700 px-3 py-3 rounded-r-lg border border-l-0 border-slate-300 dark:border-slate-600">
                                        <span id="currency-symbol">ج.م</span>
                                    </span>
                                </div>
                                <input type="number" name="balance" id="balance"
                                       value="{{ old('balance', 0) }}" step="0.01" min="0" required
                                       class="w-full pr-20 pl-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="0.00">
                            </div>
                            @error('balance')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-lg ml-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">معلومات إضافية</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Account Number -->
                        <div>
                            <label for="account_number" class="flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                <svg class="w-4 h-4 ml-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                @lang('admin.COMMON.account_number') <span class="text-slate-400 mr-1">(اختياري)</span>
                            </label>
                            <input type="text" name="account_number" id="account_number"
                                   value="{{ old('account_number') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   placeholder="أدخل رقم الحساب إن وجد">
                            @error('account_number')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="flex items-center">
                            <label class="flex items-center cursor-pointer">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status" value="1"
                                       {{ old('status', 1) ? 'checked' : '' }}
                                       class="sr-only">
                                <div class="relative">
                                    <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 rounded-full transition-colors duration-200 ease-in-out
                                                {{ old('status', 1) ? 'bg-green-500' : '' }}"></div>
                                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out
                                                {{ old('status', 1) ? 'translate-x-5' : 'translate-x-0' }}"></div>
                                </div>
                                <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">
                                    نشطة
                                </span>
                            </label>
                            @error('status')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id ?? null }}">

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-slate-200 dark:border-slate-700">
                    <a href="{{ route('admin.safes.index') }}"
                       class="inline-flex items-center px-6 py-3 border border-slate-300 dark:border-slate-600 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        @lang('admin.COMMON.cancel')
                    </a>

                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-medium rounded-lg shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            @lang('admin.COMMON.create')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            /* تحسين المظهر العام */
            .form-section {
                @apply bg-slate-50 dark:bg-slate-700/50 rounded-lg p-6 border border-slate-200 dark:border-slate-600;
            }

            .section-header {
                @apply flex items-center mb-4;
            }

            .section-icon {
                @apply bg-opacity-20 p-2 rounded-lg ml-3;
            }

            /* تحسين الحقول */
            input[type="text"],
            input[type="number"],
            input[type="password"],
            input[type="email"],
            textarea,
            select {
                @apply bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 border-slate-300 dark:border-slate-600;
                @apply focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
                @apply transition-all duration-200;
            }

            /* التأكد من أن النص في الحقول يكون أسود دائماً */
            input[type="text"],
            input[type="number"],
            input[type="password"],
            input[type="email"],
            textarea,
            select {
                color: #000000 !important;
            }

            /* حتى في الوضع الليلي */
            .dark input[type="text"],
            .dark input[type="number"],
            .dark input[type="password"],
            .dark input[type="email"],
            .dark textarea,
            .dark select {
                color: #000000 !important;
            }

            /* تحسين أزرار التبديل */
            .toggle-switch {
                @apply relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-200 ease-in-out;
            }

            .toggle-switch.active {
                @apply bg-green-500;
            }

            .toggle-slider {
                @apply absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out;
            }

            .toggle-slider.active {
                @apply translate-x-5;
            }

            /* تحسين الأزرار */
            .btn-primary {
                @apply bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800;
                @apply text-white font-medium px-6 py-3 rounded-lg shadow-lg hover:shadow-xl;
                @apply focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
                @apply transition-all duration-200 transform hover:scale-105;
            }

            .btn-secondary {
                @apply border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300;
                @apply bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700;
                @apply font-medium px-6 py-3 rounded-lg;
                @apply focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
                @apply transition-all duration-200;
            }

            /* تحسين رسائل الخطأ */
            .error-message {
                @apply mt-2 text-sm text-red-600 dark:text-red-400 flex items-center;
            }

            .error-icon {
                @apply w-4 h-4 ml-1;
            }

            /* تحسين العلامات */
            .form-label {
                @apply flex items-center text-sm font-medium text-slate-700 dark:text-slate-300 mb-2;
            }

            .label-icon {
                @apply w-4 h-4 ml-2 text-slate-400;
            }

            /* تحسين العملة */
            .currency-badge {
                @apply text-slate-500 dark:text-slate-400 sm:text-sm bg-slate-100 dark:bg-slate-700 px-3 py-3 rounded-r-lg border border-l-0 border-slate-300 dark:border-slate-600;
            }

            /* تأثيرات بصرية */
            .form-card {
                @apply bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden;
            }

            .gradient-header {
                @apply bg-gradient-to-r from-blue-600 to-blue-700;
            }

            /* تحسين الشبكة المتجاوبة */
            @media (min-width: 768px) {
                .form-grid {
                    @apply grid-cols-2 gap-6;
                }
            }

            /* تحسين الظلال والحدود */
            .card-shadow {
                @apply shadow-lg;
            }

            .card-border {
                @apply border border-slate-200 dark:border-slate-700;
            }

            /* تحسين الرسوم المتحركة */
            * {
                @apply transition-colors duration-200;
            }

            /* تحسين الوضع المظلم */
            .dark .form-section {
                @apply bg-slate-700/50 border-slate-600;
            }

            .dark .section-header {
                @apply text-slate-200;
            }

            /* تحسين النصوص */
            .section-title {
                @apply text-lg font-semibold text-slate-800 dark:text-slate-200;
            }

            .form-label-text {
                @apply text-sm font-medium text-slate-700 dark:text-slate-300;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // تحسين وظيفة العملة
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

                // تحسين زر التبديل للحالة
                const statusCheckbox = document.getElementById('status');
                const statusToggle = document.querySelector('.toggle-switch');
                const statusSlider = document.querySelector('.toggle-slider');

                if (statusCheckbox && statusToggle && statusSlider) {
                    function updateToggleStatus() {
                        if (statusCheckbox.checked) {
                            statusToggle.classList.add('active');
                            statusSlider.classList.add('active');
                        } else {
                            statusToggle.classList.remove('active');
                            statusSlider.classList.remove('active');
                        }
                    }

                    updateToggleStatus();
                    statusCheckbox.addEventListener('change', updateToggleStatus);
                }

                // تحسين تأثيرات التركيز والتحقق من الصحة
                const formInputs = document.querySelectorAll('input, select, textarea');
                formInputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
                    });

                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
                    });
                });

                // تحسين تجربة المستخدم للحقول المطلوبة
                const requiredInputs = document.querySelectorAll('input[required], select[required]');
                requiredInputs.forEach(input => {
                    const label = input.closest('div').querySelector('label');
                    if (label && input.value.trim() === '') {
                        label.classList.add('text-red-600', 'dark:text-red-400');
                    }

                    input.addEventListener('input', function() {
                        if (this.value.trim() !== '') {
                            label.classList.remove('text-red-600', 'dark:text-red-400');
                            label.classList.add('text-green-600', 'dark:text-green-400');
                        } else {
                            label.classList.remove('text-green-600', 'dark:text-green-400');
                            label.classList.add('text-red-600', 'dark:text-red-400');
                        }
                    });
                });

                // تحسين التنقل بين الحقول بالضغط على Enter
                formInputs.forEach((input, index) => {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            const nextInput = formInputs[index + 1];
                            if (nextInput) {
                                nextInput.focus();
                            }
                        }
                    });
                });

                // إضافة تأثيرات بصرية للأزرار عند التمرير
                const buttons = document.querySelectorAll('button, a[href]');
                buttons.forEach(button => {
                    button.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-1px)';
                    });

                    button.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                    });
                });
            });
        </script>
    @endpush

@endsection
