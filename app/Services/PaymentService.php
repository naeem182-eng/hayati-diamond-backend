<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentService
{
    public function recordPayment(
        Invoice $invoice,
        float $amount,
        ?string $method = null,
        ?string $note = null
    ): Payment {

        return DB::transaction(function () use (
            $invoice,
            $amount,
            $method,
            $note
        ) {

            if ($amount <= 0) {
                throw new Exception('จำนวนเงินไม่ถูกต้อง');
            }

            if ($amount > $invoice->outstanding_balance) {
                throw new Exception('จำนวนเงินเกินยอดค้างชำระ');
            }

            $payment = Payment::create([
                'invoice_id'    => $invoice->id,
                'amount'        => $amount,
                'payment_method'=> $method,
                'note'          => $note,
                'paid_at'       => now(),
            ]);

            $invoice->refreshPaymentStatus();

            return $payment;
        });
    }
}
