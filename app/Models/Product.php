<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'is_active',
    ];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }
}
