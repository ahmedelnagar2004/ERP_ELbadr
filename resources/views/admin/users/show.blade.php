@extends('layouts.admin')

@section('title', 'بيانات المستخدم')
@section('page-title', 'تفاصيل المستخدم')

@section('content')
<div class="content-card p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">البيانات الأساسية</h3>
            <p><span class="text-gray-600">الاسم الكامل:</span> {{ $user->full_name ?? $user->name }}</p>
            <p><span class="text-gray-600">اسم المستخدم:</span> {{ $user->username }}</p>
            <p><span class="text-gray-600">البريد:</span> {{ $user->email }}</p>
            <p><span class="text-gray-600">الحالة:</span> {{ ($user->status ?? 1) ? 'نشط' : 'غير نشط' }}</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">الأدوار والصلاحيات</h3>
            <p><span class="text-gray-600">الأدوار:</span> {{ $user->roles->pluck('name')->join(', ') ?: '—' }}</p>
            <div class="mt-2">
                <span class="text-gray-600">عدد الصلاحيات المباشرة:</span> {{ $user->permissions->count() }}
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        @can('edit-users')
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">تعديل</a>
        @endcan
        <a href="{{ route('admin.users.index') }}" class="btn-secondary">عودة</a>
    </div>
</div>
@endsection


