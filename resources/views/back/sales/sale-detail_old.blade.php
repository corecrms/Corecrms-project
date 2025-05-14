@extends('back.layout.app')
@section('title', 'Shipment')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <style>
        .dataTables_paginate {
            display: none;
        }

        .dataTables_length {
            display: none;
        }

        .dataTables_info {
            display: none;
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
                        <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                class="bi bi-file-earmark-text"></i></a>
                        <a href="{{ route('sales.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                class="bi bi-plus-lg"></i></a>

                        <a href="{{route('sales.downloadInvoice',$sale->id)}}" class="btn pdf rounded-3 mt-2" id="download-pdf">Download Invoice <i class="bi bi-file-earmark"></i></a>
                        <form  action="{{ route('sales.sendInvoiceEmail', ['email' => $sale->customer->user->email, 'id' => $sale->id]) }}"
                            class="d-inline" method="GET">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn create-btn rounded-3 mt-2">
                                Send Email<i class="bi bi-plus-lg"></i>
                            </button>
                        </form>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4 col-6 mt-2">
                            <h5 class="text-start mt-2 pb-1 heading">Customer Info</h5>
                            {{-- <p class="m-0">{{ $sale->customer->user->name ?? '' }}</p>
                            <p class="m-0">{{ $sale->customer->user->email ?? '' }}</p>
                            <p class="m-0">{{ $sale->customer->user->contact_no ?? '' }}</p>
                            <p class="m-0">{{ $sale->customer->user->address ?? '' }}</p> --}}
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
                                            ({{ $product->product->name ?? '' }})
                                        </td>
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
                        <div class="col-md-8 mt-4">
                            @if ($sale->invoice->saleInvoicePayment->count() > 0)
                                <h6 class="pb-1">Payment History</h6>
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
                        <div class="col-md-4 border rounded-2 py-2">
                            <div class="row border-bottom subheading">
                                <div class="col-md-6 col-6">Order Tax</div>
                                {{-- {{$sale->grand_total * ($sale->order_tax ?? 0 / 100)}} --}}
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
@endsection
