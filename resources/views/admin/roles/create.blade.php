@extends('layouts.admin')

@section('title', 'إضافة دور')
@section('page-title', 'إضافة دور جديد')

@section('content')
<div class="content-card p-6">
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.name') </label>
                <input name="name" class="form-input w-full" value="{{ old('name') }}" required />
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">@lang('admin.COMMON.permissions') </label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($permissions as $permission)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-success">@lang('admin.COMMON.save') </button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel') </a>
        </div>
    </form>
</div>
@endsection


