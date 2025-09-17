@extends('layouts.admin')

@section('title', 'إضافة صنف جديد')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-max-width">
        <div class="widget-card">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">إضافة صنف جديد</h3>
                <a href="{{ route('admin.items.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm bg-slate-100 text-slate-700 hover:bg-slate-200">رجوع</a>
            </div>
            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- اسم الصنف -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">اسم الصنف</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="مثال: تفاح أحمر">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- كود الصنف -->
                    <div>
                        <label for="item_code" class="block text-sm font-medium text-slate-700 mb-1">كود الصنف</label>
                        <input type="text" name="item_code" id="item_code" value="{{ old('item_code') }}"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="مثال: ITM-0001">
                        @error('item_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الوصف -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1">الوصف</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="وصف مختصر للمنتج...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- السعر -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-slate-700 mb-1">السعر</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0.00">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الكمية -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-slate-700 mb-1">الكمية</label>
                        <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" step="0.01" min="0"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0">
                        @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الفئة -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-1">الفئة</label>
                        <select name="category_id" id="category_id" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الوحدة -->
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-slate-700 mb-1">الوحدة</label>
                        <select name="unit_id" id="unit_id" required
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- يظهر في المتجر -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">يظهر في المتجر</label>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="is_shown_in_store" value="1" class="sr-only" {{ old('is_shown_in_store', 1) == 1 ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-lg border border-slate-300" data-role="toggle">نعم</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="is_shown_in_store" value="0" class="sr-only" {{ old('is_shown_in_store') == 0 ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-lg border border-slate-300" data-role="toggle">لا</span>
                            </label>
                        </div>
                        @error('is_shown_in_store') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الحد الأدنى للمخزون -->
                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-slate-700 mb-1">الحد الأدنى للمخزون</label>
                        <input type="number" name="minimum_stock" id="minimum_stock" value="{{ old('minimum_stock') }}" step="0.01" min="0"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0">
                        @error('minimum_stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- السماح بالفواصل العشرية -->
                    <div>
                        <label for="allow_decimal" class="block text-sm font-medium text-slate-700 mb-1">السماح بالفواصل العشرية</label>
                        <select name="allow_decimal" id="allow_decimal"
                                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="1" {{ old('allow_decimal', 1) == 1 ? 'selected' : '' }}>نعم</option>
                            <option value="0" {{ old('allow_decimal') == 0 ? 'selected' : '' }}>لا</option>
                        </select>
                        @error('allow_decimal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- صور المنتج -->
                    <div class="md:col-span-2">
                        <label for="photos" class="block text-sm font-medium text-slate-700 mb-1">صور المنتج</label>
                        <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none">
                        @error('photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="photosPreview" class="mt-3 flex flex-wrap gap-3"></div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('admin.items.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">إلغاء</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700" style="background-color: #4CAF50;">حفظ الصنف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle visual state for radio pills
        const toggles = document.querySelectorAll('[data-role="toggle"]');
        const updatePills = () => {
            toggles.forEach(el => {
                const input = el.previousElementSibling; // radio
                if (input && input.checked) {
                    el.classList.add('bg-indigo-50','border-indigo-400','text-indigo-700');
                } else {
                    el.classList.remove('bg-indigo-50','border-indigo-400','text-indigo-700');
                }
            });
        };
        document.querySelectorAll('input[name="is_shown_in_store"]').forEach(r => r.addEventListener('change', updatePills));
        updatePills();

        // Image previews
        const input = document.getElementById('photos');
        const preview = document.getElementById('photosPreview');
        if (input && preview) {
            input.addEventListener('change', () => {
                preview.innerHTML = '';
                Array.from(input.files || []).forEach(file => {
                    if (!file.type.startsWith('image/')) return;
                    const url = URL.createObjectURL(file);
                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'w-20 h-20 rounded-lg object-cover border';
                    preview.appendChild(img);
                });
            });
        }
    });
</script>
@endpush


