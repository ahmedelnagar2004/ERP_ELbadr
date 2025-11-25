<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SafeTransaction;
use App\Models\Safe;
use App\Models\ClientAccountTransaction;
use App\Models\Client;
use App\Models\WareHouseTransaction;
use App\Models\Item;
use App\Enums\safeTransactionTypeStatus;
use App\Enums\ClientAccountTransactionTypeEnum;
use App\Enums\WareHouseTransactions as WarehouseTransactionEnum;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    /**
     * Record all transactions related to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return void
     */
    public function recordSaleTransaction(Sale $sale, array $data)
    {
        // 1. Safe Transaction
        if ($sale->paid_amount > 0) {
            SafeTransaction::create([
                'type' => safeTransactionTypeStatus::in->value,
                'amount' => $sale->paid_amount,
                'description' => 'فاتوره رقم ' . $sale->id,
                'balance_after' => $sale->paid_amount, // Note: This seems to be just the amount in the original code, but usually should be running balance. Keeping original logic for now but might need fix.
                'user_id' => Auth::id(),
                'safe_id' => $data['safe_id'],
                'reference_id' => 'فاتوره رقم '.$sale->id,
                'reference_type' => Sale::class,
            ]);

            Safe::where('id', $data['safe_id'])->increment('balance', $sale->paid_amount);
        }

       
        
        if (in_array($data['payment_type'], ['credit', 'cash', 'card', 'bank'])) {
             ClientAccountTransaction::create([
                 'type' => ClientAccountTransactionTypeEnum::CREDIT->value, // Sale is a Credit to the business (revenue), or Credit to Client Account? Enum says CREDIT.
                 'amount' => $sale->net_amount,
                 'description' => 'فاتوره رقم ' . $sale->id,
                 'balance_after' => $sale->net_amount, // Again, original logic seems to just put amount.
                 'user_id' => Auth::id(),
                 'safe_id' => $data['safe_id'],
                 'client_id' => $data['client_id'],
                 'reference_id' => 'فاتوره رقم '.$sale->id,
                 'reference_type' => Sale::class,
             ]);

             
             if ($sale->remaining_amount > 0) {
                 Client::where('id', $data['client_id'])->increment('balance', $sale->remaining_amount);
             }
        }

      
        foreach ($data['items'] as $itemData) {
            WareHouseTransaction::create([
                'item_id' => $itemData['item_id'],
                'type' => WarehouseTransactionEnum::Remove->value,
                'warehouse_id' => $data['warehouse_id'],
                'quantity' => $itemData['quantity'],
                'description' => 'فاتوره رقم ' . $sale->id,
                'balance_after' => $itemData['quantity'], // Original logic
                'user_id' => Auth::id(),
                'reference_id' => 'فاتوره رقم '.$sale->id,
                'reference_type' => Sale::class,
            ]);
            
         
        }
    }

    /**
     * Reverse all transactions related to a sale.
     *
     * @param Sale $sale
     * @return void
     */
    public function voidSaleTransaction(Sale $sale)
    {
        
        if ($sale->paid_amount > 0 && $sale->safe_id) {
            Safe::where('id', $sale->safe_id)->decrement('balance', $sale->paid_amount);
           
        }

        // 2. Reverse Client Balance
        if ($sale->remaining_amount > 0 && $sale->client_id) {
            Client::where('id', $sale->client_id)->decrement('balance', $sale->remaining_amount);
        }

       
    }

    /**
     * Record all transactions related to a return.
     *
     * @param Sale $sale
     * @param array $data
     * @return void
     */
    public function recordReturnTransaction(Sale $sale, array $data)
    {
        // 1. Safe Transaction (Money OUT)
        $actualPaidAmount = $sale->paid_amount;
        if ($actualPaidAmount > 0) {
            SafeTransaction::create([
                'type' => safeTransactionTypeStatus::out->value,
                'amount' => $actualPaidAmount,
                'description' => 'مرتجع فاتورة رقم ' . $sale->id,
                'balance_after' => Safe::find($data['safe_id'])->balance - $actualPaidAmount,
                'user_id' => Auth::id(),
                'safe_id' => $data['safe_id'],
                'reference_id' => $sale->id,
                'reference_type' => Sale::class,
            ]);
            
            Safe::where('id', $data['safe_id'])->decrement('balance', $actualPaidAmount);
        }

        // 2. Client Account Transaction (DEBIT - Reduce Debt or Owe Client)
        if (in_array($data['payment_type'], ['credit', 'cash', 'card', 'bank'])) {
             ClientAccountTransaction::create([
                 'type' => ClientAccountTransactionTypeEnum::DEBIT->value,
                 'amount' => $sale->net_amount,
                 'description' => 'مرتجع فاتورة رقم ' . $sale->id,
                 'balance_after' => Client::find($data['client_id'])->balance - $sale->net_amount,
                 'user_id' => Auth::id(),
                 'safe_id' => $data['safe_id'],
                 'client_id' => $data['client_id'],
                 'reference_id' => 'مرتجع فاتورة رقم '.$sale->id,
                 'reference_type' => Sale::class,
             ]);

             // Update client balance (Decrement)
             if ($sale->remaining_amount > 0) {
                 Client::where('id', $data['client_id'])->decrement('balance', $sale->remaining_amount);
             }
        }

        // 3. Warehouse Transaction (ADD - Return to Stock)
        foreach ($data['items'] as $itemData) {
            WareHouseTransaction::create([
                'item_id' => $itemData['item_id'],
                'type' => WarehouseTransactionEnum::Add->value,
                'warehouse_id' => $data['warehouse_id'],
                'quantity' => $itemData['quantity'],
                'description' => 'مرتجع فاتورة رقم ' . $sale->id,
                'balance_after' => Item::find($itemData['item_id'])->quantity, // Note: Controller increments quantity BEFORE calling this service usually? Or we should assume it's done.
                'user_id' => Auth::id(),
                'reference_id' =>'مرتجع فاتورة رقم ' . $sale->id,
                'reference_type' => Sale::class,
            ]);
        }
    }
}
