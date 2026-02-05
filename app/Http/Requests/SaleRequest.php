<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock_item_id' => ['required', 'exists:stock_items,id'],

            'customer_id'   => ['nullable', 'integer'],
            'customer_name' => ['nullable', 'string', 'max:255'],

            'payment_type'  => [
                'required',
                'in:' . Invoice::PAYMENT_CASH . ',' . Invoice::PAYMENT_INSTALLMENT
            ],

            'discount_type' => [
                'nullable',
                'in:' . Invoice::DISCOUNT_FIXED . ',' . Invoice::DISCOUNT_PERCENT
            ],
            'discount_value'=> ['nullable', 'numeric', 'min:0'],

            'promotion_code'=> ['nullable', 'string', 'max:100'],
        ];
    }
}
