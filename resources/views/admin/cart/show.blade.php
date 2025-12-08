@extends('layouts.admin')

@section('title', 'تفاصيل السلة')
@section('page-title', 'سلة العميل: ' . ($client->name ?? 'غير معروف'))
@section('page-subtitle', 'عرض تفاصيل المنتجات الموجودة في سلة العميل')

@section('content')
<div class="row">
    <!-- Client Info -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0"><i class="fas fa-user-circle me-2"></i>معلومات العميل</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                        {{ substr($client->name ?? 'G', 0, 1) }}
                    </div>
                    <h4>{{ $client->name }}</h4>
                    <p class="text-muted">{{ $client->email }}</p>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <label class="text-muted small">رقم الهاتف</label>
                    <div class="fw-bold"><i class="fas fa-phone me-2 text-primary"></i>{{ $client->phone }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small">العنوان</label>
                    <div><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $client->address ?? 'غير مسجل' }}</div>
                </div>
                
                <div class="d-grid mt-4">
                    <a href="tel:{{ $client->phone }}" class="btn btn-outline-primary">
                        <i class="fas fa-phone-alt me-2"></i>اتصال بالعميل
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Items -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0"><i class="fas fa-shopping-basket me-2"></i>محتويات السلة</h5>
                <span class="badge bg-primary rounded-pill">{{ $cartItems->count() }} منتجات</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 50%;">المنتج</th>
                                <th class="text-center">الكمية</th>
                                <th class="text-end">سعر الوحدة</th>
                                <th class="text-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($cartItems as $item)
                            @php $grandTotal += $item->total_price; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        {{-- If item has image, we could show it here --}}
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $item->item->name }}</h6>
                                            <small class="text-muted">{{ $item->item->item_code ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <div class="alert alert-info d-none">
                                    {{-- Debugging Info --}}
                                </div>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="text-end text-muted">
                                    {{ number_format($item->price, 2) }}
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($item->total_price, 2) }} ج.م
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">إجمالي السلة:</td>
                                <td class="text-end fw-bold fs-5 text-primary">{{ number_format($grandTotal, 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end bg-white border-top p-3">
                <a href="{{ route('admin.cart.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
                {{-- Potential Future Action: Create Order for Client --}}
                {{-- <button class="btn btn-success">
                    <i class="fas fa-check me-2"></i>إنشاء طلب
                </button> --}}
            </div>
        </div>
    </div>
</div>
@endsection
