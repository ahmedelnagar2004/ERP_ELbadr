@extends('layouts.admin')

@section('title', __('admin.safes.management'))

@section('content')
<div class="container-fluid px-4">
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('admin.safes.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> {{ __('admin.COMMON.add_new') }}
                </a>
                <div>
                    <h3 class="h5 mb-0 text-gray-800 fw-bold">{{ __('admin.safes.list') }}</h3>
                    <p class="text-muted mb-0">إدارة ومراقبة الخزنات</p>
                </div>
            </div>
        </div>

        <!-- Safes Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap text-end" style="width: 8%;">#</th>
                                <th class="text-nowrap text-end" style="width: 45%;">{{ __('admin.COMMON.name') }}</th>
                                <th class="text-nowrap text-end" style="width: 12%;">{{ __('admin.safes.balance') }}</th>
                                <th class="text-nowrap text-center" style="width: 15%;">{{ __('admin.safes.status') }}</th>
                                <th class="text-nowrap text-center" style="width: 20%;">{{ __('admin.COMMON.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($safes as $safe)
                                <tr>
                                    <td class="text-nowrap text-end">
                                        <div class="fw-semibold">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="text-nowrap text-end">
                                        <div class="fw-medium">{{ $safe->name }}</div>
                                        @if($safe->description)
                                            <div class="text-muted small">{{ Str::limit($safe->description, 30) }}</div>
                                        @endif
                                        <div class="mt-1">
                                            @php
                                                $typeLabels = [
                                                    1 => ['text' => 'محفظة إلكترونية', 'icon' => 'mobile-alt', 'class' => 'info'],
                                                    2 => ['text' => 'حساب بنكي', 'icon' => 'university', 'class' => 'primary'],
                                                    3 => ['text' => 'إنستا باي', 'icon' => 'credit-card', 'class' => 'success'],
                                                    4 => ['text' => 'شبكه', 'icon' => 'network-wired', 'class' => 'warning'],
                                                    5 => ['text' => 'أجل', 'icon' => 'clock', 'class' => 'info'],
                                                    6 => ['text' => 'خزنة داخل الكاشير', 'icon' => 'cash-register', 'class' => 'success']
                                                ];
                                                $typeInfo = $typeLabels[$safe->type] ?? ['text' => 'غير محدد', 'icon' => 'question-circle', 'class' => 'secondary'];
                                            @endphp
                                            <div class="d-flex align-items-center gap-1 text-muted small">
                                                <i class="fas fa-{{ $typeInfo['icon'] }} text-{{ $typeInfo['class'] }} fa-sm"></i>
                                                <span>{{ $typeInfo['text'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-nowrap text-end fw-bold">
                                        <div>{{ number_format($safe->balance, 2) }} {{ $safe->currency }}</div>
                                        @if($safe->account_number)
                                            <div class="text-muted small">{{ $safe->account_number }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusInfo = $safe->status->value === \App\SafeStatus::ACTIVE->value
                                                ? ['class' => 'success', 'icon' => 'check-circle', 'bg' => 'bg-success-light']
                                                : ['class' => 'danger', 'icon' => 'times-circle', 'bg' => 'bg-danger-light'];
                                        @endphp
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="payment-method {{ $statusInfo['bg'] }} text-{{ $statusInfo['class'] }} d-flex align-items-center px-3 py-2 rounded-pill">
                                                <i class="fas fa-{{ $statusInfo['icon'] }} me-2"></i>
                                                <span class="fw-medium">{{ $safe->status->label() }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.safes.edit', $safe->id) }}" class="btn btn-sm btn-action btn-edit" data-bs-toggle="tooltip" title="تعديل الخزنة">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">تعديل</span>
                                            </a>
                                            <form action="{{ route('admin.safes.destroy', $safe->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف الخزنة؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-action btn-delete" data-bs-toggle="tooltip" title="حذف الخزنة">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="d-none d-md-inline">حذف</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="py-5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                                <h4 class="mt-4 mb-3">لا توجد خزنات</h4>
                                                <p class="text-muted mb-4">لم يتم العثور على أي خزنات. يمكنك إضافة خزنة جديدة بالنقر على الزر أدناه</p>
                                                <a href="{{ route('admin.safes.create') }}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-plus-circle me-2"></i> إضافة خزنة جديدة
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($safes->hasPages())
                <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4 border-top">
                    <div class="text-muted small mb-2 mb-md-0">
                        <i class="fas fa-database me-1"></i>
                        عرض <span class="fw-bold">{{ $safes->firstItem() }}</span> إلى
                        <span class="fw-bold">{{ $safes->lastItem() }}</span> من إجمالي
                        <span class="fw-bold">{{ $safes->total() }}</span> خزنة
                    </div>
                    <div class="pagination-wrap mt-3 mt-md-0">
                        {{ $safes->onEachSide(1)->withQueryString()->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

.btn-edit {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}
.btn-edit:hover {
    background-color: #ffc107;
    color: white;
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

.bg-success-light { background-color: rgba(40, 167, 69, 0.1) !important; }
.bg-danger-light { background-color: rgba(220, 53, 69, 0.1) !important; }

/* Empty State */
.empty-state {
    text-align: center;
}
.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}
</style>
@endsection
