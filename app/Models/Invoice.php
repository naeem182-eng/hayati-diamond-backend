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
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function installmentPlan()
    {
        return $this->hasOne(InstallmentPlan::class);
    }
}
