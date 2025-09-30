@extends('layouts.admin')

@section('title', 'العملاء')
@section('page-title', 'إدارة العملاء')
@section('page-subtitle', 'عرض وإدارة بيانات العملاء')

@section('content')
<style>
    .table-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .table-toolbar { display:flex; gap:12px; align-items:center; justify-content:space-between; padding:14px 16px; border-bottom:1px solid #e2e8f0; flex-wrap: wrap; }
    .toolbar-left { display:flex; gap:10px; align-items:center; }
    .toolbar-input { border:1px solid #e2e8f0; border-radius:12px; padding:.5rem .75rem; min-width:260px; background:#f8fafc; }
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
        <h2 class="text-xl font-bold text-gray-900">قائمة العملاء</h2>
        <p class="text-sm text-gray-500 mt-1">إدارة بيانات العملاء</p>
    </div>
    @can('create-clients')
    <a href="{{ route('admin.clients.create') }}" class="btn btn-success">إضافة عميل جديد</a>
    @endcan
</div>

<div class="table-card">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="clientsSearch" type="search" class="toolbar-input" placeholder="بحث بالاسم أو البريد أو الهاتف...">
        </div>
        <div class="flex items-center gap-2">
            <label for="clientsSortBy" class="text-sm text-slate-600">ترتيب حسب:</label>
            <select id="clientsSortBy" class="toolbar-select">
                <option value="name">الاسم</option>
                <option value="email">البريد</option>
                <option value="phone">الهاتف</option>
                <option value="created_at">تاريخ الإنشاء</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الاسم</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">البريد</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الهاتف</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">إجراءات</th>
                </tr>
            </thead>
            <tbody id="clientsTableBody">
                @forelse($clients as $client)
                <tr data-name="{{ Str::lower($client->name) }}" data-email="{{ Str::lower($client->email) }}" data-phone="{{ Str::lower($client->phone) }}" data-created_at="{{ optional($client->created_at)->timestamp ?? 0 }}">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $client->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $client->email }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $client->phone }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $client->created_at ? $client->created_at->format('Y-m-d') : '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            <a href="{{ route('admin.clients.show', $client->id) }}" class="action-btn btn-view">عرض</a>
                            @can('edit-clients')
                            <a href="{{ route('admin.clients.edit', $client->id) }}" class="action-btn btn-edit">تعديل</a>
                            @endcan
                            @can('delete-clients')
                            <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف العميل؟')">حذف</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات عملاء</td>
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
        const $search = document.getElementById('clientsSearch');
        const $sort = document.getElementById('clientsSortBy');
        const $tbody = document.getElementById('clientsTableBody');

        const filterRows = () => {
            const q = ($search.value || '').toLowerCase().trim();
            [...$tbody.rows].forEach(tr => {
                const name = tr.getAttribute('data-name') || '';
                const email = tr.getAttribute('data-email') || '';
                const phone = tr.getAttribute('data-phone') || '';
                tr.style.display = (name.includes(q) || email.includes(q) || phone.includes(q)) ? '' : 'none';
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
                if (!isNaN(na) && !isNaN(nb)) return na - nb; // numeric asc (for timestamp)
                return va.localeCompare(vb, 'ar'); // textual asc
            });
            rows.forEach(r => $tbody.appendChild(r));
        };

        if ($search) $search.addEventListener('input', () => { filterRows(); sortRows(); });
        if ($sort) $sort.addEventListener('change', sortRows);

        filterRows();
        sortRows();
    });
</script>
@endpush
