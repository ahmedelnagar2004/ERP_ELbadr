@extends('layouts.admin')

@section('title', 'إضافة وحدة جديدة')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-max-width">
        <div class="widget-card">
            <div class="widget-header">
                <h3 class="widget-title">إضافة وحدة جديدة</h3>
            </div>
            <form action="{{ route('admin.units.store') }}" method="POST">
                @csrf

                <!-- اسم الوحدة -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        اسم الوحدة <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name') }}"
                        placeholder="مثال: قطعة، متر، لتر، كيلو"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الحالة -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الحالة <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        required>
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- الوصف -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        الوصف
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="وصف مختصر للوحدة..."
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.units.index') }}" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        إلغاء
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        حفظ الوحدة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

