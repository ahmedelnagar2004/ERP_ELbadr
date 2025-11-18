@extends('layouts.admin')

@section('title', 'الأدوار')
@section('page-title', 'إدارة الأدوار')
@section('page-subtitle', 'عرض وإدارة الأدوار والصلاحيات')

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
        <h2 class="text-xl font-bold text-gray-900">قائمة الأدوار</h2>
        <p class="text-sm text-gray-500 mt-1">إدارة الأدوار والصلاحيات</p>
    </div>
    @can('manage-roles')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-success">إضافة دور جديد</a>
    @endcan
</div>

<div class="table-card">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="rolesSearch" type="search" class="toolbar-input" placeholder="بحث باسم الدور...">
        </div>
        <div class="flex items-center gap-2">
            <label for="sortRoles" class="text-sm text-slate-600">ترتيب حسب:</label>
            <select id="sortRoles" class="toolbar-select">
                <option value="name">اسم الدور (أ-ي)</option>
                <option value="permissions">عدد الصلاحيات</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">اسم الدور</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الصلاحيات</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">إجراءات</th>
                </tr>
            </thead>
            <tbody id="rolesTableBody">
                @forelse($roles as $role)
                <tr data-name="{{ Str::lower($role->name) }}" data-permissions="{{ (int)$role->permissions->count() }}">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $role->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $role->permissions->count() }} صلاحية</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            @can('manage-roles')
                            <a href="{{ route('admin.roles.show', $role) }}" class="action-btn btn-view">عرض</a>
                            @endcan
                            @can('manage-roles')
                            <a href="{{ route('admin.roles.edit', $role) }}" class="action-btn btn-edit">تعديل</a>
                            @endcan
                            @can('manage-roles')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">لا توجد أدوار مسجلة</td>
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
        const $search = document.getElementById('rolesSearch');
        const $sort = document.getElementById('sortRoles');
        const $tbody = document.getElementById('rolesTableBody');

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
                if (key === 'permissions') {
                    const pa = parseInt(a.getAttribute('data-permissions') || '0', 10);
                    const pb = parseInt(b.getAttribute('data-permissions') || '0', 10);
                    return pb - pa; // desc by count
                }
                const na = a.getAttribute('data-name') || '';
                const nb = b.getAttribute('data-name') || '';
                return na.localeCompare(nb, 'ar'); // asc name
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