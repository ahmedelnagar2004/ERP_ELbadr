<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreClientRequest;
use App\Http\Requests\Admin\UpdateClientRequest;
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
        $request->persist();

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);

        return view('admin.clients.show', compact('client'));
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);
        $request->persist($client);

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy($id)
    {
        // Logic for deleting client
        Client::destroy($id);

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }
}
