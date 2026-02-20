<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    public function index()
    {
    $invoices = \App\Models\Invoice::latest()->paginate(20);

    return view('admin.invoices.index', compact('invoices'));
    }



    public function show(Invoice $invoice)
    {
        $invoice->load([
            'items.stockItem.product',
            'installmentPlan.schedules',
        ]);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function printPdf(Invoice $invoice)
    {
    $invoice->load([
        'items.stockItem.product',
        'installmentPlan.schedules',
        'payments'
    ]);

    $html = view('admin.invoices.receipt', compact('invoice'))->render();

    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => storage_path('app/mpdf'),

        'fontDir' => array_merge($fontDirs, [
            public_path('fonts'),
        ]),

        'fontdata' => $fontData + [
            'thsarabun' => [
                'R' => 'THSarabunNew.ttf',
            ],
        ],

        'default_font' => 'thsarabun'
    ]);

    /*
    |--------------------------------------------------------------------------
    | ✅ เพิ่มส่วนนี้
    |--------------------------------------------------------------------------
    */

    $mpdf->SetWatermarkImage(public_path('images/logo.png'), 0.08);
    $mpdf->showWatermarkImage = true;

    /*
    |--------------------------------------------------------------------------
    */

    $mpdf->WriteHTML($html);

    return response(
        $mpdf->Output("invoice-{$invoice->id}.pdf", 'I')
    )->header('Content-Type', 'application/pdf');
    }
}
