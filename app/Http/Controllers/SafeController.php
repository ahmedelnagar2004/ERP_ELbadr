<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|boolean',
            'status' => 'required|boolean',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'account_number' => 'required|string|max:255',
         //   'branch_id' => 'required|exists:branches,id',
            
        ]);
        Safe::create($request->all());
        return redirect()->route('admin.safes.index')->with('success', 'Safe created successfully');
        
    }



    public function edit(Safe $safe)
    {
        return view('admin.safes.edit', compact('safe'));
    }
    public function update(Request $request, Safe $safe)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|boolean',
            'status' => 'required|boolean',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'account_number' => 'required|string|max:255',
         //   'branch_id' => 'required|exists:branches,id',
            
        ]);
        
        $safe->update($request->all());
        return redirect()->route('admin.safes.index')->with('success', 'Safe updated successfully');
    }
    public function destroy(Safe $safe)
    {
        $safe->delete();
        return redirect()->route('admin.safes.index')->with('success', 'Safe deleted successfully');
    }
}