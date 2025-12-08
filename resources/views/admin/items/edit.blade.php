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
              <!-- div warehouse  -->
               <div>
                <label class="block text-sm font-medium mb-1">المخزن</label>
                <select name="warehouse_id" class="form-input w-full">
                    <option value="">اختر المخزن</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $item->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
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
                <label class="block text-sm font-medium mb-1">صور المعرض (يمكنك اختيار عدة صور)</label>
                <input type="file" name="photos[]" class="form-input w-full" accept="image/*" multiple />
                <p class="text-sm text-gray-500 mt-1">يمكنك اختيار عدة صور في نفس الوقت</p>
            </div>

            @if($item->gallery && $item->gallery->count() > 0)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">الصور الحالية</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($item->gallery as $photo)
                    <div class="relative group" style="position: relative;">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Gallery Photo" class="w-full h-32 object-cover rounded">
                        <button type="button" 
                                onclick="deletePhoto({{ $photo->id }})"
                                style="position: absolute; top: 8px; right: 8px; background-color: #ef4444; color: white; border-radius: 9999px; padding: 8px; opacity: 0; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='1'"
                                onmouseout="this.style.opacity='0.7'"
                                class="delete-btn"
                                title="حذف الصورة">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-success text-white">حفظ التغييرات</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline text-slate-700">إلغاء</a>
        </div>
    </form>
</div>

<style>
.group:hover .delete-btn {
    opacity: 1 !important;
}
</style>

<script>
function deletePhoto(photoId) {
    if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        return;
    }

    fetch(`/admin/items/photo/${photoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء حذف الصورة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حذف الصورة');
    });
}
</script>
@endsection


