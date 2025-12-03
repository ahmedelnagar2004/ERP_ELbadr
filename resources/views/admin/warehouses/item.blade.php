@extends('layouts.admin')

@section('title', 'منتجات المستودع - ' . $warehouse->name)

@push('styles')
<style>
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    .low-stock {
        background-color: #fff3cd;
        border-left: 3px solid #ffc107;
    }
    .info-card {
        height: 100%;
        transition: transform 0.3s;
    }
    .info-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold py-3 mb-0">
                    <span class="text-muted fw-light">إدارة المستودعات / </span> منتجات المستودع
                </h4>
                <a href="{{ route('admin.warehouses.show', $warehouse) }}" class="btn btn-label-secondary">
                    <i class="bx bx-arrow-back me-2"></i> رجوع
                </a>
            </div>
        </div>
    </div>

    <!-- Warehouse Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-1">
                                <i class="bx bx-package me-2"></i>{{ $warehouse->name }}
                            </h5>
                            <p class="mb-0 opacity-75">{{ $warehouse->description }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-white text-primary fs-6">
                                {{ $warehouse->status?->label() ?? 'غير محدد' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-4">
            <div class="card info-card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mb-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-package fs-3"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-1">إجمالي المنتجات</h5>
                    <h3 class="mb-0">{{ $items->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card info-card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mb-3">
                        <span class="avatar-initial rounded-circle bg-label-success">
                            <i class="bx bx-dollar fs-3"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-1">إجمالي القيمة</h5>
                    <h3 class="mb-0">{{ number_format($items->sum(function($item) { return $item->quantity * $item->price; }), 2) }} ج.م</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card info-card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mb-3">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="bx bx-error fs-3"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-1">منتجات منخفضة المخزون</h5>
                    <h3 class="mb-0">{{ $items->filter(function($item) { return $item->quantity <= $item->minimum_stock; })->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">
                <i class="bx bx-list-ul me-2"></i>
                قائمة المنتجات
            </h5>
            <span class="badge bg-white text-primary">
                {{ $items->total() }} منتج
            </span>
        </div>
        <div class="card-datatable table-responsive">
            @if($items->count() > 0)
                <table class="table table-hover border-top">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">#</th>
                            <th>كود المنتج</th>
                            <th>اسم المنتج</th>
                            <th>الفئة</th>
                            <th>الوحدة</th>
                            <th class="text-center">الكمية</th>
                            <th class="text-center">الحد الأدنى</th>
                            <th class="text-center">السعر</th>
                            <th class="text-center">القيمة الإجمالية</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($items as $item)
                        <tr class="{{ $item->quantity <= $item->minimum_stock ? 'low-stock' : '' }}">
                            <td class="text-center fw-medium">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
                            <td>
                                <span class="badge bg-label-secondary" style="color: #000000ff">{{ $item->item_code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-box"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $item->name }}</span>
                                        @if($item->quantity <= $item->minimum_stock)
                                            <i class="bx bx-error-circle text-warning ms-1" 
                                               data-bs-toggle="tooltip" 
                                               title="مخزون منخفض"></i>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info" style="color: #000000ff">
                                    {{ $item->category?->name ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-success" style="color: #000000ff">
                                    {{ $item->unit?->name ?? 'غير محدد' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold {{ $item->quantity <= $item->minimum_stock ? 'text-warning' : 'text-success' }}">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ $item->minimum_stock }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-medium">{{ number_format($item->price, 2) }} ج.م</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-primary">
                                    {{ number_format($item->quantity * $item->price, 2) }} ج.م
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.items.show', $item) }}" 
                                   class="btn btn-sm btn-outline-info rounded-circle p-2"
                                   style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($items->hasPages())
                <div class="d-flex justify-content-between align-items-center px-4 py-3">
                    <div class="text-muted">
                        عرض {{ $items->firstItem() }} إلى {{ $items->lastItem() }} من أصل {{ $items->total() }} منتج
                    </div>
                    <div class="pagination-wrapper">
                        {{ $items->links() }}
                    </div>
                </div>
                @endif
            @else
                <div class="text-center p-5">
                    <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="bx bx-box bx-lg"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">لا توجد منتجات في هذا المستودع</h5>
                    <p class="text-muted mb-4">
                        لم يتم إضافة أي منتجات لهذا المستودع حتى الآن
                    </p>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-2"></i>إضافة منتج جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
