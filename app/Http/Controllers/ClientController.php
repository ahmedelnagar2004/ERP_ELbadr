<?php

namespace App\Http\Controllers;
use App\Http\Requests\admin\StoreClientRequest;
use App\Enums\ClientStatus;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-clients')->only(['index', 'show']);
        $this->middleware('permission:create-clients')->only(['create', 'store']);
        $this->middleware('permission:edit-clients')->only(['edit', 'update']);
        $this->middleware('permission:delete-clients')->only(['destroy']);
    }

    public function index()
    {
        $clients = Client::latest()->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        $validated['status'] = ClientStatus::from($validated['status']);
        $validated['balance'] = $validated['balance'] ?? 0;
        Client::create($validated);
        return redirect()->route('admin.clients.index');
    }

    public function show($id)
    {
        $client = Client::with(['transactions' => function($query) {
            $query->latest()->limit(50);
        }])->findOrFail($id);
        return view('admin.clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
        ]);
        $client = Client::findOrFail($id);
        $client->update($validated);
        
        return redirect()->route('admin.clients.index');
    }

    public function destroy($id)
    {
        // Logic for deleting client
        Client::destroy($id);
        return redirect()->route('admin.clients.index');
    }
}