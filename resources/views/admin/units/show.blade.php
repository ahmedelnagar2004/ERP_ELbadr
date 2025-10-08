@extends('layouts.admin')

@section('title', 'عرض وحدة')
@section('page-title', 'تفاصيل الوحدة')
@section('page-subtitle', 'عرض تفاصيل الوحدة: {{ $unit->name }}')

@section('content')
<div class="card p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">البيانات</h3>
            <p><span class="text-slate-500">الاسم:</span> {{ $unit->name }}</p>
            <p><span class="text-slate-500">الحالة:</span> {{ $unit->status ? 'نشط' : 'غير نشط' }}</p>
            <p><span class="text-slate-500">عدد المنتجات المرتبطة:</span> {{ $unit->items()->count() }}</p>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        @can('edit-units')
        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-primary">تعديل</a>
        @endcan
        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">عودة</a>
    </div>
</div>
@endsection


