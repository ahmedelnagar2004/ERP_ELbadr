@extends('layouts.admin')

@section('title', 'تعديل مستخدم')
@section('page-title', 'تعديل بيانات المستخدم')

@section('content')
<div class="content-card p-6">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.fullname') </label>
                <input name="full_name" class="form-input w-full" value="{{ old('full_name', $user->full_name) }}" required />
                @error('full_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.username') </label>
                <input name="username" class="form-input w-full" value="{{ old('username', $user->username) }}" required />
                @error('username')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.email') </label>
                <input type="email" name="email" class="form-input w-full" value="{{ old('email', $user->email) }}" required />
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.status') </label>
                <select name="status" class="form-input w-full">
                    <option value="active" {{ $user->status == 1 ? 'selected' : '' }}>@lang('admin.COMMON.active') </option>
                    <option value="inactive" {{ $user->status == 0 ? 'selected' : '' }}>@lang('admin.COMMON.inactive') </option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.password') </label>
                <input type="password" name="password" class="form-input w-full" />
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.password_confirmation') </label>
                <input type="password" name="password_confirmation" class="form-input w-full" />
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">@lang('admin.COMMON.roles') </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($roles as $role)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded" {{ in_array($role->name, $userRoles) ? 'checked' : '' }} />
                        <span>{{ $role->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button class="btn btn-primary">@lang('admin.COMMON.save')</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel')</a>
        </div>
    </form>
</div>
@endsection


