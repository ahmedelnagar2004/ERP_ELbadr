@extends('layouts.admin')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب #' . $order->id)
@section('page-subtitle', 'عرض معلومات الطلب الكاملة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>منتجات الطلب</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th class="text-center">الكمية</th>
                                <th class="text-end">السعر</th>
                                <th class="text-end">الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-center">{{ $item->pivot->quantity }}</td>
                                <td class="text-end">{{ number_format($item->pivot->unit_price, 2) }} ج.م</td>
                                <td class="text-end fw-bold">{{ number_format($item->pivot->total_price, 2) }} ج.م</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">المجموع الفرعي:</td>
                                <td class="text-end fw-bold">{{ number_format($order->price, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">تكلفة الشحن:</td>
                                <td class="text-end">{{ number_format($order->shipping_cost, 2) }} ج.م</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="text-end fw-bold fs-5">الإجمالي النهائي:</td>
                                <td class="text-end fw-bold fs-5">{{ number_format($order->total_price, 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">رقم الطلب</label>
                        <div class="fw-bold">#{{ $order->id }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">التاريخ</label>
                        <div>{{ $order->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">الحالة</label>
                        <div>
                            @php
                                $statusInfo = [
                                    1 => ['class' => 'primary', 'text' => 'تم التأكيد'],
                                    2 => ['class' => 'warning', 'text' => 'قيد التجهيز'],
                                    3 => ['class' => 'info', 'text' => 'تم الشحن'],
                                    4 => ['class' => 'success', 'text' => 'تم التسليم'],
                                ][$order->status] ?? ['class' => 'secondary', 'text' => 'غير محدد'];
                            @endphp
                            <span class="badge bg-{{ $statusInfo['class'] }}">{{ $statusInfo['text'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">طريقة الدفع</label>
                        <div>دفع عند الاستلام</div>
                    </div>
                    @if($order->sale_id)
                    <div class="mb-3">
                        <label class="text-muted small">رقم الفاتورة</label>
                        <div>
                            <a href="{{ route('admin.sales.show', $order->sale_id) }}" class="text-decoration-none">
                                #{{ $order->sale_id }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>معلومات العميل</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-user text-muted me-2"></i>
                        <strong>{{ $order->client->name ?? 'عميل محذوف' }}</strong>
                    </div>
                    @if($order->client)
                    <div class="mb-2">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        {{ $order->client->email }}
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone text-muted me-2"></i>
                        {{ $order->client->phone }}
                    </div>
                    @endif
                </div>
            </div>
            
            @if($order->shippingAddress)
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>عنوان الشحن</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>{{ $order->shippingAddress->name }}</strong>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        {{ $order->shippingAddress->email }}
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone text-muted me-2"></i>
                        {{ $order->shippingAddress->phone }}
                    </div>
                    <div>
                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                        {{ $order->shippingAddress->address }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
        </a>
        @if($order->status != 4)
        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-2"></i>تعديل الحالة
        </a>
        @endif
    </div>
</div>
@endsection
