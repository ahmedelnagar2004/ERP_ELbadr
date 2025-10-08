@extends('layouts.admin')

@section('title', 'المنتجات')
@section('page-title', 'إدارة المنتجات')
@section('page-subtitle', 'عرض وإدارة جميع المنتجات')

@section('content')
<style>
    .table-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .table-toolbar { display:flex; gap:12px; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #e2e8f0; flex-wrap: wrap; }
    .toolbar-left { display:flex; gap:10px; align-items:center; }
    .toolbar-input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; min-width:240px; background:#f8fafc; }
    .toolbar-select { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; background:#fff; }
    .table-wrap { max-height: calc(100vh - 320px); overflow:auto; }
    thead.sticky th { position: sticky; top: 0; background:#f8fafc; z-index: 1; }
    tbody tr:nth-child(even) { background:#fafafa; }
    tbody tr:hover { background:#f1f5f9; }
    .action-btn { padding:.25rem .5rem; border-radius:.5rem; font-weight:600; }
    .btn-view { color:#2563eb; }
    .btn-edit { color:#7c3aed; }
    .btn-delete { color:#dc2626; }
</style>

<div class="page-header flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">@lang('admin.COMMON.items')</h2>
        <p class="text-sm text-gray-500 mt-1">@lang('admin.COMMON.items_description')</p>
    </div>
    @can('create-items')
    <button type="button" class="btn btn-success" onclick="window.location.href='{{ route('admin.items.create') }}'">@lang('admin.COMMON.add_new_item')</button>
    @endcan
</div>

<div class="table-card">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="itemsSearch" type="search" class="toolbar-input" placeholder="بحث باسم المنتج أو الكود...">
        </div>
        <div class="flex items-center gap-2">
            <label for="sortBy" class="text-sm text-slate-600"> @lang('admin.COMMON.sort_by'):</label>
            <select id="sortBy" class="toolbar-select">
                <option value="name">@lang('admin.COMMON.name')</option>
                <option value="price">@lang('admin.COMMON.price')</option>
                <option value="quantity">@lang('admin.COMMON.quantity')</option>
                <option value="minimum_stock">@lang('admin.COMMON.minimum_stock')</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.image')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.name')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.category')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.price')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.quantity')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.minimum_stock')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.item_code')</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.status')</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">@lang('admin.COMMON.actions')</th>
                </tr>
            </thead>
            <tbody id="itemsTableBody">
                @forelse($items as $item)
                <tr data-name="{{ Str::lower($item->name) }}" data-price="{{ (float)$item->price }}" data-quantity="{{ (int)$item->quantity }}" data-minimum_stock="{{ (int)$item->minimum_stock }}" data-code="{{ Str::lower($item->item_code) }}">
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        @if($item->gallery && $item->gallery->count())
                            <img src="{{ asset('storage/' . $item->gallery->first()->path) }}" alt="صورة المنتج" class="rounded-lg border shadow" style="width:56px; height:56px; object-fit:cover;">
                        @else
                            <span class="text-gray-400 text-xs">لا توجد صورة</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $item->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->price }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->quantity }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $item->minimum_stock }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600"> {{ $item->item_code }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @if($item->is_shown_in_store == 1)
                            <span class="text-green-600 font-bold">@lang('admin.COMMON.active')</span>
                        @else
                            <span class="text-red-600 font-bold">@lang('admin.COMMON.inactive')</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('admin.items.show', $item) }}" class="action-btn btn-view">@lang('admin.COMMON.view')</a>
                            <a href="{{ route('admin.items.edit', $item) }}" class="action-btn btn-edit">@lang('admin.COMMON.edit')</a>
                            <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف المنتج؟')">@lang('admin.COMMON.delete')</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">لا توجد منتجات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const $search = document.getElementById('itemsSearch');
        const $sort = document.getElementById('sortBy');
        const $tbody = document.getElementById('itemsTableBody');

        const filterRows = () => {
            const q = ($search.value || '').toLowerCase().trim();
            [...$tbody.rows].forEach(tr => {
                const name = tr.getAttribute('data-name') || '';
                const code = tr.getAttribute('data-code') || '';
                tr.style.display = (name.includes(q) || code.includes(q)) ? '' : 'none';
            });
        };

        const sortRows = () => {
            const key = $sort.value;
            const rows = [...$tbody.rows].filter(r => r.style.display !== 'none');
            rows.sort((a,b) => {
                const va = a.getAttribute('data-' + key) || '';
                const vb = b.getAttribute('data-' + key) || '';
                const na = parseFloat(va);
                const nb = parseFloat(vb);
                if (!isNaN(na) && !isNaN(nb)) return na - nb; // numeric asc
                return va.localeCompare(vb, 'ar'); // textual asc
            });
            rows.forEach(r => $tbody.appendChild(r));
        };

        $search.addEventListener('input', () => { filterRows(); sortRows(); });
        $sort.addEventListener('change', sortRows);

        // initial
        filterRows();
        sortRows();
    });
</script>
@endpush

