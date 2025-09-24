@extends('layouts.admin')

@section('title', 'المستخدمون')
@section('page-title', 'إدارة المستخدمين')
@section('page-subtitle', 'عرض وإدارة المشرفين والمستخدمين')

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
        <h2 class="text-xl font-bold text-gray-900">قائمة المستخدمين</h2>
        <p class="text-sm text-gray-500 mt-1">إدارة المستخدمين وتعيين الأدوار</p>
    </div>
    @can('create-users')
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">إضافة مستخدم</a>
    @endcan
</div>

<div class="table-card">
    <div class="table-toolbar">
        <div class="toolbar-left">
            <input id="usersSearch" type="search" class="toolbar-input" placeholder="بحث بالاسم أو اسم المستخدم أو البريد...">
        </div>
        <div class="flex items-center gap-2">
            <label for="usersSortBy" class="text-sm text-slate-600">ترتيب حسب:</label>
            <select id="usersSortBy" class="toolbar-select">
                <option value="full_name">الاسم الكامل (أ-ي)</option>
                <option value="username">اسم المستخدم</option>
                <option value="email">البريد</option>
                <option value="status">الحالة</option>
            </select>
        </div>
    </div>
    <div class="table-wrap">
        <table class="min-w-full w-full">
            <thead class="sticky">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الاسم الكامل</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">اسم المستخدم</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">البريد</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الأدوار</th>
                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">الحالة</th>
                    <th class="px-4 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">إجراءات</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                @forelse($users as $user)
                <tr data-full_name="{{ Str::lower($user->full_name) }}" data-username="{{ Str::lower($user->username) }}" data-email="{{ Str::lower($user->email) }}" data-status="{{ (int)($user->status == 1) }}">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->full_name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->username }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                        @foreach($user->roles as $role)
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-1">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @if($user->status == 1)
                            <span class="text-green-600 font-bold">نشط</span>
                        @else
                            <span class="text-red-600 font-bold">غير نشط</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                        <div class="flex gap-3 justify-center">
                            @can('edit-users')
                            <a href="{{ route('admin.users.edit', $user) }}" class="action-btn btn-edit">تعديل</a>
                            @endcan
                            @can('delete-users')
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('هل أنت متأكد من حذف المستخدم؟')">حذف</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا يوجد مستخدمين</td>
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
        const $search = document.getElementById('usersSearch');
        const $sort = document.getElementById('usersSortBy');
        const $tbody = document.getElementById('usersTableBody');

        const filterRows = () => {
            const q = ($search.value || '').toLowerCase().trim();
            [...$tbody.rows].forEach(tr => {
                const name = tr.getAttribute('data-full_name') || '';
                const username = tr.getAttribute('data-username') || '';
                const email = tr.getAttribute('data-email') || '';
                tr.style.display = (name.includes(q) || username.includes(q) || email.includes(q)) ? '' : 'none';
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
                if (!isNaN(na) && !isNaN(nb)) return na - nb; // numeric asc (for status)
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


