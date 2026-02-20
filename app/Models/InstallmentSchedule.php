<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstallmentSchedule extends Model
{
    use HasFactory;

    public const STATUS_UNPAID = 'UNPAID';
    public const STATUS_PAID   = 'PAID';

    protected $fillable = [
        'installment_plan_id',
        'month_no',
        'due_date',
        'amount',
        'paid_at',
        'status',
    ];

    public function plan()
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }

    public function installmentPlan()
    {
        return $this->plan();
    }

    public function invoice()
    {
        return $this->hasOneThrough(
            Invoice::class,
            InstallmentPlan::class,
            'id',
            'id',
            'installment_plan_id',
            'invoice_id'
        );
    }
}
