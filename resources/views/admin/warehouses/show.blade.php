@extends('layouts.admin')

@section('title', 'تفاصيل المستودع - ' . $warehouse->name)

@push('styles')
<style>
    .card-icon {
        font-size: 2rem;
        margin-bottom: 1rem;
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
                    <span class="text-muted fw-light">إدارة المستودعات /</span> تفاصيل المستودع
                </h4>
                <div>
                    <a href="{{ route('admin.warehouses.index') }}" class="btn btn-label-secondary">
                        <i class="bx bx-arrow-back me-2"></i> رجوع
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        
        <div class="col-md-4 mb-4">
            <div class="card info-card">
                <div class="card-body text-center">
                    <div class="card-icon text-success">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <h5 class="card-title mb-1">الحالة</h5>
                    <h4 class="mb-0">
                        <span class="badge bg-{{ $warehouse->status?->color() ?? 'secondary' }} fs-6">
                            {{ $warehouse->status?->label() ?? 'غير محدد' }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card info-card">
                <div class="card-body text-center">
                    <div class="card-icon text-info">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <h5 class="card-title mb-1">تاريخ الإنشاء</h5>
                    <h4 class="mb-0">{{ $warehouse->created_at->format('Y-m-d') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Warehouse Info -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        معلومات المستودع
                    </h5>
                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">اسم المستودع</label>
                            <p class="fw-bold h5 mb-3">{{ $warehouse->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted">الحالة</label>
                            <p class="mb-3">
                                <span class="badge bg-{{ $warehouse->status?->color() ?? 'secondary' }} fs-6">
                                    {{ $warehouse->status?->label() ?? 'غير محدد' }}
                                </span>
                            </p>
                        </div>
                       
                        
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">الوصف</label>
                            <p class="mb-0">{{ $warehouse->description ?? 'لا يوجد وصف' }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">
                                <i class="bx bx-time me-1"></i> 
                                تم الإنشاء في {{ $warehouse->created_at->format('Y-m-d h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="text-muted small">
                                <i class="bx bx-edit me-1"></i>
                                آخر تحديث {{ $warehouse->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Withdrawal Transactions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bx bx-transfer-alt me-2"></i>
                        سجل حركات السحب من المستودع
                    </h5>
                    <span class="badge bg-white text-primary">
                        {{ $withdrawalTransactions->total() }} حركة
                    </span>
                </div>
                <div class="card-datatable table-responsive">
                    @if($withdrawalTransactions->count() > 0)
                        <table class="table table-hover border-top">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>الصنف</th>
                                    <th class="text-center">الكمية</th>
                                    <th class="text-center">الرصيد</th>
                                    <th>المستخدم</th>
                                    <th class="text-center">التاريخ</th>
                                    <th>رقم الفاتورة</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach($withdrawalTransactions as $transaction)
                                <tr class="{{ $loop->even ? 'bg-soft-light' : '' }}">
                                    <td class="text-center fw-medium">{{ ($withdrawalTransactions->currentPage() - 1) * $withdrawalTransactions->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    <i class="bx bx-package"></i>
                                                </span>
                                            </div>
                                            <span class="fw-medium">{{ $transaction->item->name ?? '--' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <span class="text-dark">
                                            {{ $transaction->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">
                                        <span class="text-dark">
                                            {{ $transaction->balance_after }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    {{ substr($transaction->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <span>{{ $transaction->user->name ?? '--' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted">{{ $transaction->created_at->format('d/m/Y') }}</span>
                                        <div class="text-muted small">{{ $transaction->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="d-block text-truncate" style="max-width: 200px;" title="{{ $transaction->description ?? '' }}">
                                            {{ $transaction->description ?? '--' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="p-3 border-top">
                            {{ $withdrawalTransactions->links() }}
                        </div>
                    @else
                        <div class="text-center p-5">
                            <div class="avatar avatar-xl mb-3">
                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                    <i class="bx bx-list-ul"></i>
                                </span>
                            </div>
                            <h5 class="mb-1">لا توجد حركات سحب مسجلة</h5>
                            <p class="text-muted mb-0">لم يتم تسجيل أي حركات سحب لهذا المستودع حتى الآن</p>
                        </div>
                    @endif
                </div>
            </div>
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


