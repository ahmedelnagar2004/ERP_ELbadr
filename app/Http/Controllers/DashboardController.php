<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        // Dashboard data
        $total_warehouses = Warehouse::count();
        $total_alerts = Item::where('quantity', '<', 'alert_quantity')->count();
        $total_users = User::count();
        $total_items = Item::count();
        $total_orders = Order::count();
        $total_sales = Sale::count();
        $total_clients = Client::count();
        $total_categories = Category::count();
        $total_units = Unit::count();

        // Get dashboard statistics based on user permissions
        $stats = [
            'users' => Gate::allows('view-users') ? $total_users : null,
            'clients' => Gate::allows('view-clients') ? $total_clients : null,
            'categories' => Gate::allows('view-categories') ? $total_categories : null,
            'items' => Gate::allows('view-items') ? $total_items : null,
            'orders' => Gate::allows('view-orders') ? $total_orders : null,
            'sales' => Gate::allows('view-sales') ? $total_sales : null,
            'warehouses' => Gate::allows('view-warehouses') ? $total_warehouses : null,
            'alerts' => Gate::allows('view-alerts') ? $total_alerts : null,
        ];

        // Build real chart datasets
        $now = now();

        // Last 7 days labels
        $days = collect(range(6, 0))->map(function ($i) use ($now) {
            return $now->copy()->subDays($i)->startOfDay();
        });

        // Sales daily totals (sum net_amount)
        $salesDailyRaw = Sale::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(COALESCE(net_amount, total)) as total'))
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('total', 'd')
            ->toArray();

        $salesDaily = $days->map(function ($day) use ($salesDailyRaw) {
            $key = $day->toDateString();
            return (float)($salesDailyRaw[$key] ?? 0);
        });

        // Orders daily count
        $ordersDailyRaw = Order::select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $ordersDaily = $days->map(function ($day) use ($ordersDailyRaw) {
            $key = $day->toDateString();
            return (int)($ordersDailyRaw[$key] ?? 0);
        });

        // Monthly sales last 12 months
        $months = collect(range(11, 0))->map(function ($i) use ($now) {
            return $now->copy()->subMonths($i)->startOfMonth();
        });

        $salesMonthlyRaw = Sale::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as m"), DB::raw('SUM(COALESCE(net_amount, total)) as total'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('m')
            ->orderBy('m')
            ->pluck('total', 'm')
            ->toArray();

        $salesMonthly = $months->map(function ($month) use ($salesMonthlyRaw) {
            $key = $month->format('Y-m');
            return (float)($salesMonthlyRaw[$key] ?? 0);
        });

        // Monthly orders count (last 12 months)
        $ordersMonthlyRaw = Order::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as m"), DB::raw('COUNT(*) as c'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('m')
            ->orderBy('m')
            ->pluck('c', 'm')
            ->toArray();

        $ordersMonthly = $months->map(function ($month) use ($ordersMonthlyRaw) {
            $key = $month->format('Y-m');
            return (int)($ordersMonthlyRaw[$key] ?? 0);
        });

        // Monthly sales count (number of Sale records)
        $salesCountMonthlyRaw = Sale::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as m"), DB::raw('COUNT(*) as c'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('m')
            ->orderBy('m')
            ->pluck('c', 'm')
            ->toArray();

        $salesCountMonthly = $months->map(function ($month) use ($salesCountMonthlyRaw) {
            $key = $month->format('Y-m');
            return (int)($salesCountMonthlyRaw[$key] ?? 0);
        });

        $daysLabels = $days->map(fn($d) => $d->format('D'));
        $monthsLabels = $months->map(fn($m) => $m->format('M'));

        return view('dashboard', compact(
            'user', 
            'stats',
            'total_users',
            'total_items',
            'total_orders', 
            'total_sales',
            'total_clients',
            'total_categories',
            'total_warehouses',
            'total_units',
            'daysLabels',
            'salesDaily',
            'ordersDaily',
            'monthsLabels',
            'salesMonthly', // income
            'ordersMonthly',
            'salesCountMonthly',
            'total_alerts'
        ));
    }
}




