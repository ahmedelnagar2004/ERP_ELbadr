<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreClientRequest;
use App\enums\clientStatus;
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
        $clients = Client::paginate(25);

        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        // 1️⃣ تحويل حالة العرض من string إلى enum
        $statusEnum = $request->status === 'active'
            ? clientStatus::LOCAL
            : clientStatus:: WEBSITE;
        // 2️⃣ إنشاء العميل
        Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => $statusEnum->value,
            'balance' => $request->balance,
        ]);

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function show(Client $client)
    {
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $request->persist($client);

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }
}
