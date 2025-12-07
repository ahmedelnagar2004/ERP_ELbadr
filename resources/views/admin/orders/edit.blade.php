@extends('layouts.admin')

@section('title', 'تعديل الطلب')
@section('page-title', 'تعديل حالة الطلب #' . $order->id)
@section('page-subtitle', 'تحديث حالة الطلب')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>تحديث حالة الطلب</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">الحالة الحالية</label>
                            <div>
                                @php
                                    $currentStatus = [
                                        1 => ['class' => 'primary', 'text' => 'تم التأكيد'],
                                        2 => ['class' => 'warning', 'text' => 'قيد التجهيز'],
                                        3 => ['class' => 'info', 'text' => 'تم الشحن'],
                                        4 => ['class' => 'success', 'text' => 'تم التسليم'],
                                    ][$order->status] ?? ['class' => 'secondary', 'text' => 'غير محدد'];
                                @endphp
                                <span class="badge bg-{{ $currentStatus['class'] }} fs-6">{{ $currentStatus['text'] }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">الحالة الجديدة <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">-- اختر الحالة --</option>
                                <option value="1" {{ old('status', $order->status) == 1 ? 'selected' : '' }}>تم التأكيد</option>
                                <option value="2" {{ old('status', $order->status) == 2 ? 'selected' : '' }}>قيد التجهيز</option>
                                <option value="3" {{ old('status', $order->status) == 3 ? 'selected' : '' }}>تم الشحن</option>
                                <option value="4" {{ old('status', $order->status) == 4 ? 'selected' : '' }}>تم التسليم</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                عند اختيار "تم التسليم" سيتم تلقائياً:
                                <ul class="mt-2 mb-0">
                                    <li>إنشاء فاتورة للطلب</li>
                                    <li>تقليل كمية المنتجات من المخزن</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>تنبيه:</strong> لا يمكن تعديل حالة الطلب بعد تحديثها إلى "تم التسليم"
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>ملخص الطلب</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">رقم الطلب</label>
                        <div class="fw-bold">#{{ $order->id }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">العميل</label>
                        <div>{{ $order->client->name ?? 'عميل محذوف' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">التاريخ</label>
                        <div>{{ $order->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">المبلغ الإجمالي</label>
                        <div class="fw-bold text-primary fs-5">{{ number_format($order->total_price, 2) }} ج.م</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">عدد المنتجات</label>
                        <div>{{ $order->items->count() }} منتج</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
