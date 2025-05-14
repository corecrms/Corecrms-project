<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: "Nunito", sans-serif;
            width: 100%;
            margin: 0 auto;
            font-size: 16px;
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
        .me-1 {
            margin-right: 0.25rem;
        }
        .me-2 {
            margin-right: 0.5rem;
        }
        .me-3 {
            margin-right: 1rem;
        }
        .me-5 {
            margin-right: 2rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }
        .mt-2 {
            margin-top: 0.5rem;
        }
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        .mb-3 {
            margin-bottom: 1rem;
        }
        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .text-white {
            color: #fff;
        }
        .border {
            border: 1px solid #000;
        }
        /* .border-2 {
            border: 2px solid #000;
        } */

    </style>
</head>

<body>
    <div class="container border-one">
        <!-- <div class="header">
            <img src="path/to/logo.png" alt="Logo">
            <h2>Atlanta Wholecell</h2>
            <p>6355 Jimmy Carter Blvd<br>Norcross, GA, USA 30071<br>Phone: 6783953444<br>Sales@Atlantawholecell.com</p>
        </div> -->
        <div class="info">
            <table>
                <tr>
                    <td class="left">
                        @if (isset($logo))
                            <img src="{{ public_path('storage' . $logo) }}" alt="Logo" width="120">
                        @else
                            <h1 class="text-white">Logo</h1>
                        @endif
                    </td>
                    <td class="right">

                    </td>
                    <td class="right">
                        <h4>
                            {{ $sale->warehouse->users->name ?? '' }}
                        </h4>
                        <p>{{ $sale->warehouse->users->address ?? 'Address' }}<br>Phone:
                            {{ $sale->warehouse->users->contact_no ?? '1234567' }}<br>{{ $sale->warehouse->users->email ?? '' }}
                        </p>
                    </td>

                </tr>
            </table>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td class="left">
                        <strong>SHIP TO:</strong><br>
                        {{ $sale->shipingAddress->name ?? 'Name' }}<br>
                        {{ $sale->shipingAddress->address ?? 'Address' }}<br>
                        Phone: {{ $sale->shipingAddress->contact_no ?? '454543' }}<br>
                        Email {{ $sale->shipingAddress->email ?? 'example@gmail.com' }}
                    </td>
                    <td class="right">
                        <strong>SOLD TO:</strong><br>
                        {{ $sale->customer->user->name ?? 'Name' }}<br>
                        {{ $sale->customer->user->address ?? 'Address' }}<br>
                        Phone: {{ $sale->customer->user->contact_no ?? '' }}<br>
                        Email {{ $sale->customer->user->email ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="details">
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
                        {{ $sale->invoice->invoice_no ?? '123456' }}
                    </td>
                    {{-- <td>
                        {{ $sale->customer->user->name ?? 'Salesperson' }}
                    </td> --}}
                    <td>
                        {{ $sale->creator?->name ?? '' }}
                    </td>
                    <td>
                        {{ $sale->shipping_method ?? 'Store Pickup' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="details">
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

        <div class="info">
            <table>
                <tr>
                    <td class="left">
                        <p>
                            <span class="fw-bold">Total Qty:</span> {{$sale?->productItems?->sum('quantity')}}<br>
                        </p>
                        <p>
                            <span class="fw-bold">Prev Balance:</span>${{ ($totalDue - $sale->amount_due) ?? '0.00' }} <br>
                        </p>
                        <p>
                            @php
                                // $total_due_balance = $totalDue + $sale->amount_due;
                                $total_due_balance = $totalDue
                            @endphp
                            <span class="fw-bold">Total Due Balance:</span>${{ $total_due_balance ?? '0.00' }}<br>
                        </p>
                    </td>
                    <!-- <td class="right">

                    </td> -->
                    {{-- <td class="" style="width: 300px;text-align: right;">

                        <p>
                            <span class="fw-bold me-5" style="text-align:left">Sub Total - </span>

                            <span style="">${{ $sale->productItems->sum('sub_total') }}</span><br>
                            <span class="fw-bold me-5">Tax - </span>
                            <span style="margin-left:46px;">$0.00</span><br>
                            <span class="fw-bold">Shipping Fee - </span>&nbsp;
                            <span style="">${{ $sale->shipping ?? '0.00' }}</span><br>
                            <span class="fw-bold me-2">Discounts - </span>  &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="">${{ $sale->discount ?? '0.00' }}</span><br>

                            <div class="border border-2 border-dark mb-2 d-block mt-1"></div>
                                <span class="fw-bold me-3">Grand Total - </span>
                                <span style="">${{ $sale->grand_total ?? '0.00' }}</span><br>
                            <div class="border border-2 border-dark mt-2 d-block"></div>

                            <span class="fw-bold me-5">Paid - </span>
                            <span style="margin-left:50px;">${{ number_format($sale->amount_recieved ?? '0.00',2) }}</span><br>
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
                        </p>
                    </td> --}}

                    <td style="width: 300px; text-align: right;">
                        @php
                                $subTotal = $sale->productItems->sum('sub_total') ?? 0.00;
                                $discountPercentage = $sale->discount ?? 0;
                                $discountAmount = ($subTotal * $discountPercentage) / 100;
                                $orderPercentage = $sale->order_tax ?? 0;
                                $orderAmount = ($subTotal * $orderPercentage) / 100;
                        @endphp
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: left; width: 60%;">Sub Total -</td>
                                <td style="text-align: right;">${{ $subTotal }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Tax -</td>
                                <td style="text-align: right;">
                                    ${{number_format($orderAmount,2)}} ({{ $orderPercentage }}%)
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Shipping Fee -</td>
                                <td style="text-align: right;">${{ number_format($sale->shipping ?? 0.00, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Discounts -</td>
                                <td style="text-align: right;">${{ number_format($discountAmount, 2) }} ({{ number_format($discountPercentage, 2) }}%)</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="border border-2 border-dark mb-2 mt-1"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;"><strong>Grand Total -</strong></td>
                                <td style="text-align: right;"><strong>${{ number_format($sale->grand_total ?? 0.00, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="border border-2 border-dark mt-2"></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Paid -</td>
                                <td style="text-align: right;">${{ number_format($sale->amount_recieved ?? 0.00, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Payment Method:</td>
                                <td style="text-align: right;">
                                    @if (count($sale->invoice->saleInvoicePayment) > 1)
                                        Multiple
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
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>

                </tr>
            </table>
        </div>
        <!-- <div class="info">
            <table>
                <tr>
                    <td class="left">
                        <img src="path/to/logo.png" alt="Logo">
                    </td>
                    <td class="right">

                    </td>
                    <td class="right">
                        <h2>Atlanta Wholecell</h2>
                        <p>Todays Total Due: $1,950.30<br>Discounts: $32344<br>Amount Paid: $6783<br>.com</p>
                    </td>
                </tr>
            </table>
        </div> -->


        {{-- <div class="footer">
            {!! DNS1D::getBarcodeHTML($sale->invoice->invoice_id, 'C128A') !!}
        </div> --}}
        <div class="footer">
            <p>Powered By 77Clouds.com</p>
        </div>
    </div>
</body>

</html>
