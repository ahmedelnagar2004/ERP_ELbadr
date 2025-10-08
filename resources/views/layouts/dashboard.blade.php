<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - لوحة التحكم</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-link {
            transition: all 0.3s ease;
            position: relative;
            margin: 4px 8px;
            border-radius: 8px;
        }
        
        .sidebar-link:hover {
            background: rgba(59, 130, 246, 0.15);
            transform: translateX(-2px);
        }
        
        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left: 3px solid #3b82f6;
        }
        
        .sidebar-link .icon-container {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin-left: 16px;
        }
        
        .sidebar-link:hover .icon-container {
            background: rgba(59, 130, 246, 0.3);
        }
        
        .sidebar-link.active .icon-container {
            background: rgba(59, 130, 246, 0.4);
        }
        
        .main-content {
            background: #f8fafc;
            min-height: 100vh;
        }
        
        .header-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            background: linear-gradient(135deg, var(--tw-gradient-from), var(--tw-gradient-to));
        }
        
        .logo-container {
            background: rgba(59, 130, 246, 0.2);
            border: 2px solid rgba(59, 130, 246, 0.3);
        }
        
        .user-avatar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 flex-shrink-0 relative">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="logo-container w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <div class="mr-3">
                        <h2 class="text-lg font-bold text-white">نظام ERP</h2>
                        <p class="text-xs text-blue-200">إدارة الموارد</p>
                    </div>
                </div>
            </div>
            
            <nav class="mt-4 px-2 space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">لوحة التحكم</span>
                </a>
                
                @can('view-clients')
                <a href="{{ route('admin.clients.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">العملاء</span>
                </a>
                @endcan
                
                @can('view-items')
                <a href="{{ route('admin.items.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">المنتجات</span>
                </a>
                @endcan
                
                @can('view-categories')
                <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">الفئات</span>
                </a>
                @endcan
                
                @can('view-orders')
                <a href="{{ route('admin.orders.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">الطلبات</span>
                </a>
                @endcan
                
                @can('view-sales')
                <a href="{{ route('admin.sales.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">المبيعات</span>
                </a>
                @endcan
                
                @can('view-reports')
                <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">التقارير</span>
                </a>
                @endcan
                
                @can('manage-roles')
                <a href="{{ route('admin.roles.index') }}" class="sidebar-link flex items-center px-4 py-4 text-gray-300 hover:text-white">
                    <div class="icon-container">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium">الأدوار والصلاحيات</span>
                </a>
                @endcan
            </nav>
            
            <!-- User Info -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-600">
                <div class="flex items-center">
                    <div class="user-avatar w-10 h-10 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="mr-3 flex-1">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-white transition-colors">تسجيل الخروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content flex-1 overflow-auto">
            <!-- Header -->
            <header class="header-gradient shadow-lg">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">{{ $title ?? 'لوحة التحكم' }}</h1>
                    <p class="text-blue-100 mt-1">{{ $subtitle ?? 'مرحباً بك في نظام إدارة الموارد' }}</p>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>





