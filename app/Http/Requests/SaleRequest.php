<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;
use Illuminate\Validation\Rule;

class SaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stock_item_ids'   => ['required', 'array', 'min:1'],
            'stock_item_ids.*' => ['exists:stock_items,id'],

            'sale_prices'   => ['required', 'array'],
            'sale_prices.*' => ['nullable', 'numeric', 'min:0.01'],

            'customer_id'   => ['nullable', 'integer'],
            'customer_name' => ['nullable', 'string', 'max:255'],

            'payment_type'  => [
                'required',
                Rule::in([
                    Invoice::PAYMENT_CASH,
                    Invoice::PAYMENT_INSTALLMENT
                ]),
            ],

            'installment_months' => [
                'nullable',
                Rule::requiredIf(
                    $this->input('payment_type') === Invoice::PAYMENT_INSTALLMENT
                ),
                'integer',
                'min:1',
            ],

            'discount_type' => [
                'nullable',
                Rule::in([
                    Invoice::DISCOUNT_FIXED,
                    Invoice::DISCOUNT_PERCENT
                ]),
            ],

            'discount_value' => ['nullable', 'numeric', 'min:0'],

            'promotion_code' => ['nullable', 'string', 'max:100'],
        ];
    }
}
