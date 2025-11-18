<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\SafeStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\admin\StoreSafeRequest;
use App\Enums\safeTransactionTypeStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validated();
        $safe = Safe::create($validated);
       
        return redirect()->route('admin.safes.index')->with('success', 'Safe created successfully');
        
    }



    public function show(Safe $safe)
    {
        $transactions = $safe->transactions()->with(['user', 'reference'])->latest()->paginate(20);
        return view('admin.safes.show', compact('safe', 'transactions'));
    }

    public function edit(Safe $safe)
    {
        return view('admin.safes.edit', compact('safe'));
    }
    public function update(StoreSafeRequest $request, Safe $safe)
    {
        $safe->update($request->validated());
        return redirect()->route('admin.safes.index')->with('success', 'Safe updated successfully');
    }
    public function destroy(Safe $safe)
    {
        $safe->delete();
        return redirect()->route('admin.safes.index')->with('success', 'Safe deleted successfully');
    }
}
    





