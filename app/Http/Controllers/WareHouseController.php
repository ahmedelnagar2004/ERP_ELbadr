<?php

namespace App\Http\Controllers;
use App\Enums\WarehouseStatus;
use App\Models\Item;
use App\Models\WareHouseTransaction;
use App\Models\User;
use App\Http\Requests\StoreWareHouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    
    public function index()
    {
        $warehouses = Warehouse::paginate(10);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWareHouseRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = (int) $validated['status'];
        
        $warehouse = Warehouse::create($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'تم إنشاء المستودع بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        // Load withdrawal transactions (type = Remove) with related item and user
        $withdrawalTransactions = $warehouse->wareHouseTransaction()
            ->where('type', \App\Enums\WareHouseTransactions::Remove->value)
            ->with(['item', 'user'])
            ->latest()
            ->paginate(10);

        // Eager load items
        $warehouse->load('items');

        // Manually set the items_count if not already loaded
        if (!isset($warehouse->items_count)) {
            $warehouse->items_count = $warehouse->items->count();
        }

        return view('admin.warehouses.show', compact('warehouse', 'withdrawalTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreWareHouseRequest $request, Warehouse $warehouse)
    {
        $validated = $request->validated();
        $validated['status'] = (int) $request->status;
        
        $warehouse->update($validated);
        
        return redirect()->route('admin.warehouses.index')
            ->with('success', 'تم تحديث المستودع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        if($warehouse->items()->count() > 0){
            return redirect()->route('admin.warehouses.index')->with('error', 'Warehouse deleted failed');
        }

        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse deleted successfully');
    }
}





