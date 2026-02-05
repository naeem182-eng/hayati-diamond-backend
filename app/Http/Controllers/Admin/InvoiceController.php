<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        $invoice->load('items.stockItem.product');

        return view('admin.invoices.show', compact('invoice'));
    }

    public function printPdf(Invoice $invoice)
    {
        $invoice->load('items.stockItem.product');

        $pdf = Pdf::loadView('admin.invoices.pdf', [
            'invoice' => $invoice,
        ]);

        return $pdf->stream("invoice-{$invoice->id}.pdf");
    }
}
