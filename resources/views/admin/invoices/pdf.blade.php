<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4; margin: 20mm; }
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
<body>

@include('admin.invoices.receipt', ['invoice' => $invoice])

</body>
</html>

