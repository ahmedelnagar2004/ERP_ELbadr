@extends('layouts.admin')

@section('title', 'إضافة مستودع جديد')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">المستودعات /</span> إضافة مستودع جديد
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
                    <form id="warehouseForm" action="{{ route('admin.warehouses.store') }}" method="POST">
                        @csrf
                        
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
                                    value="{{ old('name') }}" 
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
                                    <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>غير نشط</option>
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
                                >{{ old('description') }}</textarea>
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
                                <i class="fas fa-save me-2"></i> حفظ المستودع
                            </button>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i> إعادة تعيين
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-switch .form-check-input {
        width: 2.5em;
        margin-left: 0.5em;
    }
    .form-check-input:checked {
        background-color: #696cff;
        border-color: #696cff;
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('warehouseForm');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endpush
@endsection

