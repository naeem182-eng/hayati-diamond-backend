<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    public const STATUS_IN_STOCK = 'IN_STOCK';
    public const STATUS_SOLD = 'SOLD';

    protected $fillable = [
        'product_id',
        'serial_no',
        'ring_size',
        'gold_weight_actual',
        'gold_price_at_make',
        'diamond_detail',
        'total_cost',
        'price_sell',
        'status',
    ];

    /**
     * StockItem belongs to a Product (design / model)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * StockItem can be sold once → has one invoice item
     */
    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }
}

