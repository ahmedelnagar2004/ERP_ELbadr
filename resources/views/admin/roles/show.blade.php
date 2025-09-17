@extends('layouts.admin')

@section('title', 'تفاصيل الدور')
@section('page-title', 'تفاصيل الدور')

@section('content')
<div class="content-card p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">البيانات</h3>
            <p><span class="text-gray-600">اسم الدور:</span> {{ $role->name }}</p>
            <p><span class="text-gray-600">عدد الصلاحيات:</span> {{ $role->permissions->count() }}</p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-900 mb-2">الصلاحيات</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($role->permissions as $permission)
                <span class="badge badge-success">{{ $permission->name }}</span>
                @endforeach
            </div>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        @can('edit-roles')
        <a href="{{ route('admin.roles.edit', $role) }}" class="btn-primary">تعديل</a>
        @endcan
        <a href="{{ route('admin.roles.index') }}" class="btn-secondary">عودة</a>
    </div>
    
</div>
@endsection


