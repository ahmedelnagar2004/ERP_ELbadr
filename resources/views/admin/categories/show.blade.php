@extends('layouts.admin')

@section('title', __('admin.categories.show'))
@section('page-title', __('admin.categories.category_details'))
@section('page-subtitle', __('admin.categories.show'))

@section('content')
    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex space-x-3 rtl:space-x-reverse">
            @can('edit-categories')
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    @lang('admin.COMMON.edit')
                </a>
            @endcan

            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                @lang('admin.COMMON.cancel')
            </a>
        </div>
    </div>

    <!-- Category Details -->
    <div class="card">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">@lang('admin.categories.category_information')</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">@lang('admin.categories.name')</h4>
                    <p class="text-gray-600 dark:text-gray-400">{{ $category->name }}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">@lang('admin.status')</h4>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $category->status ? __('admin.active') : __('admin.inactive') }}
                </span>
                </div>
            </div>

            @if($category->description)
                <div class="mt-4">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">@lang('admin.description')</h4>
                    <p class="text-gray-600 dark:text-gray-400">{{ $category->description }}</p>
                </div>
            @endif

            @if($category->photo)
                <div class="mt-4">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">@lang('admin.categories.photo')</h4>
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $category->photo->path) }}"
                             alt="{{ $category->name }}"
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($category->items && $category->items->count() > 0)
        <div class="card mt-6">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    @lang('admin.categories.related_products')
                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-sm font-medium px-2.5 py-0.5 rounded-full">
                {{ $category->items->count() }}
            </span>
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($category->items as $item)
                        <div class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            @if($item->mainPhoto)
                                <img src="{{ asset('storage/' . $item->mainPhoto->path) }}"
                                     alt="{{ $item->name }}"
                                     class="w-12 h-12 rounded-lg object-cover ml-3">
                            @else
                                <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center ml-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</h4>
                                @if($item->price)
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ number_format($item->price, 2) }} @lang('admin.currency')</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
