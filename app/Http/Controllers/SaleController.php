<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Item;
use App\Models\Client;
use App\Models\Safe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    /**
     * Display a listing of sales with eager loading.
     */
    public function index()
    {
        $sales = Sale::with([
            'client:id,name,phone',
            'user:id,full_name as name',
            'items' => function($query) {
                $query->select([
                    'items.id', 
                    'name', 
                    'sale_items.quantity', 
                    'sale_items.unit_price', 
                    'sale_items.total_price'
                ])->join('sale_items', 'items.id', '=', 'sale_items.item_id');
            }
        ])
        ->latest()
        ->paginate(15);

        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $clients = Client::select('id', 'name', 'phone')->get();
        $items = Item::select('id', 'name', 'price', 'quantity')->get();
        $safes = Safe::select('id', 'name')->get();
        
        return view('admin.sales.create', [
            'clients' => $clients,
            'items' => $items,
            'safes' => $safes
        ]);
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'payment_type' => 'required|in:cash,card,bank,credit',
            'safe_id' => 'required_if:payment_type,!=,credit|exists:safes,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'shipping_cost' => 'nullable|numeric|min:0',
            'order_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated) {
            // Calculate totals
            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });

            $discount = $validated['discount'] ?? 0;
            if (($validated['discount_type'] ?? '') === 'percentage') {
                $discount = ($subtotal * $discount) / 100;
            }

            $netAmount = $subtotal - $discount + ($validated['shipping_cost'] ?? 0);

            // Create sale
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth()->id(),
                'safe_id' => $validated['safe_id'],
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'total' => $subtotal,
                'discount' => $discount,
                'discount_type' => $validated['discount_type'] ?? 'fixed', 
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'net_amount' => $netAmount,
                'paid_amount' => $validated['payment_type'] === 'credit' ? 0 : $netAmount,
                'remaining_amount' => $validated['payment_type'] === 'credit' ? $netAmount : 0,
                'payment_type' => $validated['payment_type'],
                'order_date' => $validated['order_date'] ?? now()->toDateString(),
                'notes' => $validated['notes'] ?? 'fixed',
            ]);

            // Add sale items
            foreach ($validated['items'] as $item) {
                $sale->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);

                // Update item stock
                // if (isset($item['update_stock']) && $item['update_stock']) {
                    // dd('yes');
                    Item::where('id', $item['item_id'])->decrement('quantity', $item['quantity']);
                // }
                // dd('no');
            }

            return redirect()->route('admin.sales.show', $sale->id)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');
        });
    }

    /**
     * Display the specified sale with eager loading.
     */
    public function show($id)
    {
        $sale = Sale::with([
            'client:id,name,phone',
            'user:id,full_name as name',
            'items' => function($query) {
                $query->select([
                    'items.id', 
                    'items.name',
                    'itemables.quantity as pivot_quantity',
                    'itemables.unit_price as pivot_unit_price',
                    'itemables.total_price as pivot_total_price'
                ]);
            },
            'safeTransactions'
        ])->findOrFail($id);

        return view('admin.sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     * Remove the specified sale from storage.
     */
    public function destroy($id)
    {
        $sale = Sale::with(['items', 'safeTransactions'])->findOrFail($id);

        if ($sale->status === 'completed') {
            return back()->with('error', 'لا يمكن حذف فاتورة مكتملة');
        }

        DB::transaction(function () use ($sale) {
            // Return items to stock
            foreach ($sale->items as $item) {
                $item->increment('quantity', $item->pivot->quantity);
            }
            
            // Detach items
            $sale->items()->detach();
            
            // Delete safe transactions if the relationship exists
            if (method_exists($sale, 'safeTransactions') && $sale->safeTransactions) {
                $sale->safeTransactions()->delete();
            }
            
            // Delete the sale
            $sale->delete();
        });

        return redirect()->route('admin.sales.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    /**
     * Mark sale as completed.
     */
    public function complete($id)
    {
        $sale = Sale::findOrFail($id);

        if ($sale->status !== 'completed') {
            $sale->update(['status' => 'completed']);
            return back()->with('success', 'تم إتمام الفاتورة بنجاح');
        }

        return back()->with('info', 'الفاتورة مكتملة مسبقاً');
    }

    /**
     * Print sale invoice.
     */
    public function print($id)
    {
        $sale = Sale::with(['client', 'items', 'user'])->findOrFail($id);
        return view('admin.sales.print', compact('sale'));
    }
}
