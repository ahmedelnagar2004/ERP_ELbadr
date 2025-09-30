@extends('layouts.admin')

@section('title', 'تعديل فاتورة المبيعات')

@push('styles')
<style>
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .form-section {
        padding: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .form-section:last-child {
        border-bottom: none;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #3b82f6;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-input, .form-select {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .items-section {
        background: #f8fafc;
        padding: 2rem;
    }
    .items-table {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .items-table th {
        background: #f1f5f9;
        padding: 1rem;
        text-align: right;
        font-weight: 600;
        color: #374151;
    }
    .items-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .item-input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    .add-item-btn {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .add-item-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .btn-secondary {
        background: #6b7280;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">تعديل فاتورة المبيعات</h3>
            <p class="text-sm text-gray-600 mt-1">تعديل بيانات الفاتورة والمنتجات</p>
        </div>
        <a href="{{ route('admin.sales.show', $sale->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            عرض الفاتورة
        </a>
    </div>

    @if($sale->status === 'completed')
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-800">لا يمكن تعديل هذه الفاتورة</h3>
                <p class="text-sm text-red-700 mt-1">الفاتورة مكتملة ولا يمكن تعديلها</p>
            </div>
        </div>
    </div>
    @endif

    <form id="saleForm" action="{{ route('admin.sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Invoice Info -->
        <div class="form-container">
            <div class="form-section">
                <h3 class="section-title">معلومات الفاتورة</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">العميل <span class="text-red-500">*</span></label>
                        <select name="client_id" class="form-select" required {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                            <option value="">اختر العميل</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $sale->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} - {{ $client->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">نوع الدفع <span class="text-red-500">*</span></label>
                        <select name="payment_type" class="form-select" required {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                            <option value="cash" {{ $sale->payment_type === 'cash' ? 'selected' : '' }}>نقدي</option>
                            <option value="card" {{ $sale->payment_type === 'card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                            <option value="bank" {{ $sale->payment_type === 'bank' ? 'selected' : '' }}>تحويل بنكي</option>
                            <option value="credit" {{ $sale->payment_type === 'credit' ? 'selected' : '' }}>آجل</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">تاريخ الفاتورة</label>
                        <input type="date" name="order_date" value="{{ $sale->created_at->format('Y-m-d') }}" class="form-input" {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ملاحظات</label>
                        <textarea name="notes" rows="3" class="form-input" placeholder="أي ملاحظات إضافية..." {{ $sale->status === 'completed' ? 'disabled' : '' }}>{{ $sale->notes }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="items-section">
                <h3 class="section-title">المنتجات</h3>

                <div class="items-table">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>السعر</th>
                                <th>الإجمالي</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            @foreach($sale->items as $item)
                            <tr class="item-row" data-item-id="{{ $item->id }}">
                                <td>
                                    <select name="items[][item_id]" class="item-select" required {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                                        <option value="">اختر المنتج</option>
                                        @foreach($items as $itemOption)
                                            <option value="{{ $itemOption->id }}" {{ $item->id == $itemOption->id ? 'selected' : '' }}>
                                                {{ $itemOption->name }} (المخزون: {{ $itemOption->quantity }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="items[][quantity]" min="1" value="{{ $item->pivot->quantity }}" class="item-quantity item-input" required {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                                </td>
                                <td>
                                    <input type="number" name="items[][price]" step="0.01" min="0" value="{{ $item->pivot->unit_price }}" class="item-price item-input" required {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                                </td>
                                <td class="item-total font-semibold">{{ number_format($item->pivot->total_price, 2) }} ر.س</td>
                                <td>
                                    @if($sale->status !== 'completed')
                                    <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($sale->status !== 'completed')
                <div class="mt-4 text-center">
                    <button type="button" id="addItemBtn" class="add-item-btn">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        إضافة منتج
                    </button>
                </div>
                @endif
            </div>

            <!-- Totals Section -->
            <div class="form-section">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">الخصم</label>
                        <input type="number" name="discount" step="0.01" min="0" value="{{ $sale->discount }}" class="form-input" {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">نوع الخصم</label>
                        <select name="discount_type" class="form-select" {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                            <option value="">بدون خصم</option>
                            <option value="fixed" {{ $sale->discount_type === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                            <option value="percentage" {{ $sale->discount_type === 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">تكلفة الشحن</label>
                        <input type="number" name="shipping_cost" step="0.01" min="0" value="{{ $sale->shipping_cost }}" class="form-input" {{ $sale->status === 'completed' ? 'disabled' : '' }}>
                    </div>
                    <div class="form-group">
                        <label class="form-label">المجموع الإجمالي</label>
                        <div class="form-input bg-gray-50 font-semibold">{{ number_format($sale->net_amount, 2) }} ر.س</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($sale->status !== 'completed')
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn-secondary">إلغاء</a>
            <button type="submit" class="btn-primary">حفظ التغييرات</button>
        </div>
        @endif
    </form>
</div>

<!-- Item Template -->
<template id="itemTemplate">
    <tr class="item-row">
        <td>
            <select name="items[][item_id]" class="item-select" required>
                <option value="">اختر المنتج</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-stock="{{ $item->quantity }}">
                        {{ $item->name }} (المخزون: {{ $item->quantity }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[][quantity]" min="1" value="1" class="item-quantity item-input" required>
        </td>
        <td>
            <input type="number" name="items[][price]" step="0.01" min="0" value="0" class="item-price item-input" required>
        </td>
        <td class="item-total font-semibold">0.00 ر.س</td>
        <td>
            <button type="button" class="text-red-600 hover:text-red-900 remove-item-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    </tr>
</template>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate totals when inputs change
    function calculateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = quantity * price;

            row.querySelector('.item-total').textContent = total.toFixed(2) + ' ر.س';
            subtotal += total;
        });

        // Calculate discount
        const discountInput = document.querySelector('input[name="discount"]');
        const discountType = document.querySelector('select[name="discount_type"]').value;
        let discount = parseFloat(discountInput.value) || 0;

        if (discountType === 'percentage') {
            discount = (subtotal * discount) / 100;
        }

        // Calculate shipping
        const shipping = parseFloat(document.querySelector('input[name="shipping_cost"]').value) || 0;

        // Update final total
        const netAmount = subtotal - discount + shipping;
        document.querySelector('.form-input.bg-gray-50').textContent = netAmount.toFixed(2) + ' ر.س';
    }

    // Add event listeners
    document.addEventListener('input', calculateTotals);
    document.addEventListener('change', calculateTotals);

    // Add new item
    document.getElementById('addItemBtn')?.addEventListener('click', function() {
        const template = document.getElementById('itemTemplate');
        const tbody = document.getElementById('itemsTableBody');
        tbody.insertAdjacentHTML('beforeend', template.innerHTML);
    });

    // Remove item
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-item-btn')) {
            e.target.closest('.item-row').remove();
            calculateTotals();
        }
    });

    // Initialize calculations
    calculateTotals();
});
</script>
@endpush
@endsection
