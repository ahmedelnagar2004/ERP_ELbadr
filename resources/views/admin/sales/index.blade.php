@extends('layouts.admin')

@section('title', 'المبيعات')
@section('page-title', 'إدارة المبيعات')
@section('page-subtitle', 'عرض وإدارة عمليات البيع')

@push('styles')
<style>
    .table-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .table-toolbar { display:flex; gap:12px; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #e2e8f0; flex-wrap: wrap; }
    .toolbar-left { display:flex; gap:10px; align-items:center; }
    .toolbar-input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; min-width:240px; background:#f8fafc; }
    .toolbar-select { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; }
    .table-wrap { max-height: calc(100vh - 320px); overflow:auto; }
    thead.sticky th { position: sticky; top: 0; background:#f8fafc; z-index: 1; }
    tbody tr:nth-child(even) { background:#fafafa; }
    tbody tr:hover { background:#f1f5f9; }
    
    /* Payment Method Styles */
    .payment-method {
        transition: all 0.2s ease;
        min-width: 100px;
        justify-content: center;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .bg-success-light { background-color: rgba(40, 167, 69, 0.1) !important; color: #28a745; }
    .bg-info-light { background-color: rgba(23, 162, 184, 0.1) !important; color: #17a2b8; }
    .bg-primary-light { background-color: rgba(13, 110, 253, 0.1) !important; color: #0d6efd; }
    .bg-warning-light { background-color: rgba(255, 193, 7, 0.1) !important; color: #ffc107; }
    .bg-secondary-light { background-color: rgba(108, 117, 125, 0.1) !important; color: #6c757d; }
    
    /* Action Buttons */
    .btn-action {
        border-radius: 8px;
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        transition: all 0.2s;
        min-width: 80px;
    }
    
    .btn-view { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .btn-view:hover { background-color: #0d6efd; color: white; }
    
    .btn-edit { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .btn-edit:hover { background-color: #ffc107; color: #000; }
    
    .btn-delete { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .btn-delete:hover { background-color: #dc3545; color: white; }
    
    .btn-print { background-color: rgba(111, 66, 193, 0.1); color: #6f42c1; }
    .btn-print:hover { background-color: #6f42c1; color: white; }
    
    /* Status Badges */
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge i {
        font-size: 0.6rem;
    }
    
    /* Table Styling */
    .table {
        --bs-table-bg: transparent;
        margin-bottom: 0;
    }
    
    .table > thead > tr > th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .table > tbody > tr > td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-color: #f1f5f9;
        color: #334155;
    }
    
    .table > tbody > tr:last-child > td {
        border-bottom: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        
        .toolbar-left {
            width: 100%;
        }
        
        .toolbar-input {
            width: 100%;
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid d-flex flex-column min-vh-100">
    <div class="page-header mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">قائمة المبيعات والمرتجعات</h2>
                <p class="text-sm text-gray-500 mt-1">إدارة ومتابعة جميع عمليات البيع والمرتجعات</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.returns.create') }}" class="btn btn-danger">
                    <i class="fas fa-undo me-1"></i> إضافة مرتجع
                </a>
                <a href="{{ route('admin.sales.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> إضافة عملية بيع جديدة
                </a>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.sales.index') }}" 
                   class="{{ !request()->has('type') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    الكل
                    <span class="bg-gray-100 text-gray-900 ml-2 py-0.5 px-2 rounded-full text-xs font-medium">
                        {{ \App\Models\Sale::count() }}
                    </span>
                </a>
                <a href="{{ route('admin.sales.index', ['type' => \App\Enums\SaleStatusEnum::SALE->value]) }}" 
                   class="{{ request('type') === \App\Enums\SaleStatusEnum::SALE->value ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    المبيعات
                    <span class="bg-green-100 text-green-800 ml-2 py-0.5 px-2 rounded-full text-xs font-medium">
                        {{ \App\Models\Sale::where('type', \App\Enums\SaleStatusEnum::SALE->value)->count() }}
                    </span>
                </a>
                <a href="{{ route('admin.sales.index', ['type' => \App\Enums\SaleStatusEnum::RETURN->value]) }}" 
                   class="{{ request('type') === \App\Enums\SaleStatusEnum::RETURN->value ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    المرتجعات
                    <span class="bg-red-100 text-red-800 ml-2 py-0.5 px-2 rounded-full text-xs font-medium">
                        {{ \App\Models\Sale::where('type', \App\Enums\SaleStatusEnum::RETURN->value)->count() }}
                    </span>
                </a>
            </nav>
        </div>
    </div>

    <div class="table-card">
        <div class="table-toolbar">
            <div class="toolbar-left">
                <input id="salesSearch" type="search" class="toolbar-input" placeholder="ابحث برقم الفاتورة أو اسم العميل..." value="{{ request('search') }}">
            </div>
            <div class="flex items-center gap-2">
                <label for="salesSortBy" class="text-sm text-slate-600">ترتيب حسب:</label>
                <select id="salesSortBy" class="toolbar-select">
                    <option value="date_desc">الأحدث أولاً</option>
                    <option value="date_asc">الأقدم أولاً</option>
                    <option value="amount_desc">الأعلى مبيعاً</option>
                    <option value="amount_asc">الأقل مبيعاً</option>
                    <option value="customer">اسم العميل (أ-ي)</option>
                </select>
            </div>
        </div>
        
        <div class="table-wrap">
            <table class="min-w-full w-full">
                <thead class="sticky">
                    <tr>
                        <th class="text-end py-3 px-4">رقم الفاتورة</th>
                        <th class="text-end py-3 px-4">العميل</th>
                        <th class="text-end py-3 px-4">التاريخ</th>
                        <th class="text-end py-3 px-4">الإجمالي</th>
                        <th class="text-center py-3 px-4">حالة الدفع</th>  
                        <th class="text-center py-3 px-4">حالة المبيعة</th>
                        <th class="text-center py-3 px-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    @forelse($sales ?? [] as $sale)
                        <tr data-invoice="{{ $sale->invoice_number }}" data-customer="{{ $sale->client?->name ?? 'عميل محذوف' }}" data-date="{{ $sale->created_at->format('Y-m-d') }}" data-amount="{{ $sale->total_amount }}">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-900">
                                            {{ $sale->invoice_number }}
                                        </div>
                                </td>
                                <td class="text-nowrap text-end">
                                    <div class="fw-medium">{{ $sale->client?->name ?? 'عميل غير محدد' }}</div>
                                    <div class="text-muted small">{{ $sale->client?->phone ?? '' }}</div>
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
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="status-badge {{ $sale->type === \App\Enums\SaleStatusEnum::SALE ? 'bg-success-light text-success' : 'bg-danger-light text-danger' }}">
                                            {{ $sale->type === \App\Enums\SaleStatusEnum::SALE ? 'مبيعة' : 'مرتجع' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-sm btn-action btn-view" data-bs-toggle="tooltip" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline">عرض</span>
                                        </a>
                                        @can('delete-sales')
                                        <button onclick="confirmDelete({{ $sale->id }}, '{{ $sale->invoice_number }}')" class="btn btn-sm btn-action btn-delete" data-bs-toggle="tooltip" title="حذف الفاتورة">
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="d-none d-md-inline">حذف</span>
                                        </button>
                                        @endcan
                                        <a href="{{ route('admin.sales.print', $sale->id) }}" target="_blank" class="btn btn-sm btn-action btn-print" data-bs-toggle="tooltip" title="طباعة الفاتورة">
                                            <i class="fas fa-print"></i>
                                            <span class="d-none d-md-inline">طباعة</span>
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
    <!-- Footer -->
    <footer class="mt-auto py-4 border-t border-gray-200 bg-white">
        <div class="container-fluid">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <span class="text-muted">
                        &copy; {{ date('Y') }} نظام إدارة المبيعات - جميع الحقوق محفوظة
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="text-muted me-3">
                        <i class="fas fa-info-circle me-1"></i> 
                        إصدار {{ config('app.version', '1.0.0') }}
                    </span>
                    <div class="vr mx-2 d-none d-md-block"></div>
                    <a href="#" class="text-decoration-none text-muted me-3">
                        <i class="fas fa-question-circle me-1"></i> المساعدة
                    </a>
                    <a href="#" class="text-decoration-none text-muted">
                        <i class="fas fa-cog me-1"></i> الإعدادات
                    </a>
                </div>
            </div>
        </div>
    </footer>
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Search and sort functionality
        const searchInput = document.getElementById('salesSearch');
        const sortSelect = document.getElementById('salesSortBy');
        const tableBody = document.getElementById('salesTableBody');
        
        if (searchInput && sortSelect && tableBody) {
            // Initialize search from URL parameter if exists
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) {
                searchInput.value = urlParams.get('search');
            }
            
            // Debounce function to limit how often the search runs
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
            
            // Add event listeners with debounce
            searchInput.addEventListener('input', debounce(filterAndSort, 300));
            sortSelect.addEventListener('change', filterAndSort);
            
            function filterAndSort() {
                const searchTerm = searchInput.value.trim().toLowerCase();
                const sortValue = sortSelect.value;
                const rows = Array.from(tableBody.querySelectorAll('tr[data-invoice]'));
                let hasResults = false;
                
                // Filter rows
                rows.forEach(row => {
                    const invoice = row.getAttribute('data-invoice').toLowerCase();
                    const customer = row.getAttribute('data-customer').toLowerCase();
                    
                    if (searchTerm === '' || invoice.includes(searchTerm) || customer.includes(searchTerm)) {
                        row.style.display = '';
                        hasResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                const noResults = tableBody.querySelector('tr:not([data-invoice])');
                if (noResults) {
                    noResults.style.display = hasResults ? 'none' : '';
                }
                
                // Sort visible rows
                const visibleRows = rows.filter(row => row.style.display !== 'none');
                
                visibleRows.sort((a, b) => {
                    switch(sortValue) {
                        case 'date_desc':
                            return b.getAttribute('data-date') - a.getAttribute('data-date');
                        case 'date_asc':
                            return a.getAttribute('data-date') - b.getAttribute('data-date');
                        case 'amount_desc':
                            return parseFloat(b.getAttribute('data-amount')) - parseFloat(a.getAttribute('data-amount'));
                        case 'amount_asc':
                            return parseFloat(a.getAttribute('data-amount')) - parseFloat(b.getAttribute('data-amount'));
                        case 'customer':
                            return a.getAttribute('data-customer').localeCompare(b.getAttribute('data-customer'));
                        default:
                            return 0;
                    }
                });
                
                // Re-append sorted rows
                visibleRows.forEach(row => tableBody.appendChild(row));
                
                // Update URL with search parameter (without page reload)
                const url = new URL(window.location);
                if (searchTerm) {
                    url.searchParams.set('search', searchTerm);
                } else {
                    url.searchParams.delete('search');
                }
                window.history.replaceState({}, '', url);
            }
            
            // Initial sort and filter
            filterAndSort();
        }
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