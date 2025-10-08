@extends('layouts.admin')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')


@section('content')
<div class="card p-6">
    <div class="widget-header mb-6">
        <h3 class="widget-title">@lang('admin.COMMON.edit_item') </h3>
    </div>
    
    <form action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.name') </label>
                <input name="name" class="form-input w-full" value="{{ old('name', $item->name) }}" required />
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.item_code') </label>
                <input name="item_code" class="form-input w-full" value="{{ old('item_code', $item->item_code) }}" required />
                @error('item_code')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.description') </label>
                <textarea name="description" class="form-input w-full" rows="3">{{ old('description', $item->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">  @lang('admin.COMMON.price') </label>
                <input type="number" step="0.01" name="price" class="form-input w-full" value="{{ old('price', $item->price) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.quantity') </label>
                <input type="number" step="0.01" name="quantity" class="form-input w-full" value="{{ old('quantity', $item->quantity) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.minimum_stock') </label>
                <input type="number" step="0.01" name="minimum_stock" class="form-input w-full" value="{{ old('minimum_stock', $item->minimum_stock) }}" required />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.category') </label>
                <select name="category_id" class="form-input w-full">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ (int) old('category_id', $item->category_id) === $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.unit') </label>
                <select name="unit_id" class="form-input w-full">
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}" {{ (int) old('unit_id', $item->unit_id) === $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.is_shown_in_store') </label>
                <select name="is_shown_in_store" class="form-input w-full">
                    <option value="shown" {{ $item->is_shown_in_store == 1 ? 'selected' : '' }}>نعم</option>
                    <option value="hidden" {{ $item->is_shown_in_store == 0 ? 'selected' : '' }}>لا</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">@lang('admin.COMMON.allow_decimal') </label>
                <select name="allow_decimal" class="form-input w-full">
                    <option value="1" {{ old('allow_decimal', $item->allow_decimal) ? 'selected' : '' }}>نعم</option>
                    <option value="0" {{ !old('allow_decimal', $item->allow_decimal) ? 'selected' : '' }}>لا</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-2">@lang('admin.COMMON.photos') </label>
                <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="form-input w-full" />
                @error('photos.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- عرض الصور الحالية -->
                @if($item->gallery && $item->gallery->count() > 0)
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-2">@lang('admin.COMMON.photos'):</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($item->gallery as $photo)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $photo->path) }}" alt="صورة المنتج"
                                         class="w-20 h-20 rounded-lg object-cover border">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div id="photosPreview" class="mt-3 flex flex-wrap gap-3"></div>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="btn btn-primary">@lang('admin.COMMON.save_changes')</button>
            <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">@lang('admin.COMMON.cancel')</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // معاينة الصور الجديدة
    const photosInput = document.getElementById('photos');
    const photosPreview = document.getElementById('photosPreview');

    if (photosInput && photosPreview) {
        photosInput.addEventListener('change', function() {
            photosPreview.innerHTML = '';
            Array.from(this.files || []).forEach(file => {
                if (!file.type.startsWith('image/')) return;

                const url = URL.createObjectURL(file);
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-20 h-20 rounded-lg object-cover border';

                const container = document.createElement('div');
                container.className = 'relative';
                container.appendChild(img);

                // زر حذف للصور الجديدة
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = function() {
                    container.remove();
                    // هنا يمكن إزالة الملف من input إذا لزم الأمر
                };
                container.appendChild(removeBtn);

                photosPreview.appendChild(container);
            });
        });
    }
});

// وظيفة حذف الصور الحالية
function removeExistingPhoto(photoId) {
    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        console.log('حذف الصورة:', photoId);

        // إضافة input مخفي لحذف الصورة
        let deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_photos[]';
        deleteInput.value = photoId;

        // التأكد من عدم وجود نفس الـ input بالفعل
        let existingInputs = document.querySelectorAll('input[name="delete_photos[]"]');
        let alreadyExists = false;
        existingInputs.forEach(function(input) {
            if (input.value == photoId) {
                alreadyExists = true;
            }
        });

        if (!alreadyExists) {
            document.querySelector('form').appendChild(deleteInput);
            console.log('تم إضافة input للحذف:', photoId);
        } else {
            console.log('الـ input موجود بالفعل:', photoId);
        }

        // إزالة الصورة من العرض
        event.target.closest('.relative').remove();
    }
}

// دالة لعرض الـ inputs المخفية (للتشخيص)
function showHiddenInputs() {
    const hiddenInputs = document.querySelectorAll('input[name="delete_photos[]"]');
    console.log('الـ inputs المخفية الحالية:');
    hiddenInputs.forEach(function(input) {
        console.log('Input:', input.name, '=', input.value);
    });
    return hiddenInputs.length;
}
</script>
@endpush


