<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Client;
use App\Http\Requests\Api\V1\app\StoreCartRequest;
use App\Http\Requests\Api\V1\app\UpdateCartRequest;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $client = $request->user();
        $cartItems = Cart::where('client_id', $client->id)->with('item')->get();
        return $this->responseApi($cartItems, 'تم استرجاع السلة بنجاح');
    }

    public function addToCart(StoreCartRequest $request)
    {
        $request->validated();
        $client = $request->user();
        $item = Item::find($request->item_id);
        $quantity = $request->quantity ?? 1;
        $price = $item->price; 
        $cartItem = Cart::where('client_id', $client->id)
                        ->where('item_id', $item->id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->total_price = $cartItem->quantity * $price;
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'client_id' => $client->id,
                'item_id' => $item->id,
                'price' => $price,
                'total_price' => $price * $quantity,
                'quantity' => $quantity,
            ]);
        }

        return $this->responseApi($cartItem, 'تم إضافة المنتج للسلة بنجاح');
    }

    public function updateCart(UpdateCartRequest $request, $id)
    {
        $request->validated();

        $client = $request->user();
        $cartItem = Cart::where('client_id', $client->id)->where('id', $id)->first();

        if (!$cartItem) {
            return $this->apiErrorMessage('العنصر غير موجود في السلة', 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->total_price = $cartItem->price * $request->quantity;
        $cartItem->save();

        return $this->responseApi($cartItem, 'تم تحديث الكمية بنجاح');
    }

    public function deleteFromCart(Request $request, $id)
    {
        $client = $request->user();
        $cartItem = Cart::where('client_id', $client->id)->where('id', $id)->first();

        if (!$cartItem) {
            return $this->apiErrorMessage('العنصر غير موجود في السلة', 404);
        }

        $cartItem->delete();

        return $this->responseApi(null, 'تم حذف العنصر من السلة بنجاح');
    }
}
