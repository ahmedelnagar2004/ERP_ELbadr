@extends('layouts.admin')

@section('title', 'الوحدات')
@section('page-title', 'إدارة الوحدات')
@section('page-subtitle', 'عرض وإدارة جميع الوحدات')

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
        <h2 class="text-xl font-bold text-gray-900">قائمة الوحدات</h2>
        <p class="text-sm text-gray-500 mt-1">إدارة الوحدات وتعريفاتها</p>
    </div>
    <a href="{{ route('admin.units.create') }}" class="btn btn-success">إضافة وحدة جديدة</a>
    
</div>

<div class="table-card">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="unitsSearch" type="search" class="toolbar-input" placeholder="بحث باسم الوحدة...">
        </div>
        <div class="flex items-center gap-2">
            <label for="unitsSortBy" class="text-sm text-slate-600">ترتيب حسب:</label>
            <select id="unitsSortBy" class="toolbar-select">
                <option value="name">اسم الوحدة (أ-ي)</option>
                <option value="products_count">عدد المنتجات</option>
                <option value="created_at">تاريخ الإنشاء</option>
                <option value="status">الحالة</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">اسم الوحدة</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">عدد المنتجات</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">إجراءات</th>
                </tr>
            </thead>
            <tbody id="unitsTableBody">
                @forelse($units as $unit)
                <tr data-name="{{ Str::lower($unit->name) }}" data-products_count="{{ (int)($unit->products_count ?? 0) }}" data-created_at="{{ optional($unit->created_at)->timestamp ?? 0 }}" data-status="{{ (int)($unit->status == 1) }}">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $unit->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @if($unit->status == 1)
                            <span class="text-green-600 font-bold">نشط</span>
                        @else
                            <span class="text-red-600 font-bold">غير نشط</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        {{ $unit->products_count }} منتج
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        {{ $unit->created_at ? $unit->created_at->format('Y-m-d') : '-' }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('admin.units.show', $unit) }}" class="action-btn btn-view">عرض</a>
                            <a href="{{ route('admin.units.edit', $unit) }}" class="action-btn btn-edit">تعديل</a>
                            <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف الوحدة؟')">حذف</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد وحدات</td>
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
        const $search = document.getElementById('unitsSearch');
        const $sort = document.getElementById('unitsSortBy');
        const $tbody = document.getElementById('unitsTableBody');

        const filterRows = () => {
            const q = ($search.value || '').toLowerCase().trim();
            [...$tbody.rows].forEach(tr => {
                const name = tr.getAttribute('data-name') || '';
                tr.style.display = (name.includes(q)) ? '' : 'none';
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
                return va.localeCompare(vb, 'ar'); // textual/date asc
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
