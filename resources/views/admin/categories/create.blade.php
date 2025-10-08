@extends('layouts.admin')

@section('title', __('admin.categories.create'))

@section('content')
<div class="dashboard-container">
    <div class="dashboard-max-width">
        <div class="widget-card">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">@lang('admin.COMMON.create_category')</h3>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm bg-slate-100 text-slate-700 hover:bg-slate-200">@lang('admin.back')</a>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- اسم الفئة -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">@lang('admin.COMMON.name') <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="@lang('admin.COMMON.name_placeholder')">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- الحالة -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">@lang('admin.status') <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="active" class="sr-only" {{ old('status', 'active') == 'active' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-lg border border-slate-300" data-role="toggle">@lang('admin.active')</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="sr-only" {{ old('status') == 'inactive' ? 'checked' : '' }}>
                                <span class="px-3 py-1.5 rounded-lg border border-slate-300" data-role="toggle">@lang('admin.inactive')</span>
                            </label>
                        </div>
                        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- الوصف -->
                <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">@lang('admin.COMMON.description')</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="@lang('admin.COMMON.description_placeholder')">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- صورة الفئة -->
                <div class="mt-4">
                    <label for="photo" class="block text-sm font-medium text-slate-700 mb-1">@lang('admin.COMMON.photo')</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 focus:outline-none">
                    <p class="mt-1 text-xs text-slate-500">@lang('admin.COMMON.photo_help')</p>
                    @error('photo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <div id="categoryPhotoPreview" class="mt-3"></div>
                </div>

                <!-- الأزرار -->
                <div class="flex justify-end gap-2 mt-6">
                    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">@lang('admin.cancel')</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700" style="background-color: #4CAF50;">@lang('admin.COMMON.save_category')</button>
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
        document.querySelectorAll('input[name="status"]').forEach(r => r.addEventListener('change', updatePills));
        updatePills();

        // Image preview
        const file = document.getElementById('photo');
        const preview = document.getElementById('categoryPhotoPreview');
        if (file && preview) {
            file.addEventListener('change', () => {
                preview.innerHTML = '';
                const f = file.files && file.files[0];
                if (!f || !f.type.startsWith('image/')) return;
                const url = URL.createObjectURL(f);
                const img = document.createElement('img');
                img.src = url;
                img.className = 'w-24 h-24 rounded-lg object-cover border';
                preview.appendChild(img);
            });
        }
    });
    </script>
@endpush