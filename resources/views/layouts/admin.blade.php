<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ERP') }} - @yield('title', 'لوحة التحكم')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Cairo', sans-serif; }
        .sidebar { width: 280px; background: #0f172a; }
        .sidebar-link { display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; color:#cbd5e1; border-radius:12px; transition: background .2s, color .2s, transform .2s; }
        .sidebar-link:hover { background: rgba(255,255,255,.06); color:#fff; transform: translateX(-2px); }
        .sidebar-link.active { background:#1e293b; color:#fff; box-shadow: inset 2px 0 0 0 #3b82f6; }
        .logo { color:#fff; }
        .main { background:#f8fafc; min-height:100vh; }
        .header { background:#ffffff; border-bottom:1px solid #e2e8f0; position: sticky; top: 0; z-index: 30; }
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
        .tile { border:1px solid #e2e8f0; border-radius:16px; background:#fff; padding:20px; box-shadow:0 2px 10px rgba(15,23,42,.04); transition:.2s; }
        .tile:hover { box-shadow:0 6px 20px rgba(15,23,42,.08); transform: translateY(-2px); }

        /* Smooth custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar { width: 8px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 8px; }
        .sidebar::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Toolbar inputs */
        .toolbar-input { border:1px solid #e2e8f0; border-radius: 12px; padding: .5rem .75rem; background:#f8fafc; transition: border-color .2s, box-shadow .2s; }
        .toolbar-input:focus { outline: none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.15); background:#fff; }

        /* Footer */
        .footer { background:#0f172a; border-top:1px solid #1e293b; color:#ffffff; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="sidebar h-screen sticky top-0 p-5 overflow-y-auto">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                </div>
                <div class="mr-3">
                    <p class="logo text-lg font-bold text-white" style="color: white;">نظام ERP</p>
                </div>
            </div>

            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2" style="color: white; text-align: center; font-weight: bold; ">القائمة</p>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span>لوحة التحكم</span>
                </a>

                @can('view-categories')
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                    <span>الفئات</span>
                </a>
                @endcan

                @can('view-items')
                <a href="{{ route('admin.items.index') }}" class="sidebar-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                    <span>المنتجات</span>
                </a>
                @endcan

                <a href="{{ route('admin.units.index') }}" class="sidebar-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>الوحدات</span>
                </a>

                @can('view-users')
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                    <span>المستخدمون</span>
                </a>
                @endcan

                @can('manage-roles')
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>الأدوار</span>
                </a>
                @endcan
            </nav>

            <div class="mt-8 pt-5 border-t border-slate-700/40">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-indigo-600/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="mr-3">
                        <p class="text-sm text-white">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button class="text-xs text-white inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5a1.5 1.5 0 011.5 1.5V7a1 1 0 102 0V4.5A3.5 3.5 0 009.5 1h-5A3.5 3.5 0 001 4.5v11A3.5 3.5 0 004.5 19h5a3.5 3.5 0 003.5-3.5V13a1 1 0 10-2 0v2.5A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/><path d="M12.293 7.293a1 1 0 011.414 0L16 9.586V9.5a1 1 0 112 0v1a1 1 0 01-.293.707l-2.293 2.293a1 1 0 11-1.414-1.414L14.586 11H8a1 1 0 110-2h6.586l-1.293-1.293a1 1 0 010-1.414z"/></svg>
                                <span>تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div class="main flex-1">
            <header class="header">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800">@yield('page-title','لوحة التحكم')</h1>
                            <p class="text-slate-500 mt-1">@yield('page-subtitle', 'مرحباً بك في نظام إدارة الموارد')</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="hidden md:block">
                                <input type="search" class="toolbar-input" placeholder="بحث سريع...">
                            </div>
                            @if (Route::has('admin.reports.index'))
                            <a href="{{ route('admin.reports.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h14a1 1 0 011 1v3H2V4a1 1 0 011-1zm-1 7h16v6a1 1 0 01-1 1H3a1 1 0 01-1-1v-6z"/></svg>
                                تقارير
                            </a>
                            @endif
                            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                                طلب جديد
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
            <footer class="footer px-6 py-3">
                <div class="text-xs text-white text-center">© {{ date('Y') }} نظام ERP - جميع الحقوق محفوظة.</div>
            </footer>
        </div>
    </div>
    @stack('scripts')
</body>
</html>