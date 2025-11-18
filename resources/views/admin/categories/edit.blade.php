@extends('layouts.admin')

@section('title', __('admin.categories.edit'))
@section('page-title', __('admin.categories.edit'))

@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <p class="widget-subtitle">@lang('admin.categories.edit') @lang('admin.categories.name'): {{ $category->name }}</p>
    </div>
    
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2 font-bold">@lang('admin.categories.name')</label>
            <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>
        

        <div class="mb-4">
            <label for="image" class="block mb-2 font-bold">@lang('admin.categories.photo')</label>
            <input type="file" name="image" id="image" class="form-input w-full">
            @if($category->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="@lang('admin.categories.photo')" style="width:80px; height:80px; object-fit:cover;" class="rounded border">
                </div>
            @endif
            @error('image')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block mb-2 font-bold">الحالة</label>
            <select name="status" id="status" class="form-input w-full">
                <option value="1" {{ $category->status->value == 1 ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ $category->status->value == 0 ? 'selected' : '' }}>غير نشط</option>
            </select>
            @error('status')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn-primary">@lang('admin.categories.save_changes')</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">@lang('admin.cancel')</a>
        </div>
    </form>
</div>
@endsection




