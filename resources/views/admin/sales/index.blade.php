@extends('layouts.admin')

@section('title', 'المبيعات')
@section('page-title', 'إدارة المبيعات')
@section('page-subtitle', 'عرض وإدارة عمليات البيع')

@push('styles')
<style>
    /* Payment Method Styles */
    .payment-method {
        transition: all 0.2s ease;
        min-width: 100px;
        justify-content: center;
    }
    .payment-method i {
        font-size: 1rem;
    }
    .bg-success-light { background-color: rgba(40, 167, 69, 0.1) !important; }
    .bg-info-light { background-color: rgba(23, 162, 184, 0.1) !important; }
    .bg-primary-light { background-color: rgba(13, 110, 253, 0.1) !important; }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.1) !important; }
    .bg-secondary-light { background-color: rgba(108, 117, 125, 0.1) !important; }
    
    /* Action Buttons */
    .btn-action {
        border-radius: 6px;
        padding: 0.35rem 0.75rem;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.2s;
    }
    
    .btn-view {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.2);
    }
    .btn-view:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .btn-edit {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    .btn-edit:hover {
        background-color: #ffc107;
        color: #000;
    }
    
    .btn-delete {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
    }
    .btn-delete:hover {
        background-color: #dc3545;
        color: white;
    }
    
    .btn-print {
        background-color: rgba(111, 66, 193, 0.1);
        color: #6f42c1;
        border: 1px solid rgba(111, 66, 193, 0.2);
    }
    .btn-print:hover {
        background-color: #6f42c1;
        color: white;
    }
    
    /* Table Styling */
    .table {
        --bs-table-bg: transparent;
    }
    .table > :not(:first-child) {
        border-top: none;
    }
    .table > thead > tr > th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 1px;
        padding: 1rem 1.5rem;
    }
    .table > tbody > tr > td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-color: #f1f1f1;
    }
    .table-hover > tbody > tr:hover {
        --bs-table-accent-bg: rgba(0, 0, 0, 0.015);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('admin.sales.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> @lang('admin.COMMON.add_new_sale')
                </a>
                <div>
                    <h3 class="h5 mb-0 text-gray-800 fw-bold">@lang('admin.COMMON.sales')</h3>
                    <p class="text-muted mb-0">@lang('admin.COMMON.sales_description')</p>
                </div>
            </div>
        </div>
    <!-- Sales Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap text-end" style="width: 10%;">@lang('admin.COMMON.invoice_number')</th>
                            <th class="text-nowrap text-end" style="width: 20%;">@lang('admin.COMMON.client')</th>
                            <th class="text-nowrap text-end" style="width: 15%;">@lang('admin.COMMON.created_at')</th>
                            <th class="text-nowrap text-end" style="width: 15%;">@lang('admin.COMMON.total')</th>
                            <th class="text-nowrap text-center" style="width: 15%;">@lang('admin.COMMON.payment_type')</th>
                            <th class="text-nowrap text-center" style="width: 15%;">@lang('admin.COMMON.actions')</th>
                        </tr>
                </thead>
                    <tbody>
                        @forelse($sales ?? [] as $sale)
                            <tr>
                                <td class="text-nowrap text-end">
                                    <div class="fw-semibold">
                                        {{ $sale->invoice_number }}
                                    </div>
                                </td>
                                <td class="text-nowrap text-end">
                                    <div class="fw-medium">{{ $sale->client->name ?? 'عميل غير محدد' }}</div>
                                    <div class="text-muted small">{{ $sale->client->phone ?? '' }}</div>
                                </td>
                                <td class="text-nowrap text-end">
                                    <div class="fw-medium">
                                        {{ $sale->created_at ? $sale->created_at->format('Y-m-d') : '' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $sale->created_at ? $sale->created_at->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td class="text-nowrap text-end fw-bold">
                                    {{ number_format($sale->net_amount, 2) }} ج.م
                                </td>
                                <td class="text-center">
                                    @php
                                        $paymentInfo = [
                                            'cash' => [
                                                'class' => 'success',
                                                'text' => 'نقدي',
                                                'icon' => 'money-bill-wave',
                                                'bg' => 'bg-success-light',
                                                'color' => 'text-success'
                                            ],
                                            'card' => [
                                                'class' => 'info',
                                                'text' => 'بطاقة',
                                                'icon' => 'credit-card',
                                                'bg' => 'bg-info-light',
                                                'color' => 'text-info'
                                            ],
                                            'bank' => [
                                                'class' => 'primary',
                                                'text' => 'تحويل بنكي',
                                                'icon' => 'university',
                                                'bg' => 'bg-primary-light',
                                                'color' => 'text-primary'
                                            ],
                                            'credit' => [
                                                'class' => 'warning',
                                                'text' => 'آجل',
                                                'icon' => 'hand-holding-usd',
                                                'bg' => 'bg-warning-light',
                                                'color' => 'text-warning'
                                            ]
                                        ][$sale->payment_type] ?? [
                                            'class' => 'secondary',
                                            'text' => 'غير محدد',
                                            'icon' => 'question-circle',
                                            'bg' => 'bg-secondary-light',
                                            'color' => 'text-secondary'
                                        ];
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="payment-method {{ $paymentInfo['bg'] }} {{ $paymentInfo['color'] }} d-flex align-items-center px-3 py-2 rounded-pill">
                                            <i class="fas fa-{{ $paymentInfo['icon'] }} me-2"></i>
                                            <span class="fw-medium">{{ $paymentInfo['text'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-sm btn-action btn-view" data-bs-toggle="tooltip" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline">@lang('admin.COMMON.view')</span>
                                        </a>
                                        @can('delete-sales')
                                        <button onclick="confirmDelete({{ $sale->id }}, '{{ $sale->invoice_number }}')" class="btn btn-sm btn-action btn-delete" data-bs-toggle="tooltip" title="حذف الفاتورة">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="d-none d-md-inline">@lang('admin.COMMON.delete')</span>
                                        </button>
                                        @endcan
                                        <a href="{{ route('admin.sales.print', $sale->id) }}" target="_blank" class="btn btn-sm btn-action btn-print" data-bs-toggle="tooltip" title="طباعة الفاتورة">
                                            <i class="fas fa-print"></i>
                                            <span class="d-none d-md-inline">@lang('admin.COMMON.print')</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <h4 class="mt-4 mb-3">لا توجد عمليات بيع</h4>
                                            <p class="text-muted mb-4">لم يتم العثور على أي سجلات للعرض. يمكنك بدء بيع جديد بالنقر على الزر أدناه</p>
                                            <a href="{{ route('admin.sales.create') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-plus-circle me-2"></i> بدء عملية بيع جديدة
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sales->hasPages())
            <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4 border-top">
                <div class="text-muted small mb-2 mb-md-0">
                    <i class="fas fa-database me-1"></i>
                    عرض <span class="fw-bold">{{ $sales->firstItem() }}</span> إلى 
                    <span class="fw-bold">{{ $sales->lastItem() }}</span> من إجمالي 
                    <span class="fw-bold">{{ $sales->total() }}</span> سجل
                </div>
                <div class="pagination-wrap mt-3 mt-md-0">
                    {{ $sales->onEachSide(1)->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 me-3">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">حذف الفاتورة <span id="invoiceNumber" class="fw-bold"></span></h6>
                        <p class="mb-0 text-muted">هل أنت متأكد من رغبتك في حذف هذه الفاتورة؟</p>
                        <p class="mb-0 text-danger">
                            <small><i class="fas fa-exclamation-circle me-1"></i> لا يمكن التراجع عن هذا الإجراء</small>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> إلغاء
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function confirmDelete(saleId, invoiceNumber) {
        document.getElementById('invoiceNumber').textContent = invoiceNumber;
        document.getElementById('deleteForm').action = `{{ route('admin.sales.destroy', ':saleId') }}`.replace(':saleId', saleId);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.hide();
        }
    });
</script>
@endpush
@endsection