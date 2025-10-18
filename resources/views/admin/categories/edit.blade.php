@extends('layouts.admin')

@section('title', __('admin.categories.edit'))
@section('page-title', __('admin.categories.edit'))

@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <p class="widget-subtitle">@lang('admin.COMMON.edit') @lang('admin.COMMON.name'): {{ $category->name }}</p>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2 font-bold">@lang('admin.COMMON.name')</label>
            <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>


        <div class="mb-4">
            <label for="photo" class="block mb-2 font-bold">@lang('admin.COMMON.photo')</label>
            <input type="file" name="photo" id="photo" class="form-input w-full">
            @if($category->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="@lang('admin.COMMON.photo')" style="width:80px; height:80px; object-fit:cover;" class="rounded border">
                </div>
            @endif
            @error('photo')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block mb-2 font-bold">@lang('admin.COMMON.status')</label>
            <select name="status" id="status" class="form-input w-full">
                <option value="active" {{ $category->status == 1 ? 'selected' : '' }}>@lang('admin.active')</option>
                <option value="inactive" {{ $category->status == 0 ? 'selected' : '' }}>@lang('admin.inactive')</option>
            </select>
            @error('status')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-primary">@lang('admin.COMMON.save_changes')</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel')</a>
        </div>
    </form>
</div>
@endsection


