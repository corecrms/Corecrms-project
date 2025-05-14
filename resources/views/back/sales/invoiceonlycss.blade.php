<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1" /> --}}
    <style>
        @page {
            margin: 0px;
        }

        #invoice {
            padding: 0px;
            margin: 0px;
        }

        .invoice {
            display: flex;
            justify-content: center;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
        }

        .card {
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            margin: 2.5rem 0;
            border: 0;
            padding: 1rem;
        }

        /* .border {
        border: 0.1875rem solid #343a40;
      } */

        .card-header {
            background: white !important;
            border-bottom: 3px solid #000 !important;
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .table {
            font-family: "Nunito", sans-serif;
            font-size: 13px !important;
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 0.5rem;
        }

        .table th {
            font-weight: bold;
        }

        .heading {
            margin: 0;
        }

        .main-heading {
            margin: 0;
            font-size: 1.5rem;
        }

        .align-middle {
            vertical-align: middle;
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

        .rounded-3 {
            border-radius: 0.375rem;
        }

        .main-heading {
            font-family: "Nunito", sans-serif;
            font-size: 70px;
            font-weight: 700;
        }

        .heading {
            font-family: "Nunito", sans-serif;
            font-size: 17.6px;
            font-weight: 400;
        }

        p {
            font-family: "Nunito", sans-serif;
            font-size: 14px;
            font-weight: 400;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }

        .col {
            flex: 1;
            padding: 0.5rem;
        }

        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-md-7 {
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }

        .col-md-5 {
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }

        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .mt-3 {
            margin-top: 1rem;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mt-5 {
            margin-top: 3rem;
        }

        .pb-4 {
            padding-bottom: 1.5rem;
        }

        .pb-5 {
            padding-bottom: 3rem;
        }

        @media (max-width: 576px) {

            .col-md-6,
            .col-md-7,
            .col-md-5,
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }


        @media (max-width: 576px) {
            .invoice-border {
                border: none !important;
            }
        }
    </style>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <section class="invoice" id="invoice">
        <div class="">
            <div class=" rounded-3 card-shadow my-5 border-0 p-4">
                <div class="border-one">
                    <div class="card-header p-4">
                        <div class="row align-items-center">
                            <div class="col-lg-9 col-md-7 align-items-center align-middle">
                                <h4 class="main-heading m-0">LOGO</h4>
                            </div>
                            <div class="col-lg-3 col-md-5">
                                <h5 class="heading m-0">Atlanta Wholecell</h5>
                                <h5 class="heading m-0">6355 Jimmy Carter Blvd</h5>
                                <h5 class="heading m-0">Norcross, GA, USA 30071</h5>
                                <h5 class="heading m-0">Phone: 6783953444</h5>
                                <h5 class="heading m-0">Sales@Atlantawholecell.com</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row row-border pb-4">
                            <div class="col-md-6 border-end invoice-border border-dark mt-3">
                                <h5 class="heading m-0">SOLD TO:</h5>
                                <h5 class="heading m-0">Cellairis Fayettevilles</h5>
                                <h5 class="heading m-0">125 pavilion pkwy</h5>
                                <h5 class="heading m-0">fayetteville , Georgia 30214 US</h5>
                                <h5 class="heading m-0">Phone: 4703574652</h5>
                                <h5 class="heading m-0">Email Address</h5>
                            </div>
                            <div class="col-md-6 ps-3 ps-md-4 mt-3">
                                <h5 class="heading m-0">SOLD TO:</h5>
                                <h5 class="heading m-0">Cellairis Fayettevilles</h5>
                                <h5 class="heading m-0">125 pavilion pkwy</h5>
                                <h5 class="heading m-0">fayetteville , Georgia 30214 US</h5>
                                <h5 class="heading m-0">Phone: 4703574652</h5>
                                <h5 class="heading m-0">Email Address</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 col-6 mt-4">
                                <h5 class="heading m-0">Date:</h5>
                                <h5 class="heading m-0">27 Jul 24</h5>
                            </div>
                            <div class="col-md-3 col-6 mt-4">
                                <h5 class="heading m-0">Invoice#:</h5>
                                <h5 class="heading m-0">13915</h5>
                            </div>
                            <div class="col-md-3 col-6 mt-4">
                                <h5 class="heading m-0">Rep Name:</h5>
                                <h5 class="heading m-0">Salesperson</h5>
                            </div>
                            <div class="col-md-3 col-6 mt-4">
                                <h5 class="heading m-0">Shipping Method:</h5>
                                <h5 class="heading m-0">Store Pickup</h5>
                            </div>
                        </div>

                        <div class="table-responsive mt-5">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="fw-bold align-middle">UPC</th>
                                        <th class="fw-bold align-middle">Product Description</th>
                                        <th class="fw-bold align-middle">Qty</th>
                                        <th class="fw-bold align-middle">Price</th>
                                        <th class="fw-bold align-middle">Tax</th>
                                        <th class="fw-bold align-middle">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="align-middle">1</td>
                                        <td class="align-middle">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Quod natus reiciendis magni
                                        </td>
                                        <td class="align-middle">2</td>
                                        <td class="align-middle">$3.00</td>
                                        <td class="align-middle">$3.00</td>
                                        <td class="align-middle">$9.00</td>
                                    </tr>
                                    <tr>
                                        <td class="align-middle">1</td>
                                        <td class="align-middle">
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Quod natus reiciendis magni
                                        </td>
                                        <td class="align-middle">2</td>
                                        <td class="align-middle">$3.00</td>
                                        <td class="align-middle">$3.00</td>
                                        <td class="align-middle">$9.00</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3 pb-5">
                            <div class="col-lg-8 col-md-6">
                                <p class="fs-5">Previous Balance:</p>
                                <p class="heading fs-5">Total Due Balance: $1,950.30</p>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-6 heading">Todays Total Due</div>
                                    <div class="col-md-6 col-6 heading">____________</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6 heading">Discounts:</div>
                                    <div class="col-md-6 col-6 heading">____________</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6 heading">Shipping Fees:</div>
                                    <div class="col-md-6 col-6 heading">____________</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6 heading">Amount Paid</div>
                                    <div class="col-md-6 col-6 heading">____________</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6 heading">Payment Method:</div>
                                    <div class="col-md-6 col-6 heading fs-6">
                                        VISA (Last 4): 1184
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="footer">
                            {!! DNS1D::getBarcodeHTML($sale->invoice->invoice_id ?? '12', 'C128A') !!}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
