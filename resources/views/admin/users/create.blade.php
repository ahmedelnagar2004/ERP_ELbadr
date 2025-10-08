@extends('layouts.admin')

@section('title', 'إضافة مستخدم')
@section('page-title', 'إضافة مستخدم جديد')

@section('content')
<div class="content-card p-6">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">الاسم الكامل</label>
                <input name="full_name" class="form-input w-full" value="{{ old('full_name') }}" required />
                @error('full_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">اسم المستخدم</label>
                <input name="username" class="form-input w-full" value="{{ old('username') }}" required />
                @error('username')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-input w-full" value="{{ old('email') }}" required />
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الحالة</label>
                <select name="status" class="form-input w-full">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">كلمة المرور</label>
                <input type="password" name="password" class="form-input w-full" required />
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-input w-full" required />
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">الأدوار</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded" />
                        <span>{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button class="btn btn-success">حفظ</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection


