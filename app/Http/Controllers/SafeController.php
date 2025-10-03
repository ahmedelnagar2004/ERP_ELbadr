<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreSafeRequest;
use App\Http\Requests\Admin\UpdateSafeRequest;
use App\Models\Safe;

class SafeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-safes', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-safes', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-safes', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-safes', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the safes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $safes = Safe::latest()->paginate(10);

        return view('admin.safes.index', compact('safes'));
    }

    public function create()
    {
        return view('admin.safes.create');
    }

    public function store(StoreSafeRequest $request)
    {
        $request->persist();

        return redirect()->route('admin.safes.index')->with('success', 'Safe created successfully');
    }

    /**
     * Display the specified safe.
     */
    public function show(Safe $safe)
    {
        $safe->load(['transactions' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.safes.show', compact('safe'));
    }

    public function edit(Safe $safe)
    {
        return view('admin.safes.edit', compact('safe'));
    }

    public function update(UpdateSafeRequest $request, Safe $safe)
    {
        $request->persist($safe);

        return redirect()->route('admin.safes.index')->with('success', 'Safe updated successfully');
    }

    public function destroy(Safe $safe)
    {
        $safe->delete();

        return redirect()->route('admin.safes.index')->with('success', 'Safe deleted successfully');
    }
}
