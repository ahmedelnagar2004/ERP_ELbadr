@extends('layouts.admin')

@section('title', 'تعديل وحدة')
@section('page-title', 'تعديل الوحدة')

@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <h3 class="widget-title">@lang('admin.COMMON.edit_data')</h3>
    </div>

    <form action="{{ route('admin.units.update', $unit) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.name')</label>
                <input name="name" class="form-input w-full" value="{{ old('name', $unit->name) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.status')</label>
                <select name="status" class="form-input w-full">
                    <option value="active" {{ $unit->status == 1 ? 'selected' : '' }}>@Lang("admin.COMMON.active")</option>
                    <option value="inactive" {{ $unit->status == 0 ? 'selected' : '' }}>@lang('admin.COMMON.inactive') </option>
                </select>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-primary">@lang('admin.COMMON.save') </button>
            <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel')</a>
        </div>
    </form>
</div>
@endsection


