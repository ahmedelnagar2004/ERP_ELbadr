@extends('layouts.admin')

@section('title', 'إضافة وحدة جديدة')

@section('content')
<style>
    .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .form-header { padding:14px 16px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; }
    .form-body { padding:16px; }
    .input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; width:100%; }
    .select { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; width:100%; }
    .label { font-weight:600; color:#334155; margin-bottom:6px; display:block; }
    .hint { color:#64748b; font-size:.85rem; margin-top:6px; }
    .error { color:#dc2626; font-size:.85rem; margin-top:4px; }
</style>

<div class="form-card">
    <div class="form-header">
        <h3 class="text-lg font-bold text-gray-900">  @lang('admin.COMMON.create')</h3>
        <a href="{{ route('admin.units.index') }}" class="text-sm text-blue-600">@lang('admin.COMMON.cancel') </a>
    </div>
    <div class="form-body">
        <form action="{{ route('admin.units.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- اسم الوحدة -->
            <div>
                <label for="name" class="label">@lang('admin.COMMON.name') <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="@lang('admin.COMMON.hint')" class="input" required>
                @error('name')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- الحالة -->
            <div>
                <label for="status" class="label">@lang('admin.COMMON.status') <span class="text-red-500">*</span></label>
                <select name="status" id="status" class="select" required>
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>@lang('admin.COMMON.active')</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}> @lang('admin.COMMON.inactive')</option>
                </select>
                @error('status')<div class="error">{{ $message }}</div>@enderror
            </div>

            <!-- الوصف -->
            <div>
                <label for="description" class="label">@lang('admin.COMMON.description')</label>
                <textarea name="description" id="description" rows="3" placeholder="@lang('admin.COMMON.description')" class="input">{{ old('description') }}</textarea>
                @error('description')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="btn-success" style="width:120px; height:40px;"> @lang('admin.COMMON.save')</button>
                <a href="{{ route('admin.units.index') }}" class="btn-secondary" style="width:120px; height:40px; text-align:center; line-height:40px;">@lang('admin.COMMON.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection


