<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Download Inventory-Stock-Count-PDF</title>
    {{-- Boostrap CDN  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        label {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }

        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }

        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }

        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }

        .no-border {
            border: 1px solid #fff !important;
        }

        .bg-blue {
            background-color: #414ab1;
            color: #fff;
        }


    </style>
</head>

<body>

    {{-- <h3 style="text-align: center"></h3> --}}

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2">
                    <h2 class="text-start">Client: {{ $customer->user->name }}</h2>
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    {{-- <span>Zip code : 560077</span> <br>
                    <span>Address: #555, Main road, shivaji nagar, Bangalore, India</span> <br> --}}
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="2">Customer Details</th>
                <th width="50%" colspan="2">Company Info</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Full Name: </td>
                <td>{{ $customer->user->name }}</td>

                <td>Company Name:</td>
                <td>{{ auth()->user()->name}}</td>

            </tr>
            <tr>
                <td>Phone:</td>
                <td>{{ $customer->user->contact_no ?? "N/A"}}</td>

                <td>Address:</td>
                <td>{{ auth()->user()->address ?? "N/A"}}</td>

            </tr>
            <tr>
                <td>Total Sale:</td>
                <td>{{ $sale->count() }}</td>

                <td>Phone:</td>
                <td>{{ auth()->user()->contact_no ?? "N/A"}}</td>
            </tr>
            <tr>
                <td>Total Amount:</td>
                <td>{{ $sale->sum('grand_total') }}</td>

                <td>Phone:</td>
                <td>{{ auth()->user()->email }}</td>

            </tr>
            <tr>
                <td>Total Paid:</td>
                <td>{{ $sale->sum('amount_paid') }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Total Sales Due:</td>
                <td>{{ $sale->sum('amount_due') }}</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Total Sell Return Due::</td>
                <td>0.00</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>


    <br><br>
    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    All Sales ( Unpaid/Partial )
                </th>
            </tr>
            <tr class="bg-blue">
                <th>DATE</th>
                <th>REF</th>
                <th>PAID</th>
                <th>Due</th>
                <th>PAYMENT STATUS</th>
            </tr>
        </thead>
        <tbody>

            @forelse($sale as $sale)
                <tr>
                    <th scope="row">{{ $sale->date }}</th>
                    <td>{{ $sale->reference ?? "N/A"}}</td>
                    <td>{{ $sale->amount_recieved ?? 0.00 }}</td>
                    <td>{{ $sale->amount_due ?? 0.00}}</td>
                    <td>{{ $sale->payment_status ?? "N/A"}}</td>
                    {{-- <td>{{ $product->quantity }}{{ $product->unit?->short_name ? $product->unit->short_name : '' }}</td> --}}
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center">No Sale found.</td>
                </tr>
            @endforelse


        </tbody>
    </table>


</body>

</html>
