@extends('back.layout.app')
@section('title', 'Shipment')
@section('style')
    <style>
        body {
            width: 100%;
            margin: 0 auto;
        }

        .container {
            /* width: 100%;
                            margin: 0 auto; */
            padding: 4px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
        }

        .info {
            width: 100%;
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 5px;
            vertical-align: top;
        }

        .info .left,
        .info .right {
            width: 50%;
        }

        .endright {
            text-align: right;
            width: 100%;
        }

        .details {
            width: 100%;
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .details th {
            background-color: #f2f2f2;
        }

        .footer {
            font-size: 12px;
        }


        .border-one {
            border: 3px solid #000 !important;
        }

        .row-border {
            border-bottom: 3px solid #000;
        }

        .border-end {
            border-right: 3px solid #343a40;
        }

        .fw-bold {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Sale Details</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                <div class="card-body">
                    <div class="mb-4">
                        {{-- <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                class="bi bi-file-earmark-text"></i></a> --}}
                        <a href="{{ route('sales.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                class="bi bi-plus-lg"></i></a>

                        <a href="{{ route('sales.downloadInvoice', $sale->id) }}" class="btn pdf rounded-3 mt-2"
                            id="download-pdf">Download Invoice <i class="bi bi-file-earmark"></i>
                        </a>

                        <form
                            action="{{ route('sales.sendInvoiceEmail', ['email' => $sale->customer->user->email, 'id' => $sale->id]) }}"
                            class="d-inline" method="GET">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn create-btn rounded-3 mt-2">
                                Send Email<i class="bi bi-plus-lg"></i>
                            </button>
                        </form>
                        <a href="#" class="btn pdf rounded-3 mt-2" id="print-invoice">print <i
                            class="bi bi-file-earmark"></i>
                        </a>
                    </div>
                    <div class="container border-one p-4" id="invoice">

                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="">
                                @if (isset($logo))
                                    <img src="{{ asset('storage' . $logo) }}" alt="Logo" width="120">
                                @else
                                    <h1 class="">Logo</h1>
                                @endif
                            </div>
                            <div class="mt-3">
                                <h4>
                                    {{ $sale->warehouse->users->name ?? '' }}
                                </h4>
                                <p>{{ $sale->warehouse->users->address ?? 'Address' }}<br>Phone:
                                    {{ $sale->warehouse->users->contact_no ?? '1234567' }}<br>{{ $sale->warehouse->users->email ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="row my-3">
                            <div class="col-md-6 mt-3">
                                <strong>SHIP TO:</strong><br>
                                {{ $sale->shipingAddress->name ?? 'Name' }}<br>
                                {{ $sale->shipingAddress->address ?? 'Address' }}<br>
                                Phone: {{ $sale->shipingAddress->contact_no ?? '454543' }}<br>
                                Email {{ $sale->shipingAddress->email ?? 'example@gmail.com' }}
                            </div>
                            <div class="col-md-6 mt-3">
                                <strong>SOLD TO:</strong><br>
                                {{ $sale->customer->user->name ?? 'Name' }}<br>
                                {{ $sale->customer->user->address ?? 'Address' }}<br>
                                Phone: {{ $sale->customer->user->contact_no ?? '' }}<br>
                                Email {{ $sale->customer->user->email ?? '' }}
                            </div>
                        </div>

                        <div class="details">
                            <div class="table-responsive">
                                <table>
                                    <tr>
                                        <th>Date:</th>
                                        <!-- <td></td> -->
                                        <th>Invoice#:</th>
                                        <!-- <td>13915</td> -->
                                        <th>Rep Name:</th>
                                        <!-- <td>Salesperson</td> -->
                                        <th>Shipping Method:</th>
                                        <!-- <td>Store Pickup</td> -->
                                    </tr>
                                    <tr>
                                        <td>
                                            {{ $sale->date ?? '2021-01-01' }}
                                        </td>
                                        <td>
                                            {{ $sale->invoice->invoice_id ?? '123456' }}
                                        </td>
                                        <td>
                                            {{-- {{ $sale->customer->user->name ?? 'Salesperson' }} --}}
                                            {{ $sale->creator->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $sale->shipping_method ?? 'Store Pickup' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                        <div class="details">
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>UPC</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add your product rows here -->
                                        @foreach ($sale->productItems as $item)
                                            <tr>
                                                <td>{{ $item->product->sku ?? '102883' }}</td>
                                                <td>{{ $item->product->name ?? 'Product Name' }}</td>
                                                <td>${{ $item->price ?? '0.00' }}</td>
                                                <td>{{ $item->quantity ?? '0' }}</td>
                                                <td>${{ $item->sub_total ?? '0.00' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="details">
                            @if ($sale->invoice->saleInvoicePayment->count() > 0)
                                <h6 class="pb-1 fw-bold">Payment History</h6>
                                <div class="table-responsive">
                                    <table class="table" border="1">
                                        <thead class="fw-bold">
                                            <tr>
                                                <th class="align-middle">No#</th>
                                                <th class="align-middle">Payment Method</th>
                                                <th class="align-middle">Amount</th>
                                                <th class="align-middle">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                <tr>
                                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                                    <td class="align-middle">
                                                        {{ $payment->salesPayment->payment_method ?? '' }}</td>
                                                    <td class="align-middle">
                                                        ${{ $payment->salesPayment->total_pay ?? '0.00' }}</td>
                                                    <td class="align-middle">
                                                        <span class="badges green-border text-center">Sent</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif
                        </div>

                        <div class="">


                            <div class="row">
                                <div class=" col-md-9">
                                    <p>
                                        <span class="fw-bold">Total Qty:</span> {{$sale?->productItems?->sum('quantity')}}<br>
                                    </p>
                                    <p>
                                        <span class="fw-bold">Prev
                                            Balance:</span>${{ ($totalDue - $sale->amount_due) ?? '0.00' }} <br>
                                    </p>
                                    <p>
                                        @php
                                            // $total_due_balance = $totalDue + $sale->amount_due;
                                            $total_due_balance = $totalDue
                                        @endphp
                                        <span class="fw-bold">Total Due
                                            Balance:</span>${{ $total_due_balance ?? '0.00' }}<br>
                                    </p>
                                </div>
                                <div class=" col-md-3 text-md-end text-sm-start">
                                    @php
                                            $subTotal = $sale->productItems->sum('sub_total') ?? 0.00;
                                            $discountPercentage = $sale->discount ?? 0;
                                            $discountAmount = ($subTotal * $discountPercentage) / 100;
                                            $orderPercentage = $sale->order_tax ?? 0;
                                            $orderAmount = ($subTotal * $orderPercentage) / 100;
                                            // dd($orderAmount);
                                    @endphp

                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <tbody>
                                                <!-- Sub Total -->
                                                <tr>
                                                    <td class="fw-bold pe-3" style="width: 40%">Sub Total:</td>
                                                    <td class="text-end" style="text-decoration: underline">${{ number_format($subTotal, 2) }}</td>
                                                </tr>

                                                <!-- Tax -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Tax:</td>
                                                    <td class="text-end" style="text-decoration: underline">

                                                        ${{number_format($orderAmount,2)}} ({{ $orderPercentage }}%)
                                                    </td>
                                                </tr>

                                                <!-- Shipping Fee -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Shipping Fee:</td>
                                                    <td class="text-end" style="text-decoration: underline">${{ number_format($sale->shipping ?? 0, 2) }}</td>
                                                </tr>

                                                <!-- Discount -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Discount:</td>
                                                    <td class="text-end" style="text-decoration: underline">
                                                        ${{ number_format($discountAmount, 2) }} ({{ number_format($discountPercentage, 2) }}%)
                                                    </td>
                                                </tr>

                                                <!-- Grand Total Separator -->
                                                <tr>
                                                    <td colspan="2">
                                                        <hr class="border border-2 border-dark my-1">
                                                    </td>
                                                </tr>

                                                <!-- Grand Total -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Grand Total:</td>
                                                    <td class="text-end" style="text-decoration: underline">${{ number_format($sale->grand_total ?? 0, 2) }}</td>
                                                </tr>

                                                <!-- Paid Separator -->
                                                <tr>
                                                    <td colspan="2">
                                                        <hr class="border border-2 border-dark my-1">
                                                    </td>
                                                </tr>

                                                <!-- Paid Amount -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Paid:</td>
                                                    <td class="text-end" style="text-decoration: underline">${{ number_format($sale->amount_recieved ?? 0, 2) }}</td>
                                                </tr>

                                                <!-- Payment Method -->
                                                <tr>
                                                    <td class="fw-bold pe-3">Payment Method:</td>
                                                    <td class="text-end">
                                                        @if (count($sale->invoice->saleInvoicePayment) > 1)
                                                            <span>Multiple</span>
                                                        @elseif ($sale->invoice->saleInvoicePayment->count() == 1)
                                                            @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                                @if ($payment->salesPayment->card)
                                                                    {{ $payment->salesPayment->card->card_brand ?? 'Visa' }}:
                                                                    (Last 4):
                                                                    {{ $payment->salesPayment->card->card_last_four ?? '' }}
                                                                @else
                                                                    {{ $payment->salesPayment->payment_method ?? '' }}
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <span>N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    {{-- <p>
                                        <span class="fw-bold me-5">Sub Total - </span>

                                        @php
                                            $subTotal = $sale->productItems->sum('sub_total') ?? 0.00;
                                            $discountPercentage = $sale->discount ?? 0;
                                            $discountAmount = ($subTotal * $discountPercentage) / 100;
                                        @endphp
                                        <span style="text-decoration: underline;">${{ $subTotal }}</span><br>
                                        <span class="fw-bold me-5">Tax - </span>
                                        <span style="text-decoration: underline;">$0.00</span><br>
                                        <span class="fw-bold me-2">Shipping Fee - </span> &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->shipping ?? '0.00' }}</span><br>
                                        <span class="fw-bold me-2">Discounts - </span>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ number_format($discountAmount, 2) }} ({{ number_format($discountPercentage, 2) }}%)</span><br>

                                        <span class="border border-2 border-dark mb-2 d-block mt-1"></span>
                                            <span class="fw-bold me-3">Grand Total - </span>
                                            <span style="text-decoration: underline;">${{ $sale->grand_total ?? '0.00' }}</span><br>
                                        <span class="border border-2 border-dark mt-2 d-block"></span>

                                        <span class="fw-bold me-5">Paid - </span>
                                        <span style="text-decoration: underline;">${{ $sale->amount_recieved ?? '0.00' }}</span><br>
                                        <span class="fw-bold ">Payment Method :
                                            @if (count($sale->invoice->saleInvoicePayment) > 1)
                                                <span>Multiple</span>

                                                @elseif ($sale->invoice->saleInvoicePayment->count() == 1)
                                                    @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                        @if ($payment->salesPayment->card)
                                                            {{ $payment->salesPayment->card->card_brand ?? 'Visa' }}:
                                                            (Last 4)
                                                            :
                                                            {{ $payment->salesPayment->card->card_last_four ?? '' }}
                                                        @else
                                                            {{ $payment->salesPayment->payment_method ?? '' }}
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                        </span>
                                        <br>
                                    </p> --}}
                                    {{-- <p>
                                        <span class="fw-bold">Grand Total:</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->grand_total ?? '0.00' }}</span><br>
                                        <span class="fw-bold">Todays Total Due:</span>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->amount_due ?? '0.00' }}</span><br>
                                        <span class="fw-bold">Shipping Fee:</span> &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->shipping ?? '0.00' }}</span><br>
                                        <span class="fw-bold">Discounts:</span>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->discount ?? '0.00' }}</span><br>
                                        <span class="fw-bold">Amount Paid:</span>  &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span style="text-decoration: underline;">${{ $sale->amount_recieved ?? '0.00' }}</span><br>
                                        <span class="fw-bold">Payment Method:
                                            @if (count($sale->invoice->saleInvoicePayment) > 1)
                                                <span>Multiple</span>
                                            @else
                                                @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                    @if ($payment->salesPayment->card)
                                                        {{ $payment->salesPayment->card->card_brand ?? 'Visa' }}:
                                                        (Last 4)
                                                        :
                                                        {{ $payment->salesPayment->card->card_last_four ?? '' }}
                                                    @else
                                                        {{ $payment->salesPayment->payment_method ?? '' }}
                                                    @endif
                                                @endforeach
                                            @endif
                                        </span>
                                        <br>
                                    </p> --}}
                                </div>
                            </div>
                        </div>
                        {{-- <div class="footer">
                            {!! DNS1D::getBarcodeHTML($sale->invoice->invoice_id, 'C128A') !!}
                        </div> --}}
                        <div class="footer">
                            <p>Powered By 77Clouds.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, ]
                        }
                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, ]
                        }
                    }
                ]
            });

            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            $('#download-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });



        });
    </script>

