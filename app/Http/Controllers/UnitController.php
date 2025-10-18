<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use App\Models\Unit;
use App\UnitStatus;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-units')->only(['index', 'show']);
        $this->middleware('permission:create-units')->only(['create', 'store']);
        $this->middleware('permission:edit-units')->only(['edit', 'update']);
        $this->middleware('permission:delete-units')->only(['destroy']);
    }

    public function index()
    {
        $units = Unit::paginate(15);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(StoreUnitRequest $request)
    {
        $statusEnum = UnitStatus::fromString($request->status);

        $unit = new Unit();
        $unit->name = $request->name;
        $unit->status = $statusEnum->value();
        $unit->save();

        return redirect()->route('admin.units.index')
            ->with('success', 'تم إنشاء الوحدة بنجاح');
    }

    public function show(Unit $unit)
    {
        $unit->load('items');
        return view('admin.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $statusEnum = UnitStatus::fromString($request->status);

        $unit->update([
            'name' => $request->name,
            'status' => $statusEnum->value(),
        ]);

        return redirect()->route('admin.units.index')
            ->with('success', 'تم تحديث الوحدة بنجاح');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->items()->count() > 0) {
            return redirect()->route('admin.units.index')
                ->with('error', 'لا يمكن حذف الوحدة لأنها مستخدمة في منتجات');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'تم حذف الوحدة بنجاح');
    }
}
