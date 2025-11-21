<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\WarehouseTransaction;
use App\Enums\WareHouseTransactions as WarehouseTransactionEnum;
use App\Models\SafeTransaction;
use App\Models\ClientAccountTransaction;
use App\Enums\ClientAccountTransactionTypeEnum;
use App\Models\Item;
use App\Models\Client;
use App\Models\Safe;
use App\Models\Warehouse;
use App\Enums\safeTransactionTypeStatus;
use App\Enums\ClientStatus;
use App\Http\Requests\admin\StoreSaleRequest;
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
                    'name'
                ]);
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
        $clients = Client::select('id', 'name', 'phone')->where('status', ClientStatus::LOCAL->value)->get();
        $items = Item::select('id', 'name', 'price', 'quantity')->get();
        $safes = Safe::select('id', 'name')->get();
        $sale = Sale::select('id')->get();
        $warehouses = Warehouse::select('id', 'name')->get();
        
        return view('admin.sales.create', [
            'clients' => $clients,
            'items' => $items,
            'safes' => $safes,
            'warehouses' => $warehouses,
            'salesSettings' => app(\App\Settings\SalesSettings::class),
        ]);
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $validated = $request->validated();

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

            // Calculate paid and remaining amounts
            $actualPaidAmount = $validated['paid_amount'] ?? 0;
            
            // If payment type is cash/card/bank and no paid amount specified, use full amount
            if (in_array($validated['payment_type'], ['cash', 'card', 'bank']) && $actualPaidAmount == 0) {
                $actualPaidAmount = $netAmount;
            }
            
            // Ensure paid amount doesn't exceed net amount
            $actualPaidAmount = min($actualPaidAmount, $netAmount);
            $remainingAmount = $netAmount - $actualPaidAmount;

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
                'paid_amount' => $actualPaidAmount,
                'remaining_amount' => $remainingAmount,
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
                Item::where('id', $item['item_id'])->decrement('quantity', $item['quantity']);
                
                
            }
            //create safe transaction
            SafeTransaction::create([
               'type' => safeTransactionTypeStatus::in->value,
               'amount' => $sale->paid_amount,
               'description' => 'فاتوره رقم '.$sale->id,
               'balance_after' => $sale->paid_amount,
               'user_id' => Auth()->id(),
               'safe_id' => $validated['safe_id'],
               'reference_id' => $sale->id,
               'reference_type' => Sale::class,
            ]);
            //update balance for safe
             Safe::where('id', $validated['safe_id'])->increment('balance', $sale->paid_amount);

          if($validated['payment_type'] === 'credit' || $validated['payment_type'] === 'cash' ){
             ClientAccountTransaction::create([
                'type' => ClientAccountTransactionTypeEnum::CREDIT->value,
                'amount' => $netAmount,
                'description' => 'فاتوره رقم '.$sale->id,
                'balance_after' => $netAmount,
                'user_id' => Auth()->id(),
                'safe_id' => $validated['safe_id'],
                'client_id' => $validated['client_id'],
                'reference_id' => $sale->id,
                'reference_type' => Sale::class,
             ]);
             //update client account balance
                if($sale->remaining_amount > 0){
                    Client::where('id', $validated['client_id'])->increment('balance', $sale->remaining_amount);
                }
                //create warehouse transaction 
                WareHouseTransaction::create([
                    'item_id' => $item['item_id'],
                    'type' => WarehouseTransactionEnum::Remove->value,
                    'warehouse_id' => $validated['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'description' => 'فاتوره رقم '.$sale->id,
                    'balance_after' => $item['quantity'],
                    'user_id' => Auth()->id(),
                    'reference_id' => $sale->id,
                    'reference_type' => Sale::class,
                ]);
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
     * Print sale invoice.
     */
    public function print($id)
    {
        $sale = Sale::with(['client', 'items', 'user'])->findOrFail($id);
        return view('admin.sales.print', compact('sale'));
    }
}
