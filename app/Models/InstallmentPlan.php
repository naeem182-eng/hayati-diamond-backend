<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    protected $fillable = [
        'invoice_id',
        'total_amount',
        'down_payment',
        'months',
        'interest_rate',
        'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function schedules()
    {
        return $this->hasMany(InstallmentSchedule::class);
    }
}
