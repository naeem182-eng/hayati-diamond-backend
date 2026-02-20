@extends('admin.layout')

@section('content')

<h2 class="text-xl font-bold mb-4">💰 Payments Ledger</h2>

{{-- ===== Summary ===== --}}
<div class="mb-6 p-4 bg-gray-100 rounded">
    <p><strong>Today Total:</strong> {{ number_format($todayTotal, 2) }}</p>
    <p>Cash Sale: {{ number_format($cashToday, 2) }}</p>
    <p>Installment Received: {{ number_format($installmentToday, 2) }}</p>
</div>

{{-- ===== Filter ===== --}}
<form method="GET" class="mb-4">
    <input type="date" name="date" value="{{ request('date') }}">
    <button type="submit">Filter</button>
</form>

<table class="table-auto w-full border-collapse border border-gray-300">
    <thead class="bg-gray-200">
        <tr>
            <th class="border p-2">Date</th>
            <th class="border p-2">Invoice</th>
            <th class="border p-2">Customer</th>
            <th class="border p-2">Type</th>
            <th class="border p-2 text-right">Amount</th>
            <th class="border p-2">Note</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payments as $payment)
            <tr>
                <td class="border p-2">
                    {{ $payment->paid_at?->format('Y-m-d H:i') }}
                </td>

                <td class="border p-2">
                    <a href="{{ route('admin.invoices.show', $payment->invoice_id) }}"
                       class="text-blue-600 underline">
                        #{{ $payment->invoice_id }}
                    </a>
                </td>

                <td class="border p-2">
                    {{ $payment->invoice->customer_name ?? '-' }}
                </td>

                <td class="border p-2">
                    @if($payment->invoice?->payment_type === \App\Models\Invoice::PAYMENT_CASH)
                        <span class="text-green-600 font-semibold">CASH</span>
                    @else
                        <span class="text-yellow-600 font-semibold">INSTALLMENT</span>
                    @endif
                </td>

                <td class="border p-2 text-right">
                    {{ number_format($payment->amount, 2) }}
                </td>

                <td class="border p-2">
                    {{ $payment->note ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border p-4 text-center">
                    No payments found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $payments->links() }}
</div>

@endsection

