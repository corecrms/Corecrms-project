@extends('back.layout.app')
@section('title', 'Purchases List')
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

        td a {
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

@endsection

@section('content')

    <div class="content">

        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Purchase Payment Report</h3>
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
                                <input type="text" placeholder="Search Purchase"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                                aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i>
                            </a>
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            <a href="{{ route('purchases.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                    class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th class="text-secondary">
                                    <label for="myCheckbox09" class="checkbox">
                                        <input class="checkbox__input" type="checkbox" id="myCheckbox09" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>
                                </th>
                                <th class="text-secondary">Date</th>
                                <th class="text-secondary">Refference</th>
                                <th class="text-secondary">Purchase</th>
                                <th class="text-secondary">Supplier</th>
                                <th class="text-secondary">Paid by</th>
                                <th class="text-secondary">Account</th>
                                <th class="text-secondary">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td>
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td>{{ $purchase['date'] }}</td>
                                    <td><a
                                            href="/purchases/{{ $purchase['purchase_id'] }}">{{ $purchase['invoice_id'] }}</a>
                                    </td>
                                    <td>{{ $purchase['purchase_reference'] }}</td>
                                    <td>{{ $purchase['supplier'] }}</td>
                                    <td>{{ $purchase['paid_by'] ?? '...' }}</td>
                                    <td>{{ $purchase['account'] ?? '...' }}</td>
                                    <td>${{ $purchase['total_amount'] }}</td>
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

    <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
        style="width: 20rem">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('purchase.payment.filter.vendor') }}" method="POST">
                @csrf
                @method('POST')
                <div>
                    <div class="form-group">
                        <label for="vendor_id">Choose Vendor</label>
                        <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                            name="vendor_id" id="vendor_id">
                            <option disabled>Select Vendor</option>
                            <option value="">All Vendors</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ request()->vendor_id == $vendor->id ? 'selected' : '' }}>{{ $vendor->user->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn save-btn text-white mt-3" type="submit">Filter
                        <i class="bi bi-funnel"></i>
                    </button>

                </div>
            </form>
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

            //on click .applyBtn ajax call for filter data of purchases between date range
            $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
                var start = picker.startDate.format('YYYY-MM-DD');
                var end = picker.endDate.format('YYYY-MM-DD');
                let baseUrl = "{{ route('purchase.payment.filter') }}";
                let fullUrl = `${baseUrl}?start=${start}&end=${end}`;
                $('#example').DataTable().ajax.url(fullUrl).load();
            });

            var table = $('#example').DataTable({
                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox',
                },
                paging: true,
                responsive: false,
                processing: true,
                serverSide: false,
                columns: [{
                        data: null,
                        defaultContent: '', // This line can be added to handle empty checkbox data
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `<label class="checkbox">
                                        <input class="checkbox__input select-checkbox" type="checkbox" id="checkbox${meta.row}" />
                                        <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 22">
                                            <rect width="21" height="21" x=".5" y=".5" fill="#FFF" stroke="rgba(76, 73, 227, 1)" rx="3" />
                                            <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none" stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                        </svg>
                                    </label>`;
                        }
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'purchase_reference',
                        name: 'purchase_reference'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'paid_by',
                        name: 'paid_by'
                    },
                    {
                        data: 'account',
                        name: 'account'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },

                ],
                // buttons: [{
                //         extend: 'pdf',
                //         footer: true,
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                //         }
                //     },
                //     {
                //         extend: 'csv',
                //         footer: false,
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                //         }

                //     },
                //     {
                //         extend: 'excel',
                //         footer: false,
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                //         }
                //     }
                // ]
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


            // Select all checkbox click handler
            $('#myCheckbox09').on('click', function() {
                var isSelected = $(this).is(':checked'); // Check if checkbox is checked

                // Select/deselect all checkboxes with class 'select-checkbox'
                $('.select-checkbox').prop('checked', isSelected);

                // Optional: Update DataTables selection based on checkbox state
                if (isSelected) {
                    table.rows().select(); // Select all rows in DataTables (adjust if needed)
                } else {
                    table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                }
            });

            // Handle click on checkbox to toggle row selection
            $('#example tbody').on('click', 'input[type="checkbox"]', function(e) {
                var $row = $(this).closest('tr');

                // Check the checkbox state and toggle row selection accordingly
                if (this.checked) {
                    table.row($row).select();
                    // $('#myCheckbox09').prop('checked', true);
                } else {
                    table.row($row).deselect();
                    // if ($('.deleteRow:checked').length === 0)
                    //     $('#myCheckbox09').prop('checked', false);
                }

                // Prevent click event from propagating to parent
                e.stopPropagation();
            });

            // Handle click on table cells with .select-checkbox
            $('#example tbody').on('click', 'td.select-checkbox', function(e) {
                $(this).parent().find('input[type="checkbox"]').trigger('click');
            });


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
