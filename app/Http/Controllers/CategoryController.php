<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['create', 'store']);
        $this->middleware('permission:edit-categories')->only(['edit', 'update']);
        $this->middleware('permission:delete-categories')->only(['destroy']);
    }

    public function index()
    {

        $categories = Cache::remember('categories_page_'.request('page', 1), 60, function () {
            return Category::with('photo')->paginate(15);
        });

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * تخزين فئة جديدة في قاعدة البيانات
     */
    public function store(StoreCategoryRequest $request)
    {
        $request->persist();

        Cache::flush();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    public function show(Category $category)
    {

        $category->load('photo');
        $items = $category->items()->with('photo')->paginate(15);

        return view('admin.categories.show', compact('category', 'items'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $request->persist($category);

        Cache::flush();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    public function destroy(Category $category)
    {

        if ($category->items()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على منتجات');
        }

        if ($category->photo) {
            Storage::disk('public')->delete($category->photo->path);
            $category->photo->delete();
        }

        $category->delete();

        Cache::flush();

        return redirect()->route('admin.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}
