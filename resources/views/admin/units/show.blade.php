@extends('layouts.admin')

@section('title', 'عرض وحدة')
@section('page-title', 'تفاصيل الوحدة')

@section('content')
<div class="card p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="font-semibold text-slate-800 mb-2">@lang('admin.COMMON.data')</h3>
            <p><span class="text-slate-500">@lang('admin.COMMON.name'):</span> {{ $unit->name }}</p>
            <p>
                <span class="text-slate-500">@lang('admin.COMMON.status'):</span>
                {{ $unit->status ? __('admin.COMMON.active') : __('admin.COMMON.inactive') }}
            </p>
            <p><span class="text-slate-500">@lang('admin.COMMON.count') :</span> {{ $unit->items()->count() }}</p>
        </div>
    </div>
    <div class="mt-6 flex gap-3">
        @can('edit-units')
        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-primary">@lang('admin.COMMON.edit')</a>
        @endcan
        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel')</a>
    </div>
</div>
@endsection


