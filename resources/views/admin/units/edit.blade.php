@extends('layouts.admin')

@section('title', 'تعديل وحدة')
@section('page-title', 'تعديل الوحدة')
@section('page-subtitle', 'تعديل بيانات الوحدة: {{ $unit->name }}')

@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <h3 class="widget-title">تعديل بيانات الوحدة</h3>
    </div>
    
    <form action="{{ route('admin.units.update', $unit) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">الاسم</label>
                <input name="name" class="form-input w-full" value="{{ old('name', $unit->name) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الحالة</label>
                <select name="status" class="form-input w-full">
                    <option value="1" {{ old('status', $unit->status) ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ !old('status', $unit->status) ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.units.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection


