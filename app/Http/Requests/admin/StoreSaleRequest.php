<?php

namespace App\Http\Requests\Admin;

use App\Models\Item;
use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'payment_type' => 'required|in:cash,card,bank,credit',
            'safe_id' => 'required_unless:payment_type,credit|exists:safes,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'shipping_cost' => 'nullable|numeric|min:0',
            'order_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'paid_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'العميل مطلوب',
            'client_id.exists' => 'العميل المحدد غير صالح',
            'payment_type.required' => 'نوع الدفع مطلوب',
            'payment_type.in' => 'نوع الدفع يجب أن يكون نقدي أو كارت أو بنكي أو آجل',
            'safe_id.required_unless' => 'الخزنة مطلوبة لطرق الدفع النقدية',
            'safe_id.exists' => 'الخزنة المحددة غير صالحة',
            'items.required' => 'يجب إضافة منتج واحد على الأقل',
            'items.*.item_id.exists' => 'المنتج المحدد غير صالح',
            'items.*.quantity.min' => 'الكمية يجب أن تكون أكبر من صفر',
        ];
    }

    /**
     * حفظ الفاتورة والمنتجات المرتبطة بها داخل transaction آمنة.
     */
    public function persist(): Sale
    {
        return DB::transaction(function () {
            $subtotal = collect($this->items)->sum(function ($item) {
                return $item['quantity'] * $item['price'];
            });
            $discount = $this->discount ?? 0;
            if (($this->discount_type ?? '') === 'percentage') {
                $discount = ($subtotal * $discount) / 100;
            }

            $netAmount = $subtotal - $discount + ($this->shipping_cost ?? 0);

            $sale = Sale::create([
                'client_id' => $this->client_id,
                'user_id' => Auth::id(),
                'safe_id' => $this->safe_id,
                'invoice_number' => 'INV-'.strtoupper(Str::random(8)),
                'total' => $subtotal,
                'discount' => $discount,
                'discount_type' => $this->discount_type ?? 'fixed',
                'shipping_cost' => $this->shipping_cost ?? 0,
                'net_amount' => $netAmount,
                'paid_amount' => $this->payment_type === 'credit' ? 0 : $netAmount,
                'remaining_amount' => $this->payment_type === 'credit' ? $netAmount : 0,
                'payment_type' => $this->payment_type,
                'order_date' => $this->order_date ?? now()->toDateString(),
                'notes' => $this->notes,
            ]);
            $paidAmount = $this->paid_amount ?? 0;
            if ($this->payment_type !== 'credit') {
                $paidAmount = $netAmount; 
            }
            $remainingAmount = max(0, $netAmount - $paidAmount);

          
            $sale->update([
                'paid_amount' => $paidAmount,
                'remaining_amount' => $remainingAmount,
            ]);

            
            foreach ($this->items as $item) {
                $sale->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);

           
                Item::where('id', $item['item_id'])->decrement('quantity', $item['quantity']);
            }

            return $sale;
        });
    }
}
