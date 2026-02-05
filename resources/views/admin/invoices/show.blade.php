@if($invoice->payment_type === 'INSTALLMENT' && $invoice->installmentPlan)

<h3>Installment Schedule</h3>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Due Date</th>
            <th class="text-end">Amount</th>
            <th>Status</th>
            <th width="180">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoice->installmentPlan->schedules as $schedule)
        <tr>
            <td>{{ $schedule->month_no }}</td>
            <td>{{ $schedule->due_date }}</td>
            <td class="text-end">
                {{ number_format($schedule->amount, 2) }}
            </td>
            <td>
                @if($schedule->status === 'PAID')
                    <span class="badge bg-success">PAID</span>
                @else
                    <span class="badge bg-warning">UNPAID</span>
                @endif
            </td>
            <td>
                @if($schedule->status === 'UNPAID')
                    <form method="POST"
                          action="{{ route('admin.installments.pay', $schedule) }}"
                          onsubmit="return confirm('ยืนยันรับชำระงวดนี้?')">
                        @csrf
                        <button class="btn btn-sm btn-primary">
                            รับชำระงวดนี้
                        </button>
                    </form>
                @else
                    <small>
                        Paid at {{ $schedule->paid_at?->format('Y-m-d') }}
                    </small>
                @endif
            </td>
        </tr>
    @endforeach
    @if($invoice->payment_type === 'INSTALLMENT' && $invoice->installmentPlan)

<hr>
<h3>Installment Schedule</h3>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Due Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoice->installmentPlan->schedules as $schedule)
        <tr>
            <td>{{ $schedule->month_no }}</td>
            <td>{{ $schedule->due_date }}</td>
            <td>{{ number_format($schedule->amount, 2) }}</td>
            <td>{{ $schedule->status }}</td>
            <td>
                @if($schedule->status === 'UNPAID')
                    <form method="POST"
                          action="{{ route('admin.installments.pay', $schedule) }}">
                        @csrf
                        <button type="submit">
                            Pay
                        </button>
                    </form>
                @else
                    ✔ Paid
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endif

    </tbody>
</table>

@endif
