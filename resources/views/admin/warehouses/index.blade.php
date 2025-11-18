@extends('layouts.admin')

@section('title', 'إدارة المستودعات')

@push('styles')
<style>
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
    .action-btns .btn {
        margin: 0 2px;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">المخزون /</span> إدارة المستودعات
                </h4>
            
                <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
                    إضافة مستودع جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    

    <!-- Table -->
    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-warehouses table border-top">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المستودع</th>
                        <th>الوصف</th>
                        <th>الحالة</th>
                   
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouses as $warehouse)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-package"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $warehouse->name }}</span>
                                    <small class="text-muted">#{{ $warehouse->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                  data-bs-toggle="tooltip" 
                                  title="{{ $warehouse->description }}">
                                {{ Str::limit($warehouse->description, 50) }}
                            </span>
                        </td>
                        <td>
                            @if(is_int($warehouse->status))
                                @php
                                    $statusEnum = \App\Enums\WarehouseStatus::from($warehouse->status);
                                @endphp
                                <span class="badge bg-{{ $statusEnum->color() }}">
                                    {{ $statusEnum->label() }}
                                </span>
                            @else
                                <span class="badge bg-{{ $warehouse->status->color() }}">
                                    {{ $warehouse->status->label() }}
                                </span>
                            @endif
                        </td>
                        
                        <td>{{ $warehouse->created_at->format('Y-m-d') }}</td>
                        <td class="text-nowrap">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.warehouses.show', $warehouse) }}" 
                                   class="btn btn-sm btn-outline-info rounded-circle p-2"
                                   style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.warehouses.edit', $warehouse) }}" 
                                   class="btn btn-sm btn-outline-primary rounded-circle p-2"
                                   style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="top"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.warehouses.destroy', $warehouse) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المستودع؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger rounded-circle p-2"
                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="misc-wrapper">
                                <h4 class="mb-2">لا توجد مستودعات</h4>
                                <p class="mb-4">
                                    لم يتم إضافة أي مستودعات بعد. يمكنك البدء بإضافة مستودع جديد.
                                </p>
                                <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus me-2"></i>إضافة مستودع جديد
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($warehouses->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3">
                <div class="text-muted">
                    عرض {{ $warehouses->firstItem() }} إلى {{ $warehouses->lastItem() }} من أصل {{ $warehouses->total() }} عنصر
                </div>
                <div class="pagination-wrapper">
                    {{ $warehouses->links() }}
                </div>
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





