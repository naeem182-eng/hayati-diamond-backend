<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'product_id',
        'serial_no',
        'ring_size',
        'gold_weight_actual',
        'gold_price_at_make',
        'diamond_detail',
        'total_cost',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }
}
