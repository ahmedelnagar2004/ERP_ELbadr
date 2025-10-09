@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'إدارة العملاء')
@section('page-subtitle', 'عرض وإدارة بيانات العملاء')

@php
    use App\Enums\ClientStatus;
@endphp

@section('content')
<div class="container-fluid px-4">
    <div class="space-y-6">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('admin.clients.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> إضافة عميل جديد
                </a>
                <div>
                    <h3 class="h5 mb-0 text-gray-800 fw-bold">قائمة العملاء</h3>
                    <p class="text-muted mb-0">إدارة بيانات العملاء</p>
                </div>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap text-end" style="width: 8%;">#</th>
                                <th class="text-nowrap text-end" style="width: 25%;">الاسم</th>
                                <th class="text-nowrap text-end" style="width: 20%;">البريد الإلكتروني</th>
                                <th class="text-nowrap text-end" style="width: 15%;">رقم الهاتف</th>
                                <th class="text-nowrap text-center" style="width: 12%;">الحالة</th>
                                <th class="text-nowrap text-center" style="width: 20%;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td class="text-nowrap text-end">{{ $loop->iteration }}</td>
                                    <td class="text-nowrap text-end">{{ $client->name }}</td>
                                    <td class="text-nowrap text-end">{{ $client->email }}</td>
                                    <td class="text-nowrap text-end">{{ $client->phone }}</td>
                                    <td class="text-center" style="width: 12%;">
                                        @if ($client->status instanceof ClientStatus)
                                            <span class="badge bg-{{ $client->status->color() }}">
                                                {{ $client->status->label() }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-sm btn-action btn-view">
                                                <i class="fas fa-eye"></i>
                                                <span class="d-none d-md-inline">@lang('admin.COMMON.view')</span>
                                            </a>
                                            @can('edit-clients')
                                            <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                                <span class="d-none d-md-inline">@lang('admin.COMMON.edit')</span>
                                            </a>
                                            @endcan
                                            @can('delete-clients')
                                            <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف العميل؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-action btn-delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="d-none d-md-inline">@lang('admin.COMMON.delete')</span>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <h4 class="mt-4 mb-3">لا يوجد عملاء</h4>
                                            <p class="text-muted mb-4">لم يتم العثور على أي عملاء. يمكنك إضافة عميل جديد بالنقر على الزر أدناه</p>
                                            <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-plus-circle me-2"></i> إضافة عميل جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($clients->hasPages())
                <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center py-3 px-4 border-top">
                    <div class="text-muted small mb-2 mb-md-0">
                        <i class="fas fa-database me-1"></i>
                        عرض <span class="fw-bold">{{ $clients->firstItem() }}</span> إلى
                        <span class="fw-bold">{{ $clients->lastItem() }}</span> من إجمالي
                        <span class="fw-bold">{{ $clients->total() }}</span> عميل
                    </div>
                    <div class="pagination-wrap mt-3 mt-md-0">
                        {{ $clients->onEachSide(1)->withQueryString()->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.payment-method {
    transition: all 0.2s ease;
    min-width: 100px;
    justify-content: center;
}
.payment-method i {
    font-size: 1rem;
}
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

/* لون الكلام داخل الشارات */
.badge {
    color: black !important;
}

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
