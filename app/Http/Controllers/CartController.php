<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cart;
use App\Models\Cart as CartModel;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Group by client to show distinct carts
        $carts = CartModel::with('client')
            ->select('client_id', \Illuminate\Support\Facades\DB::raw('count(*) as items_count'), \Illuminate\Support\Facades\DB::raw('sum(total_price) as total_amount'))
            ->groupBy('client_id')
            ->get();
            
        return view('admin.cart.index', compact('carts'));
    }

    public function show($id)
    {
        // $id here represents client_id because we are viewing a client's cart
        $client = \App\Models\Client::find($id);
        
        if (!$client) {
            return redirect()->route('admin.cart.index')->with('error', 'العميل غير موجود');
        }

        $cartItems = CartModel::where('client_id', $id)->with('item')->get();
        
        if ($cartItems->isEmpty()) {
             return redirect()->route('admin.cart.index')->with('warning', 'سلة العميل فارغة');
        }

        return view('admin.cart.show', compact('client', 'cartItems'));
    }
}
