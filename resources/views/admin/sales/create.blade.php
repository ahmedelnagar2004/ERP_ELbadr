@extends('layouts.admin')

@section('title', 'إنشاء فاتورة جديدة')
@section('page-title', 'إنشاء فاتورة')
@section('page-subtitle', 'إضافة فاتورة جديدة للنظام')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
    }
    .discount-field {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="card shadow-lg rounded-lg p-4">
    <form action="{{ route('admin.sales.store') }}" method="POST" id="sale-form">
        @csrf

        <!-- اختيار العميل -->
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('admin.COMMON.search')</label>
            <select name="client_id" id="client-search" class="form-control" required>
                <option value="">-- @lang('admin.COMMON.search') --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->display_name }} - {{ $client->phone ?? '' }}</option>
                @endforeach
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">@lang('admin.COMMON.safe')</label>
                <select name="safe_id" id="safe_id" class="form-control" required>
                    <option value="">-- @lang('admin.COMMON.safe') --</option>
                    @foreach($safes as $safe)
                        <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- طريقة الدفع -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">@lang('admin.COMMON.payment_type')</label>
                <select name="payment_type" id="payment_type" class="form-control" required>
                    <option value="cash">@lang('admin.COMMON.cash')</option>
                    <option value="card">@lang('admin.COMMON.credit_card')</option>
                    <option value="credit">@lang('admin.COMMON.credit')</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">@lang('admin.COMMON.paid_amount')</label>
                <div class="input-group">
                    <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="0" min="0" step="0.01" disabled>
                    <span class="input-group-text">ج.م</span>
                </div>
                <small class="text-muted" id="remaining-amount-hint">@lang('admin.COMMON.remaining_amount') : <span id="remaining-amount">0.00</span> ج.م</small>
            </div>
        </div>
        <!-- العناصر -->
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('admin.COMMON.items')</label>
            <table class="table table-bordered" id="items-table">
                <thead class="table-light">
                    <tr>
                        <th>@lang('admin.COMMON.item')</th>
                        <th>@lang('admin.COMMON.quantity')</th>
                        <th>@lang('admin.COMMON.price')</th>
                        <th>@lang('admin.COMMON.total')</th>
                        <th>
                            <button type="button" class="btn btn-success btn-sm" id="add-item">
                                + @lang('admin.COMMON.add')
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select name="items[0][item_id]" class="form-control item-select" required>
                                <option value="">-- اختر المنتج --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}"
                                            data-price="{{ $item->price }}"
                                            data-quantity="{{ $item->quantity }}"
                                            data-code="{{ $item->code ?? '' }}">
                                        {{ $item->name }}  - متاح: {{ $item->quantity }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="items[0][quantity]" class="form-control quantity" min="1" value="1"></td>
                        <td><input type="number" step="0.01" name="items[0][price]" class="form-control price" readonly></td>
                        <td class="total">0</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- الخصم والشحن -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">@lang('admin.COMMON.discount_type')</label>
                <select name="discount_type" id="discount_type" class="form-control">
                    <option value="">@lang('admin.COMMON.discount_type')</option>
                    <option value="fixed">@lang('admin.COMMON.fixed')</option>
                    <option value="percentage">@lang('admin.COMMON.percentage')</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">@lang('admin.COMMON.discount_value')</label>
                <div class="input-group">
                    <input type="number" name="discount" id="discount" class="form-control discount-field" value="0" min="0" step="0.01" disabled>
                    <span class="input-group-text discount-percentage" style="display: none;">%</span>
                </div>
                <small class="text-muted" id="discount-hint"></small>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">@lang('admin.COMMON.shipping_cost')</label>
                <input type="number" name="shipping_cost" id="shipping_cost" class="form-control" value="0" min="0" step="0.01">
            </div>
        </div>

        <!-- المجموع -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>@lang('admin.COMMON.subtotal'): <span id="subtotal">0.00</span> ج.م</h5>
                    </div>
                    <div class="col-md-4">
                        <h5>@lang('admin.COMMON.discount_value'): <span id="discount-amount">0.00</span> ج.م</h5>
                    </div>
                    <div class="col-md-4">
                        <h5>@lang('admin.COMMON.shipping_cost'): <span id="shipping-amount">0.00</span> ج.م</h5>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h4 class="border-top pt-2">@lang('admin.COMMON.net_total'): <span id="net_total">0.00</span> ج.م</h4>
                        <div id="payment-summary" class="mt-2" style="display: none;">
                            <div class="d-flex justify-content-between">
                                <span>المبلغ المدفوع:</span>
                                <span id="paid-amount-display">0.00 ج.م</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>المبلغ المتبقي:</span>
                                <span id="remaining-amount-display">0.00 ج.م</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ملاحظات -->
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('admin.COMMON.notes')</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">@lang('admin.COMMON.save_invoice')</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for client search
    $('#client-search').select2({
        placeholder: 'ابحث عن عميل...',
        allowClear: true,
        language: {
            noResults: function() {
                return "لا توجد نتائج";
            },
            searching: function() {
                return "جاري البحث...";
            }
        },
        width: '100%'
    });

    // Initialize Select2 for product search
    $('.item-select').select2({
        placeholder: 'ابحث عن منتج...',
        allowClear: true,
        language: {
            noResults: function() {
                return "لا توجد نتائج";
            },
            searching: function() {
                return "جاري البحث...";
            }
        },
        width: '100%'
    });

    // Global row index for new items
    let rowIndex = 1;

    // Calculate totals function
    function calculateTotals() {
        let subtotal = 0;
        $('#items-table tbody tr').each(function() {
            const qty = parseFloat($(this).find('.quantity').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) || 0;
            const total = qty * price;
            $(this).find('.total').text(total.toFixed(2));
            subtotal += total;
        });

        let discount = parseFloat($('#discount').val()) || 0;
        const discountType = $('#discount_type').val();

        if (discountType === 'percentage') {
            discount = (subtotal * discount) / 100;
            $('.discount-percentage').show();
            $('#discount-hint').text('أقصى خصم: ' + (parseFloat($('#discount').val()) || 0) + '% = ' + discount.toFixed(2) + ' جنيه');
        } else if (discountType === 'fixed') {
            $('.discount-percentage').hide();
            $('#discount-hint').text('خصم بقيمة: ' + discount.toFixed(2) + ' جنيه');
        } else {
            $('.discount-percentage').hide();
            $('#discount-hint').text('');
            discount = 0;
        }

        const shipping = parseFloat($('#shipping_cost').val()) || 0;
        const net = subtotal - discount + shipping;

        // Update display
        $('#subtotal').text(subtotal.toFixed(2));
        $('#discount-amount').text(discount.toFixed(2));
        $('#shipping-amount').text(shipping.toFixed(2));
        $('#net_total').text(net.toFixed(2));

        // Update paid amount if not credit
        if ($('#payment_type').val() !== 'credit') {
            $('#paid_amount').val(net.toFixed(2));
        } else {
            // For credit, ensure paid amount doesn't exceed net total
            const paidAmount = parseFloat($('#paid_amount').val()) || 0;
            if (paidAmount > net) {
                $('#paid_amount').val(net.toFixed(2));
            }
        }

        // Update payment summary
        updatePaymentSummary();
    }

    // Add new item row
    $('#add-item').on('click', function() {
        const newRow = `
            <tr>
                <td>
                    <select name="items[${rowIndex}][item_id]" class="form-control item-select" required>
                        <option value="">-- اختر المنتج --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}"
                                    data-price="{{ $item->price }}"
                                    data-quantity="{{ $item->quantity }}"
                                    data-code="{{ $item->code ?? '' }}">
                                {{ $item->name }} ({{ $item->code ?? 'بدون كود' }}) - متاح: {{ $item->quantity }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control quantity" min="1" value="1"></td>
                <td><input type="number" step="0.01" name="items[${rowIndex}][price]" class="form-control price" readonly></td>
                <td class="total">0</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
            </tr>
        `;

        $('#items-table tbody').append(newRow);

        // Initialize Select2 for the new row
        $('.item-select').last().select2({
            placeholder: 'ابحث عن منتج...',
            allowClear: true,
            language: {
                noResults: function() {
                    return "لا توجد نتائج";
                },
                searching: function() {
                    return "جاري البحث...";
                }
            },
            width: '100%'
        });

        rowIndex++;
    });

    // Product search functionality
    $('#product-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();

        if (searchTerm.length > 0) {
            $('.item-select').each(function() {
                const $select = $(this);
                let found = false;

                $('option', $select).each(function() {
                    const text = $(this).text().toLowerCase();
                    const code = $(this).data('code') || '';
                    const isMatch = text.includes(searchTerm) || code.toString().includes(searchTerm);

                    if (isMatch) {
                        found = true;
                        $select.val($(this).val()).trigger('change');
                        return false; // Exit the loop once a match is found
                    }
                });

                if (found) {
                    $select.trigger('change');
                    return false; // Exit the loop if a match is found in any select
                }
            });
        }
    });

    // Handle item selection
    $(document).on('change', '.item-select', function() {
        const selectedOption = $(this).find('option:selected');
        const price = selectedOption.data('price') || 0;
        const availableQty = selectedOption.data('quantity') || 0;

        $(this).closest('tr').find('.price').val(price);
        $(this).closest('tr').find('.quantity').attr('max', availableQty);

        calculateTotals();
    });

    // Handle quantity changes
    $(document).on('input', '.quantity', function() {
        const $row = $(this).closest('tr');
        const selectedOption = $row.find('.item-select option:selected');
        const availableQty = selectedOption.data('quantity') || 0;
        const enteredQty = parseInt($(this).val()) || 0;

        if (enteredQty > availableQty) {
            $(this).val(availableQty);
            alert('الكمية المطلوبة غير متوفرة. الحد الأقصى المتاح: ' + availableQty);
        }

        calculateTotals();
    });

    // Handle discount type changes
    $('#discount_type').on('change', function() {
        const discountType = $(this).val();
        const $discountField = $('#discount');

        if (discountType === '') {
            $discountField.prop('disabled', true).val(0);
            $('.discount-field').hide();
        } else {
            $discountField.prop('disabled', false).val('');
            $('.discount-field').show();

            if (discountType === 'percentage') {
                $('.discount-percentage').show();
                $discountField.attr('max', '100');
            } else {
                $('.discount-percentage').hide();
                $discountField.removeAttr('max');
            }
        }

        calculateTotals();
    });

    // Handle shipping cost changes
    $(document).on('input', '#shipping_cost', function() {
        calculateTotals();
    });

    // Handle payment type changes
    $('#payment_type').on('change', function() {
        const paymentType = $(this).val();
        const $paidAmountInput = $('#paid_amount');

        if (paymentType === 'credit') {
            $paidAmountInput.prop('disabled', false).val('0').css('background-color', '#fff');
            $paidAmountInput.trigger('focus');
        } else {
            $paidAmountInput.prop('disabled', true).val($('#net_total').text()).css('background-color', '#f8f9fa');
        }

        calculateTotals();
    });

    // Handle paid amount changes
    $(document).on('input', '#paid_amount', function() {
        updatePaymentSummary();
    });

    // Update payment summary
    function updatePaymentSummary() {
        const netTotal = parseFloat($('#net_total').text()) || 0;
        const paidAmount = parseFloat($('#paid_amount').val()) || 0;
        const remainingAmount = Math.max(0, netTotal - paidAmount).toFixed(2);

        $('#remaining-amount').text(remainingAmount);
        $('#paid-amount-display').text(paidAmount.toFixed(2) + ' ج.م');
        $('#remaining-amount-display').text(remainingAmount + ' ج.م');

        // Show/hide payment summary based on payment type
        if ($('#payment_type').val() === 'credit') {
            $('#payment-summary').show();
        } else {
            $('#payment-summary').hide();
        }
    }

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        if ($('#items-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotals();
        } else {
            alert('يجب أن تحتوي الفاتورة على منتج واحد على الأقل');
        }
    });

    // Initialize field states
    $('#discount_type').trigger('change');
    $('#payment_type').trigger('change');

    // Style disabled inputs
    $('input:disabled').css('background-color', '#f8f9fa');
});
</script>
@endpush
