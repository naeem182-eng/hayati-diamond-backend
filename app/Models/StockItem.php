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
        'status',
    ];

    protected $appends = ['market_reference', 'current_gold_price', 'gold_difference'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function invoiceItem()
    {
        return $this->hasOne(InvoiceItem::class);
    }

    public function getCurrentGoldPriceAttribute(): float
    {
    return \DB::table('gold_prices')
        ->orderByDesc('effective_date')
        ->value('price_per_gram') ?? 0;
    }

    public function getGoldDifferenceAttribute(): float
    {
    if (!$this->gold_weight_actual || !$this->gold_price_at_make) {
        return 0;
    }

    $diffPerGram = $this->current_gold_price - $this->gold_price_at_make;

    return $diffPerGram * $this->gold_weight_actual;
    }

    public function getMarketReferenceAttribute(): float
    {
        $goldPrice = \DB::table('gold_prices')
            ->orderByDesc('effective_date')
            ->value('price_per_gram') ?? 0;

        return ($this->gold_weight_actual * $goldPrice);
    }
}
