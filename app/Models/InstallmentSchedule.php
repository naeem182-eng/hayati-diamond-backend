<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSchedule extends Model
{
    protected $fillable = [
        'installment_plan_id',
        'month_no',
        'due_date',
        'amount',
        'paid_at',
        'status',
    ];

    /**
     * relation หลัก (ของเดิม)
     */
    public function plan()
    {
        return $this->belongsTo(InstallmentPlan::class, 'installment_plan_id');
    }

    /**
     * alias ให้โค้ดที่เรียก installmentPlan ใช้ได้
     */
    public function installmentPlan()
    {
        return $this->plan();
    }

    /**
     * shortcut ให้เรียก invoice ได้ตรง ๆ
     */
    public function invoice()
    {
        return $this->hasOneThrough(
            Invoice::class,
            InstallmentPlan::class,
            'id',          // FK on InstallmentPlan
            'id',          // FK on Invoice
            'installment_plan_id',
            'invoice_id'
        );
    }
}
