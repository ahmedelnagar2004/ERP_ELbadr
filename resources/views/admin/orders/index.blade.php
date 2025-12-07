@extends('layouts.admin')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')
@section('page-subtitle', 'عرض وإدارة طلبات العملاء')

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
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-confirmed { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
    .status-processing { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .status-shipped { background-color: rgba(111, 66, 193, 0.1); color: #6f42c1; }
    .status-delivered { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    
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
</style>
@endpush

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-card">
        <div class="table-toolbar">
            <div class="toolbar-left">
                <input id="ordersSearch" type="search" class="toolbar-input" placeholder="بحث بالطلب أو العميل...">
            </div>
            <div class="flex items-center gap-2">
                <label for="statusFilter" class="text-sm text-slate-600">الحالة:</label>
                <select id="statusFilter" class="toolbar-select">
                    <option value="">الكل</option>
                    <option value="1">تم التأكيد</option>
                    <option value="2">قيد التجهيز</option>
                    <option value="3">تم الشحن</option>
                    <option value="4">تم التسليم</option>
                </select>
            </div>
        </div>
        
        <div class="table-wrap">
            <table class="min-w-full w-full">
                <thead class="sticky">
                    <tr>
                        <th class="text-end py-3 px-4">#</th>
                        <th class="text-end py-3 px-4">العميل</th>
                        <th class="text-end py-3 px-4">التاريخ</th>
                        <th class="text-end py-3 px-4">المبلغ الإجمالي</th>
                        <th class="text-center py-3 px-4">الحالة</th>
                        <th class="text-center py-3 px-4">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    @forelse($orders as $order)
                        <tr data-order-id="{{ $order->id }}" data-status="{{ $order->status }}" data-client="{{ $order->client->name ?? 'عميل محذوف' }}">
                            <td class="py-3 px-4">
                                <div class="font-semibold text-gray-900">#{{ $order->id }}</div>
                            </td>
                            <td class="text-nowrap text-end">
                                <div class="fw-medium">{{ $order->client->name ?? 'عميل محذوف' }}</div>
                                <div class="text-muted small">{{ $order->client->phone ?? '' }}</div>
                            </td>
                            <td class="text-nowrap text-end">
                                <div class="fw-medium">{{ $order->created_at->format('Y-m-d') }}</div>
                                <div class="text-muted small">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="text-nowrap text-end fw-bold">
                                {{ number_format($order->total_price, 2) }} ج.م
                            </td>
                            <td class="text-center">
                                @php
                                    $statusInfo = [
                                        1 => ['class' => 'status-confirmed', 'text' => 'تم التأكيد', 'icon' => 'check'],
                                        2 => ['class' => 'status-processing', 'text' => 'قيد التجهيز', 'icon' => 'cog'],
                                        3 => ['class' => 'status-shipped', 'text' => 'تم الشحن', 'icon' => 'shipping-fast'],
                                        4 => ['class' => 'status-delivered', 'text' => 'تم التسليم', 'icon' => 'check-circle'],
                                    ][$order->status] ?? ['class' => 'bg-secondary-light', 'text' => 'غير محدد', 'icon' => 'question'];
                                @endphp
                                <div class="status-badge {{ $statusInfo['class'] }}">
                                    <i class="fas fa-{{ $statusInfo['icon'] }}"></i>
                                    {{ $statusInfo['text'] }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-action btn-view">
                                        <i class="fas fa-eye"></i>
                                        <span class="d-none d-md-inline">عرض</span>
                                    </a>
                                    @if($order->status != 4)
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-action btn-edit">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-md-inline">تعديل</span>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h4 class="mt-4 mb-3">لا توجد طلبات</h4>
                                    <p class="text-muted">لم يتم تسجيل أي طلبات بعد</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center py-3 px-4 border-top">
            <div class="text-muted small">
                عرض {{ $orders->firstItem() }} إلى {{ $orders->lastItem() }} من إجمالي {{ $orders->total() }} سجل
            </div>
            <div>{{ $orders->links() }}</div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('ordersSearch');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('ordersTableBody');
    
    function filterOrders() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const rows = tableBody.querySelectorAll('tr[data-order-id]');
        
        rows.forEach(row => {
            const client = row.getAttribute('data-client').toLowerCase();
            const orderId = row.getAttribute('data-order-id');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = searchTerm === '' || client.includes(searchTerm) || orderId.includes(searchTerm);
            const matchesStatus = statusValue === '' || status === statusValue;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }
    
    searchInput.addEventListener('input', filterOrders);
    statusFilter.addEventListener('change', filterOrders);
});
</script>
@endpush
@endsection