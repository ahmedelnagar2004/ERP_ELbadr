@extends('layouts.admin')

@section('title', 'سداد متبقي للعميل: ' . $client->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">
            <i class="fas fa-money-bill-wave ml-2"></i>
            إضافة سداد متبقي للعميل
        </h2>

        <form action="{{ route('admin.payremaining.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Client Information -->
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">
                    <i class="fas fa-user-circle ml-2"></i>
                    معلومات العميل
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
                        <input type="text" value="{{ $client->name }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               disabled>
                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الرصيد الحالي</label>
                        <input type="text" value="{{ number_format($client->balance, 2) }} {{ config('settings.currency_symbol') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100" 
                               disabled>
                        <input type="hidden" name="balance" value="{{ $client->balance }}">
                    </div>
                    <div>
                        <label for="sale_id" class="block text-sm font-medium text-gray-700 mb-1">رقم الفاتورة</label>
                        <select name="sale_id" id="sale_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                required>
                            <option value="">-- اختر الفاتورة --</option>
                            @foreach($client->sales()->where('remaining_amount', '>', 0)->get() as $sale)
                                <option value="{{ $sale->id }}">
                                    فاتورة #{{ $sale->id }} - المتبقي: {{ number_format($sale->remaining_amount, 2) }} {{ config('settings.currency_symbol') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-money-bill-wave ml-2"></i>
                    تفاصيل السداد
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                            مبلغ السداد
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="number" 
                                   name="amount" 
                                   id="amount" 
                                   step="0.01" 
                                   min="0.01"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md p-2 border" 
                                   placeholder="0.00"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">{{ config('settings.currency_symbol') }}</span>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            الرصيد المتاح: <span id="availableBalance">0.00</span> {{ config('settings.currency_symbol') }}
                        </p>
                    </div>

                    <!-- Safe Selection -->
                    <div>
                        <label for="safe_id" class="block text-sm font-medium text-gray-700 mb-1">
                            الخزينة
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="safe_id" id="safe_id" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                            <option value="">-- اختر الخزينة --</option>
                            @foreach($safes as $safe)
                                <option value="{{ $safe->id }}">
                                    {{ $safe->name }} ({{ number_format($safe->balance, 2) }} {{ config('settings.currency_symbol') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                            تاريخ السداد
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="payment_date" 
                               id="payment_date" 
                               value="{{ now()->format('Y-m-d\TH:i') }}"
                               class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                               required>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                            طريقة الدفع
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                required>
                            <option value="cash">نقدي</option>
                            <option value="bank_transfer">تحويل بنكي</option>
                            <option value="check">شيك</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        ملاحظات إضافية
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md p-2"
                              placeholder="أي ملاحظات إضافية حول عملية السداد"></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.clients.show', $client->id) }}" 
                   href="{{ route('admin.clients.show', $client->id) }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-md transition duration-200">
                    رجوع
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save ml-2"></i>
                    حفظ السداد
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const saleSelect = document.getElementById('sale_id');
        const amountInput = document.getElementById('amount');
        const availableBalanceSpan = document.getElementById('availableBalance');
        
        // Update available balance when sale is selected
        saleSelect.addEventListener('change', function() {
            if (this.value) {
                const selectedOption = this.options[this.selectedIndex];
                const remainingAmount = parseFloat(selectedOption.text.match(/المتبقي:\s*([\d.]+)/)[1]);
                availableBalanceSpan.textContent = remainingAmount.toFixed(2);
                // No maximum amount restriction
            } else {
                availableBalanceSpan.textContent = '0.00';
                amountInput.removeAttribute('max');
            }
        });

        // Allow any payment amount
        amountInput.addEventListener('input', function() {
            // No validation - user can enter any amount
        });
    });
</script>
@endpush

@endsection
