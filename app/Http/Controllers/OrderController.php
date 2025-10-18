<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        // Logic for storing order

        return redirect()->route('admin.orders.index');
    }

    public function show($id)
    {
        return view('admin.orders.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.orders.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Logic for updating order
        return redirect()->route('admin.orders.index');
    }

    public function destroy($id)
    {
        // Logic for deleting order
        return redirect()->route('admin.orders.index');
    }
}
