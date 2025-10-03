<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

// Removed Laravel 11 static middleware interfaces to avoid conflicts

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['create', 'store']);
        $this->middleware('permission:edit-categories')->only(['edit', 'update']);
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
        $request->persist();

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
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $request->persist($category);

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
