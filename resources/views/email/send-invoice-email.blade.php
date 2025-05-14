<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Sale and Purchase - Sale Detail</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    {{-- <!-- Stylesheet -->
    <link href="{{ asset('back/assets/dasheets/css/style.css') }}" rel="stylesheet" />
    <style>
        .card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .mb-4,
        .mt-4,
        .mt-2,
        .pb-1 {
            margin-bottom: 16px;
            /* margin-top: 16px; */
            padding-bottom: 4px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .tax-detail {
            display: flex;
            justify-content: space-between;
        }

        .col-md-4,
        .col-6 {
            flex: 0 0 auto;
            width: 33.333333%;
        }

        @media (max-width: 768px) {
            .col-6 {
                width: 50%;
            }
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: left;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .align-middle {
            vertical-align: middle;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6;
        }

        .border {
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        .rounded-2 {
            border-radius: 0.25rem;
        }

        .rounded-3 {
            border-radius: 0.3rem;
        }

        .p-3 {
            padding: 16px;
        }

        .px-3 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .mt-2 {
            margin-top: 16px;
        }

        .all-adjustment {
            border-bottom: 3px solid rgba(76, 73, 227, 1);
            width: 20%;
            font-family: "Nunito", sans-serif;
            font-size: 24px;
            font-weight: 500;
        }

        .heading {
            font-family: "Nunito", sans-serif;
            font-size: 17.6px;
            font-weight: 400;
        }

        .subheading {
            font-family: "Nunito", sans-serif;
            font-size: .873rem !important;
            font-weight: 400 !important;
            /* font-size: 1.1rem; */
        }

        table {
            font-family: "Nunito", sans-serif;
            font-size: 13px !important;
        }

        /* badges */
        .badges {
            display: inline-block;
            border-radius: 5px;
            color: #fff;
            min-width: 66px;
        }

        .badge.bg-purple {
            background: rgba(76, 73, 227, 1);
            width: 40px !important;
        }

        .badges.bg-lightgreen {
            background: rgba(113, 216, 117, 1);
        }

        .badges.bg-lightred {
            background: rgba(244, 99, 99, 1);
        }

        .badges.bg-darkwarning {
            background: rgba(226, 166, 64, 0.2);
            color: rgba(191, 136, 44, 1);
        }

        .badges.green-border {
            border: 1px solid rgba(114, 220, 105, 1);
            color: rgba(114, 220, 105, 1);
        }

        .badges.blue-border {
            border: 1px solid rgba(76, 73, 227, 1);
            color: rgba(76, 73, 227, 1);
        }

    </style> --}}
    <style>
        body {
          font-family: "Nunito", sans-serif;
        }
  
        .card {
          background-color: #fff;
          border-radius: 10px;
          padding: 20px;
          margin-top: 20px;
        }
        .card-shadow {
          box-shadow: 0px 1px 12px 0px rgba(0, 0, 0, 0.15);
        }
  
        .all-adjustment {
          border-bottom: 3px solid rgba(76, 73, 227, 1);
          width: 20%;
          font-family: "Nunito", sans-serif;
          font-size: 24px;
          font-weight: 500;
          text-align: center;
        }
  
        .heading {
          font-family: "Nunito", sans-serif;
          font-size: 17.6px;
          font-weight: 400;
        }
  
        .subheading {
          font-family: "Nunito", sans-serif;
          font-size: 0.873rem !important;
          font-weight: 400 !important;
          /* font-size: 1.1rem; */
        }
  
        p {
          font-family: "Nunito", sans-serif;
          font-size: 14px;
          font-weight: 400;
        }
  
        .badges {
          display: inline-block;
          border-radius: 5px;
          color: #fff;
          min-width: 66px;
          text-align: center;
        }
  
        .badges.blue-border {
          border: 1px solid rgba(76, 73, 227, 1);
          color: rgba(76, 73, 227, 1);
        }
  
        .badges.green-border {
          border: 1px solid rgba(114, 220, 105, 1);
          color: rgba(114, 220, 105, 1);
        }
  
        .mb-4,
        .mt-4,
        .mt-2,
        .pb-1 {
          margin-bottom: 16px;
          /* margin-top: 16px; */
          padding-bottom: 4px;
        }
  
        .row {
          display: flex;
          flex-wrap: wrap;
        }
  
        .tax-detail {
          display: flex;
          justify-content: space-between;
          width: 100%;
        }
  
        .col-md-4,
        .col-6 {
          flex: 0 0 auto;
          width: 33.333333%;
        }
  
        .table-responsive {
          overflow-x: auto;
        }
  
        .table {
          width: 100%;
          margin-bottom: 1rem;
          border-collapse: collapse;
          font-family: "Nunito", sans-serif;
          font-size: 13px !important;
        }
  
        .table th,
        .table td {
          padding: 8px;
          text-align: left;
        }
  
        .table thead th {
          vertical-align: bottom;
        }
  
        .table tbody + tbody {
          border-top: 2px solid #dee2e6;
        }
  
        .align-middle {
          vertical-align: middle;
        }
  
        .border-bottom {
          border-bottom: 1px solid #dee2e6;
        }
  
        .border {
          border: 1px solid #dee2e6;
          padding: 10px;
        }
  
        .rounded-2 {
          border-radius: 0.25rem;
        }
  
        .rounded-3 {
          border-radius: 0.3rem;
        }
  
        .p-3 {
          padding: 16px;
        }
  
        .px-3 {
          padding-left: 16px;
          padding-right: 16px;
        }
  
        .mt-2 {
          margin-top: 16px;
        }
  
        .order-detail {
          display: flex;
          justify-content: space-between;
        }
  
        .order-1 {
          order: 1;
        }
  
        @media (max-width: 768px) {
          .all-adjustment {
            border-bottom: 3px solid rgba(76, 73, 227, 1);
            width: 100% !important;
          }
  
          .col-6 {
            width: 50%;
          }
        }
  
        @media (max-width: 576px) {
          .order-detail {
            flex-wrap: wrap;
          }
  
          .main-order {
            width: 100%;
          }
          .order-2 {
            order: 2;
          }
        }
      </style>
</head>

<body>
    <div class="">

        {{-- <div class="container-fluid pt-4 px-4 mb-5">
                <div class="border-bottom">
                    <h3 class="all-adjustment text-center pb-2 mb-0">Sale Details</h3>
                </div>

                @include('back.layout.errors')

                <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf" >Pdf <i class="bi bi-file-earmark"></i></a>
                            <a href="{{ route('sales.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                    class="bi bi-plus-lg"></i></a>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Customer Info</h5>
                                <p class="m-0">{{ $sale->shipingAddress->name ?? 'N/A' }}</p>
                                <p class="m-0">{{ $sale->shipingAddress->email ?? 'N/A' }}</p>
                                <p class="m-0">{{ $sale->shipingAddress->contact_no ?? 'N/A' }}</p>
                                <p class="m-0">{{ $sale->shipingAddress->address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Company Info</h5>
                                <p class="m-0">{{ $sale->invoice->user->name ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->email ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->contact_no ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->address ?? '' }}</p>
                            </div>
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Invoice Info</h5>
                                <p class="m-0">Reference : {{ $sale->reference ?? '' }}</p>
                                <p class="m-0">Payment Status : <span
                                        class="badges green-border text-center">{{ $sale->payment_status ?? '' }}</span></p>
                                <p class="m-0">Warehouse : {{ $sale->warehouse->users->name ?? '' }}</p>
                                <p class="m-0">Status : <span
                                        class="badges blue-border text-center">{{ $sale->status ?? '' }}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="example">
                                <h3 class="all-adjustment text-center pb-1">Order Summary</h3>
                                <thead class="fw-bold">
                                    <tr>
                                        <th class="align-middle">Product</th>
                                        <th class="align-middle">Net Unit Cost</th>
                                        <th class="align-middle">Quantity</th>
                                        <th class="align-middle">Unit Cost</th>
                                        <th class="align-middle">Discount</th>
                                        <th class="align-middle">Tax</th>
                                        <th class="align-middle">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->productItems as $product)
                                        <tr>
                                            <td class="align-middle">{{ $product->product->sku ?? '' }}
                                                ({{ $product->product->name ?? '' }})</td>
                                            <td class="align-middle">$ {{ $product->price ?? '' }}</td>
                                            <td class="align-middle"> {{ $product->quantity ?? '' }}
                                                {{ $product->sale_units->short_name ?? '' }}</td>
                                            <td class="align-middle">$ {{ $product->product->sell_price ?? '' }}</td>
                                            <td class="align-middle">$ {{ $product->discount ?? '' }}</td>
                                            <td class="align-middle">$ {{ $product->order_tax ?? '' }}</td>
                                            <td class="align-middle">$ {{ $product->sub_total ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-2 px-3 ">
                            <div class="col-md-8"></div>
                            <div class="col-md-4 border rounded-2 py-2">
                                <div class="row border-bottom subheading">
                                    <div class="col-md-6 col-6">Order Tax</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->order_tax ?? '0' }} %</div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Discount</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->discount ?? '0.00' }}</div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Shipping</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->shipping ?? '0.00' }}</div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Grand Total</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->grand_total ?? '0.00' }}</div>
                                </div>
                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Paid</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->amount_recieved ?? '0.00' }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6">Due</div>
                                    <div class="col-md-6 col-6">$ {{ $sale->amount_due ?? '0.00' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Sale Detail</h3>
            </div>

            <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-4 col-6 mt-2">
                            <h5 class="text-start mt-2 pb-1 heading">Customer Info</h5>
                            <p class="m-0">{{ $sale->shipingAddress->name ?? 'N/A' }}</p>
                            <p class="m-0">{{ $sale->shipingAddress->email ?? 'N/A' }}</p>
                            <p class="m-0">{{ $sale->shipingAddress->contact_no ?? 'N/A' }}</p>
                            <p class="m-0">{{ $sale->shipingAddress->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 col-6 mt-2">
                            <h5 class="text-start mt-2 pb-1 heading">Company Info</h5>
                            <p class="m-0">{{ $sale->invoice->user->name ?? '' }}</p>
                            <p class="m-0">{{ $sale->invoice->user->email ?? '' }}</p>
                            <p class="m-0">{{ $sale->invoice->user->contact_no ?? '' }}</p>
                            <p class="m-0">{{ $sale->invoice->user->address ?? '' }}</p>
                        </div>
                        <div class="col-md-4 col-6 mt-2">
                            <h5 class="text-start mt-2 pb-1 heading">Invoice Info</h5>
                            <p class="m-0">Reference : {{ $sale->reference ?? '' }}</p>
                            <p class="m-0">Payment Status : <span
                                    class="badges green-border text-center">{{ $sale->payment_status ?? '' }}</span>
                            </p>
                            <p class="m-0">Warehouse : {{ $sale->warehouse->users->name ?? '' }}</p>
                            <p class="m-0">Status : <span
                                    class="badges blue-border text-center">{{ $sale->status ?? '' }}</span></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <h3 class="all-adjustment text-center pb-1">Order Summary</h3>
                        <table class="table" >
                            <thead class="fw-bold">
                                <tr>
                                    <th class="align-middle">Product</th>
                                    <th class="align-middle">Net Unit Cost</th>
                                    <th class="align-middle">Quantity</th>
                                    <th class="align-middle">Unit Cost</th>
                                    <th class="align-middle">Discount</th>
                                    <th class="align-middle">Tax</th>
                                    <th class="align-middle">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->productItems as $product)
                                    <tr>
                                        <td class="align-middle">{{ $product->product->sku ?? '' }}
                                            ({{ $product->product->name ?? '' }})</td>
                                        <td class="align-middle">$ {{ $product->price ?? '' }}</td>
                                        <td class="align-middle"> {{ $product->quantity ?? '' }}
                                            {{ $product->sale_units->short_name ?? '' }}</td>
                                        <td class="align-middle">$ {{ $product->product->sell_price ?? '' }}</td>
                                        <td class="align-middle">$ {{ $product->discount ?? '' }}</td>
                                        <td class="align-middle">$ {{ $product->order_tax ?? '' }}</td>
                                        <td class="align-middle">$ {{ $product->sub_total ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 px-3 order-detail">
                        <div class="order-2">
                            @if($sale->invoice->saleInvoicePayment->count() > 0)
                                <div class="table-responsive">
                                    <h6 class="pb-1">Payment History</h6>
                                    <table class="table">
                                        <thead class="fw-bold">
                                            <tr class="border-bottom">
                                                <th class="align-middle">No#</th>
                                                <th class="align-middle">Payment Method</th>
                                                <th class="align-middle">Amount</th>
                                                <th class="align-middle">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sale->invoice->saleInvoicePayment as $payment)
                                                <tr class="border-bottom">
                                                    <td class="align-middle">{{$loop->iteration}}</td>
                                                    <td class="align-middle">{{$payment->salesPayment->payment_method ?? ''}}</td>
                                                    <td class="align-middle">${{$payment->salesPayment->total_pay ?? '0.00'}}</td>
                                                    <td class="align-middle">
                                                        <span class="badges green-border text-center">Received</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 order-1 border rounded-2 main-order">
                            <div class="tax-detail border-bottom subheading">
                                <div class="col-md-6 col-6">Order Tax</div>
                                <div class="col-md-6 col-6">$ {{ $sale->order_tax ?? '0' }} %</div>
                            </div>

                            <div class="tax-detail border-bottom">
                                <div class="col-md-6 col-6">Discount</div>
                                <div class="col-md-6 col-6">$ {{ $sale->discount ?? '0.00' }}</div>
                            </div>

                            <div class="tax-detail border-bottom">
                                <div class="col-md-6 col-6">Shipping</div>
                                <div class="col-md-6 col-6">$ {{ $sale->shipping ?? '0.00' }}</div>
                            </div>

                            <div class="tax-detail border-bottom">
                                <div class="col-md-6 col-6">Grand Total</div>
                                <div class="col-md-6 col-6">$ {{ $sale->grand_total ?? '0.00' }}</div>
                            </div>
                            <div class="tax-detail border-bottom">
                                <div class="col-md-6 col-6">Paid</div>
                                <div class="col-md-6 col-6">$ {{ $sale->amount_recieved ?? '0.00' }}</div>
                            </div>
                            <div class="tax-detail">
                                <div class="col-md-6 col-6">Due</div>
                                <div class="col-md-6 col-6">$ {{ $sale->amount_due ?? '0.00' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
