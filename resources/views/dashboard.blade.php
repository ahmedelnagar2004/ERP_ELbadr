@extends('layouts.admin')

@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard'))
@section('page-subtitle') {{ __('admin.welcome_back') }}, {{ auth()->user()->name }} - {{ __('admin.system_overview') }} @endsection

@section('content')
<style>
    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(15,23,42,.04);
        border: 1px solid #e2e8f0;
        transition: transform .2s, box-shadow .2s;
        position: relative;
        overflow: hidden;
        min-height: 160px; /* larger card height */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-card:before {
        content: "";
        position: absolute;
        inset-inline-start: 0; top: 0; height: 3px; width: 100%;
        background: linear-gradient(90deg, #3b82f6, #9333ea);
        opacity: .25;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(15,23,42,.08); }
    .stat-number { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 6px 0; }
    .stat-label { color: #64748b; font-size: .875rem; font-weight: 600; letter-spacing: .3px; }
    .stat-change { font-size: .875rem; font-weight: 700; display: flex; align-items: center; gap: 6px; }
    .stat-change.positive { color: #10b981; }
    .stat-change.negative { color: #ef4444; }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0; }
    .mini-chart { height: 48px; }
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(15,23,42,.04);
        border: 1px solid #e2e8f0;
        min-height: 300px;
    }
    /* Enforced rows to ensure cards appear side-by-side */
    .stats-row { display: flex !important; flex-wrap: wrap; gap: 1.5rem; }
    .stats-row .stat-card { flex: 1 1 calc(25% - 1.5rem); max-width: calc(25% - 1.5rem); }
    .stats-row--3 .stat-card { flex-basis: calc(25% - 1.5rem); max-width: calc(25% - 1.5rem); }
    @media (max-width: 1279px) {
        .stats-row .stat-card { flex-basis: calc(33.333% - 1.5rem); max-width: calc(33.333% - 1.5rem); }
    }
    @media (max-width: 1023px) {
        .stats-row .stat-card, .stats-row--3 .stat-card { flex-basis: calc(50% - 1.5rem); max-width: calc(50% - 1.5rem); }
    }
    @media (max-width: 639px) {
        .stats-row .stat-card, .stats-row--3 .stat-card { flex-basis: calc(50% - 1.5rem); max-width: calc(50% - 1.5rem); }
    }
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .chart-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
    }
    .chart-tabs {
        display: flex;
        gap: 8px;
    }
    .chart-tab {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .chart-tab.active {
        background: #3b82f6;
        color: white;
    }
    .chart-tab:not(.active) {
        color: #64748b;
        background: #f1f5f9;
    }
    .revenue-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    .revenue-stat {
        text-align: center;
    }
    .revenue-stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    .revenue-stat-label {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 4px;
    }
    .map-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(15,23,42,.04);
        border: 1px solid #e2e8f0;
        height: 400px;
    }
    .location-stats {
        margin-top: 20px;
    }
    .location-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .location-item:last-child {
        border-bottom: none;
    }
    .location-name {
        font-weight: 500;
        color: #1e293b;
    }
    .location-percentage {
        font-weight: 600;
        color: #1e293b;
    }
    .location-bar {
        height: 4px;
        background: #f1f5f9;
        border-radius: 2px;
        margin: 8px 0;
        overflow: hidden;
    }
    .location-progress {
        height: 100%;
        border-radius: 2px;
        transition: width 0.3s ease;
    }
    /* Earning Reports Card */
    .earnings-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; box-shadow:0 2px 12px rgba(15,23,42,.04); }
    .earnings-toolbar { display:flex; gap:12px; flex-wrap: wrap; }
    .earnings-tab { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:12px; border:1px dashed #e2e8f0; color:#64748b; background:#fafafa; cursor:pointer; transition: all .2s; }
    .earnings-tab svg { width:18px; height:18px; }
    .earnings-tab.active { background:#eef2ff; color:#4338ca; border-color:#6366f1; box-shadow: inset 0 0 0 1px #c7d2fe; }
    .earnings-body { padding: 16px; }
    .earnings-title { font-weight:700; color:#1e293b; }
    .earnings-sub { color:#64748b; font-size:.875rem; }
    /* Fancy action links inside stat cards */
    .stat-action { display:inline-flex; align-items:center; gap:8px; margin-top:8px; padding:8px 14px; border-radius:9999px; font-weight:700; font-size:.85rem; color:#fff; box-shadow:0 6px 14px rgba(2,6,23,.12); transition: all .2s; border: 1px solid transparent; }
    .stat-action svg { width:16px; height:16px; }
    .stat-action:hover { transform: translateY(-1px); box-shadow:0 10px 22px rgba(2,6,23,.16); }
    .stat-action:active { transform: translateY(0); box-shadow:0 4px 10px rgba(2,6,23,.12); }
    .stat-action--indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); border-color:#6366f1; }
    .stat-action--green { background: linear-gradient(135deg, #10b981, #059669); border-color:#10b981; }
    .stat-action--blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-color:#3b82f6; }
    .stat-action--amber { background: linear-gradient(135deg, #f59e0b, #d97706); border-color:#f59e0b; }
    .stat-action--red { background: linear-gradient(135deg, #ef4444, #dc2626); border-color:#ef4444; }
    .stat-action--purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-color:#8b5cf6; }
    .stat-action--cyan { background: linear-gradient(135deg, #06b6d4, #0891b2); border-color:#06b6d4; }
    .stat-action--disabled { background:#e5e7eb; color:#9ca3af; cursor:not-allowed; box-shadow:none; }
</style>

<!-- Statistics Cards -->
<div class="stats-row mb-8">
    <!-- Total Users -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.users')</div>
                <div class="stat-number">{{ number_format($total_users) }}</div>
                
                <x-stat-action href="{{ route('admin.users.index') }}" color="indigo" permission="view-users">@lang('admin.manage') @lang('admin.users')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Items -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.items')</div>
                <div class="stat-number">{{ number_format($total_items) }}</div>
                <x-stat-action href="{{ route('admin.items.index') }}" color="green" permission="view-items">@lang('admin.manage') @lang('admin.items')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Orders -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.orders')</div>
                <div class="stat-number">{{ number_format($total_orders) }}</div>
                
                <x-stat-action href="{{ route('admin.orders.index') }}" color="blue" permission="view-orders">@lang('admin.manage') @lang('admin.orders')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Sales -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.sales')</div>
                <div class="stat-number">{{ number_format($total_sales) }}</div>
                <x-stat-action href="{{ route('admin.sales.index') }}" color="amber" permission="view-sales">@lang('admin.manage') @lang('admin.sales')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M12 2l3.09 6.26L22 9l-5 4.87L18.18 22 12 18.27 5.82 22 7 13.87 2 9l6.91-.74L12 2z"/>
                </svg>
            </div>
        </div>
    </div>

<!-- Earning Reports Chart -->


    <!-- Clients -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.clients')</div>
                <div class="stat-number">{{ number_format($total_clients) }}</div>
                    <x-stat-action href="{{ route('admin.clients.index') }}" color="red" permission="view-clients">@lang('admin.manage') @lang('admin.clients')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.categories')</div>
                <div class="stat-number">{{ number_format($total_categories) }}</div>
                <x-stat-action href="{{ route('admin.categories.index') }}" color="purple" permission="view-categories">@lang('admin.manage') @lang('admin.categories')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Units -->
    <div class="stat-card">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <div class="stat-label">@lang('admin.units')</div>
                <div class="stat-number">{{ number_format($total_units) }}</div>
                <x-stat-action href="{{ route('admin.units.index') }}" color="cyan" permission="view-units">@lang('admin.manage') @lang('admin.units')</x-stat-action>
            </div>
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>
        <div class="stat-card">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="stat-label">@lang('admin.roles')</div>
                    <div class="stat-number">{{ number_format($total_roles ?? 0) }}</div>
                    <x-stat-action href="{{ route('admin.roles.index') }}" color="amber" permission="manage-roles">@lang('admin.manage') @lang('admin.roles')</x-stat-action>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M12 2l3.09 6.26L22 9l-5 4.87L18.18 22 12 18.27 5.82 22 7 13.87 2 9l6.91-.74L12 2z"/>
                    </svg>
                </div>
            </div>
        </div>


    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mt-8 w-full">
        <div class="p-6 border-b border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-2xl font-bold text-slate-800">@lang('admin.COMMON.earning_reports')</h3>
                    <p class="text-slate-500 text-base">@lang('admin.COMMON.yearly_earnings_overview')</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="earnings-tab flex items-center gap-2 px-5 py-2.5 rounded-lg text-base font-medium transition-all duration-200 active" data-series="orders" type="button">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222.01.042 1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z"/></svg>
                        @lang('admin.orders')
                    </button>
                    <button class="earnings-tab flex items-center gap-2 px-5 py-2.5 rounded-lg text-base font-medium transition-all duration-200" data-series="sales" type="button">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3h14a1 1 0 011 1v3H2V4a1 1 0 011-1zm-1 7h16v6a1 1 0 01-1 1H3a1 1 0 01-1-1v-6z"/></svg>
                        @lang('admin.sales')
                    </button>
                    <button class="earnings-tab flex items-center gap-2 px-5 py-2.5 rounded-lg text-base font-medium transition-all duration-200" data-series="profit" type="button">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11a2.25 2.25 0 011.5 4.031v.219a.75.75 0 01-1.5 0v-.219a.75.75 0 10-1.5 0v.219a2.25 2.25 0 101.5-4.031z"/></svg>
                        @lang('admin.profit')
                    </button>
                    <button class="earnings-tab flex items-center gap-2 px-5 py-2.5 rounded-lg text-base font-medium transition-all duration-200" data-series="income" type="button">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 10-2 0v1H6.5a.5.5 0 000 1H9v9H6.5a.5.5 0 000 1H9v1a1 1 0 102 0v-1h2.5a.5.5 0 000-1H11V5h2.5a.5.5 0 000-1H11V3z"/></svg>
                        @lang('admin.income')
                    </button>
                </div>
            </div>
        </div>
        <div class="p-4 md:p-6">
            <div class="relative w-full" style="min-height: 500px;">
                <div id="earningsChart" class="w-full h-[500px] md:h-[600px] lg:h-[700px]"></div>
            </div>
        </div>
    </div>

<!-- Additional Statistics -->


@endsection

@push('scripts')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Labels and datasets from controller
            const months = @json($monthsLabels ?? []);
            const CURRENCY = @json(config('app.currency', 'ريال سعودي'));
            const datasets = {
                // Orders count per month
                orders: @json($ordersMonthly ?? []),
                // Sales count per month
                sales: @json($salesCountMonthly ?? []),
                // If profit not available, mirror income or set zeros
                profit: @json(($salesMonthly ?? [])),
                // Income: total net sales per month
                income: @json($salesMonthly ?? []),
            };
            const colors = {
                orders: '#6366f1',
                sales: '#f59e0b',
                profit: '#10b981',
                income: '#06b6d4'
            };

            const options = {
                chart: { type: 'bar', height: 360, toolbar: { show: false }, fontFamily: 'Cairo, sans-serif' },
                series: [{ name: 'Orders', data: datasets.orders }],
                xaxis: { categories: months, labels: { style: { colors: '#94a3b8' } } },
                yaxis: { labels: { formatter: (val) => `${Math.round(val)}` , style: { colors: '#94a3b8' } } },
                plotOptions: { bar: { columnWidth: '40%', borderRadius: 8 } },
                dataLabels: { enabled: false },
                grid: { borderColor: '#eef2f7' },
                colors: [colors.orders],
                tooltip: { y: { formatter: (val) => `${val}` } }
            };

            const chartEl = document.querySelector('#earningsChart');
            if (!chartEl) return;
            const chart = new ApexCharts(chartEl, options);
            chart.render();

            // Tabs switching
            const tabs = document.querySelectorAll('.earnings-tab');
            const setFormatters = (key) => {
                const isMoney = (key === 'income' || key === 'profit');
                chart.updateOptions({
                    yaxis: { labels: { formatter: (val) => isMoney ? `${Number(val).toLocaleString()} ${CURRENCY}` : `${Math.round(val)}`, style: { colors: '#94a3b8' } } },
                    tooltip: { y: { formatter: (val) => isMoney ? `${Number(val).toLocaleString()} ${CURRENCY}` : `${val}` } }
                });
            };

            // initialize formatters for default series (orders)
            // counts formatter already set; ensure tooltip is simple number
            setFormatters('orders');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    const key = tab.getAttribute('data-series');
                    const name = key.charAt(0).toUpperCase() + key.slice(1);
                    chart.updateOptions({ colors: [colors[key]] });
                    chart.updateSeries([{ name, data: datasets[key] }]);
                    setFormatters(key);
                });
            });
        });
    </script>
@endpush




