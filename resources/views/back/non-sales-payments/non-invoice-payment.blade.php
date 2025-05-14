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


                </tr>
            </table>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td class="left">
                        Customer: {{ $invoicePayments->customer->user->name ?? 'Name' }}<br>
                        Phone: {{ $invoicePayments->customer->user->contact_no ?? '454543' }}<br>
                        Email {{ $invoicePayments->customer->user->email ?? 'example@gmail.com' }}
                    </td>
                </tr>
            </table>
        </div>


        <div class="info">
            <table>
                <tr>
                    <td class="left">
                        <p>
                            <span class="fw-bold">
                                Total Payment:</span>${{$invoicePayments->amount_pay ?? '0.00'}} <br>
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
