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
    // private mixed $payment_type;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_type' => ['required', \Illuminate\Validation\Rule::in(app(\App\Settings\SalesSettings::class)->enabled_payment_methods)],
            'safe_id' => 'required_unless:payment_type,credit|exists:safes,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => [
                'required', 
                'numeric', 
                'min:0.01', 
                function ($attribute, $value, $fail) {
                    $salesSettings = app(\App\Settings\SalesSettings::class);
                    
                    // Check decimal quantities
                    if (!$salesSettings->allow_decimal_quantities && $value != (int) $value) {
                        $fail('الكميات العشرية غير مسموح بها.');
                        return;
                    }
                    
                    // Check stock availability (only if negative stock is NOT allowed)
                    if (!$salesSettings->allow_negative_stock) {
                        // Extract item_id from attribute path
                        $index = explode('.', $attribute)[1];
                        $itemId = request()->input("items.{$index}.item_id");
                        
                        if ($itemId) {
                            $item = Item::find($itemId);
                            if ($item && $item->quantity < $value) {
                                $fail("الكمية المتاحة للمنتج '{$item->name}' هي {$item->quantity} فقط.");
                            }
                        }
                    }
                }
            ],
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

}
