<?php

namespace App\Http\Controllers;
use App\Models\Warehouse;
use App\Http\Requests\Admin\StoreItemRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Removed Laravel 11 static middleware interfaces to avoid conflicts

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-items')->only(['index', 'show']);
        $this->middleware('permission:create-items')->only(['create', 'store']);
        $this->middleware('permission:edit-items')->only(['edit', 'update']);
        $this->middleware('permission:delete-items')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'unit', 'mainPhoto'])->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        return view('admin.items.create', compact('categories', 'units', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( StoreItemRequest $request)
    {
        $validated = $request->validated();
        $validated['is_shown_in_store'] = (int) $validated['is_shown_in_store'];
        $item = Item::create($validated);   

        // رفع عدة صور وحفظها في جدول الصور المرتبط بالمنتج
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('items', $filename, 'public');
                $item->gallery()->create([
                    'filename' => $filename,
                    'path' => $path,
                    'ext' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'usage' => 'item_gallery'
                ]);
            }
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'تم إنشاء المنتج والصور بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['category', 'unit', 'mainPhoto', 'gallery']);
        return view('admin.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::where('status', 1)->get();
        
        $units = Unit::where('status', 1)->get();
        $warehouses = Warehouse::where('status', 1)->get();
        return view('admin.items.edit', compact('item', 'categories', 'units', 'warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreItemRequest $request, Item $item)
    {
       $data = $request->validated();
       $data['is_shown_in_store'] = (int) $data['is_shown_in_store'];
       
       $item->update($data);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($item->mainPhoto) {
                Storage::disk('public')->delete($item->mainPhoto->path);
                $item->mainPhoto->delete();
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('items', $filename, 'public');
            
            $item->mainPhoto()->create([
                'filename' => $filename,
                'path' => $path,
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'usage' => 'item_photo'
            ]);
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Check if item is used in orders or sales
        if ($item->orders()->count() > 0 || $item->sales()->count() > 0) {
            return redirect()->route('admin.items.index')
                ->with('error', 'لا يمكن حذف المنتج لأنه مستخدم في طلبات أو مبيعات');
        }

        // Delete photos if exist
        if ($item->mainPhoto) {
            Storage::disk('public')->delete($item->mainPhoto->path);
            $item->mainPhoto->delete();
        }

        foreach ($item->gallery as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }

        $item->delete();

        return redirect()->route('admin.items.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}





