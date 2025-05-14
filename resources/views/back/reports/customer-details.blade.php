{{-- @dd($customers) --}}
@extends('back.layout.app')
@section('title', 'Customers List')
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Customer Report</h3>
            </div>

            @include('back.layout.errors')

            <div class="row">
                <div class="col-md-6 col-xl-3 mt-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card-shadow border rounded d-flex align-items-center p-3">
                            <img src="dasheets/img/content-sale.svg" class="img-fluid text-center" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-muted subheading">Sales</p>
                                <h6 class="mb-0 sales-amount">{{ $sale->count() ?? '0.00' }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card-shadow border rounded d-flex align-items-center p-3">
                            <img src="dasheets/img/content-bag.svg" class="img-fluid text-center" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 text-muted subheading">Total Amount</p>
                                <h6 class="mb-0 sales-amount">${{ $sale->sum('grand_total') ?? '0.00' }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card-shadow border rounded d-flex align-items-center p-3">
                            <img src="dasheets/img/content-right-arrow.svg" class="img-fluid text-center" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-muted subheading">Total Paid</p>
                                <h6 class="mb-0 sales-amount">${{ $sale->sum('amount_recieved') ?? '0.00' }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-xl-3 mt-4">
                    <a href="#" class="text-decoration-none">
                        <div class="card-shadow border rounded d-flex align-items-center p-3">
                            <img src="dasheets/img/content-left-arrow.svg" class="img-fluid text-center" alt="" />
                            <div class="ms-3">
                                <p class="mb-1 fs-6 text-muted subheading">
                                    Due
                                </p>
                                <h6 class="mb-0 sales-amount">${{ $sale->sum('amount_due') ?? '0.00' }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="card border-0 card-shadow rounded-3 p-2 mt-4">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-quotations-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-quotations" type="button" role="tab" aria-controls="nav-quotations"
                            aria-selected="true">
                            Sales
                        </button>
                        <button class="nav-link" id="nav-sales-tab" data-bs-toggle="tab" data-bs-target="#nav-sales"
                            type="button" role="tab" aria-controls="nav-sales" aria-selected="true">
                            Return
                        </button>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade px-2 show active" id="nav-quotations" role="tabpanel"
                        aria-labelledby="nav-quotations-tab" tabindex="0">
                        <div class="row my-3">
                            <div class="col-md-3 col-12 mt-2">
                                <div class="input-search position-relative">
                                    <input type="text" placeholder="Search Sale"
                                        class="form-control rounded-3 subheading" id="custom-filter1" />
                                    <span class="fa fa-search search-icon text-secondary"></span>
                                </div>
                            </div>

                            <div class="col-md-9 col-12 text-end">
                                <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                        id="download-pdf1"></i></a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="table1">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">Date</th>
                                        <th class="text-secondary">Refference</th>
                                        <th class="text-secondary">Customer</th>
                                        <th class="text-secondary">Warehouse</th>
                                        <th class="text-secondary">Grand Total</th>
                                        <th class="text-secondary">Status</th>
                                        <th class="text-secondary">Payment Status</th>
                                        {{-- <th class="text-secondary">Shipping Status</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale as $sale)
                                        <tr>
                                            <td class="align-middle">{{ $sale->date ?? '' }}</td>
                                            {{-- <td class="text-primary align-middle">{{$sale->reference ?? 'N/A'}}</td> --}}
                                            <td class="text-primary align-middle"><a class="text-decoration-none"
                                                    href="{{ route('sales.show', $sale->id) }}">{{ $sale->reference ?? '' }}</a>
                                            </td>
                                            <td class="align-middle">{{ $sale->customer->user->name ?? 'N/A' }}</td>
                                            <td class="align-middle">{{ $sale->warehouse->users->name ?? 'N/A' }}</td>
                                            <td class="align-middle">${{ $sale->grand_total ?? '0.00' }}</td>
                                            <td class="align-middle">
                                                {{-- <span class="badges green-border text-center">{{$sale->status ?? 'N/A'}}</span> --}}
                                                @if ($sale->status == 'completed' || $sale->status == 'Completed')
                                                    <span
                                                        class="badges bg-lightgreen text-center">{{ ucwords($sale->status ?? '') }}</span>
                                                @elseif ($sale->status == 'pending' || $sale->status == 'Pending')
                                                    <span  class="badges bg-lightred text-center">{{ ucwords($sale->status ?? '') }}</span>
                                                @endif

                                            </td>
                                            <td class="align-middle">
                                                @if ($sale->payment_status == 'paid')
                                                <span
                                                    class="badges bg-lightgreen text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                            @elseif ($sale->payment_status == 'partial')
                                                <span
                                                    class="badges bg-lightyellow text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                            @else
                                                <span
                                                    class="badges bg-lightred text-center">{{ ucwords($sale->payment_status ?? '') }}</span>
                                            @endif
                                            </td>
                                            {{-- <td class="align-middle">
                                                <span class="badges green-border text-center">{{$sale->shipment->status ?? 'pending'}}</span>
                                            </td> --}}
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between p-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <label for="rowsPerPage" class="col-form-label">Rows per page:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select id="rowsPerPage" class="form-select border-0">
                                            <option value="3" selected>3</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center text-end">
                                    <div class="col-auto">
                                        <p class="subheading col-form-label " id="dataTableInfo">

                                        </p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="new-pagination">
                                            <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                            <a class="rounded-end paginate_button page-item next" style="cursor: pointer">
                                                ❯ </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade px-2" id="nav-sales" role="tabpanel" aria-labelledby="nav-sales-tab"
                        tabindex="0">
                        <div class="row my-3">
                            <div class="col-md-3 col-12 mt-2">
                                <div class="input-search position-relative">
                                    <input type="text" placeholder="Search Return"
                                        class="form-control rounded-3 subheading" id="custom-filter2" />
                                    <span class="fa fa-search search-icon text-secondary"></span>
                                </div>
                            </div>

                            <div class="col-md-9 col-12 text-end">
                                <a href="#" class="btn pdf rounded-3 mt-2">Pdf <i class="bi bi-file-earmark"
                                        id="download-pdf2"></i></a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="table2">
                                <thead>
                                    <tr>
                                        <th class="text-secondary">Refference</th>
                                        <th class="text-secondary">Customer</th>
                                        <th class="text-secondary">Sale Ref</th>
                                        <th class="text-secondary">Warehouse</th>
                                        <th class="text-secondary">Grand Total</th>
                                        <th class="text-secondary">Paid</th>
                                        <th class="text-secondary">Due</th>
                                        <th class="text-secondary">Status</th>
                                        <th class="text-secondary">Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale_return as $sale)
                                        {{-- {{dd($sale)}} --}}
                                        <tr>
                                            <td class="text-primary align-middle">{{ $sale->sale_return->reference }}</td>
                                            <td class="align-middle">{{ $sale->customer->user->name ?? 'N/A' }}</td>
                                            <td class="align-middle">{{ $sale->reference ?? '' }}</td>
                                            <td class="align-middle">{{ $sale->warehouse->users->name ?? 'N/A' }}</td>
                                            <td class="align-middle">${{ $sale->sale_return->grand_total ?? '0.00' }}</td>
                                            <td class="align-middle">${{ $sale->sale_return->amount_paid ?? '0.00' }}</td>
                                            <td class="align-middle">${{ $sale->sale_return->amount_due ?? '0.00' }}</td>
                                            <td class="align-middle">
                                                <span
                                                    class="badges blue-border text-center">{{ $sale->sale_return->status ?? '' }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badges green-border text-center">{{ $sale->sale_return->payment_status ?? '' }}</span>
                                            </td>
                                            {{-- <td class="align-middle">
                                                <span class="badges border border-dark text-dark text-center">Shipped</span>
                                            </td> --}}
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer bg-white border-0 rounded-3">
                            <div class="d-flex justify-content-between p-0">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <label for="rowsPerPage1" class="col-form-label">Rows per page:</label>
                                    </div>
                                    <div class="col-auto">
                                        <select id="rowsPerPage1" class="form-select border-0">
                                            <option value="3" selected>3</option>
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center text-end">
                                    <div class="col-auto">
                                        <p class="subheading col-form-label " id="dataTableInfo1">

                                        </p>
                                    </div>
                                    <div class="col-auto">
                                        <div class="new-pagination">
                                            <a class="rounded-start paginate_button" style="cursor: pointer"> ❮ </a>
                                            <a class="rounded-end paginate_button page-item next" style="cursor: pointer">
                                                ❯ </a>
                                        </div>
                                    </div>
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


            var table = $('#table1').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ]
            });

            $('#custom-filter1').keyup(function() {
                table.search(this.value).draw();
            });

            $('#download-pdf1').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });


            // // Custom pagination events
            $('.new-pagination .paginate_button').on('click', function() {
                if ($(this).hasClass('rounded-start')) {
                    table.page('previous').draw('page');
                } else if ($(this).hasClass('rounded-end')) {
                    table.page('next').draw('page');
                }
            });

            // Handle rows per page change
            $('#rowsPerPage').on('change', function() {
                var rowsPerPage = $(this).val();
                table.page.len(rowsPerPage).draw();
            });

            // Update rows per page select on table draw
            table.on('draw', function() {

                var pageInfo = table.page.info();
                var currentPage = pageInfo.page + 1; // Adding 1 to match human-readable page numbering
                var totalPages = pageInfo.pages;
                var totalRecords = pageInfo.recordsTotal;

                // Calculate start and end records for the current page
                var startRecord = pageInfo.start + 1;
                var endRecord = startRecord + pageInfo.length - 1;
                if (endRecord > totalRecords) {
                    endRecord = totalRecords;
                }

                $('#rowsPerPage').val(table.page.len());
                $('#dataTableInfo').text('Showing ' + startRecord + '-' + endRecord + ' of ' +
                    totalRecords + ' entries');
            });

            table.draw();

        });
        $(document).ready(function() {


            var table = $('#table2').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
            });

            $('#custom-filter2').keyup(function() {
                table.search(this.value).draw();
            });

            $('#download-pdf2').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });


            // // Custom pagination events
            $('.new-pagination .paginate_button').on('click', function() {
                if ($(this).hasClass('rounded-start')) {
                    table.page('previous').draw('page');
                } else if ($(this).hasClass('rounded-end')) {
                    table.page('next').draw('page');
                }
            });

            // Handle rows per page change
            $('#rowsPerPage1').on('change', function() {
                var rowsPerPage = $(this).val();
                table.page.len(rowsPerPage).draw();
            });

            // Update rows per page select on table draw
            table.on('draw', function() {

                var pageInfo = table.page.info();
                var currentPage = pageInfo.page + 1; // Adding 1 to match human-readable page numbering
                var totalPages = pageInfo.pages;
                var totalRecords = pageInfo.recordsTotal;

                // Calculate start and end records for the current page
                var startRecord = pageInfo.start + 1;
                var endRecord = startRecord + pageInfo.length - 1;
                if (endRecord > totalRecords) {
                    endRecord = totalRecords;
                }

                $('#rowsPerPage1').val(table.page.len());
                $('#dataTableInfo1').text('Showing ' + startRecord + '-' + endRecord + ' of ' +
                    totalRecords + ' entries');
            });

            table.draw();

        });
    </script>

@endsection
