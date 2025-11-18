@extends('layouts.admin')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')


@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <h3 class="widget-title">تعديل بيانات المنتج</h3>
    </div>
    
    <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">الاسم</label>
                <input name="name" class="form-input w-full" value="{{ old('name', $item->name) }}" required />
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">كود المنتج</label>
                <input name="item_code" class="form-input w-full" value="{{ old('item_code', $item->item_code) }}" required />
                @error('item_code')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">الوصف</label>
                <textarea name="description" class="form-input w-full" rows="3">{{ old('description', $item->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">السعر</label>
                <input type="number" step="0.01" name="price" class="form-input w-full" value="{{ old('price', $item->price) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الكمية</label>
                <input type="number" step="0.01" name="quantity" class="form-input w-full" value="{{ old('quantity', $item->quantity) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الحد الأدنى للمخزون</label>
                <input type="number" step="0.01" name="minimum_stock" class="form-input w-full" value="{{ old('minimum_stock', $item->minimum_stock) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الفئة</label>
                <select name="category_id" class="form-input w-full">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (int) old('category_id', $item->category_id) === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">الوحدة</label>
                <select name="unit_id" class="form-input w-full">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ (int) old('unit_id', $item->unit_id) === $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">يظهر في المتجر</label>
                <select name="is_shown_in_store" class="form-input w-full">
                    <option value="1" {{ old('is_shown_in_store', $item->is_shown_in_store) ? 'selected' : '' }}>نعم</option>
                    <option value="0" {{ !old('is_shown_in_store', $item->is_shown_in_store) ? 'selected' : '' }}>لا</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">السماح بالكسور</label>
                <select name="allow_decimal" class="form-input w-full">
                    <option value="1" {{ old('allow_decimal', $item->allow_decimal) ? 'selected' : '' }}>نعم</option>
                    <option value="0" {{ !old('allow_decimal', $item->allow_decimal) ? 'selected' : '' }}>لا</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">الصورة</label>
                <input type="file" name="photo" class="form-input w-full" />
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn-primary">حفظ التغييرات</button>
            <a href="{{ route('admin.items.index') }}" class="btn-secondary">إلغاء</a>
        </div>
    </form>
</div>
@endsection


