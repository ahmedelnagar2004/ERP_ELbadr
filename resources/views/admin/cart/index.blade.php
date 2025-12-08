@extends('layouts.admin')

@section('title', 'سلات التسوق المتروكة')
@section('page-title', 'سلات التسوق')
@section('page-subtitle', 'عرض العملاء الذين لديهم منتجات في السلة ولم يكملوا الطلب')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">قائمة السلات</h5>
                <div class="card-options">
                    <span class="badge bg-primary">{{ count($carts) }} سلة نشطة</span>
                </div>
            </div>
            
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                @if($carts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">لا توجد سلات تسوق نشطة حالياً</h5>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>العميل</th>
                                <th class="text-center">عدد المنتجات</th>
                                <th class="text-end">إجمالي السلة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carts as $cartGroup)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold me-3">
                                            {{ substr($cartGroup->client->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">{{ $cartGroup->client->name ?? 'عميل محذوف' }}</h6>
                                            <small class="text-muted">{{ $cartGroup->client->phone ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary rounded-pill px-3 py-2">
                                        {{ $cartGroup->items_count }} منتجات
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    {{ number_format($cartGroup->total_amount, 2) }} ج.م
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.cart.show', $cartGroup->client_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i> عرض التفاصيل
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
