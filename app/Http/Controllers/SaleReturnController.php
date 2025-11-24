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
use App\Services\TransactionService;

class SaleReturnController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
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

            // Record return transactions via service
            $this->transactionService->recordReturnTransaction($sale, $validated);

            return redirect()->route('admin.sales.show', $sale->id)
                ->with('success', 'تم إنشاء المرتجع بنجاح');
        });
    }
}
