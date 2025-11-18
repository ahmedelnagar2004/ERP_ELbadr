<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;

class AlertQuantityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:alert-quantity');
    }

    public function index()
    {
      
        $items = Item::whereColumn('quantity', '<=', 'minimum_stock')->get();

        return view('admin.alerts.index', compact('items'));
    }


}
