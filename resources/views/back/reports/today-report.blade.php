@extends('back.layout.app')
@section('title', 'Today Report')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">Today's Report</h3>
            </div>

            @include('back.layout.errors')

            <div class="card card-shadow rounded-3 border-0 mt-4">
                <div class="card-body">
                    <div class="row my-2 px-3">
                        <div class="col-md-6 p-3">

                            <div class="row border-bottom subheading align-items-center py-2">
                                <div class="col-md-6 col-6">

                                    <span class="mb-0">Total Purchase</span>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayPurchase ?? '0.00'}}</div>
                            </div>
                            <div class="row border-bottom subheading align-items-center py-2 disabled-bg">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0 ">Total Expense</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayExpense ?? '0.00'}}</div>
                            </div>

                            <div class="row border-bottom subheading align-items-center py-2 disabled-bg">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0">Total Sell Discount</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todaySaleDiscount ?? '0.00'}}</div>
                            </div>
                        </div>
                        <div class="col-md-6 rounded-2 p-3">

                            <div class="row border-bottom subheading align-items-center py-2">
                                <div class="col-md-6 col-6">
                                    <div class="mb-0">Total Sales</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todaySale ?? '0.00'}}</div>
                            </div>
                            <div class="row border-bottom subheading align-items-center py-2 disabled-bg">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0">Total Purchase Return</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayPurchaseReturn ?? '0.00'}}</div>
                            </div>
                            <div class="row border-bottom subheading align-items-center py-2">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0">Total Purchase Discount</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayPurchaseDiscount ?? '0.00'}}</div>
                            </div>
                            <div class="row border-bottom subheading align-items-center py-2">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0">Total Cash Payment</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayCashPayment ?? '0.00'}}</div>
                            </div>
                            <div class="row border-bottom subheading align-items-center py-2">
                                <div class="col-md-6 col-6">
                                    <div class=" mb-0">Total Card Payment</div>
                                </div>
                                <div class="col-md-6 col-6 ">${{$todayCardPayment ?? '0.00'}}</div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class=" mt-3">Gross Profit: ${{$grossProfit ?? '0.00'}}</div>
                    <div class="">Net Loss: ${{$netLoss ?? '0.00'}}</div> --}}
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
                let baseUrl = "{{ route('best.customer.filter') }}";
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
                        data: 'customer',
                        name: 'Name'
                    },
                    {
                        data: 'phone',
                        name: 'Phone',
                        render: function(data, type, row) {
                            console.log(data);
                            return `<td class="text-primary align-middle">${data}</td>`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'Email'
                    },
                    {
                        data: 'total_sales',
                        name: 'Total Sales'
                    },
                    {
                        data: 'total_amount',
                        name: 'Total Amount'
                    }
                ],

            });


            $('#download-pdf').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
            // $('#download-excel').on('click', function() {
            //     table.button('.buttons-excel').trigger();
            // });

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
