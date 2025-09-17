@extends('layouts.admin')

@section('title', 'تعديل فئة')
@section('page-title', 'تعديل الفئة')


@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        
        <p class="widget-subtitle">تعديل بيانات الفئة: {{ $category->name }}</p>
        
    </div>
    
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block mb-2 font-bold">اسم الفئة</label>
            <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $category->name) }}" required>
            @error('name')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>
        

        <div class="mb-4">
            <label for="image" class="block mb-2 font-bold">صورة الفئة</label>
            <input type="file" name="image" id="image" class="form-input w-full">
            @if($category->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $category->image) }}" alt="صورة الفئة" style="width:80px; height:80px; object-fit:cover;" class="rounded border">
                </div>
            @endif
            @error('image')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="block mb-2 font-bold">حالة الفئة</label>
            <select name="status" id="status" class="form-input w-full">
                <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>غير نشط</option>
            </select>
            @error('status')
                <span class="text-red-600 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection


