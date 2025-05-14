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
                        Customer: {{ $sale->shipingAddress->name ?? 'Name' }}<br>
                        Address: {{ $sale->shipingAddress->address ?? 'Address' }}<br>
                        Phone: {{ $sale->shipingAddress->contact_no ?? '454543' }}<br>
                        Email {{ $sale->shipingAddress->email ?? 'example@gmail.com' }}
                    </td>

                </tr>
            </table>
        </div>


        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Payment Mode</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th>Due Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add your product rows here -->
                    @foreach ($paymentInvoice->invoicePayment as $invoice)
                        <tr>
                            <td>{{ $invoice->saleInvoice->invoice_id ?? '102883' }}</td>
                            <td>{{ $invoice->created_at ? $invoice->created_at->format('F j, Y') : '2021-09-09' }}</td>
                            <td>{{$paymentInvoice->payment_method ?? 'Method'}}</td>
                            <td>${{ $invoice->saleInvoice->sale->grand_total ?? '0' }}</td>
                            <td>${{ $invoice->paid_amount ?? '0' }}</td>
                            <td>${{ $invoice->saleInvoice->sale->amount_due ?? '0' }}</td>
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
                            <span class="fw-bold">
                                Total Payment:</span>${{$paymentInvoice->total_pay ?? '0.00'}} <br>
                        </p>
                        <p>
                            <span class="fw-bold">Total Due
                                Balance:</span>${{$totalDue ?? ''}}<br>
                        </p>
                    </td>


                </tr>
            </table>
        </div>
        <div class="footer">
            <p>Thank You For Your Business</p>
        </div>
    </div>
</body>

</html>
