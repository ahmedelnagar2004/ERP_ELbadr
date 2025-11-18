<?php

namespace App\Http\Controllers;
use App\Http\Requests\admin\StoreUnitRequest;
use App\Http\Requests\admin\UpdateUnitRequest;
use App\UnitStatus;
use App\Models\Unit;
use Illuminate\Http\Request;

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
        $validated = $request->validated();
        
        // تحويل status من string إلى enum value
        $statusEnum = UnitStatus::fromString($validated['status']);
        
        Unit::create([
            'name' => $validated['name'],
            'status' => $statusEnum->value
        ]);

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
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
            'status' => 'required|boolean'
        ]);

        $unit->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

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



