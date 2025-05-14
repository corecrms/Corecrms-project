@extends('back.layout.app')
@section('title', 'Top Selling Productss List')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Top Selling Products Report</h3>
            </div>

            @include('back.layout.errors')

            <div class="d-flex justify-content-center text-center my-3">
                <input class="form-control w-auto" type="text" name="dates" id="dateRange">
            </div>
            <div class="card border-0 card-shadow rounded-3 p-2 mt-5">
                <div class="card-header border-0 bg-white">
                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Top Selling Products"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th class="text-secondary">Code </th>
                                <th class="text-secondary">Product</th>
                                <th class="text-secondary">Total Sales</th>
                                <th class="text-secondary">Total Quantity</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($top_products as $products)
                                <tr>
                                    <td class="align-middle">{{ $products['code'] }}</td>
                                    <td class="text-primary align-middle">{{ $products['product'] }}</td>
                                    <td class="align-middle">{{ $products['total_sales'] }} </td>
                                    <!-- This is now in the parent unit -->
                                    <td class="align-middle">{{ $products['total_amount'] }}</td>

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

            //on click .applyBtn ajax call for filter data of products between date range
            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                var start = picker.startDate.format('YYYY-MM-DD');
                var end = picker.endDate.format('YYYY-MM-DD');
                let baseUrl = "{{ route('top.product.filter') }}";
                let fullUrl = `${baseUrl}?start=${start}&end=${end}`;
                $('#example').DataTable().ajax.url(fullUrl).load();
            });




            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                paging: true,
                responsive: false,
                processing: true,
                serverSide: false,
                columns: [

                    {
                        data: 'code',
                        name: 'Code'
                    },
                    {
                        data: 'product',
                        name: 'Product',
                        render: function(data, type, row) {
                            console.log(data);
                            return `<td class="text-primary align-middle">${data}</td>`;
                        }
                    },

                    {
                        data: 'total_sales',
                        name: 'Total Sales',
                        // render: function(data, type, row) {
                        //     return `<td class="align-middle">$${data}</td>`;
                        // }
                    },
                    {
                        data: 'total_amount',
                        name: 'Total Amount',
                        // render: function(data, type, row) {
                        //     return `<td class="align-middle">$${data}</td>`;
                        // }
                    }
                ],

            });


            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            // $('#download-excel').on('click', function() {
            //     table.button('.buttons-excel').trigger();
            // });
            $(`#download-pdf`).on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            $(`#custom-filter`).on('keyup', function() {
                table.search(this.value).draw();
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


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.delete-category-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-category-form').submit();
            });

            $(".delete-category-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this category?");
                if (decision) {
                    return true;
                }
                return false;
            });


        });
    </script>

@endsection
