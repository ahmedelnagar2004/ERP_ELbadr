<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreSaleRequest;
use App\Models\Client;
use App\Models\Item;
use App\Models\Safe;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'user'])->latest()->paginate(10);

        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $clients = Client::select('id', 'name')->where('status',0)->get();
        $items = Item::select('id', 'name', 'price', 'quantity')->where('is_shown_in_store', 1)->get();
        $safes = Safe::select('id', 'name')->where('status',1)->get();

        return view('admin.sales.create', compact('clients', 'items', 'safes'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            // 1️⃣ حساب المبالغ
            $subtotal = collect($request->items)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            $discount = $request->discount ?? 0;
            if (($request->discount_type ?? '') === 'percentage') {
                $discount = ($subtotal * $discount) / 100;
            }

            $netAmount = $subtotal - $discount + ($request->shipping_cost ?? 0);

            // 2️⃣ إنشاء الفاتورة
            $sale = Sale::create([
                'client_id' => $request->client_id,
                'user_id' => Auth::id(),
                'safe_id' => $request->safe_id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'total' => $subtotal,
                'discount' => $discount,
                'discount_type' => $request->discount_type ?? 'fixed',
                'shipping_cost' => $request->shipping_cost ?? 0,
                'net_amount' => $netAmount,
                'payment_type' => $request->payment_type,
                'order_date' => $request->order_date ?? now()->toDateString(),
                'notes' => $request->notes,
            ]);

            // 3️⃣ حساب المبالغ المدفوعة والمتبقية
            $paidAmount = $request->paid_amount ?? 0;
            if ($request->payment_type !== 'credit') {
                $paidAmount = $netAmount;
            }
            $remainingAmount = max(0, $netAmount - $paidAmount);

            $sale->update([
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
            ]);

            // 4️⃣ إضافة المنتجات وتحديث المخزون
            foreach ($request->items as $item) {
                $sale->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);

                Item::where('id', $item['item_id'])->decrement('quantity', $item['quantity']);
            }

            // 5️⃣ تحديث رصيد الخزنة إذا كان هناك دفع
            if ($paidAmount > 0) {
                $safe = Safe::find($request->safe_id);
                $safe->increment('balance', $paidAmount);

                // تسجيل العملية في سجل الخزنة
                $safe->transactions()->create([
                    'safe_id' => $safe->id,
                    'user_id' => Auth::id(),
                    'type' => 1, // 1 for inflow, 2 for outflow
                    'reference_type' => Sale::class,
                    'reference_id' => $sale->id,
                    'amount' => $paidAmount,
                    'description' => 'Payment received for Sale ID: ' . $sale->id,
                    'balance_after' => $safe->fresh()->balance,
                ]);
            }

            return $sale;
        });

        return redirect()->route('admin.sales.index')->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show($id)
    {
        $sale = Sale::with([
            'client',
            'user',
            'items' => function ($query) {
                $query->select([
                    'items.id',
                    'items.name',
                    'itemables.quantity as pivot_quantity',
                    'itemables.unit_price as pivot_unit_price',
                    'itemables.total_price as pivot_total_price',
                ]);
            },
        ])->findOrFail($id);

        return view('admin.sales.show', compact('sale'));
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        foreach ($sale->items as $item) {
            $item->increment('quantity', $item->pivot->quantity);
        }

        $sale->items()->detach();
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'تم حذف الفاتورة بنجاح');
    }

    public function print($id)
    {
        $sale = Sale::with(['client', 'items', 'user'])->findOrFail($id);
        return view('admin.sales.print', compact('sale'));
    }
}
