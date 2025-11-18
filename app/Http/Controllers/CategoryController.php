<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\admin\UpdateCategoryRequest;
use App\Http\Requests\admin\StoreCategoryRequest;
use App\CategoryStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// Removed Laravel 11 static middleware interfaces to avoid conflicts

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index','show']);
        $this->middleware('permission:create-categories')->only(['create','store']);
        $this->middleware('permission:edit-categories')->only(['edit','update']);
        $this->middleware('permission:delete-categories')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $categories = Category::with('photo')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();
        
        // تحويل status من string إلى enum value
        $statusEnum = ($validated['status'] === 'active') ? CategoryStatus::Active : CategoryStatus::Inactive;

        $category = Category::create([
            'name' => $validated['name'],
            'status' => $statusEnum->value
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('categories', $filename, 'public');
            
            $category->photo()->create([
                'filename' => $filename,
                'path' => $path,
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'usage' => 'category_photo'
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->load(['photo', 'items']);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(storeCategoryRequest $request, Category $category)
    {
       

        $validated = $request->validated();
        $category->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($category->photo) {
                Storage::disk('public')->delete($category->photo->path);
                $category->photo->delete();
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('categories', $filename, 'public');
            
            $category->photo()->create([
                'filename' => $filename,
                'path' => $path,
                'ext' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'usage' => 'category_photo'
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has items
        if ($category->items()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على منتجات');
        }

        // Delete photo if exists
        if ($category->photo) {
            Storage::disk('public')->delete($category->photo->path);
            $category->photo->delete();
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}







