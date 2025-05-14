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
                <h3 class="all-adjustment text-center pb-2 mb-0">Bill Detail</h3>
            </div>

            <div class="card card-shadow rounded-3 border-0 mt-4 p-3">
                <div class="card-body">
                    {{-- <div class="mb-4">
                        <a href="#" class="btn create-btn rounded-3 mt-2">Filter <i class="bi bi-funnel"></i></a>
                        <a href="#" class="btn rounded-3 mt-2 excel-btn">Excel <i
                                class="bi bi-file-earmark-text"></i></a>
                        <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"></i></a>
                        <a href="createsale.html" class="btn create-btn rounded-3 mt-2">Create <i
                                class="bi bi-plus-lg"></i></a>
                    </div> --}}
                    {{-- <h3 class="all-adjustment pb-2 mb-0 border-0">Hey Anna</h3>
                    <p>
                        This is the receipt for a payment of
                        <span class="fw-bold">$312.00</span> (USD) you made to Spacial
                        Themes.
                    </p> --}}
                    <div class="row  py-4">
                        <div class="col-md-6">
                            <p>Payment No.</p>
                            <p class="fw-bold m-0">#{{$bill->id}}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Payment Date</p>
                            <p class="fw-bold m-0">{{$bill->bill->date ?? date('Y-m-d')}}</p>
                        </div>
                    </div>
                    <div class="row border-top pt-4">
                        <div class="col-md-6">
                            <p>Vendor</p>
                            <p class="fw-bold">{{$bill->vendor->user->name ?? 'N/A'}}</p>
                            <p class="fw-bold">{{$bill->vendor->user->contact_no ?? 'N/A'}}</p>
                            <p>{{$bill->vendor->user->address ?? 'N/A'}}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Payment To</p>
                            <p class="fw-bold">{{$bill->bill->pay_to ?? ''}}</p>
                            <p>{{$bill->bill->street_address ?? ''}} {{$bill->bill->full_address ?? ''}} {{$bill->bill->zip_code ?? ''}}</p>
                        </div>
                    </div>
                    <div class="table-responsive border-top py-4">
                        <table class="table text-center">
                            <thead>
                                <tr>

                                    <th class="text-secondary">#</th>
                                    <th class="text-secondary">Product</th>
                                    <th class="text-secondary">Price</th>
                                    <th class="text-secondary">Qty</th>
                                    <th class="text-secondary">Sub Total</th>

                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>

                                    <td class="align-middle">02/01/2024</td>
                                    <td class="text-primary align-middle">AD_1115</td>
                                    <td class="align-middle">Fred.C Remen</td>
                                    <td class="align-middle">Warehouse</td>
                                    <td class="align-middle">
                                        <span class="badges green-border">Sent</span>
                                    </td>
                                    <td class="align-middle">332.00</td>
                                    <td class="align-middle">
                                        <div>
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>

                                            <div class="dropdown-menu p-2 ps-0" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" href="productdetail.html">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Product Detail
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Edit Quotation
                                                </a>
                                                <a class="dropdown-item" href="createproduct.html">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Create Sale
                                                </a>
                                                <a class="dropdown-item" href="createproduct.html">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Download PDF
                                                </a>
                                                <a class="dropdown-item" href="createproduct.html">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Email Notification
                                                </a>
                                                <a class="dropdown-item" href="createproduct.html">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    SMS Notification
                                                </a>
                                                <a class="dropdown-item confirm-text" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal-delete" href="#">
                                                    <img src="dasheets/img/menu.svg" class="img-fluid me-1"
                                                        alt="" />
                                                    Delete Quotation
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr> --}}
                                @foreach ($bill->purchaseItems as $product)
                                    <tr>
                                        <td class="align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">{{ $product->product->sku ?? '' }} ({{ $product->product->name ?? '' }})</td>
                                        <td class="align-middle">{{ $product->price ?? ''}}</td>
                                        <td class="align-middle">{{ $product->quantity ?? '' }}</td>
                                        <td class="align-middle">{{ $product->sub_total ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-5 d-flex justify-content-between">
                        <h5>
                            @if (isset($bill->bill->type))
                                Paid By: {{ $bill->bill->type ?? ''}}
                            @endif
                        </h5>
                        <h5>
                            Grand Total:
                            <span class="heading">${{number_format($bill->grand_total) ?? ''}} USD</span>
                        </h5>
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
