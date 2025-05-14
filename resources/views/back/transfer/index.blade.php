@extends('back.layout.app')

@section('title', 'Inventories')
@section('style')

    </style>
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
                <h3 class="all-adjustment text-center pb-2 mb-0">All Transfer</h3>
            </div>

            @include('back.layout.errors')

            <div class="card border-0 card-shadow rounded-3 p-2 mt-4">
                <div class="card-header border-0 bg-white">
                    <div class="row my-3">
                        <div class="col-md-3 col-12 mt-2">
                            <div class="input-search position-relative">
                                <input type="text" placeholder="Search Transfer"
                                    class="form-control rounded-3 subheading" id="custom-filter" />
                                <span class="fa fa-search search-icon text-secondary"></span>
                            </div>
                        </div>

                        <div class="col-md-9 col-12 text-end">
                            <a href="#" class="btn create-btn rounded-3 mt-2" type="button"
                                data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop"
                                aria-controls="staticBackdrop">Filter <i class="bi bi-funnel"></i></a>
                            <a href="#" class="btn rounded-3 mt-2 excel-btn" id="download-excel">Excel <i
                                    class="bi bi-file-earmark-text"></i></a>
                            <a href="#" class="btn pdf rounded-3 mt-2" id="download-pdf"> Pdf <i
                                    class="bi bi-file-earmark"></i></a>
                            @can('tranfer-create')
                                <a href="{{ route('transfers.create') }}" class="btn create-btn rounded-3 mt-2">Create <i
                                        class="bi bi-plus-lg"></i></a>
                            @endcan
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
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th class="align-middle">
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
                                <th class="text-secondary">Reference</th>
                                <th class="text-secondary">From Warehouse</th>
                                <th class="text-secondary">To Warehouse</th>
                                <th class="text-secondary">Items</th>
                                <th class="text-secondary">Grand Total</th>
                                <th class="text-secondary">Status</th>
                                @canany(['transfer-edit', 'transfer-delete', 'transfer-show'])
                                    <th class="text-secondary">Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($transfers as $transfer)
                                <tr>
                                    <td class="align-middle">
                                        <label for="select-checkbox" class="checkbox">
                                            <input class="checkbox__input select-checkbox deleteRow" type="checkbox"
                                                id="select-checkbox" data-id="{{ $transfer->id }}" />
                                            <svg class="checkbox__icon" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 22 22">
                                                <rect width="21" height="21" x=".5" y=".5" fill="#FFF"
                                                    stroke="rgba(76, 73, 227, 1)" rx="3" />
                                                <path class="tick" stroke="rgba(76, 73, 227, 1)" fill="none"
                                                    stroke-linecap="round" stroke-width="3" d="M4 10l5 5 9-9" />
                                            </svg>
                                        </label>
                                    </td>
                                    <td class="align-middle">{{ $transfer->date ?? '' }}</td>
                                    <td class="align-middle">{{ $transfer->reference ?? '' }}</td>
                                    <td class=" align-middle">{{ $transfer->from_warehouse->users['name'] ?? '' }}</td>
                                    <td class="align-middle">{{ $transfer->to_warehouse->users['name'] ?? '' }}</td>
                                    <td class="align-middle">{{ $transfer->items ?? '' }}</td>
                                    <td class="align-middle">${{ $transfer->grand_total ?? '' }}</td>
                                    <td class="align-middle">
                                        {{-- <span class="badges green-border text-center">{{ $transfer->status ?? '' }}</span> --}}
                                        @if ($transfer->status == 'completed')
                                            <span class="badges bg-lightgreen text-center">{{ $transfer->status ?? '' }}</span>
                                        @elseif($transfer->status == 'pending')
                                            <span class="badges bg-lightred text-center">{{ $transfer->status ?? '' }}</span>
                                        @endif

                                    </td>

                                    <td class="align-middle">

                                        <div class="d-flex">

                                            @can('transfer-show')
                                                <a class=" text-decoration-none btn"
                                                    data-bs-target="#exampleModalToggle2{{ $transfer->id }}"
                                                    data-bs-toggle="modal">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z"
                                                            stroke="#2563EB" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                            stroke="#2563EB" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </a>
                                            @endcan
                                            @can('transfer-edit')
                                                <a href="{{ route('transfers.edit', $transfer->id) }}"
                                                    class=" text-decoration-none btn">
                                                    <img src="{{ asset('back/assets/dasheets/img/edit-2.svg') }}"
                                                        class="p-0 ms-0" alt="" />
                                                </a>
                                            @endcan




                                            @can('transfer-delete')
                                                <form action="{{ route('transfers.destroy', $transfer->id) }}"
                                                    class="d-inline" method="post" action="">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn text-danger btn-outline-light"
                                                        onclick="return confirm('Are you sure, you want to delete this record?')">
                                                        <img src="{{ asset('back/assets/dasheets/img/plus-circle.svg') }}"
                                                            class="p-0" data-bs-target="#exampleModalToggle2"
                                                            data-bs-toggle="modal" alt="" />
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
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

        <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
            style="width: 20rem">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form action="{{ route('transfers.filter') }}" method="POST">
                    @csrf
                    @method('POST')
                    <div>
                        <div class="form-group">
                            <label for="reference">Reference</label>
                            <input type="text" class="form-control mt-2" name="reference" id="reference"
                                placeholder="Reference">
                        </div>
                        <div class="form-group">
                            <label for="from_warehouse_id">From Warehouse</label>
                            <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                name="from_warehouse_id" id="from_warehouse_id">
                                <option value="0">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->users->name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="to_warehouse_id">To Warehouse</label>
                            <select class="form-control form-select subheading mt-1" aria-label="Default select example"
                                name="to_warehouse_id" id="to_warehouse_id">
                                <option value="0">Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->users->name ?? ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status" class="mb-1 fw-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control form-select subheading" aria-label="Default select example"
                                id="status" name="status">
                                <option value="0">Select Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="ordered">Ordered</option>
                            </select>
                        </div>

                        <button class="btn save-btn text-white mt-3" type="submit">Filter <i
                                class="bi bi-funnel"></i></button>

                    </div>
                </form>
            </div>
        </div>

    </div>

    @foreach ($transfers as $transfer)
        <!-- Modal 2 -->
        <div class="modal fade" id="exampleModalToggle2{{ $transfer->id }}" aria-hidden="true"
            aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h3 class="all-adjustment text-center pb-2 mb-0">
                            Details
                        </h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="m-2">
                                    <table class="table  table-bordered">
                                        {{-- <thead class="fw-bold">
                                            <th>Date</th>
                                            <th>Warehouse</th>
                                        </thead> --}}
                                        <tbody>
                                            <tr>
                                                <td>Date</td>
                                                <th>{{ $transfer->date ?? '' }}</th>
                                            </tr>
                                            <tr>
                                                <td>Reference</td>
                                                <th>{{ $transfer->reference ?? '' }}</th>
                                            </tr>
                                            <tr>
                                                <td>From Warehouse</td>
                                                <th>{{ $transfer->from_warehouse->users->name ?? '' }}</th>
                                            </tr>
                                            <tr>
                                                <td>To Warehouse</td>
                                                <th>{{ $transfer->to_warehouse->users->name ?? '' }}</th>
                                            </tr>
                                            <tr>
                                                <td>Grand Total</td>
                                                <th>$ {{ $transfer->grand_total ?? '' }}</th>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <th>
                                                    <span
                                                        class="badges green-border text-center">{{ $transfer->status ?? '' }}</span>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7 me-2">
                                <div class="m-2">
                                    <table class="table  table-bordered">
                                        <thead class="fw-bold">
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($transfer->transfer_products as $product)
                                                <tr>
                                                    <td>{{ $product->products->name ?? '' }}</td>
                                                    <td>{{ $product->products->sku ?? '' }}</td>
                                                    <td>{{ $product['quantity'] ?? '' }}{{ $product->products['unit']->short_name ?? '' }}
                                                    </td>
                                                    <td>$ {{ $product['subtotal'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
                            url: "{{ route('transfers.delete') }}",
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
