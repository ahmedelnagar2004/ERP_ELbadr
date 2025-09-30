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
                        <div class="flex flex-col space-y-2">
                            @can('edit-safes')
                            <a href="{{ route('admin.safes.edit', $safe->id) }}" 
                               class="btn btn-primary">
                                @lang('admin.COMMON.update')
                            </a>
                            @endcan
                            <form action="{{ route('admin.safes.destroy', $safe->id) }}" method="POST" 
                                  onsubmit="return confirm('@lang('admin.COMMON.confirm_delete')')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger">
                                    @lang('admin.COMMON.delete')
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
