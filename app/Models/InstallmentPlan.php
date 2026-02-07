<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentPlan extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_COMPLETED = 'COMPLETED';

    protected $fillable = [
        'invoice_id',
        'total_amount',
        'months',
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
