@extends('layouts.admin')

@section('title', __('admin.menu.safes') . ' - ' . __('admin.COMMON.create'))

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
            @lang('admin.COMMON.create') @lang('admin.menu.safes')
        </h2>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.safes.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        اسم الخزنة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        placeholder="أدخل اسم الخزنة"
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        الوصف
                    </label>
                    <textarea name="description" id="description" rows="3" placeholder="أدخل وصف الخزنة (اختياري)"
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Safe Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        نوع الخزنة <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="1" {{ old('type', 1) == '1' ? 'selected' : '' }}>محفظة إلكترونية</option>
                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>حساب بنكي</option>
                        <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>إنستا باي</option>
                        <option value="4" {{ old('type') == '4' ? 'selected' : '' }}>شبكة</option>
                        <option value="5" {{ old('type') == '5' ? 'selected' : '' }}>أجل</option>
                        <option value="6" {{ old('type') == '6' ? 'selected' : '' }}>خزنة داخل الكاشير</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Initial Balance -->
                <div>
                    <label for="balance" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        الرصيد الافتتاحي <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span id="currency-symbol" class="text-slate-500 dark:text-slate-300 sm:text-sm">
                                ج.م
                            </span>
                        </div>
                        <input type="number" name="balance" id="balance" 
                            value="{{ old('balance', 0) }}" step="0.01" min="0" required
                            placeholder="0.00"
                            class="pl-16 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        العملة <span class="text-red-500">*</span>
                    </label>
                    <select name="currency" id="currency" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="EGP" {{ old('currency', 'EGP') == 'EGP' ? 'selected' : '' }}>ج.م (مصري)</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>$ (دولار)</option>
                        <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>ريال (سعودي)</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                 
                

                <!-- Status -->
                <div class="mt-4">
                    <label class="flex items-center">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" id="status" value="1" 
                            {{ old('status', 1) ? 'checked' : '' }}
                            class="rounded border-slate-300 dark:border-slate-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-700">
                        <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                            نشط
                        </span>
                    </label>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch ID (Hidden if not used) -->
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id ?? null }}">
                
                <!-- Account Number (Optional) -->
                <div class="mt-4">
                    <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        رقم الحساب
                    </label>
                    <input type="text" name="account_number" id="account_number" 
                        value="{{ old('account_number') }}"
                        placeholder="أدخل رقم الحساب (اختياري)"
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.safes.index') }}" class="btn btn-secondary">
                    إلغاء
                </a>
                <button type="submit" class="btn btn-primary">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update currency symbol when currency changes
        const currencySelect = document.getElementById('currency');
        const currencySymbol = document.getElementById('currency-symbol');
        
        const currencySymbols = {
            'EGP': 'ج.م',
            'USD': '$',
            'SAR': 'ريال'
        };
        
        function updateCurrencySymbol() {
            const selectedCurrency = currencySelect.value;
            currencySymbol.textContent = currencySymbols[selectedCurrency] || 'ج.م';
        }
        
        currencySelect.addEventListener('change', updateCurrencySymbol);
        
        // Initialize symbol on page load
        updateCurrencySymbol();
    });
</script>
@endpush

@endsection
