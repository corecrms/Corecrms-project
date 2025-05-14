@extends('user.dashboard-layout.app')

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
    <div class="container-xxl container-fluid pt-2 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">Recent Orders</h3>
        </div>

        <div class="card card-shadow border-0 mt-4 rounded-3">
            <div class="card-header bg-white border-0 rounded-3 pb-0">
                <div class="row my-3">
                    <div class="col-md-4 col-12">
                        <div class="input-search position-relative">
                            <input type="text" placeholder="Search Table" id="custom-filter"
                                class="form-control rounded-3 subheading" />
                            <span class="fa fa-search search-icon text-secondary"></span>
                        </div>
                    </div>

                    <div class="col-md-8 col-12 text-end">
                        <button href="#" class="btn rounded-3 mt-2 excel-btn">
                            Excel <i class="bi bi-file-earmark-text"></i>
                        </button>
                        <button class="btn pdf rounded-3 mt-2">
                            Pdf <i class="bi bi-file-earmark"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive p-2">
                    <table class="table" id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>Invoice Id</th>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td class="align-middle">{{ $sale->invoice->invoice_id }}</td>
                                    <td class="pt-3">{{ $sale->date ?? '' }}</td>
                                    <td class="pt-3">{{ $sale->warehouse->users->name }}</td>
                                    <td class="pt-3">${{ $sale->grand_total }}</td>
                                    <td class="align-middle">
                                        <span
                                            class="badges {{ $sale->status == 'pending' ? 'pending-bg' : 'shiped-bg' }} text-center text-dark">
                                            {{ $sale->status }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('user.orders.show', $sale->id) }}" class="text-decoration-none">
                                            <img src="{{ asset('front/dasheets/img/eye.svg') }}" class="p-0 m-0"
                                                alt="" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h3 class="all-adjustment text-center pb-2 mt-4 w-25">
                Account Information
            </h3>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="card border-0 card-shadow rounded-3 h-100">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h2 class="heading m-0">Contact Information</h2>
                                <a href="{{ route('user.account.info') }}"
                                    class="text-decoration-none text-dark heading">Edit</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="m-0">{{ auth()->user()->name ?? '' }}</p>
                            <p class="m-0">{{ auth()->user()->email ?? '' }}</p>
                            <a href="{{ route('user.account.info') }}">Change Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="card border-0 card-shadow rounded-3 h-100">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h2 class="heading m-0">Newsletter</h2>
                                <a href="#" class="text-decoration-none text-dark heading">Edit</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="m-0">
                                You are not currently subscribed to any newsletter.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="all-adjustment text-center pb-2 mt-4">Address Book</h3>
            <div class="row">
                <div class="col-md-6 mt-3">
                    <div class="card border-0 card-shadow rounded-3 h-100">
                        <div class="card-header border-0">
                            <h2 class="heading m-0">Default Billing Address</h2>
                        </div>
                        <div class="card-body">
                            <div class="">
                                Email: <span class="fw-bold">{{ auth()->user()->email ?? '' }}</span>
                            </div>
                            <div class="">
                                Contact No#: <span class="fw-bold">{{ auth()->user()->contact_no ?? '' }}</span>
                            </div>
                            <div class="">
                                Country: <span class="fw-bold">{{ auth()->user()->customer->country ?? '' }}</span>
                            </div>
                            <div class="">
                                City: <span class="fw-bold">{{ auth()->user()->customer->city ?? '' }}</span>
                            </div>
                            <div class="">
                                Address: <span class="fw-bold">{{ auth()->user()->address ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-3">
                    <div class="card border-0 card-shadow rounded-3 h-100">
                        <div class="card-header border-0">
                            <h2 class="heading m-0">Default Shipping Address</h2>
                        </div>
                        <div class="card-body">
                            <div class="">
                                Email: <span class="fw-bold">{{ auth()->user()->email ?? '' }}</span>
                                </d>
                                <div class="">
                                    Contact No#: <span class="fw-bold">{{ auth()->user()->contact_no ?? '' }}</span>
                                    </d>
                                    <div class="">
                                        Country: <span class="fw-bold">{{ auth()->user()->customer->country ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        City: <span class="fw-bold">{{ auth()->user()->customer->city ?? '' }}</span>
                                    </div>
                                    <div class="">
                                        Address: <span class="fw-bold">{{ auth()->user()->address ?? '' }}</span>
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

                    var table = $('#example').DataTable({
                        dom: 'Bfrtip',
                        order: [],
                        buttons: [{
                                extend: 'pdf',
                                footer: true,
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'csv',
                                footer: false,
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }

                            },
                            {
                                extend: 'excel',
                                footer: false,
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
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

                    // Select all checkbox click handler
                    $('#myCheckbox09').on('click', function() {
                        var isSelected = $(this).is(':checked'); // Check if checkbox is checked

                        // Select/deselect all checkboxes with class 'select-checkbox'
                        $('.select-checkbox').prop('checked', isSelected);

                        // Optional: Update DataTables selection based on checkbox state
                        if (isSelected) {
                            table.rows().select(); // Select all rows in DataTables (adjust if needed)
                            $('#deletedAlert').css('display', 'block');
                            $('#deleteRowCount').text($('.deleteRow:checked').length);
                        } else {
                            table.rows().deselect(); // Deselect all rows in DataTables (adjust if needed)
                            $('#deletedAlert').css('display', 'none');
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

                    // Update the count and alert display whenever the selection changes
                    table.on('select.dt deselect.dt', function() {
                        var selectedRows = table.rows('.selected').count();
                        if (selectedRows === 0) {
                            $('#deletedAlert').css('display', 'none');
                        } else {
                            $('#deletedAlert').css('display', 'block');
                            $('#deleteRowCount').text(selectedRows);
                        }
                    });

                    $('#deleteRowTrigger').on("click", function(event) { // triggering delete one by one
                        if (confirm("Are you sure you won't be able to revert this!")) {
                            if ($('.deleteRow:checked').length > 0) { // at-least one checkbox checked
                                var ids = [];
                                $('.deleteRow').each(function() {
                                    if ($(this).is(':checked')) {
                                        let id = $(this).data('id');
                                        ids.push(id);
                                    }
                                });
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });

                                $.ajax({
                                    type: "POST",
                                    url: "{{ route('sales.delete') }}",
                                    data: {
                                        ids
                                    },
                                    success: function(result) {
                                        if (result.status === 200) {
                                            toastr.success(result.message)
                                            location.reload();
                                        }
                                    },
                                    async: false
                                });
                            }
                        }
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
            </script>
        @endsection
