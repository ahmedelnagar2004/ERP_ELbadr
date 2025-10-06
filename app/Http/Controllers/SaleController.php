<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreSaleRequest;
use App\Models\Client;
use App\Models\Item;
use App\Models\Safe;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'user'])->latest()->paginate(10);

        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $clients = Client::select('id', 'name')->get();
        $items = Item::select('id', 'name', 'price', 'quantity')->get();
        $safes = Safe::select('id', 'name')->get();

        return view('admin.sales.create', compact('clients', 'items', 'safes'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = $request->persist();

        return redirect()->route('admin.sales.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show($id)
    {
        $sale = Sale::with([
            'client',
            'user',
            'items' => function ($query) {
                $query->select([
                    'items.id',
                    'items.name',
                    'itemables.quantity as pivot_quantity',
                    'itemables.unit_price as pivot_unit_price',
                    'itemables.total_price as pivot_total_price',
                ]);
            },
        ])->findOrFail($id);

        return view('admin.sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        foreach ($sale->items as $item) {
            $item->increment('quantity', $item->pivot->quantity);
        }

        $sale->items()->detach();
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'تم حذف الفاتورة بنجاح');
    }
    public function print($id)
    {
        $sale = Sale::with(['client', 'items', 'user'])->findOrFail($id);
        return view('admin.sales.print', compact('sale'));
    }
}
