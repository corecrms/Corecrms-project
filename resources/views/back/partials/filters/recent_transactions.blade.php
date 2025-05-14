@forelse ($recentTransactions as $sale)
<tr>
    <td class="text-muted align-middle">#{{ $sale->reference ?? 'N/A' }}</td>
    <td class="align-middle">
        <div class="d-flex gap-2 align-items-center ">
            <div>
                <h6 class="heading fw-bold" style="font-size: 14px">
                    {{ $sale->customer->user->name ?? 'N/A' }}
                </h6>
            </div>
        </div>
    </td>
    <td class="align-middle">{{ $sale->warehouse->users->name ?? 'N/A' }}</td>
    <td class="align-middle">
        @if ($sale->status == 'completed' || $sale->status == 'Completed')
            <span class="badges bg-green text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span> Completed </span>
            </span>
        @elseif ($sale->status == 'pending' || $sale->status == 'Pending')
            <span class="badges bg-red text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span> Pending </span>
            </span>
        @endif
    </td>
    <td class="align-middle">${{ number_format($sale->grand_total ?? '0.00', 2) }}</td>
    <td class="align-middle">${{ number_format($sale->amount_recieved ?? '0.00', 2) }}</td>
    <td class="align-middle">${{ number_format($sale->amount_due ?? '0.00', 2) }}</td>
    <td class="align-middle">
        @if ($sale->payment_status == 'paid')
            <span class="badges bg-green text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span> {{ ucwords($sale->payment_status ?? '') }} </span>
            </span>
        @elseif ($sale->payment_status == 'partial')
            <span class="badges bg-yellow text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span> {{ ucwords($sale->payment_status ?? '') }} </span>
            </span>
        @else
            <span class="badges bg-red text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                <span class="mb-1"><i class="bi bi-circle-fill text-white" style="font-size: 6px"></i></span>
                <span> {{ ucwords($sale->payment_status ?? '') }} </span>
            </span>
        @endif
    </td>
</tr>
@empty

<tr>
    <td colspan="8" class="text-center">
        No Data Found
    </td>
</tr>
@endforelse
