<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public const PAYMENT_CASH = 'CASH';
    public const PAYMENT_INSTALLMENT = 'INSTALLMENT';

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_PAID = 'PAID';

    public const DISCOUNT_FIXED = 'FIXED';
    public const DISCOUNT_PERCENT = 'PERCENT';

    protected $fillable = [
        'customer_id',
        'customer_name',
        'total_amount',
        'payment_type',
        'status',
        'discount_type',
        'discount_amount',
        'promotion_code',
    ];

    protected $appends = [
        'paid_total',
        'outstanding_balance',
    ];

    /* ===============================
     * Relations
     * =============================== */

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function installmentPlan()
    {
        return $this->hasOne(InstallmentPlan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /* ===============================
     * Accessors
     * =============================== */

    public function getPaidTotalAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getOutstandingBalanceAttribute(): float
    {
        return max(
            (float) $this->total_amount - $this->paid_total,
            0
        );
    }

    /* ===============================
     * Business Logic
     * =============================== */

    public function refreshPaymentStatus(): void
    {
        if ($this->outstanding_balance <= 0) {
            $this->update([
                'status' => self::STATUS_PAID,
            ]);
        }
    }
}
