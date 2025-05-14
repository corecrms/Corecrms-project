@extends('back.layout.app')
@section('title', 'Stocks List')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Product Report</h3>
            </div>

            @include('back.layout.errors')
            {{-- <div class="container-fluid mt-4">
                <div class="row">
                    <div class="mb-4 col-lg-12">
                        <h3 class="text-center">{{ $stock_show->products->name }}</h3>
                    </div>
                    <div class="col-md-5">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Warehouse</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stock_warehouse as $warehouse)

                                    <tr>
                                        <td>{{ $warehouse['warehouse'] }}</td>
                                        <td>{{ $warehouse['quantity'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}

            <div class="d-flex justify-content-center text-center my-3">
                <input class="form-control w-auto"  type="text" name="dates" id="dateRange">
            </div>

            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-body">
                    {{-- generate nav tab content for sales and purchases --}}
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales"
                                type="button" role="tab" aria-controls="sales" aria-selected="true">Sales</button>
                        </li>
                    </ul>

                    {{-- and add table in their content --}}
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active p-2" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                            <div class="card-header bg-white border-0 rounded-3">
                                <div class="row my-3">
                                    <div class="col-md-4 col-12">
                                        <div class="input-search position-relative">
                                            <input type="text" placeholder="Search Sale"
                                                class="form-control rounded-3 subheading" id="custom-filter" />
                                            <span class="fa fa-search search-icon text-secondary"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-8 col-12 text-end">
                                        <button href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">
                                            Excel <i class="bi bi-file-earmark-text"></i>
                                        </button>
                                        <button class="btn pdf rounded-3 mt-2" id="download-pdf">
                                            Pdf <i class="bi bi-file-earmark"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="example" class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-secondary">Date</th>
                                            <th class="text-secondary">Reference</th>
                                            <th class="text-secondary">Product Name</th>
                                            <th class="text-secondary">Customer</th>
                                            <th class="text-secondary">Warehouse</th>
                                            <th class="text-secondary">Quantity</th>
                                            <th class="text-secondary">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product_sale as $sale)

                                            <tr>
                                                <td class="align-middle">{{ $sale['date'] ?? '' }}</td>
                                                {{-- <td class="align-middle">{{ $sale['reference'] ?? ''}}</td> --}}
                                                <td class="text-primary align-middle"><a class="text-decoration-none" href="{{route('sales.show', $sale['id'])}}">{{ $sale['reference'] }}</a></td>
                                                <td class="align-middle">{{ $sale['product_name'] ?? ''}}</td>
                                                <td class="align-middle">{{ $sale['customer'] ?? ''}}</td>
                                                <td class="align-middle">{{ $sale['warehouse'] ?? ''}}</td>
                                                <td class="align-middle">{{ $sale['quantity'] ?? ''}}</td>
                                                <td class="align-middle">${{ $sale['sub_total'] ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                                    <a class="rounded-end paginate_button page-item next" style="cursor: pointer"> ❯ </a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $('input[name="dates"]').daterangepicker();

            // on click .applyBtn ajax call for filter data of sale product between date range
            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                var start = picker.startDate.format('YYYY-MM-DD');
                var end = picker.endDate.format('YYYY-MM-DD');
                let baseUrl = "{{ route('productSale.filter') }}";
                let fullUrl = `${baseUrl}?start=${start}&end=${end}&product_id={{$product->id}}`;
                $('#example').DataTable().ajax.url(fullUrl).load();
            });

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                paging: true,
                responsive: false,
                processing: true,
                serverSide: false,
                columns: [
                    {data:'date',name:'date'},
                    {data:'reference',name:'reference'},
                    {data:'product_name',name:'product_name'},
                    {data:'customer',name:'customer'},
                    {data:'warehouse',name:'warehouse'},
                    {data:'quantity',name:'quantity'},
                    {data:'sub_total',name:'sub_total'},
                ],
                buttons: [{
                        extend: 'pdf',
                        footer: true,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'csv',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }

                    },
                    {
                        extend: 'excel',
                        footer: false,
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    }
                ]
            });

            $('#custom-filter').keyup(function() {
                table.search(this.value).draw();
            });

            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            $('#download-excel').on('click', function() {
                table.button('.buttons-excel').trigger();
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


            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            // $(document).on('click', '.delete-category-link', function(e) {
            //     e.preventDefault();
            //     $(this).find('.delete-category-form').submit();
            // });

            // $(".delete-category-form").submit(function() {
            //     var decision = confirm("Are you sure, You want to Delete this category?");
            //     if (decision) {
            //         return true;
            //     }
            //     return false;

            // });


        });
    </script>

@endsection
