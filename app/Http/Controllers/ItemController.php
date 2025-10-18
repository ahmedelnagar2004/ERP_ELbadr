<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreItemRequest;
use App\Http\Requests\Admin\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\ItemStatus;
use Illuminate\Support\Facades\Storage;

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
        $categories = Category::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();

        return view('admin.items.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        // 1️⃣ تحويل حالة العرض من string إلى enum
        $statusEnum = $request->is_shown_in_store === 'shown'
            ? ItemStatus::Shown
            : ItemStatus::Hidden;

        // 2️⃣ إنشاء المنتج
        $item = Item::create([
            'name' => $request->name,
            'item_code' => $request->item_code,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'is_shown_in_store' => $statusEnum->value(),
            'minimum_stock' => $request->minimum_stock,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'allow_decimal' => $request->allow_decimal ?? false,
        ]);

        // 3️⃣ رفع الصور المرتبطة بالمنتج
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('items', $filename, 'public');

                $item->gallery()->create([
                    'filename' => $filename,
                    'path' => $path,
                    'ext' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'usage' => 'item_gallery',
                ]);
            }
        }

        return redirect()->route('admin.items.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
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

        // تحميل الصور المرتبطة بالمنتج
        $item->load(['mainPhoto', 'gallery']);

        return view('admin.items.edit', compact('item', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        // 1️⃣ تحويل حالة العرض من string إلى enum
        $statusEnum = $request->is_shown_in_store === 'shown'
            ? ItemStatus::Shown
            : ItemStatus::Hidden;

        // 2️⃣ تحديث المنتج
        $item->update([
            'name' => $request->name,
            'item_code' => $request->item_code,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'is_shown_in_store' => $statusEnum->value(),
            'minimum_stock' => $request->minimum_stock,
            'category_id' => $request->category_id,
            'unit_id' => $request->unit_id,
            'allow_decimal' => $request->allow_decimal ?? false,
        ]);

        // 3️⃣ رفع الصور الجديدة إن وجدت
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $path = $file->storeAs('items', $filename, 'public');

                $item->gallery()->create([
                    'filename' => $filename,
                    'path' => $path,
                    'ext' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'usage' => 'item_gallery',
                ]);
            }
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