<script>
    // $(document).ready(function() {
    //     $('#print-invoice').click(function() {
    //         var printContents = document.getElementById('invoice').innerHTML;
    //         var originalContents = document.body.innerHTML;
    //         document.body.innerHTML = printContents;
    //         window.print();
    //         document.body.innerHTML = originalContents;
    //     });
    // });

    $(document).ready(function() {
    $('#print-invoice').click(function() {
        var printContents = document.getElementById('invoice').innerHTML;

        // Open a new window for printing
        var printWindow = window.open('', '', 'height=600,width=800');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Invoice</title>
                    <link rel="stylesheet" href="{{ asset('back/assets/css/bootstrap.min.css') }}">
                    <link rel="stylesheet" href="{{ asset('back/assets/dasheets/css/style.css') }}">
                    <link rel="stylesheet" href="{{ asset('back/assets/css/datatable.min.css') }}">
                    <style>
                        body {
                            font-family: DejaVu Sans, sans-serif;
                            width: 100%;
                            margin: 0 auto;
                        }
                        .container {
                            padding: 4px;
                        }
                        .header, .footer {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        .header img {
                            max-width: 100px;
                        }
                        .info {
                            width: 100%;
                            margin-bottom: 20px;
                        }
                        .info table {
                            width: 100%;
                        }
                        .info td {
                            padding: 5px;
                            vertical-align: top;
                        }
                        .info .left, .info .right {
                            width: 50%;
                        }
                        .endright {
                            text-align: right;
                            width: 100%;
                        }
                        .details {
                            width: 100%;
                            margin-bottom: 20px;
                        }
                        .details table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        .details th, .details td {
                            border: 1px solid #000;
                            padding: 5px;
                            text-align: left;
                        }
                        .details th {
                            background-color: #f2f2f2;
                        }
                        .footer {
                            font-size: 12px;
                        }
                        .border-one {
                            border: 3px solid #000 !important;
                        }
                        .row-border {
                            border-bottom: 3px solid #000;
                        }
                        .border-end {
                            border-right: 3px solid #343a40;
                        }
                        .fw-bold {
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `);

        // Close the document to ensure the content is loaded
        printWindow.document.close();

        // Print the content
        printWindow.print();

        // Close the window after printing (optional)
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    });
});

</script>


@endsection
