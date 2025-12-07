<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\ShippingAddress;
use App\Models\ClientAccountTransaction;
use App\Models\Item;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\ClientAccountTransactionTypeEnum;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder($client, $shippingData, $paymentMethod = null)
    {

        // 1. Validate Cart & Stock
        $cartItems = Cart::where('client_id', $client->id)->get();
        if ($cartItems->isEmpty()) {
            throw new \Exception('السلة فارغة');
        }

        foreach ($cartItems as $cartItem) {
            $item = Item::find($cartItem->item_id);
            
            if (!$item) {
                throw new \Exception("المنتج (ID: {$cartItem->item_id}) غير موجود");
            }

            if ($item->quantity < $cartItem->quantity) {
                 throw new \Exception("الكمية المطلوبة للمنتج '{$item->name}' غير متوفرة. المتاح: {$item->quantity}");
            }
        }

        // 2. Calculate Totals
        $totalPrice = $cartItems->sum('total_price');
        $shippingCost = 0;
        $grandTotal = $totalPrice + $shippingCost;

        return DB::transaction(function () use ($client, $cartItems, $totalPrice, $shippingCost, $grandTotal, $shippingData, $paymentMethod) {
            
            // 3. Create Order
            $order = Order::create([
                'client_id' => $client->id,
                'status' => OrderStatus::PROCESSING->value,
                'payment_method' => $paymentMethod ?? PaymentMethod::CASH->value,
                'price' => $totalPrice,
                'shipping_cost' => $shippingCost,
                'total_price' => $grandTotal,
            ]);

            // 4. Create Shipping Address
            ShippingAddress::create([
                'order_id' => $order->id,
                'name' => $shippingData['name'],
                'email' => $shippingData['email'],
                'phone' => $shippingData['phone'],
                'address' => $shippingData['address'],
            ]);

            // 5. Create Order Items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'unit_price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'total_price' => $cartItem->total_price,
                ]);
            }

            // 6. Record Client Account Transaction (For tracking purposes only)
            // Note: Balance is NOT updated because payment is Cash on Delivery
            // ClientAccountTransaction::create([
            //     'client_id' => $client->id,
            //     'safe_id' => 1 ?? null,
            //     'amount' => $grandTotal,
            //     'type' => ClientAccountTransactionTypeEnum::CREDIT->value, 
            //     'reference_type' => Order::class,
            //     'reference_id' => $order->id,
            //     'user_id' => 1, 
            //     'balance_after' => $client->balance, // Balance unchanged
            //     'description' => 'طلب رقم #' . $order->id . ' (دفع عند الاستلام)',
            // ]);

            // 7. Clear Cart
            Cart::where('client_id', $client->id)->delete();

            return $order->load(['items', 'shippingAddress']);
        });
    }
}
