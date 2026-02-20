@extends('admin.layout')

@section('content')

<h2 class="text-xl font-bold mb-4">📄 Invoices</h2>

<table class="table-auto w-full border-collapse border border-gray-300">
    <thead class="bg-gray-200">
        <tr>
            <th class="border p-2">ID</th>
            <th class="border p-2">Customer</th>
            <th class="border p-2">Type</th>
            <th class="border p-2">Status</th>
            <th class="border p-2 text-right">Total</th>
            <th class="border p-2 text-right">Outstanding</th>
        </tr>
    </thead>
    <tbody>
        @forelse($invoices as $invoice)
            <tr>
                <td class="border p-2">
                    <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                       class="text-blue-600 underline">
                        #{{ $invoice->id }}
                    </a>
                </td>

                <td class="border p-2">
                    {{ $invoice->customer_name ?? '-' }}
                </td>

                <td class="border p-2">
                    {{ $invoice->payment_type }}
                </td>

                <td class="border p-2">
                    @if($invoice->status === \App\Models\Invoice::STATUS_PAID)
                        <span class="text-green-600 font-semibold">PAID</span>
                    @else
                        <span class="text-yellow-600 font-semibold">ACTIVE</span>
                    @endif
                </td>

                <td class="border p-2 text-right">
                    {{ number_format($invoice->total_amount, 0) }}
                </td>

                <td class="border p-2 text-right">
                    @if($invoice->outstanding_balance > 0)
                            {{ number_format($invoice->outstanding_balance, 0) }}
                    @else
                            -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="border p-4 text-center">
                    No invoices found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $invoices->links() }}
</div>

@endsection

