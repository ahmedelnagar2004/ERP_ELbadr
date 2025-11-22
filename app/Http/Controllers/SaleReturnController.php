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
use App\Enums\SaleStatusEnum;
use App\Models\Warehouse;
use App\Enums\safeTransactionTypeStatus;
use App\Enums\ClientStatus;
use App\Http\Requests\admin\StoreSaleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleReturnController extends Controller
{
    /**
     * Show the form for creating a new return.
     */
    public function create()
    {
        $clients = Client::select('id', 'name', 'phone')->where('status', ClientStatus::LOCAL->value)->get();
        $items = Item::select('id', 'name', 'price', 'quantity')->get();
        $safes = Safe::select('id', 'name')->get();
        $warehouses = Warehouse::select('id', 'name')->get();
        
        return view('admin.returns.create', [
            'clients' => $clients,
            'items' => $items,
            'safes' => $safes,
            'warehouses' => $warehouses,
            'salesSettings' => app(\App\Settings\SalesSettings::class),
        ]);
    }

    /**
     * Store a newly created return in storage.
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

            // Create return (Sale with type RETURN)
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth()->id(),
                'safe_id' => $validated['safe_id'],
                'invoice_number' => 'RET-' . strtoupper(Str::random(8)),
                'total' => $subtotal,
                'type' => SaleStatusEnum::RETURN->value,
                'discount' => $discount,
                'discount_type' => $validated['discount_type'] ?? 'fixed', 
                'shipping_cost' => $validated['shipping_cost'] ?? 0,
                'net_amount' => $netAmount,
                'paid_amount' => $actualPaidAmount,
                'remaining_amount' => $remainingAmount,
                'payment_type' => $validated['payment_type'],
                'order_date' => $validated['order_date'] ?? now()->toDateString(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Add return items
            foreach ($validated['items'] as $item) {
                $sale->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);
                // Increment item quantity (Return adds back to stock)
                Item::where('id', $item['item_id'])->increment('quantity', $item['quantity']);
            }

            // Create safe transaction (OUT - Money leaving safe to pay customer)
            if ($actualPaidAmount > 0) {
                SafeTransaction::create([
                    'type' => safeTransactionTypeStatus::out->value,
                    'amount' => $actualPaidAmount,
                    'description' => 'مرتجع فاتورة رقم '.$sale->id,
                    'balance_after' => Safe::find($validated['safe_id'])->balance - $actualPaidAmount, // Approximate, safer to fetch fresh
                    'user_id' => Auth()->id(),
                    'safe_id' => $validated['safe_id'],
                    'reference_id' => $sale->id,
                    'reference_type' => Sale::class,
                ]);
                // Update balance for safe (Decrement)
                Safe::where('id', $validated['safe_id'])->decrement('balance', $actualPaidAmount);
            }

            // Client Account Transaction
            // If Credit or Cash (and there is a remaining amount or full amount depending on logic)
            // For returns:
            // If Cash: We paid client. No debt change unless we track "Cash Sales" in client account. 
            // Usually, Client Account tracks DEBT.
            // If Credit: We owe client money or reduce their debt.
            // Let's follow SaleController logic but reversed.
            
            // In SaleController:
            // if credit or cash: Create Transaction (CREDIT - Client Pays/Owes?) -> Actually SaleController uses CREDIT enum for Sale.
            // Let's look at ClientAccountTransactionTypeEnum:
            // CREDIT = 1;  // دائن (Inflow) - العميل يدفع
            // DEBIT = 2;   // مدين (Outflow) - العميل يشتري بالآجل
            
            // In SaleController:
            // type => ClientAccountTransactionTypeEnum::CREDIT->value (1)
            // This seems wrong in SaleController if CREDIT means "Client Pays". A Sale on Credit means Client OWES (Debit).
            // However, if the Enum says CREDIT = Inflow (Client Pays), then a Sale should be DEBIT (Client takes goods, owes money).
            // Let's check SaleController again.
            // SaleController: type => ClientAccountTransactionTypeEnum::CREDIT->value.
            // If SaleController uses CREDIT for Sales, then Return should use DEBIT.
            
            // Wait, let's re-read the Enum in the previous turn.
            // CREDIT = 1; // دائن (Inflow) - العميل يدفع
            // DEBIT = 2; // مدين (Outflow) - العميل يشتري بالآجل
            
            // SaleController uses CREDIT (1). This implies "Client Pays" or "Inflow".
            // But a Sale increases what the client owes (if Credit) or is neutral (if Cash).
            // If SaleController adds to Client Balance using CREDIT, then Return should subtract using DEBIT.
            
            // Let's assume SaleController logic is the "Truth" for this system even if naming is confusing.
            // SaleController: Creates CREDIT transaction. Updates Client Balance (Increment).
            // So Return should: Create DEBIT transaction. Update Client Balance (Decrement).

            if($validated['payment_type'] === 'credit' || $validated['payment_type'] === 'cash' ){
                ClientAccountTransaction::create([
                    'type' => ClientAccountTransactionTypeEnum::DEBIT->value,
                    'amount' => $netAmount,
                    'description' => 'مرتجع فاتورة رقم '.$sale->id,
                    'balance_after' => Client::find($validated['client_id'])->balance - $netAmount, // Approximate
                    'user_id' => Auth()->id(),
                    'safe_id' => $validated['safe_id'],
                    'client_id' => $validated['client_id'],
                    'reference_id' => $sale->id,
                    'reference_type' => Sale::class,
                ]);
                
                // Update client account balance (Decrement)
                // In SaleController: if($sale->remaining_amount > 0) { increment balance }
                // For Return: We should decrement balance.
                // If it's a return, we owe the client. Or we reduce their debt.
                // If we reduce their debt, we decrement.
                if($sale->remaining_amount > 0){
                    Client::where('id', $validated['client_id'])->decrement('balance', $sale->remaining_amount);
                }

                // Create warehouse transaction (ADD - Items coming back)
                foreach ($validated['items'] as $item) {
                    WareHouseTransaction::create([
                        'item_id' => $item['item_id'],
                        'type' => WarehouseTransactionEnum::Add->value,
                        'warehouse_id' => $validated['warehouse_id'],
                        'quantity' => $item['quantity'],
                        'description' => 'مرتجع فاتورة رقم '.$sale->id,
                        'balance_after' => Item::find($item['item_id'])->quantity, // Already incremented above
                        'user_id' => Auth()->id(),
                        'reference_id' => $sale->id,
                        'reference_type' => Sale::class,
                    ]);
                }
            }

            return redirect()->route('admin.sales.show', $sale->id)
                ->with('success', 'تم إنشاء المرتجع بنجاح');
        });
    }
}
