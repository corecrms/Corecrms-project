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
                <h3 class="all-adjustment text-center pb-2 mb-0">Return Details</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                <div class="card-body">
                    <div class="mb-4">

                        <a href="#" class="btn pdf rounded-3 mt-2" id="print-invoice" >Print <i class="bi bi-file-earmark"></i></a>
                    </div>
                    <div id="invoice">
                        <div class="row mb-4">
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Customer Info</h5>
                                <p class="m-0">{{ $sale->customer->user->name ?? '' }}</p>
                                <p class="m-0">{{ $sale->customer->user->email ?? '' }}</p>
                                <p class="m-0">{{ $sale->customer->user->contact_no ?? '' }}</p>
                                <p class="m-0">{{ $sale->customer->user->address ?? '' }}</p>
                            </div>
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Company Info</h5>
                                <p class="m-0">{{ $sale->invoice->user->name ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->email ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->contact_no ?? '' }}</p>
                                <p class="m-0">{{ $sale->invoice->user->address ?? '' }}</p>
                            </div>
                            <div class="col-md-4 col-6 mt-2">
                                <h5 class="text-start mt-2 pb-1 heading">Info of Return</h5>
                                <p class="m-0">Reference : {{ $sale_return->reference ?? '' }}</p>
                                <p class="m-0">Sale Ref : {{ $sale->reference ?? '' }}</p>
                                <p class="m-0">Payment Status : <span
                                        class="badges green-border text-center">{{ $sale->payment_status ?? '' }}</span></p>
                                <p class="m-0">Warehouse : {{ $sale->warehouse->users->name ?? '' }}</p>
                                <p class="m-0">Status : <span
                                        class="badges blue-border text-center">{{ $sale->status ?? '' }}</span></p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <h3 class="all-adjustment text-center pb-1">Order Summary</h3>
                                <thead class="fw-bold">
                                    <tr>
                                        <th class="align-middle">Product</th>
                                        <th class="align-middle">Net Unit Cost</th>
                                        <th class="align-middle">Quantity</th>
                                        <th class="align-middle">Unit Price</th>
                                        <th class="align-middle">Discount</th>
                                        <th class="align-middle">Tax</th>
                                        <th class="align-middle">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale_return->return_items as $product)
                                        <tr>
                                            <td class="align-middle">{{ $product->product->sku ?? '' }}
                                                ({{ $product->product->name ?? '' }})</td>
                                            <td class="align-middle">$ {{ $product->price ?? '' }}</td>
                                            <td class="align-middle"> {{ $product->return_quantity ?? '' }}
                                                {{ $product->sale_units->short_name ?? '' }}</td>
                                            <td class="align-middle">$ {{ $product->product->sell_price ?? '0.00' }}</td>
                                            <td class="align-middle">$ {{ $product->discount ?? '0.00' }}</td>
                                            <td class="align-middle">$ {{ $product->order_tax ?? '0.00' }}</td>
                                            <td class="align-middle">$ {{ $product->subtotal ?? '0.00' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-2 px-3 ">
                            @php
                                $subtotal = $sale_return->return_items->sum('subtotal');
                                $discountPercentage = $sale_return->discount ?? 0;
                                            $discountAmount = ($subtotal * $discountPercentage) / 100;
                                            $orderPercentage = $sale_return->order_tax ?? 0;
                                            $orderAmount = ($subtotal * $orderPercentage) / 100;
                            @endphp
                            <div class="col-md-8"></div>
                            <div class="col-md-4 border rounded-2 py-2">
                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Order Tax</div>

                                    <div class="col-md-6 col-6">
                                        {{-- $ {{ $sale_return->order_tax ?? '0' }} % --}}
                                        $ {{number_format($orderAmount,2)}} ({{ $orderPercentage }}%)
                                    </div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Discount</div>
                                    <div class="col-md-6 col-6">
                                        {{-- $ {{ $sale_return->discount ?? '0.00' }} --}}
                                        $ {{ number_format($discountAmount, 2) }} ({{ number_format($discountPercentage, 2) }}%)
                                    </div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Shipping</div>
                                    <div class="col-md-6 col-6">$ {{ number_format($sale_return->shipping ?? '0.00',2) }}</div>
                                </div>

                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Grand Total</div>
                                    <div class="col-md-6 col-6">$ {{ number_format($sale_return->grand_total ?? '0.00',2) }}</div>
                                </div>
                                <div class="row border-bottom">
                                    <div class="col-md-6 col-6">Paid</div>
                                    <div class="col-md-6 col-6">$ {{ number_format($sale_return->amount_paid ?? '0.00',2) }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6">Due</div>
                                    <div class="col-md-6 col-6">$ {{ number_format($sale_return->amount_due ?? '0.00',2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection

@section('scripts')
    <script src="{{ asset('back/assets/js/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#print-invoice').click(function() {
                var printContents = document.getElementById('invoice').innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            });
        });

    </script>
@endsection
