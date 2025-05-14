<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
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
                            <img src="{{ asset('storage' . $logo) }}" alt="Comapny Logo" width="120">
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
                    <td>
                        {{ $sale->customer->user->name ?? 'Salesperson' }}
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
                        <th>Tax</th>
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
                            <td>${{ $item->order_tax ?? '0.00' }}</td>
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
                            <span class="fw-bold">Prev Balance:</span>${{ $sale->customer->balance ?? '0.00' }} <br>
                        </p>
                        <p>
                            <span class="fw-bold">Total Due
                                Balance:</span>${{ $totalDue ?? '0.00' }}<br>
                        </p>
                    </td>
                    <!-- <td class="right">

                    </td> -->
                    <td class="" style="width: 300px;text-align: end;">
                        <p><span class="fw-bold">Todays Total Due:</span> <span
                                style="text-decoration: underline;">${{ $sale->amount_due ?? '0.00' }}</span><br><span
                                class="fw-bold">Discounts:</span> <span
                                style="text-decoration: underline;">${{ $sale->discount ?? '0.00' }}</span><br><span
                                class="fw-bold">Amount Paid:</span> <span
                                style="text-decoration: underline;">${{ $sale->amount_recieved ?? '0.00' }}</span><br><span
                                class="fw-bold">Payment Method:
                                @if ($sale->payment_method == 'Cash')
                                    Cash
                                @elseif ($sale->payment_method == 'Card')
                                    {{ $sale->invoice->saleInvoicePayment[0]->salesPayment->card->card_brand ?? 'Visa' }}:
                                    (Last 4):
                                    {{ $sale->invoice->saleInvoicePayment[0]->salesPayment->card->card_last_four ?? '12234' }}
                                @elseif ($sale->payment_method == 'Credit Store')
                                    Credit Store
                                @endif
                            </span>
                            <br>
                        </p>
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

        <div class="footer">
            <p>Powered By 77Clouds.com</p>
        </div>
    </div>
</body>

</html>
