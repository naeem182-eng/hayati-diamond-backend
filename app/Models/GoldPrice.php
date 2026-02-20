<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldPrice extends Model
{
    protected $fillable = [
        'price_per_gram',
        'effective_date',
    ];
}
