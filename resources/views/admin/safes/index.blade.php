@extends('layouts.admin')

@section('title', __('admin.safes.management'))

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="p-4 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-black">@lang('admin.COMMON.list')</h3>
        @can('create-safes')
        <a href="{{ route('admin.safes.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            @lang('admin.COMMON.add_new')
        </a>
        @endcan
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-black uppercase">@lang('admin.COMMON.name')</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-black uppercase">@lang('admin.COMMON.description')</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-black uppercase">@lang('admin.COMMON.balance')</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-black uppercase">@lang('admin.COMMON.actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($safes as $safe)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-black">
                            {{ $safe->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($safe->description)
                        <div class="text-sm text-black">
                            {{ $safe->description }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-black">
                            {{ number_format($safe->balance, 2) }} @lang('admin.COMMON.currency')
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.safes.show', $safe->id) }}" 
                               class="btn btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>عرض</span>
                            </a>
                            @can('edit-safes')
                            <a href="{{ route('admin.safes.edit', $safe->id) }}" 
                               class="btn btn-warning">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>تعديل</span>
                            </a>
                            @endcan
                            @can('delete-safes')
                            <form action="{{ route('admin.safes.destroy', $safe->id) }}" method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الخزنة؟')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    <span>حذف</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
