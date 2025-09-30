@extends('layouts.admin')

@section('title', __('admin.menu.safes') . ' - ' . __('admin.actions.edit'))

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-lg shadow">
    <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
            @lang('admin.actions.edit') @lang('admin.menu.safes')
        </h2>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.safes.update', $safe) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.name') <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $safe->name) }}" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Number -->
                <div>
                    <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.account_number') <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="account_number" id="account_number" 
                        value="{{ old('account_number', $safe->account_number) }}" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.type') <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="1" {{ old('type', $safe->type) == 1 ? 'selected' : '' }}>@lang('admin.COMMON.types.cash')</option>
                        <option value="2" {{ old('type', $safe->type) == 2 ? 'selected' : '' }}>@lang('admin.COMMON.types.bank')</option>
                        <option value="3" {{ old('type', $safe->type) == 3 ? 'selected' : '' }}>@lang('admin.COMMON.types.credit_card')</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Balance -->
                <div>
                    <label for="balance" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.balance') <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="balance" id="balance" 
                        value="{{ old('balance', $safe->balance) }}" step="0.01" min="0" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    @error('balance')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.currency') <span class="text-red-500">*</span>
                    </label>
                    <select name="currency" id="currency" required
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="EGP" {{ old('currency', $safe->currency) == 'EGP' ? 'selected' : '' }}>EGP - @lang('admin.COMMON.currencies.EGP')</option>
                        <option value="USD" {{ old('currency', $safe->currency) == 'USD' ? 'selected' : '' }}>USD - @lang('admin.COMMON.currencies.USD')</option>
                        <option value="EUR" {{ old('currency', $safe->currency) == 'EUR' ? 'selected' : '' }}>EUR - @lang('admin.COMMON.currencies.EUR')</option>
                        <option value="SAR" {{ old('currency', $safe->currency) == 'SAR' ? 'selected' : '' }}>SAR - @lang('admin.COMMON.currencies.SAR')</option>
                        <option value="AED" {{ old('currency', $safe->currency) == 'AED' ? 'selected' : '' }}>AED - @lang('admin.COMMON.currencies.AED')</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" 
                            {{ old('status', $safe->status) ? 'checked' : '' }}
                            class="rounded border-slate-300 dark:border-slate-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-white">
                        <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.active')
                        </span>
                    </label>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        @lang('admin.COMMON.description')
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-white dark:text-black shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('description', $safe->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" 
                            {{ old('status', $safe->status) ? 'checked' : '' }}
                            class="rounded border-slate-300 dark:border-slate-600 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-700">
                        <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                            @lang('admin.COMMON.active')
                        </span>
                    </label>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.safes.index') }}" class="btn btn-secondary">
                    @lang('admin.COMMON.cancel')
                </a>
                <button type="submit" class="btn btn-primary">
                    @lang('admin.COMMON.update')
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Initialize any JavaScript for the edit form here
    document.addEventListener('DOMContentLoaded', function() {
        // Add any client-side validation or other JavaScript functionality
    });
</script>
@endpush

@endsection
