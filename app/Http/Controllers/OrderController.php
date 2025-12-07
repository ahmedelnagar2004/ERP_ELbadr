<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['client', 'items'])->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $order = Order::create($request->all());
        return redirect()->route('admin.orders.index')->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function show($id)
    {
        $order = Order::with(['items', 'shippingAddress'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with(['items', 'shippingAddress'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:1,2,3,4',
        ]);

        $order = Order::with('items')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = (int) $request->status;

        if ($oldStatus === OrderStatus::DELIVERED->value) {
            return redirect()->back()->with('error', 'لا يمكن تغيير حالة الطلب بعد التسليم');
        }

        DB::beginTransaction();
        try {
            $order->update(['status' => $newStatus]);

            // If status changed to DELIVERED, create Sale and Decrement Inventory
            if ($newStatus === OrderStatus::DELIVERED->value && $oldStatus !== OrderStatus::DELIVERED->value) {
                
                // 1. Create Sale (Invoice)
                $sale = Sale::create([
                    'client_id' => $order->client_id,
                    'user_id' => auth()->id() ?? 1,
                    'safe_id' => 1,
                    'total' => $order->price,
                    'discount' => 0,
                    'shipping_cost' => $order->shipping_cost,
                    'net_amount' => $order->total_price,
                    'invoice_number' => 'INV-' . time(),
                    'type' => 0,
                    'payment_type' => \App\Enums\PaymentMethod::COLLECTION_FROM_SHIPPING_COMPANY->value,
                    'order_date' => now(),
                ]);

                // 2. Process Items - Decrement Inventory
                foreach ($order->items as $orderItem) {
                    // Create Sale Item (Polymorphic)
                    DB::table('itemables')->insert([
                        'itemable_id' => $sale->id,
                        'itemable_type' => 'App\Models\Sale',
                        'item_id' => $orderItem->id,
                        'quantity' => $orderItem->pivot->quantity,
                        'unit_price' => $orderItem->pivot->unit_price,
                        'total_price' => $orderItem->pivot->total_price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    // Decrement Inventory
                    $item = Item::find($orderItem->id);
                    if ($item) {
                        $item->decrement('quantity', $orderItem->pivot->quantity);
                    }
                }
                
                // 3. Link Sale to Order
                $order->update(['sale_id' => $sale->id]);
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', 'تم تحديث حالة الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}