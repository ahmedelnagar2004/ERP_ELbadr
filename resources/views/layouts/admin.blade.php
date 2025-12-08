<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('direction', 'rtl') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ERP') }} - @yield('title', __('admin.dashboard'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Cairo', sans-serif; }
        .sidebar { width: 280px; background: #0f172a; }
        .sidebar-link { display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; color:#cbd5e1; border-radius:12px; transition: background .2s, color .2s, transform .2s; }
        .sidebar-link:hover { background: rgba(255,255,255,.06); color:#fff; transform: translateX(-2px); }
        .sidebar-link.active { background:#1e293b; color:#fff; box-shadow: inset 2px 0 0 0 #3b82f6; }
        .logo { color:#fff; }
        .brand-title { font-weight: 800; letter-spacing:.3px; background: linear-gradient(90deg,#93c5fd,#c4b5fd); -webkit-background-clip: text; background-clip: text; color: transparent; }
        .brand-user { color:#cbd5e1; font-size:.8rem; font-weight:600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px; }
        .brand-wrap { display:flex; align-items:center; gap:.75rem; }
        .brand-logo { width: 40px; height: 40px; border-radius: 10px; overflow: hidden; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #3b82f6, #4f46e5); }
        .brand-logo img { width:100%; height:100%; object-fit: cover; }
        .user-badge { display:inline-flex; align-items:center; gap:6px; padding:2px 8px; border-radius:9999px; background: rgba(59,130,246,.15); color:#bfdbfe; font-size:.7rem; font-weight:700; margin-top:2px; }
        .user-dot { width:8px; height:8px; border-radius:9999px; background:#60a5fa; }
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
            <div class="brand-wrap mb-6">
                @if(function_exists('public_path') && file_exists(public_path('images/elbadr-logo.svg')))
                    <div class="brand-logo">
                        <img src="{{ asset('images/elbadr-logo.svg') }}" alt="Elbadr Logo">
                    </div>
                @else
                    <div class="brand-logo">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                    </div>
                @endif
                <div class="leading-tight">
                    <p class="brand-title text-lg">ELBADR ERP</p>
                    <hr style="color: white;">
                    <p class="brand-user" title="{{ Auth::user()->full_name }}">{{ Auth::user()->full_name }}</p>
                    @php($roles = Auth::user()->getRoleNames() ?? collect())
                    @if($roles->isNotEmpty())
                        <span class="user-badge"><span class="user-dot"></span>{{ $roles->first() }}</span>
                    @endif
                </div>
            </div>

            <p class="text-xs uppercase tracking-wider text-slate-400 mb-2" style="color: white; text-align: center; font-weight: bold; ">القائمة</p>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span>@lang('admin.dashboard')</span>
                </a>

                @can('view-categories')
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.menu.categories')</span>
                </a>
                @endcan
                @can('edit-settings')
                <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.general_settings.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.menu.settings')</span>
                </a>
                @endcan

                @can('view-orders')
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                    <span>@lang('admin.COMMON.orders')</span>
                </a>
                @endcan
                
                @can('view-cart')
                <a href="{{ route('admin.cart.index') }}" class="sidebar-link {{ request()->routeIs('admin.cart.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                    <span>@lang('admin.COMMON.carts')</span>
                </a>
                @endcan

                @can('view-items')
                <a href="{{ route('admin.items.index') }}" class="sidebar-link {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                    <span>@lang('admin.menu.products')</span>
                </a>
                @endcan
                @can('alert-quantity')
                <a href="{{ route('admin.alerts.index') }}" class="sidebar-link {{ request()->routeIs('admin.alerts.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.COMMON.alert_quantity')</span>
                </a>
                @endcan
                @can('view-warehouses')
                <a href="{{ route('admin.warehouses.index') }}" class="sidebar-link {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4A1 1 0 013 8V4zm4-1v4.586L9.586 7 7 4.414V3zm7 14a1 1 0 01-1 1H5a1 1 0 01-1-1v-4a1 1 0 011-1h4a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L9 11.414V17h1z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.COMMON.warehouse')</span>
                </a>
                @endcan

                <a href="{{ route('admin.units.index') }}" class="sidebar-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 01.707.293l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4A1 1 0 013 8V4zm4-1v4.586L9.586 7 7 4.414V3zm7 14a1 1 0 01-1 1H5a1 1 0 01-1-1v-4a1 1 0 011-1h4a1 1 0 01.707.293l4 4a1 1 0 01-1.414 1.414L9 11.414V17h1z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.menu.units')</span>
                </a>

                @can('view-users')
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                    <span>@lang('admin.menu.staff')</span>
                </a>
                @endcan

                @can('manage-roles')
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.menu.roles')</span>
                </a>
                @endcan
                @can('view-sales')
                <a href="{{ route('admin.sales.index') }}" class="sidebar-link {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                    <span>@lang('admin.menu.sales')</span>
                </a>
                @endcan
                @can('view-reports')
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>
                    <span>@lang('admin.menu.reports')</span>
                </a>
                @endcan
                @can('view-clients')
                <a href="{{ route('admin.clients.index') }}" class="sidebar-link {{ request()->routeIs('admin.sales.clients.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/></svg>
                    <span>@lang('admin.menu.clients')</span>
                </a>
                @endcan
                @can('view-safes')
                <a href="{{ route('admin.safes.index') }}" class="sidebar-link {{ request()->routeIs('admin.safes.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 00-8 8c0 2.8 1.6 5.2 4 6.3V19a1 1 0 001 1h6a1 1 0 001-1v-2.7c2.4-1.1 4-3.5 4-6.3a8 8 0 00-8-8zm0 11a3 3 0 110-6 3 3 0 010 6z" />
                        <circle cx="10" cy="10" r="1" fill="currentColor" />
                    </svg>
                    <span>@lang('admin.menu.safes')</span>
                </a>
                @endcan
            </nav>
            
            <div class="mt-8 pt-5 border-t border-slate-700/40">
                <div class="flex items-center">
                    <div class="w-9 h-9 rounded-full bg-indigo-600/30 flex items-center justify-center">
                    </div>
                    <div class="mr-3">
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button class="text-xs text-white inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4.5A1.5 1.5 0 014.5 3h5a1.5 1.5 0 011.5 1.5V7a1 1 0 102 0V4.5A3.5 3.5 0 009.5 1h-5A3.5 3.5 0 001 4.5v11A3.5 3.5 0 004.5 19h5a3.5 3.5 0 003.5-3.5V13a1 1 0 10-2 0v2.5A1.5 1.5 0 019.5 17h-5A1.5 1.5 0 013 15.5v-11z" clip-rule="evenodd"/><path d="M12.293 7.293a1 1 0 011.414 0L16 9.586V9.5a1 1 0 112 0v1a1 1 0 01-.293.707l-2.293 2.293a1 1 0 11-1.414-1.414L14.586 11H8a1 1 0 110-2h6.586l-1.293-1.293a1 1 0 010-1.414z"/></svg>
                                <span>@lang('admin.logout')</span>
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
                            <!-- Language Switcher -->
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <form action="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                        <span class="mr-2">{{ app()->getLocale() === 'ar' ? '🇸🇦' : '🇺🇸' }}</span>
                                        {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                                    </button>
                                </form>
            </div>
                            
                           
                            @if (Route::has('admin.reports.index'))
                            <a href="{{ route('admin.reports.index') }}" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h14a1 1 0 011 1v3H2V4a1 1 0 011-1zm-1 7h16v6a1 1 0 01-1 1H3a1 1 0 01-1-1v-6z"/></svg>
                                @lang('admin.menu.reports')
                            </a>
                            @endif
                            <a href="{{ route('admin.sales.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-colors">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                @lang('admin.menu.sales')
                            </a>
                        </div>
                    </div>
                </div>
            </header>
            <main class="p-6">
                @yield('content')
            </main>
            
        </div>
    </div>
    @stack('scripts')
</body>
</html>