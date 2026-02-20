<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],

            'serial_no' => ['nullable', 'string', 'max:255'],
            'ring_size' => ['nullable', 'string', 'max:50'],

            'gold_weight_actual' => ['nullable', 'numeric', 'min:0'],
            'gold_price_at_make' => ['nullable', 'numeric', 'min:0'],

            'diamond_detail' => ['nullable', 'string'],

            'total_cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
