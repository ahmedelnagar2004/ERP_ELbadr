<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-units')->only(['index', 'show']);
        $this->middleware('permission:create-units')->only(['create', 'store']);
        $this->middleware('permission:edit-units')->only(['edit', 'update']);
        $this->middleware('permission:delete-units')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::paginate(15);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $request->persist();

        return redirect()->route('admin.units.index')
            ->with('success', 'تم إنشاء الوحدة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $unit->load('items');

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $request->persist($unit);

        return redirect()->route('admin.units.index')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        // Check if unit has items
        if ($unit->items()->count() > 0) {
            return redirect()->route('admin.units.index')
                ->with('error', 'لا يمكن حذف الوحدة لأنها مستخدمة في منتجات');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }
}
