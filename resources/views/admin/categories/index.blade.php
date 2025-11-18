@extends('layouts.admin')

@section('title', 'إدارة الفئات')

@section('header')

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
        <h2 class="text-xl font-bold text-gray-900">@lang('admin.categories')</h2>
        <p class="text-sm text-gray-500 mt-1">@lang('admin.categories.management')</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">@lang('admin.categories.create')</a>
    </div>

<div class="table-card">
    @if(session('success'))
        <div class="mx-4 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mx-4 mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded">{{ session('error') }}</div>
    @endif

    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="categoriesSearch" type="search" class="toolbar-input" placeholder="بحث باسم الفئة...">
        </div>
        <div class="flex items-center gap-2">
            <label for="sortCats" class="text-sm text-slate-600">ترتيب حسب:</label>
            <select id="sortCats" class="toolbar-select">
                <option value="name">اسم الفئة (أ-ي)</option>
                <option value="status">الحالة</option>
                <option value="created_at">تاريخ الإنشاء</option>
            </select>
        </div>
    </div>

    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">اسم الفئة</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="categoriesTableBody">
                @forelse($categories as $category)
                <tr data-name="{{ Str::lower($category->name) }}" data-status="{{ $category->status->value }}" data-created_at="{{ $category->created_at ? $category->created_at->timestamp : 0 }}">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->status->style() }}">
                            {{ $category->status->label() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('admin.categories.show', $category) }}" class="action-btn btn-view">عرض</a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn btn-edit">تعديل</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف الفئة؟')">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">لا توجد فئات</td>
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
        const $search = document.getElementById('categoriesSearch');
        const $sort = document.getElementById('sortCats');
        const $tbody = document.getElementById('categoriesTableBody');

        const filterRows = () => {
            const q = ($search.value || '').toLowerCase().trim();
            [...$tbody.rows].forEach(tr => {
                const name = tr.getAttribute('data-name') || '';
                tr.style.display = name.includes(q) ? '' : 'none';
            });
        };

        const sortRows = () => {
            const key = $sort.value;
            const rows = [...$tbody.rows].filter(r => r.style.display !== 'none');
            rows.sort((a,b) => {
                const va = a.getAttribute('data-' + key) || '';
                const vb = b.getAttribute('data-' + key) || '';
                if (key === 'created_at' || key === 'status') {
                    return (parseInt(va,10) || 0) - (parseInt(vb,10) || 0);
                }
                return va.localeCompare(vb, 'ar');
            });
            rows.forEach(r => $tbody.appendChild(r));
        };

        $search.addEventListener('input', () => { filterRows(); sortRows(); });
        $sort.addEventListener('change', sortRows);

        filterRows();
        sortRows();
    });
</script>
@endpush


