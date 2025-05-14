@extends('back.layout.app')
@section('title', 'Categories')
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
                <h3 class="all-adjustment text-center pb-2 mb-0">All Non Purchase Payments</h3>
            </div>
            @if (session('success'))
                <div class="mt-2 alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                @if (is_array(session('error')))
                    {{-- If the error is an array, loop through it --}}
                    @foreach (session('error') as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ $error }}.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endforeach
                @else
                    {{-- If the error is a string, display it normally --}}
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 card-shadow rounded-3 p-2 mt-4">
                <div class="card-header border-0 bg-white">



                    <div class="row my-3">
                        <div class="col-md-4 col-12">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Invoice" class="form-control rounded-3 subheading"
                                    id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-8 col-12 text-end">
                            <a href="#" class="btn border-danger text-danger rounded-3 mt-2 excel-btn"
                                id="download-excel">Excel <i class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf">Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            <a href="{{ route('non-purchase-payments.create') }}"
                                class="btn create-btn rounded-3 mt-2">Create <i class="bi bi-plus-lg"></i></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="alert alert-danger p-2 text-end" id="deletedAlert" style="display: none">
                        <div style="display: flex;justify-content:space-between">
                            <span><span id="deleteRowCount">0</span> rows selected</span>
                            <button class="btn btn-sm btn-danger" id="deleteRowTrigger">Delete</button>
                        </div>
                    </div>
                    <table class="table " id="example">
                        <thead class="fw-bold">
                            <tr>
                                <th>
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
                                {{-- <th class="text-secondary">Invoice No</th> --}}
                                <th class="text-secondary">Vendor</th>
                                <th class="text-secondary">Payment Type</th>
                                <th class="text-secondary">Paid Amount</th>
                                <th class="text-secondary">Account</th>
                                <th class="text-secondary"> Payment Date</th>
                                <th class="text-secondary">Status</th>
                                <th class="text-secondary">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($NonPurchaseInvoicePayments as $purchaseInvoice)
                                <tr>
                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $purchaseInvoice->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>

                                    <td class="align-middle">
                                        {{ $purchaseInvoice?->vendor?->user?->name }}
                                    </td>
                                    {{-- <td class="align-middle">{{ $purchaseInvoice->invoice->vendor->users->name ?? '' }}</td> --}}
                                    <td class="align-middle">{{ $purchaseInvoice->payment_type ?? '' }}</td>
                                    <td class="align-middle">${{ $purchaseInvoice->amount_pay }}</td>
                                    <td class="align-middle">{{ $purchaseInvoice?->account?->name }}</td>
                                    <td class="align-middle">{{ $purchaseInvoice->payment_date }}</td>
                                    <td class="align-middle">{{ $purchaseInvoice->status === 1 ? 'Yes' : 'No' }}</td>
                                    <td class="align-middle">
                                        <div class="">
                                            <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button"
                                                id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu " aria-labelledby="dropdownMenuLink">
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('get-one-product-details', $purchaseInvoice->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Product Detail
                                                </a> --}}
                                                {{-- <a class="dropdown-item"
                                                    href="{{ route('non-purchase-payments.edit', $purchaseInvoice->id) }}">
                                                    <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                        class="img-fluid me-1" alt="" />
                                                    Edit Purchase
                                                </a> --}}

                                                <form class=""
                                                    action="{{ route('non-purchase-payments.destroy', $purchaseInvoice->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item confirm-text">
                                                        <img src="{{ asset('back/assets/dasheets/img/menu.svg') }}"
                                                            class="img-fluid me-1" alt="">
                                                        Delete Purchase
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse

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

    <script>
        $(document).ready(function() {

            var table = $('#example').DataTable({

                dom: 'Bfrtip',
                select: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child .select-checkbox',
                },
                buttons: [{
                        extend: 'pdf',
                        footer: true,
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
                            url: "{{ route('non-purchase-payment.multiplae.delete') }}",
                            data: {
                                ids
                            },
                            success: function(result) {
                                if (result.status === 200) {
                                    location.reload();
                                }
                            },
                            async: false
                        });
                    }
                }
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
            $(document).on('click', '.delete-payment-link', function(e) {
                e.preventDefault();
                $(this).find('.delete-payment-form').submit();
            });

            $(".delete-payment-form").submit(function() {
                var decision = confirm("Are you sure, You want to Delete this non-invoice payment?");
                if (decision) {
                    return true;
                }
                return false;
            });
        });
    </script>
@endsection
