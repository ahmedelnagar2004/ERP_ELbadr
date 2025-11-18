@extends('layouts.admin')

@section('title', 'تعديل مستودع - ' . $warehouse->name)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">المستودعات /</span> تعديل مستودع
                </h4>
                <a href="{{ route('admin.warehouses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i> رجوع
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form id="warehouseForm" action="{{ route('admin.warehouses.update', $warehouse) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- الاسم -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    اسم المستودع <span class="text-danger">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name', $warehouse->name) }}" 
                                    required
                                    autofocus
                                >
                                @error('name')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- الحالة -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">-- اختر الحالة --</option>
                                    @foreach(\App\Enums\WarehouseStatus::cases() as $status)
                                        <option value="{{ $status->value }}" {{ old('status', $warehouse->status->value) == $status->value ? 'selected' : '' }}>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- الوصف -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">الوصف</label>
                                <textarea 
                                    class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="3"
                                >{{ old('description', $warehouse->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i> حفظ التغييرات
                            </button>
                            <a href="{{ route('admin.warehouses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

