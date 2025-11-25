<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Item;
use App\Models\WareHouseTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesExport;
use App\Exports\ItemTransactionsExport;
use App\Exports\ClientsExport;
use App\Exports\ProductsExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $query = Sale::with(['client', 'items']);

        if ($request->filled('start_date')) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('item_id')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('items.id', $request->item_id);
            });
        }

        $sales = $query->latest()->get();

        if ($request->export === 'excel') {
            return Excel::download(new SalesExport($sales), 'sales_report_' . date('Y-m-d') . '.xlsx');
        }

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.sales', compact('sales'));
            return $pdf->download('sales_report_' . date('Y-m-d') . '.pdf');
        }

        $clients = Client::all();
        $items = Item::all();

        return view('admin.reports.sales', compact('sales', 'clients', 'items'));
    }

    public function itemTransactions(Request $request)
    {
        $query = WareHouseTransaction::with(['item', 'warehouse', 'user']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        $transactions = $query->latest()->get();

        if ($request->export === 'excel') {
            return Excel::download(new ItemTransactionsExport($transactions), 'item_transactions_' . date('Y-m-d') . '.xlsx');
        }

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.item_transactions', compact('transactions'));
            return $pdf->download('item_transactions_' . date('Y-m-d') . '.pdf');
        }

        $items = Item::all();

        return view('admin.reports.item_transactions', compact('transactions', 'items'));
    }

    public function clients(Request $request)
    {
        $query = Client::withCount('sales')->withSum('sales', 'net_amount');

        if ($request->filled('client_id')) {
            $query->where('id', $request->client_id);
        }

        $clientsData = $query->get();

        if ($request->export === 'excel') {
            return Excel::download(new ClientsExport($clientsData), 'clients_report_' . date('Y-m-d') . '.xlsx');
        }

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.clients', compact('clientsData'));
            return $pdf->download('clients_report_' . date('Y-m-d') . '.pdf');
        }

        $clients = Client::all(); // For filter dropdown

        return view('admin.reports.clients', compact('clientsData', 'clients'));
    }

    public function products(Request $request)
    {
        $query = Item::with(['category', 'unit', 'warehouse']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('min_quantity')) {
            $query->where('quantity', '>=', $request->min_quantity);
        }

        if ($request->filled('max_quantity')) {
            $query->where('quantity', '<=', $request->max_quantity);
        }

        if ($request->filled('low_stock')) {
            $query->whereColumn('quantity', '<=', 'minimum_stock');
        }

        $products = $query->latest()->get();

        if ($request->export === 'excel') {
            return Excel::download(new ProductsExport($products), 'products_report_' . date('Y-m-d') . '.xlsx');
        }

        if ($request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf.products', compact('products'));
            return $pdf->download('products_report_' . date('Y-m-d') . '.pdf');
        }

        $categories = \App\Models\Category::all();
        $warehouses = \App\Models\Warehouse::all();

        return view('admin.reports.products', compact('products', 'categories', 'warehouses'));
    }
}
