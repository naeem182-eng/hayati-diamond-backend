<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'is_active',
        'image_url',
    ];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }
}
