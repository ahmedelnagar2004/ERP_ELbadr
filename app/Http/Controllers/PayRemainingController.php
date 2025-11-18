<?php

namespace App\Http\Controllers;
use App\Models\client;
use App\Models\Safe;
use App\Models\User;
use App\Models\Sale;
use App\Enums\ClientAccountTransactionTypeEnum;
use App\Enums\SafeTransactionTypeStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayRemainingController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        $safes = Safe::all();
        return view('admin.payremaining.index', compact('clients', 'safes'));
    }

    public function create(Client $client)
    {
        $sales = Sale::where('client_id', $client->id)->get();
        $safes = Safe::all();
        return view('admin.payremaining.create', compact('client', 'safes', 'sales'));
    }

    public function store(Request $request)
    {
        $client = Client::findOrFail($request->client_id);
        $safe = Safe::findOrFail($request->safe_id);        
        // Calculate new balance
        $newBalance = $client->balance - $request->amount;
        
        // Create the transaction with the new balance
        $clientTransaction = $client->transactions()->create([
            'safe_id' => $request->safe_id,
            'user_id' => Auth::id(),
            'type' => ClientAccountTransactionTypeEnum::DEBIT->value,
            'reference_type' => Sale::class,
            'reference_id' => $request->sale_id,
            'amount' => $request->amount,
            'description' => 'فاتورة آجلة رقم: '.$request->sale_id,
            'balance_after' => $newBalance,
        ]);

        $safeTransaction = $safe->transactions()->create([
            'safe_id' => $request->safe_id,
            'user_id' => Auth::id(),
            'type' => SafeTransactionTypeStatus::in->value,
            'reference_type' => Sale::class,
            'reference_id' => $request->sale_id,
            'amount' => $request->amount,
            'description' => 'فاتورة آجلة رقم: '.$request->sale_id,
            'balance_after' => $newBalance,
        ]);

        // Update the client's balance
        $client->update([
            'balance' => $newBalance,
        ]);
        
        // Update the sale's remaining amount if sale_id is provided
        if ($request->sale_id) {
            $sale = Sale::find($request->sale_id);
            if ($sale) {
                $sale->decrement('remaining_amount', $request->amount);
            }

            if ($request->safe_id) {
                $safe = Safe::find($request->safe_id);
                if ($safe) {
                    $safe->increment('balance', $request->amount);
                }
            }

           
      
        
        return redirect()->route('admin.clients.show', $client->id)
            ->with('success', 'تم إضافة سداد متبقي بنجاح');   
    }        
    }

}
