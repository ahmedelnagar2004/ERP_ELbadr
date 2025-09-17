<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients.index');
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        // Logic for storing client
        return redirect()->route('admin.clients.index');
    }

    public function show($id)
    {
        return view('admin.clients.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.clients.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Logic for updating client
        return redirect()->route('admin.clients.index');
    }

    public function destroy($id)
    {
        // Logic for deleting client
        return redirect()->route('admin.clients.index');
    }
}