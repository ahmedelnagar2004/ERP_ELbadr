@extends('layouts.admin')

@section('title', 'تعديل دور')
@section('page-title', 'تعديل الدور')

@section('content')
<div class="content-card p-6">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">اسم الدور</label>
                <input name="name" class="form-input w-full" value="{{ old('name', $role->name) }}" required />
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">الصلاحيات</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-h-80 overflow-auto p-2 border rounded-lg">
                    @foreach($permissions as $permission)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded" {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }} />
                        <span>{{ $permission->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button class="btn-primary">حفظ</button>
            <a href="{{ route('admin.roles.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection


